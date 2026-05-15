<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\InventoryItem;
use App\Models\FarmerRequest;
use App\Models\Task;
use App\Models\Order;
use App\Models\CropListing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $role   = $user->role;
        $farmId = $user->farm_id;

        // Auto-update crops that reached their harvest date
        if ($role !== 'buyer') {
            Crop::where('farm_id', $farmId)
                ->where('status', 'growing')
                ->where('expected_harvest_date', '<=', now())
                ->update(['status' => 'harvested']);
        }

        // ── Common Stats ──────────────────────────────────────────────────────────
        $pendingTasksQuery = Task::where('farm_id', $farmId)->where('completed', false);
        if ($role === 'farmer') {
            $pendingTasksQuery->where('assigned_to', $user->id);
        }
        $pendingTasks = $pendingTasksQuery->count();

        $messages = \App\Models\Message::where(function($query) use ($user) {
            $query->where('receiver_id', $user->id)
                  ->orWhere('sender_id', $user->id);
        })->with(['sender', 'receiver'])->latest()->limit(5)->get();

        // ── Role-specific data ────────────────────────────────────────────────────
        $stats = [];
        $recentOrders = collect();
        $recentRequests = collect();
        $marketHighlights = collect();

        if ($role === 'admin') {
            $stats = [
                'total_orders' => Order::count(),
                'revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
                'pending_requests' => FarmerRequest::where('status', 'pending')->count(),
                'inventory_alerts' => InventoryItem::whereRaw('quantity <= threshold_alert')->count(),
            ];
            $recentOrders = Order::with(['buyer', 'farmer'])->latest()->limit(5)->get();
            $recentRequests = FarmerRequest::with('farmer')->where('status', 'pending')->latest()->limit(5)->get();
        } elseif ($role === 'farmer') {
            $stats = [
                'my_crops' => Crop::where('user_id', $user->id)->count(),
                'my_orders' => Order::where('farmer_id', $user->id)->count(),
                'revenue' => Order::where('farmer_id', $user->id)->where('payment_status', 'paid')->sum('total_amount'),
                'low_stock' => InventoryItem::where('user_id', $user->id)->whereRaw('quantity <= threshold_alert')->count(),
            ];
            $recentOrders = Order::where('farmer_id', $user->id)->with('buyer')->latest()->limit(5)->get();
            $recentRequests = collect(); // Requests disabled for farmers
        } elseif ($role === 'buyer') {
            $stats = [
                'my_orders' => Order::where('buyer_id', $user->id)->count(),
                'total_spent' => Order::where('buyer_id', $user->id)->where('payment_status', 'paid')->sum('total_amount'),
                'active_orders' => Order::where('buyer_id', $user->id)->whereNotIn('order_status', ['delivered'])->count(),
            ];
            $recentOrders = Order::where('buyer_id', $user->id)->with('farmer')->latest()->limit(5)->get();
            $marketHighlights = CropListing::with(['crop', 'farmer'])->where('is_active', true)->latest()->limit(4)->get();
        }

        $selectedRecipientId = request('chat');

        return view('dashboard', compact('role', 'stats', 'recentOrders', 'recentRequests', 'marketHighlights', 'messages', 'pendingTasks', 'selectedRecipientId'));
    }
}
