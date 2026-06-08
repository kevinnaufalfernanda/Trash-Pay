<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LeaderboardController extends Controller
{
    public function index()
    {
        $topUsers = User::where('role', 'user')
            ->withSum(['pickups as total_weight' => function($query) {
                $query->where('status', 'completed');
            }], 'total_weight')
            ->orderByDesc('total_weight')
            ->take(10)
            ->get()
            ->map(function($user) {
                $user->impact_score = ($user->total_weight ?? 0) * 1.2;
                return $user;
            });

        $topDrivers = User::where('role', 'driver')
            ->withSum(['driverPickups as driver_total_weight' => function($query) {
                $query->where('status', 'completed');
            }], 'total_weight')
            ->orderByDesc('driver_total_weight')
            ->take(10)
            ->get()
            ->map(function($driver) {
                $driver->collected_weight = $driver->driver_total_weight ?? 0;
                return $driver;
            });

        return view('leaderboard.index', compact('topUsers', 'topDrivers'));
    }
}
