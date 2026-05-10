<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\InventoryItem;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $farmId = $user->farm_id;
        $role = $user->role; // 'admin', 'farmer', 'viewer'

        // Global Metrics
        $activeCrops = Crop::where('farm_id', $farmId)->where('status', 'growing')->count();
        $lowStockItems = InventoryItem::where('farm_id', $farmId)
            ->whereRaw('quantity <= threshold_alert')->count();
        
        // Task counts based on role
        $pendingTasksQuery = Task::where('farm_id', $farmId)->where('completed', false);
        $overdueTasksQuery = Task::where('farm_id', $farmId)->where('completed', false)->where('due_date', '<', now());
        
        if ($role === 'farmer') {
            $pendingTasksQuery->where('assigned_to', $user->id);
            $overdueTasksQuery->where('assigned_to', $user->id);
        }
        
        $pendingTasks = $pendingTasksQuery->count();
        $overdueTasks = $overdueTasksQuery->count();

        // Tasks Feed
        $recentTasksQuery = Task::where('farm_id', $farmId)
            ->with('assignee')
            ->where('completed', false)
            ->orderBy('due_date');
            
        if ($role === 'farmer') {
            $recentTasksQuery->where('assigned_to', $user->id);
        }
        
        $recentTasks = $recentTasksQuery->limit(5)->get();

        // Messaging logic
        $messages = collect();
        if ($role === 'admin') {
            $messages = \App\Models\Message::where('farm_id', $farmId)->with('sender')->latest()->limit(10)->get();
        } else {
            $admin = \App\Models\User::where('farm_id', $farmId)->where('role', 'admin')->first();
            if ($admin) {
                $messages = \App\Models\Message::where('farm_id', $farmId)
                    ->where(function($q) use ($user, $admin) {
                        $q->where(function($q) use ($user, $admin) {
                            $q->where('sender_id', $user->id)->where('receiver_id', $admin->id);
                        })->orWhere(function($q) use ($user, $admin) {
                            $q->where('sender_id', $admin->id)->where('receiver_id', $user->id);
                        });
                    })
                    ->with('sender')->latest()->limit(10)->get();
            }
        }

        // Low Stock List
        $lowStockList = InventoryItem::where('farm_id', $farmId)
            ->whereRaw('quantity <= threshold_alert')
            ->limit(4)
            ->get();

        // Admin specific: Team & Activity
        $teamCount = 0;
        $teamMembers = collect();
        if ($role === 'admin') {
            $teamMembers = \App\Models\User::where('farm_id', $farmId)->limit(3)->get();
            $teamCount = \App\Models\User::where('farm_id', $farmId)->count();
        }

        // Dashboard specific crops overview
        $topCrops = Crop::where('farm_id', $farmId)
            ->where('status', 'growing')
            ->orderBy('expected_harvest_date')
            ->limit(3)
            ->get();

        return view('dashboard', compact(
            'role', 'activeCrops', 'lowStockItems', 'pendingTasks', 'overdueTasks', 
            'recentTasks', 'teamCount', 'topCrops', 'lowStockList', 'teamMembers', 'messages'
        ));
    }
}
