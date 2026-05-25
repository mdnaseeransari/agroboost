<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FarmerRequest;
use Illuminate\Support\Facades\Auth;

class FarmerRequestController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isFarmer()) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        $query = FarmerRequest::with('farmer');

        if (Auth::user()->isFarmer()) {
            $query->where('farmer_id', Auth::id());
        }

        // Filter by status if provided, otherwise default to pending
        $status = $request->query('status', 'pending');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $requests = $query->latest()->paginate(15)->withQueryString();
        return view('requests.index', compact('requests'));
    }

    public function create()
    {
        if (!Auth::user()->isFarmer()) {
            abort(403);
        }

        return view('requests.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isFarmer()) {
            abort(403);
        }

        $data = $request->validate([
            'request_type' => 'required|in:seeds,fertilizer,tools,irrigation,equipment',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:1000',
        ]);

        FarmerRequest::create([
            'farmer_id' => Auth::id(),
            'request_type' => $data['request_type'],
            'item_name' => $data['item_name'],
            'quantity' => $data['quantity'],
            'description' => $data['description'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your request has been sent to admin for review.');
    }

    public function respond(Request $request, FarmerRequest $farmerRequest)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected,delivered',
            'admin_response' => 'nullable|string',
        ]);

        $oldStatus = $farmerRequest->status;
        $newStatus = $request->status;

        // Auto-action: Transfer inventory when status changes from pending to approved/delivered
        if ($oldStatus === 'pending' && ($newStatus === 'approved' || $newStatus === 'delivered')) {
            $adminItem = \App\Models\InventoryItem::where('farm_id', $farmerRequest->farmer->farm_id)
                ->whereNull('user_id')
                ->where('name', 'LIKE', $farmerRequest->item_name)
                ->first();

            if ($adminItem && $adminItem->quantity >= $farmerRequest->quantity) {
                // 1. Deduct from Admin
                $adminItem->decrement('quantity', $farmerRequest->quantity);

                // 2. Add/Update Farmer inventory
                $farmerItem = \App\Models\InventoryItem::where('user_id', $farmerRequest->farmer_id)
                    ->where('name', 'LIKE', $farmerRequest->item_name)
                    ->first();

                if ($farmerItem) {
                    $farmerItem->increment('quantity', $farmerRequest->quantity);
                } else {
                    \App\Models\InventoryItem::create([
                        'farm_id' => $farmerRequest->farmer->farm_id,
                        'user_id' => $farmerRequest->farmer_id,
                        'name' => $farmerRequest->item_name,
                        'type' => $adminItem->type ?? 'other',
                        'quantity' => $farmerRequest->quantity,
                        'unit' => $adminItem->unit ?? 'units',
                        'threshold_alert' => $adminItem->threshold_alert ?? 5,
                    ]);
                }
            } else if (!$adminItem) {
                return back()->with('error', 'Item "' . $farmerRequest->item_name . '" not found in Admin inventory.');
            } else {
                return back()->with('error', 'Insufficient quantity in Admin inventory for "' . $farmerRequest->item_name . '".');
            }
        }

        $farmerRequest->update([
            'status' => $newStatus,
            'admin_response' => $request->admin_response,
        ]);

        return back()->with('success', 'Request status updated to ' . $newStatus . ' and inventory adjusted.');
    }
}
