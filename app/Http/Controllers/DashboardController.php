<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\InventoryItem;
use App\Models\InventoryRequest;
use App\Models\Task;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $farmId = $user->farm_id;
        $role   = $user->role; // 'admin', 'farmer', 'buyer'

        // ── Task Counts ──────────────────────────────────────────────────────────
        $pendingTasksQuery = Task::where('farm_id', $farmId)->where('completed', false);
        $overdueTasksQuery = Task::where('farm_id', $farmId)->where('completed', false)->where('due_date', '<', now());

        if ($role === 'farmer') {
            $pendingTasksQuery->where('assigned_to', $user->id);
            $overdueTasksQuery->where('assigned_to', $user->id);
        }

        $pendingTasks = $pendingTasksQuery->count();
        $overdueTasks = $overdueTasksQuery->count();

        // ── Recent Tasks ─────────────────────────────────────────────────────────
        $recentTasksQuery = Task::where('farm_id', $farmId)
            ->with('assignee')
            ->where('completed', false)
            ->orderBy('due_date');

        if ($role === 'farmer') {
            $recentTasksQuery->where('assigned_to', $user->id);
        }

        $recentTasks = $recentTasksQuery->limit(5)->get();

        // ── Messaging ────────────────────────────────────────────────────────────
        $messages = collect();
        if ($role === 'admin') {
            $messages = \App\Models\Message::where('farm_id', $farmId)->with('sender')->latest()->limit(10)->get();
        } elseif ($role === 'farmer') {
            $admin = \App\Models\User::where('farm_id', $farmId)->where('role', 'admin')->first();
            if ($admin) {
                $messages = \App\Models\Message::where('farm_id', $farmId)
                    ->where(function ($q) use ($user, $admin) {
                        $q->where(fn($q) => $q->where('sender_id', $user->id)->where('receiver_id', $admin->id))
                          ->orWhere(fn($q) => $q->where('sender_id', $admin->id)->where('receiver_id', $user->id));
                    })
                    ->with('sender')->latest()->limit(10)->get();
            }
        }

        // ── Role-specific data ────────────────────────────────────────────────────

        // Admin data
        $activeCrops  = 0;
        $lowStockItems = 0;
        $lowStockList  = collect();
        $teamCount    = 0;
        $teamMembers  = collect();
        $topCrops     = collect();
        $inventoryRequests = collect();

        // Farmer data
        $myFarmerCrops     = collect();
        $farmerInventory   = collect();
        $adminInventory    = collect();
        $myRequests        = collect();
        $cropYieldData     = [];

        // Buyer data
        $marketCrops     = collect();
        $myTransactions  = collect();

        if ($role === 'admin') {
            $activeCrops   = Crop::where('farm_id', $farmId)->where('status', 'growing')->count();
            $lowStockItems = InventoryItem::where('farm_id', $farmId)->whereNull('user_id')->whereRaw('quantity <= threshold_alert')->count();
            $lowStockList  = InventoryItem::where('farm_id', $farmId)->whereNull('user_id')->whereRaw('quantity <= threshold_alert')->limit(4)->get();
            $teamMembers   = \App\Models\User::where('farm_id', $farmId)->limit(5)->get();
            $teamCount     = \App\Models\User::where('farm_id', $farmId)->count();
            $topCrops      = Crop::where('farm_id', $farmId)->whereNull('user_id')->where('status', 'growing')->orderBy('expected_harvest_date')->limit(5)->get();
            // Pending inventory requests for admin to action
            $inventoryRequests = InventoryRequest::whereHas('item', fn($q) => $q->where('farm_id', $farmId)->whereNull('user_id'))
                ->with(['farmer', 'item'])
                ->where('status', 'pending')
                ->latest()
                ->limit(10)
                ->get();
        }

        if ($role === 'farmer') {
            $myFarmerCrops   = Crop::where('farm_id', $farmId)->where('user_id', $user->id)->latest()->get();
            $farmerInventory = InventoryItem::where('farm_id', $farmId)->where('user_id', $user->id)->orderBy('name')->get();
            $adminInventory  = InventoryItem::where('farm_id', $farmId)->whereNull('user_id')->orderBy('name')->get();
            $myRequests      = InventoryRequest::where('farmer_id', $user->id)->with('item')->latest()->limit(5)->get();

            // Build crop yield data for Chart.js (last 5 crops with yield)
            $harvestedCrops = Crop::where('farm_id', $farmId)
                ->where('user_id', $user->id)
                ->where('status', 'harvested')
                ->whereNotNull('yield_kg')
                ->latest('actual_harvest_date')
                ->limit(5)
                ->get();

            $cropYieldData = [
                'labels' => $harvestedCrops->map(fn($c) => $c->name . ($c->variety ? ' ('.$c->variety.')' : ''))->values()->toArray(),
                'data'   => $harvestedCrops->pluck('yield_kg')->values()->toArray(),
            ];

            $pendingTasks = $pendingTasksQuery->getQuery()->exists() ? $pendingTasks : Task::where('farm_id', $farmId)->where('completed', false)->where('assigned_to', $user->id)->count();
        }

        if ($role === 'buyer') {
            // Show harvested crops from all farmers that have price & available stock
            $marketCrops = Crop::where('status', 'harvested')
                ->where('price', '>', 0)
                ->whereNotNull('user_id')
                ->with('owner')
                ->get()
                ->map(function ($crop) {
                    $sold = Transaction::where('crop_id', $crop->id)->sum('quantity');
                    $crop->available = max(0, $crop->yield_kg - $sold);
                    return $crop;
                })
                ->filter(fn($c) => $c->available > 0);

            $myTransactions = Transaction::where('buyer_id', $user->id)->with('crop')->latest()->limit(10)->get();
        }

        return view('dashboard', compact(
            'role', 'pendingTasks', 'overdueTasks', 'recentTasks', 'messages',
            // Admin
            'activeCrops', 'lowStockItems', 'lowStockList', 'teamCount', 'teamMembers', 'topCrops', 'inventoryRequests',
            // Farmer
            'myFarmerCrops', 'farmerInventory', 'adminInventory', 'myRequests', 'cropYieldData',
            // Buyer
            'marketCrops', 'myTransactions'
        ));
    }
}
