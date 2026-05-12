<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Crop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Store a new crop purchase by a buyer.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'buyer') {
            return redirect()->route('dashboard')->with('error', 'Only buyers can purchase crops.');
        }

        $validated = $request->validate([
            'crop_id'  => 'required|exists:crops,id',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $crop = Crop::where('id', $validated['crop_id'])
            ->where('status', 'harvested')
            ->where('price', '>', 0)
            ->firstOrFail();

        // Check available yield
        $alreadySold = Transaction::where('crop_id', $crop->id)->sum('quantity');
        $available = $crop->yield_kg - $alreadySold;

        if ($validated['quantity'] > $available) {
            return back()->with('error', "Only {$available} kg available for this crop.");
        }

        $totalPrice = $validated['quantity'] * $crop->price;

        Transaction::create([
            'buyer_id'    => $user->id,
            'crop_id'     => $crop->id,
            'quantity'    => $validated['quantity'],
            'total_price' => $totalPrice,
        ]);

        return redirect()->route('dashboard')->with('success', "Purchased {$validated['quantity']} kg of {$crop->name} for ₹" . number_format($totalPrice, 2));
    }
}
