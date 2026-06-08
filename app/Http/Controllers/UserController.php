<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pickup;
use App\Models\WasteCategory;
use App\Models\Redemption;

class UserController extends Controller
{
    /**
     * User Dashboard — Impact Score & Coin Balance
     */
    public function dashboard()
    {
        $user = auth()->user();
        $impactScore = Pickup::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('total_weight') * 1.2; // Formula E = W * 1.2

        $recentPickups = Pickup::where('user_id', $user->id)->latest()->take(5)->get();
        $categories = WasteCategory::all();

        return view('user.dashboard', compact('user', 'impactScore', 'recentPickups', 'categories'));
    }

    /**
     * Request Pickup — form with integrated AI Waste Scanner
     */
    public function pickupRequest()
    {
        $categories = WasteCategory::all();
        $pickups = Pickup::where('user_id', auth()->id())
                            ->latest()
                            ->get();
                            
        return view('user.pickup', compact('categories', 'pickups'));
    }

    /**
     * Store Pickup Request — saves photo + category detected by AI
     */
    public function storePickupRequest(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:waste_categories,id',
            'estimated_weight' => 'required|numeric|min:0.1',
            'waste_photo' => 'required|image|max:5120',
            'address' => 'required|string|max:500',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'address_notes' => 'nullable|string|max:500',
        ]);

        $path = $request->file('waste_photo')->store('pickups', 'public');

        Pickup::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'waste_photo' => $path,
            'total_weight' => $request->estimated_weight,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address_notes' => $request->address_notes,
            'status' => 'pending'
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Pickup requested! A driver will pick it up soon.');
    }

    /**
     * Redeem Center — exchange coins for e-wallet (Dana/GoPay/ShopeePay)
     */
    public function redeem()
    {
        $user = auth()->user();
        $redemptions = Redemption::where('user_id', $user->id)->latest()->get();
        return view('user.redeem', compact('user', 'redemptions'));
    }

    /**
     * Store Redeem — deduct coins and create pending payout
     */
    public function storeRedeem(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:100',
            'provider' => 'required|in:Dana,GoPay,ShopeePay',
            'account_number' => 'required|string|max:20'
        ]);

        $user = auth()->user();

        if ($user->coin_balance < $request->amount) {
            return back()->with('error', 'Insufficient coins!');
        }

        $user->decrement('coin_balance', $request->amount);

        Redemption::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'provider' => $request->provider,
            'account_number' => $request->account_number,
            'status' => 'pending'
        ]);

        return redirect()->route('user.redeem')->with('success', 'Redemption requested! Admin will review it shortly.');
    }
}
