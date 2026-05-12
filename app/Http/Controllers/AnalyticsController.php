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

        if ($user->role === 'buyer') {
            return redirect()->route('dashboard')->with('error', 'Access denied to Analytics section.');
        }

        // For farmers, show only their crops, etc.
        $queryFarmId = $farmId;
        if ($user->role === 'farmer') {
            $queryFarmId = $farmId; // already their farm
        }

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

        return view('analytics', compact('cropDistribution', 'inventoryData', 'tasksCompleted', 'tasksPending', 'harvestTimeline'));
}
}