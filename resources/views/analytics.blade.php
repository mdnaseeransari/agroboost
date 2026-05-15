@extends('layouts.app')
@section('title', 'Advanced Analytics')

@section('content')
<div class="space-y-8">
    <!-- Top Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Sales</p>
            <p class="text-3xl font-bold text-gray-900">{{ $totalSales }}</p>
            <p class="text-[10px] text-agro-green font-bold mt-2 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                Orders recorded
            </p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Revenue</p>
            <p class="text-3xl font-bold text-gray-900">${{ number_format($totalRevenue, 2) }}</p>
            <p class="text-[10px] text-agro-green font-bold mt-2 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Paid earnings
            </p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Tasks Done</p>
            <p class="text-3xl font-bold text-gray-900">{{ $tasksCompleted }}</p>
            <p class="text-[10px] text-gray-400 font-bold mt-2">{{ $tasksPending }} pending</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Active Crops</p>
            <p class="text-3xl font-bold text-gray-900">{{ $cropDistribution->sum('count') }}</p>
            <p class="text-[10px] text-agro-gold font-bold mt-2">Diversity in fields</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Revenue Growth Line Chart -->
        <div class="lg:col-span-2">
            <x-card title="Revenue Growth" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'>
                <div class="relative h-80 w-full">
                    @if($monthlyRevenue->isEmpty())
                        <div class="absolute inset-0 flex items-center justify-center text-gray-400">Not enough revenue data to show trend</div>
                    @else
                        <canvas id="revenueChart"></canvas>
                    @endif
                </div>
            </x-card>
        </div>

        <!-- Crop Distribution Doughnut -->
        <x-card title="Crop Distribution" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>'>
            <div class="relative h-72 w-full">
                @if($cropDistribution->isEmpty())
                    <div class="absolute inset-0 flex items-center justify-center text-gray-400">No active crops</div>
                @else
                    <canvas id="cropChart"></canvas>
                @endif
            </div>
        </x-card>

        <!-- Inventory Levels Bar Chart -->
        <x-card title="Inventory Stock Levels" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>'>
            <div class="relative h-72 w-full">
                @if($inventoryData->isEmpty())
                    <div class="absolute inset-0 flex items-center justify-center text-gray-400">No inventory data</div>
                @else
                    <canvas id="inventoryChart"></canvas>
                @endif
            </div>
        </x-card>

        <!-- Harvest Timeline -->
        <div class="lg:col-span-2">
            <x-card title="Harvest Frequency" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>'>
                <div class="relative h-80 w-full">
                    @if($harvestTimeline->isEmpty())
                        <div class="absolute inset-0 flex items-center justify-center text-gray-400">No recent harvests recorded</div>
                    @else
                        <canvas id="harvestChart"></canvas>
                    @endif
                </div>
            </x-card>
        </div>
    </div>
</div>

<!-- Include Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colors = ['#2D5016', '#D4A574', '#10B981', '#3B82F6', '#F59E0B', '#EF4444'];
        
        // 1. Revenue Chart
        @if(!$monthlyRevenue->isEmpty())
        const ctxRev = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: @json($monthlyRevenue->pluck('month')),
                datasets: [{
                    label: 'Revenue ($)',
                    data: @json($monthlyRevenue->pluck('total')),
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#f3f4f6', drawBorder: false } },
                    x: { grid: { display: false } }
                }
            }
        });
        @endif

        // 2. Crop Chart
        @if(!$cropDistribution->isEmpty())
        const ctxCrop = document.getElementById('cropChart').getContext('2d');
        new Chart(ctxCrop, {
            type: 'doughnut',
            data: {
                labels: @json($cropDistribution->pluck('name')),
                datasets: [{
                    data: @json($cropDistribution->pluck('count')),
                    backgroundColor: colors,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { usePointStyle: true, padding: 20 } }
                },
                cutout: '75%'
            }
        });
        @endif

        // 3. Inventory Chart
        @if(!$inventoryData->isEmpty())
        const ctxInv = document.getElementById('inventoryChart').getContext('2d');
        new Chart(ctxInv, {
            type: 'bar',
            data: {
                labels: @json($inventoryData->pluck('name')),
                datasets: [{
                    label: 'Quantity',
                    data: @json($inventoryData->pluck('quantity')),
                    backgroundColor: '#D4A574',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#f3f4f6', drawBorder: false } },
                    x: { grid: { display: false } }
                }
            }
        });
        @endif

        // 4. Harvest Chart
        @if(!$harvestTimeline->isEmpty())
        const ctxHarv = document.getElementById('harvestChart').getContext('2d');
        new Chart(ctxHarv, {
            type: 'line',
            data: {
                labels: @json($harvestTimeline->pluck('month')),
                datasets: [{
                    label: 'Harvests',
                    data: @json($harvestTimeline->pluck('count')),
                    borderColor: '#2D5016',
                    backgroundColor: 'rgba(45, 80, 22, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
        @endif
    });
</script>
@endsection
