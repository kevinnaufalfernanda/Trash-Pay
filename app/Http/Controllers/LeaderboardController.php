<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->query('period', 'all');

        $pickupQuery = function($query) use ($period) {
            $query->where('status', 'completed');
            if ($period === 'weekly') {
                $query->where('created_at', '>=', now()->startOfWeek());
            } elseif ($period === 'monthly') {
                $query->where('created_at', '>=', now()->startOfMonth());
            }
        };

        $topUsers = User::where('role', 'user')
            ->withSum(['pickups as total_weight' => $pickupQuery], 'total_weight')
            ->get()
            ->map(function($user) {
                $user->total_weight = $user->total_weight ?? 0;
                $user->impact_score = $user->total_weight * 1.2;
                return $user;
            })
            ->sortByDesc('total_weight')
            ->values();

        $topDrivers = User::where('role', 'driver')
            ->withSum(['driverPickups as driver_total_weight' => $pickupQuery], 'total_weight')
            ->get()
            ->map(function($driver) {
                $driver->driver_total_weight = $driver->driver_total_weight ?? 0;
                $driver->collected_weight = $driver->driver_total_weight;
                return $driver;
            })
            ->sortByDesc('driver_total_weight')
            ->values();

        $authUser = auth()->user();
        $currentUserRank = null;
        $currentUserData = null;

        if ($authUser->role === 'user') {
            $currentUserRank = $topUsers->search(function($user) use ($authUser) {
                return $user->id === $authUser->id;
            });
            if ($currentUserRank !== false) {
                $currentUserRank += 1;
                $currentUserData = $topUsers[$currentUserRank - 1];
            }
        } elseif ($authUser->role === 'driver') {
            $currentUserRank = $topDrivers->search(function($driver) use ($authUser) {
                return $driver->id === $authUser->id;
            });
            if ($currentUserRank !== false) {
                $currentUserRank += 1;
                $currentUserData = $topDrivers[$currentUserRank - 1];
            }
        }

        $topUsers = $topUsers->take(10);
        $topDrivers = $topDrivers->take(10);

        return view('leaderboard.index', compact('topUsers', 'topDrivers', 'period', 'currentUserRank', 'currentUserData'));
    }
}
