<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pickup;
use App\Models\WasteCategory;
use App\Models\Redemption;

class DriverController extends Controller
{
    /**
     * Driver Dashboard — stats overview + my active orders
     */
    public function dashboard()
    {
        $driver = auth()->user();

        if (!\Illuminate\Support\Facades\Cache::has('driver_auto_cancel_run')) {
            Pickup::where('status', 'pending')
                ->where('created_at', '<', now()->subHours(24))
                ->update(['status' => 'cancelled']);
            \Illuminate\Support\Facades\Cache::put('driver_auto_cancel_run', true, 60);
        }

        $completedCount = \Illuminate\Support\Facades\Cache::remember("driver_{$driver->id}_completed_count", 60, function() use ($driver) {
            return Pickup::where('driver_id', $driver->id)->where('status', 'completed')->count();
        });
        
        $totalWeightCollected = \Illuminate\Support\Facades\Cache::remember("driver_{$driver->id}_total_weight", 60, function() use ($driver) {
            return Pickup::where('driver_id', $driver->id)->where('status', 'completed')->sum('total_weight');
        });
        
        $activeOrders = Pickup::with('user')->where('driver_id', $driver->id)->where('status', 'on-the-way')->latest()->get();
        
        $pendingCount = \Illuminate\Support\Facades\Cache::remember('driver_pending_pickups_count', 30, function() {
            return Pickup::where('status', 'pending')->count();
        });

        return view('driver.dashboard', compact('driver', 'completedCount', 'totalWeightCollected', 'activeOrders', 'pendingCount'));
    }

    /**
     * Update driver online/offline status via AJAX
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:online,offline'
        ]);

        $driver = auth()->user();
        $driver->update(['driver_status' => $request->status]);

        return response()->json(['success' => true]);
    }

    /**
     * Order Pool — list all pending pickups available in Malang
     */
    public function orderPool()
    {
        Pickup::where('status', 'pending')
            ->where('created_at', '<', now()->subHours(24))
            ->update(['status' => 'cancelled']);

        $pickups = Pickup::with(['user', 'category'])->where('status', 'pending')->latest()->get();

        $highRewardPickups = collect();
        $standardPickups = collect();

        foreach ($pickups as $pickup) {
            $estCoins = round($pickup->total_weight * $pickup->category->price_per_kg);
            $pickup->est_coins = $estCoins;

            $lat = $pickup->latitude ?? -7.95;
            $lon = $pickup->longitude ?? 112.61;
            // Approximate distance using Pythagorean theorem (1 deg ~ 111km)
            $pickup->distance = round(sqrt(pow(($lat - (-7.95))*111, 2) + pow(($lon - 112.61)*111, 2)), 1);

            if ($estCoins >= 50) {
                $highRewardPickups->push($pickup);
            } else {
                $standardPickups->push($pickup);
            }
        }

        $driver = auth()->user();
        $activeOrders = Pickup::with(['user', 'category'])->where('driver_id', $driver->id)->where('status', 'on-the-way')->latest()->get();
        $currentCapacity = $activeOrders->sum('total_weight');

        return view('driver.orders', compact('highRewardPickups', 'standardPickups', 'activeOrders', 'currentCapacity'));
    }

    /**
     * Preview Order — show details and map before accepting
     */
    public function preview(Pickup $pickup)
    {
        if ($pickup->status !== 'pending') {
            return redirect()->route('driver.orders')->with('error', 'This order is no longer available.');
        }

        return view('driver.preview', compact('pickup'));
    }

    /**
     * Accept Order — assign this driver and set status to on-the-way
     */
    public function acceptOrder(Pickup $pickup)
    {
        // Block offline drivers
        if (auth()->user()->driver_status === 'offline') {
            return redirect()->route('driver.orders')->with('error', 'Status Anda sedang offline! Silakan aktifkan akun (Online) terlebih dahulu untuk mengambil pesanan.');
        }

        // Only accept if still pending
        if ($pickup->status !== 'pending') {
            return redirect()->route('driver.orders')->with('error', 'This order has already been taken.');
        }

        // Check 10kg capacity limit
        $currentCapacity = Pickup::where('driver_id', auth()->id())->where('status', 'on-the-way')->sum('total_weight');
        if ($currentCapacity + $pickup->total_weight > 10) {
            return redirect()->route('driver.orders')->with('error', 'Kapasitas maksimal (10kg) terlampaui. Selesaikan pesanan Anda terlebih dahulu.');
        }

        $pickup->update([
            'driver_id' => auth()->id(),
            'status' => 'on-the-way'
        ]);

        return redirect()->route('driver.navigation', $pickup->id);
    }

    /**
     * Navigation — Leaflet map with user pickup location
     */
    public function navigation(Pickup $pickup)
    {
        // Guard: only the assigned driver can navigate
        if ($pickup->driver_id !== auth()->id()) {
            abort(403, 'This order is not assigned to you.');
        }

        return view('driver.navigation', compact('pickup'));
    }

    /**
     * Verify Weight — form for driver to input final weight
     */
    public function verifyWeight(Pickup $pickup)
    {
        // Guard: only the assigned driver
        if ($pickup->driver_id !== auth()->id()) {
            abort(403, 'This order is not assigned to you.');
        }

        $categories = WasteCategory::all();
        return view('driver.verify', compact('pickup', 'categories'));
    }

    /**
     * Store Weight — finalize pickup, calculate coins, auto-transfer to user
     */
    public function storeWeight(Request $request, Pickup $pickup)
    {
        // Guard: only the assigned driver
        if ($pickup->driver_id !== auth()->id()) {
            abort(403, 'This order is not assigned to you.');
        }

        $request->validate([
            'weight' => 'required|numeric|min:0.1',
            'category_id' => 'required|exists:waste_categories,id'
        ]);

        $category = WasteCategory::findOrFail($request->category_id);
        $coins = round($request->weight * $category->price_per_kg);

        $pickup->update([
            'status' => 'completed',
            'total_weight' => $request->weight,
            'total_coins' => $coins
        ]);

        // Auto-transfer coins to user account
        $pickup->user->increment('coin_balance', $coins);

        // Auto-transfer coins to driver account
        auth()->user()->increment('coin_balance', $coins);

        return redirect()->route('driver.orders')->with('success', "Pickup #{$pickup->id} completed! {$coins} coins transferred to you and the user.");
    }

    /**
     * Redeem Center for Driver
     */
    public function redeem()
    {
        $driver = auth()->user();
        $redemptions = Redemption::where('user_id', $driver->id)->latest()->get();
        return view('driver.redeem', compact('driver', 'redemptions'));
    }

    /**
     * Store Driver Redeem
     */
    public function storeRedeem(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:100',
            'provider' => 'required|in:Dana,GoPay,ShopeePay',
            'account_number' => 'required|string|max:20'
        ]);

        $driver = auth()->user();

        if ($driver->coin_balance < $request->amount) {
            return back()->with('error', 'Koin tidak cukup!');
        }

        $driver->decrement('coin_balance', $request->amount);

        $redemption = Redemption::create([
            'user_id' => $driver->id,
            'amount' => $request->amount,
            'provider' => $request->provider,
            'account_number' => $request->account_number,
            'status' => 'pending'
        ]);

        return redirect()->route('driver.redeem')->with([
            'success' => 'Permintaan penarikan saldo berhasil! Admin akan segera memprosesnya.',
            'redemption_data' => $redemption
        ]);
    }

    /**
     * Driver requests to cancel a pickup
     */
    public function requestCancel(Pickup $pickup)
    {
        if ($pickup->driver_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        if ($pickup->status === 'on-the-way') {
            $pickup->update(['cancel_requested_by' => 'driver']);
            return back()->with('success', 'Pengajuan pembatalan telah dikirim ke User.');
        }

        return back()->with('error', 'Pesanan tidak bisa dibatalkan.');
    }

    /**
     * Driver approves user's cancellation request
     */
    public function approveCancel(Pickup $pickup)
    {
        if ($pickup->driver_id !== auth()->id() || $pickup->cancel_requested_by !== 'user') {
            abort(403, 'Unauthorized.');
        }

        $pickup->update([
            'status' => 'cancelled',
            'cancel_requested_by' => null
        ]);

        return back()->with('success', 'Pembatalan disetujui. Pesanan telah dibatalkan.');
    }

    /**
     * Driver rejects user's cancellation request
     */
    public function rejectCancel(Pickup $pickup)
    {
        if ($pickup->driver_id !== auth()->id() || $pickup->cancel_requested_by !== 'user') {
            abort(403, 'Unauthorized.');
        }

        $pickup->update(['cancel_requested_by' => null]);

        return back()->with('success', 'Anda menolak pembatalan. Pesanan tetap dilanjutkan.');
    }

    /**
     * History Page for Driver
     */
    public function history()
    {
        $driver = auth()->user();
        $pickups = Pickup::where('driver_id', $driver->id)->latest()->get();
        $redemptions = Redemption::where('user_id', $driver->id)->latest()->get();
        return view('driver.history', compact('driver', 'pickups', 'redemptions'));
    }
}
