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

        $completedCount = Pickup::where('driver_id', $driver->id)->where('status', 'completed')->count();
        $totalWeightCollected = Pickup::where('driver_id', $driver->id)->where('status', 'completed')->sum('total_weight');
        $activeOrders = Pickup::with('user')->where('driver_id', $driver->id)->where('status', 'on-the-way')->latest()->get();
        $pendingCount = Pickup::where('status', 'pending')->count();

        return view('driver.dashboard', compact('driver', 'completedCount', 'totalWeightCollected', 'activeOrders', 'pendingCount'));
    }

    /**
     * Order Pool — list all pending pickups available in Malang
     */
    public function orderPool()
    {
        $pickups = Pickup::with(['user', 'category'])->where('status', 'pending')->latest()->get();

        $highRewardPickups = collect();
        $standardPickups = collect();

        foreach ($pickups as $pickup) {
            $estCoins = round($pickup->total_weight * $pickup->category->price_per_kg);
            $pickup->est_coins = $estCoins;

            if ($estCoins >= 50) {
                $highRewardPickups->push($pickup);
            } else {
                $standardPickups->push($pickup);
            }
        }

        return view('driver.orders', compact('highRewardPickups', 'standardPickups'));
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
        // Only accept if still pending
        if ($pickup->status !== 'pending') {
            return redirect()->route('driver.orders')->with('error', 'This order has already been taken.');
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

        Redemption::create([
            'user_id' => $driver->id,
            'amount' => $request->amount,
            'provider' => $request->provider,
            'account_number' => $request->account_number,
            'status' => 'pending'
        ]);

        return redirect()->route('driver.redeem')->with('success', 'Permintaan penarikan saldo berhasil! Admin akan segera memprosesnya.');
    }
}
