<?php

namespace App\Http\Controllers;

use App\Models\InventoryRequest;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryRequestController extends Controller
{
    /**
     * Farmer submits a request for an item from admin's inventory.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'farmer') {
            return redirect()->route('dashboard')->with('error', 'Only farmers can request inventory items.');
        }

        $validated = $request->validate([
            'item_id'  => 'required|exists:inventory_items,id',
            'quantity' => 'required|numeric|min:0.01',
            'notes'    => 'nullable|string|max:500',
        ]);

        // Ensure item belongs to admin inventory (user_id is null = admin's central inventory)
        $item = InventoryItem::where('id', $validated['item_id'])
            ->where('farm_id', $user->farm_id)
            ->whereNull('user_id')
            ->firstOrFail();

        InventoryRequest::create([
            'farmer_id' => $user->id,
            'item_id'   => $item->id,
            'quantity'  => $validated['quantity'],
            'status'    => 'pending',
            'notes'     => $validated['notes'] ?? null,
        ]);

        return redirect()->route('dashboard')->with('success', "Request for {$item->name} submitted to Admin.");
    }

    /**
     * Admin approves or rejects a request.
     */
    public function update(Request $request, InventoryRequest $inventoryRequest)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Only admins can manage requests.');
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $inventoryRequest->update(['status' => $validated['status']]);

        // If approved, deduct from admin inventory and add to farmer's inventory
        if ($validated['status'] === 'approved') {
            $adminItem = $inventoryRequest->item;
            
            // Deduct from admin stock
            $adminItem->decrement('quantity', $inventoryRequest->quantity);

            // Add to farmer's personal inventory (create or increment)
            $farmerItem = InventoryItem::firstOrNew([
                'farm_id' => $user->farm_id,
                'user_id' => $inventoryRequest->farmer_id,
                'name'    => $adminItem->name,
                'type'    => $adminItem->type,
                'unit'    => $adminItem->unit,
            ]);
            
            if ($farmerItem->exists) {
                $farmerItem->increment('quantity', $inventoryRequest->quantity);
            } else {
                $farmerItem->quantity = $inventoryRequest->quantity;
                $farmerItem->threshold_alert = 0;
                $farmerItem->save();
            }
        }

        return redirect()->route('dashboard')->with('success', "Request {$validated['status']} successfully.");
    }
}
