<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InventoryController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        if (Auth::user()->role === 'farmer') {
            return redirect()->route('dashboard')->with('error', 'Access denied to Inventory section.');
        }
        $inventoryItems = InventoryItem::where('farm_id', Auth::user()->farm_id)
            ->orderBy('name')
            ->paginate(12);
            
        return view('inventory.index', compact('inventoryItems'));
    }

    public function create()
    {
        $this->authorize('create', InventoryItem::class);
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', InventoryItem::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:seed,fertilizer,equipment,other',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'threshold_alert' => 'required|numeric|min:0',
        ]);

        $validated['farm_id'] = Auth::user()->farm_id;

        InventoryItem::create($validated);

        return redirect()->route('inventory.index')->with('success', 'Item added to inventory.');
    }

    public function edit(InventoryItem $inventory)
    {
        $this->authorize('update', $inventory);
        return view('inventory.edit', ['inventoryItem' => $inventory]);
    }

    public function update(Request $request, InventoryItem $inventory)
    {
        $this->authorize('update', $inventory);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:seed,fertilizer,equipment,other',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'threshold_alert' => 'required|numeric|min:0',
        ]);

        $inventory->update($validated);

        return redirect()->route('inventory.index')->with('success', 'Inventory item updated.');
    }

    public function destroy(InventoryItem $inventory)
    {
        $this->authorize('delete', $inventory);
        $inventory->delete();

        return redirect()->route('inventory.index')->with('success', 'Item removed from inventory.');
    }
}
