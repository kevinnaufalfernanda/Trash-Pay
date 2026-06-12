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
        $totalWeight = \Illuminate\Support\Facades\Cache::remember('admin_total_weight', 60, function() {
            return Pickup::where('status', 'completed')->sum('total_weight');
        });
        $co2Reduced = $totalWeight * 1.2;
        $completedPickups = \Illuminate\Support\Facades\Cache::remember('admin_completed_pickups', 60, function() {
            return Pickup::where('status', 'completed')->count();
        });
        $pendingPayouts = \Illuminate\Support\Facades\Cache::remember('admin_pending_payouts', 60, function() {
            return Redemption::where('status', 'pending')->count();
        });
        $totalUsers = \Illuminate\Support\Facades\Cache::remember('admin_total_users', 60, function() {
            return User::where('role', 'user')->count();
        });
        $totalDrivers = \Illuminate\Support\Facades\Cache::remember('admin_total_drivers', 60, function() {
            return User::where('role', 'driver')->count();
        });

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
        $chartLabels = $categories->pluck('name')->map(function($name) {
            return __($name);
        })->toArray();
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
    public function drivers(Request $request)
    {
        $sort = $request->query('sort', 'default');
        $status = $request->query('status', 'all');

        $query = User::where('role', 'driver')
            ->with('driverApplication')
            ->withCount(['driverPickups as completed_jobs' => function($q) {
                $q->where('status', 'completed');
            }])
            ->withSum(['driverPickups as collected_weight' => function($q) {
                $q->where('status', 'completed');
            }], 'total_weight');

        if ($status === 'online') {
            $query->where('driver_status', 'online');
        } elseif ($status === 'offline') {
            $query->where('driver_status', 'offline');
        }

        switch ($sort) {
            case 'date_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'date_desc':
            case 'default':
            default:
                $query->latest();
                break;
        }

        $drivers = $query->get();
        $applications = DriverApplication::with('user')->where('status', 'pending')->latest()->get();
        return view('admin.drivers', compact('drivers', 'applications', 'sort', 'status'));
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
     * Revoke Driver Status — revert to user and delete application
     */
    public function revokeDriver(User $driver)
    {
        if ($driver->role !== 'driver') {
            return redirect()->route('admin.drivers')->withErrors(['error' => 'User is not a driver.']);
        }

        // Change role back to user
        $driver->update([
            'role' => 'user',
            'driver_status' => 'offline'
        ]);

        // Delete their driver application so they have to start fresh if they want to apply again
        if ($driver->driverApplication) {
            $driver->driverApplication()->delete();
        }

        return redirect()->route('admin.drivers')->with('success', "Status Mitra Driver untuk {$driver->name} berhasil diberhentikan. Mereka sekarang adalah User biasa.");
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
