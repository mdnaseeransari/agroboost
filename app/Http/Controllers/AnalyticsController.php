<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Crop;
use App\Models\InventoryItem;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $farmId = $user->farm_id;
        $queryFarmId = $farmId;

        // Crop Distribution
        $cropDistribution = Crop::where('farm_id', $queryFarmId)
            ->select('name', DB::raw('count(*) as count'))
            ->groupBy('name')
            ->get();
            
        // Inventory Data
        $inventoryData = InventoryItem::where('farm_id', $queryFarmId)
            ->select('name', 'quantity')
            ->limit(10)
            ->get();

        // Tasks Status
        $tasksCompleted = Task::where('farm_id', $queryFarmId)->where('completed', true)->count();
        $tasksPending = Task::where('farm_id', $queryFarmId)->where('completed', false)->count();

        // Harvest Timeline (Last 6 months)
        $harvestTimeline = \App\Models\Harvest::where('farm_id', $queryFarmId)
            ->where('harvest_date', '>=', now()->subMonths(6))
            ->select(
                DB::raw("DATE_FORMAT(harvest_date, '%Y-%m') as sort_key"),
                DB::raw("DATE_FORMAT(harvest_date, '%b %Y') as month"), 
                DB::raw('count(*) as count')
            )
            ->groupBy('sort_key', 'month')
            ->orderBy('sort_key')
            ->get();

        // --- Sales & Revenue Analytics ---
        $totalSales = 0;
        $totalRevenue = 0;
        $monthlyRevenue = collect();

        if ($user->isAdmin()) {
            $totalSales = \App\Models\Order::count();
            $totalRevenue = \App\Models\Order::where('payment_status', 'paid')->sum('total_amount');
            $monthlyRevenue = \App\Models\Order::where('payment_status', 'paid')
                ->where('paid_at', '>=', now()->subMonths(6))
                ->select(
                    DB::raw("DATE_FORMAT(paid_at, '%Y-%m') as sort_key"),
                    DB::raw("DATE_FORMAT(paid_at, '%b %Y') as month"),
                    DB::raw('SUM(total_amount) as total')
                )
                ->groupBy('sort_key', 'month')
                ->orderBy('sort_key')
                ->get();
        } elseif ($user->isFarmer()) {
            $totalSales = \App\Models\Order::where('farmer_id', $user->id)->count();
            $totalRevenue = \App\Models\Order::where('farmer_id', $user->id)->where('payment_status', 'paid')->sum('total_amount');
            $monthlyRevenue = \App\Models\Order::where('farmer_id', $user->id)
                ->where('payment_status', 'paid')
                ->where('paid_at', '>=', now()->subMonths(6))
                ->select(
                    DB::raw("DATE_FORMAT(paid_at, '%Y-%m') as sort_key"),
                    DB::raw("DATE_FORMAT(paid_at, '%b %Y') as month"),
                    DB::raw('SUM(total_amount) as total')
                )
                ->groupBy('sort_key', 'month')
                ->orderBy('sort_key')
                ->get();
        }

        return view('analytics', compact(
            'cropDistribution', 'inventoryData', 'tasksCompleted', 'tasksPending', 'harvestTimeline',
            'totalSales', 'totalRevenue', 'monthlyRevenue'
        ));
    }
}