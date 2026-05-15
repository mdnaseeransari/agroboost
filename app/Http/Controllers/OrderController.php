<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CropListing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $orders = Order::with(['buyer', 'farmer'])->latest()->paginate(15);
        } elseif ($user->isFarmer()) {
            $orders = Order::where('farmer_id', $user->id)->with('buyer')->latest()->paginate(15);
        } else {
            $orders = Order::where('buyer_id', $user->id)->with('farmer')->latest()->paginate(15);
        }

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load(['buyer', 'farmer', 'items.crop']);
        return view('orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:crop_listings,id',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $listing = CropListing::findOrFail($request->listing_id);

        if ($listing->quantity_available < $request->quantity) {
            return back()->with('error', 'Insufficient quantity available.');
        }

        DB::transaction(function () use ($request, $listing) {
            $totalAmount = $request->quantity * $listing->price_per_unit;

            $order = Order::create([
                'buyer_id' => Auth::id(),
                'farmer_id' => $listing->farmer_id,
                'total_amount' => $totalAmount,
                'payment_status' => 'pending',
                'order_status' => 'pending',
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'crop_id' => $listing->crop_id,
                'quantity' => $request->quantity,
                'price' => $listing->price_per_unit,
                'subtotal' => $totalAmount,
            ]);

            // Reduce listing quantity
            $listing->decrement('quantity_available', $request->quantity);
            if ($listing->quantity_available <= 0) {
                $listing->update(['is_active' => false]);
            }

            // Also reduce the yield_kg in the Crop model to reflect current stock
            $listing->crop->decrement('yield_kg', $request->quantity);
        });

        return redirect()->route('orders.index')->with('success', 'Order placed successfully.');
    }

    public function pay(Order $order)
    {
        if ($order->buyer_id !== Auth::id()) {
            abort(403);
        }

        if ($order->payment_status === 'paid') {
            return back()->with('info', 'Order is already paid.');
        }

        // Simulate payment
        $order->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Payment completed successfully');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $user = Auth::user();
        if ($user->id !== $order->farmer_id && !$user->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,accepted,packed,shipped,delivered',
        ]);

        $order->update(['order_status' => $request->status]);

        return back()->with('success', 'Order status updated to ' . $request->status);
    }
}
