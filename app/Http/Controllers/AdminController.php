<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pickup;
use App\Models\WasteCategory;
use App\Models\Redemption;
use App\Models\User;
use App\Models\DriverApplication;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    /**
     * Admin Dashboard — unified control panel with live stats + pending payouts
     */
    public function dashboard()
    {
        $totalWeight = Pickup::where('status', 'completed')->sum('total_weight');
        $co2Reduced = $totalWeight * 1.2;
        $completedPickups = Pickup::where('status', 'completed')->count();
        $pendingPayouts = Redemption::where('status', 'pending')->count();
        $totalUsers = User::where('role', 'user')->count();
        $totalDrivers = User::where('role', 'driver')->count();

        return view('admin.dashboard', compact(
            'totalWeight', 'co2Reduced', 'completedPickups',
            'pendingPayouts', 'totalUsers', 'totalDrivers'
        ));
    }

    /**
     * Analytics — detailed charts and category breakdown
     */
    public function analytics()
    {
        $totalWeight = Pickup::where('status', 'completed')->sum('total_weight');
        $co2Reduced = $totalWeight * 1.2;
        $completedPickups = Pickup::where('status', 'completed')->count();

        // Real category-based data
        $categories = WasteCategory::all();
        $chartLabels = $categories->pluck('name')->toArray();
        $chartValues = [];

        foreach ($categories as $cat) {
            $chartValues[] = Pickup::where('status', 'completed')
                ->where('category_id', $cat->id)
                ->sum('total_weight');
        }

        $chartData = [
            'labels' => $chartLabels ?: ['No data'],
            'data' => $chartValues ?: [0]
        ];

        return view('admin.analytics', compact('totalWeight', 'co2Reduced', 'completedPickups', 'chartData'));
    }

    /**
     * Driver Management — view and create drivers
     */
    public function drivers()
    {
        $drivers = User::where('role', 'driver')->latest()->get();
        $applications = DriverApplication::with('user')->where('status', 'pending')->latest()->get();
        return view('admin.drivers', compact('drivers', 'applications'));
    }

    /**
     * Approve Driver Application
     */
    public function approveDriverApplication(DriverApplication $application)
    {
        $application->update(['status' => 'approved']);
        $application->user->update(['role' => 'driver']);

        return redirect()->route('admin.drivers')->with('success', "Aplikasi dari {$application->user->name} disetujui!");
    }

    /**
     * Reject Driver Application
     */
    public function rejectDriverApplication(DriverApplication $application)
    {
        $application->update(['status' => 'rejected']);

        return redirect()->route('admin.drivers')->with('success', "Aplikasi dari {$application->user->name} ditolak.");
    }

    /**
     * Store a new driver account
     */
    public function storeDriver(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'driver',
        ]);

        return redirect()->route('admin.drivers')->with('success', 'Akun Eco-Driver berhasil dibuat!');
    }

    /**
     * Pricing Control — manage price per kg for each category
     */
    public function pricing()
    {
        $categories = WasteCategory::all();
        return view('admin.pricing', compact('categories'));
    }

    /**
     * Update Pricing — modify price_per_kg for a category
     */
    public function updatePricing(Request $request, WasteCategory $category)
    {
        $request->validate([
            'price_per_kg' => 'required|numeric|min:0'
        ]);

        $category->update(['price_per_kg' => $request->price_per_kg]);

        return redirect()->route('admin.pricing')->with('success', "Price for {$category->name} updated!");
    }

    /**
     * Payouts — list pending redemption requests for approval
     */
    public function payouts()
    {
        $redemptions = Redemption::with('user')->where('status', 'pending')->latest()->get();
        return view('admin.payouts', compact('redemptions'));
    }

    /**
     * Approve Payout — mark redemption as approved
     */
    public function approvePayout(Redemption $redemption)
    {
        $redemption->update(['status' => 'approved']);
        return redirect()->route('admin.payouts')->with('success', "Payout #{$redemption->id} approved!");
    }

    /**
     * Reject Payout — mark as rejected and refund coins to user
     */
    public function rejectPayout(Redemption $redemption)
    {
        $redemption->update(['status' => 'rejected']);
        // Refund coins to user
        $redemption->user->increment('coin_balance', $redemption->amount);

        return redirect()->route('admin.payouts')->with('success', "Payout #{$redemption->id} rejected. Coins refunded.");
    }
}
