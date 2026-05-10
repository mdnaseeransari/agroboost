@extends('layouts.app')
@section('title', 'Farm Analytics')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    
    <!-- Crop Distribution Doughnut -->
    <x-card title="Crop Distribution" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>'>
        <div class="relative h-72 w-full">
            @if($cropDistribution->isEmpty())
                <div class="absolute inset-0 flex items-center justify-center text-gray-400">Not enough data</div>
            @else
                <canvas id="cropChart"></canvas>
            @endif
        </div>
    </x-card>

    <!-- Task Completion Progress -->
    <x-card title="Task Completion Rate" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>'>
        <div class="flex flex-col items-center justify-center h-72">
            @php
                $totalTasks = $tasksCompleted + $tasksPending;
                $percent = $totalTasks > 0 ? round(($tasksCompleted / $totalTasks) * 100) : 0;
            @endphp
            
            <div class="relative w-48 h-48">
                <svg class="w-full h-full" viewBox="0 0 36 36">
                    <path class="text-gray-100" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <path class="text-agro-green" stroke-dasharray="{{ $percent }}, 100" stroke-width="3" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-4xl font-extrabold text-gray-900 font-poppins">{{ $percent }}%</span>
                    <span class="text-sm text-gray-500 font-medium">Completed</span>
                </div>
            </div>
            
            <div class="flex gap-8 mt-6">
                <div class="text-center">
                    <p class="text-sm text-gray-500 font-medium mb-1">Completed</p>
                    <p class="text-xl font-bold text-gray-900">{{ $tasksCompleted }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-500 font-medium mb-1">Pending</p>
                    <p class="text-xl font-bold text-gray-900">{{ $tasksPending }}</p>
                </div>
            </div>
        </div>
    </x-card>

    <!-- Harvest Timeline (New) -->
    <div class="lg:col-span-2">
        <x-card title="Harvest Timeline" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>'>
            <div class="relative h-80 w-full">
                @if($harvestTimeline->isEmpty())
                    <div class="absolute inset-0 flex items-center justify-center text-gray-400">Not enough data to show trend</div>
                @else
                    <canvas id="harvestChart"></canvas>
                @endif
            </div>
        </x-card>
    </div>

    <!-- Inventory Levels Bar Chart -->
    <div class="lg:col-span-2">
        <x-card title="Inventory Stock Levels" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>'>
            <div class="relative h-80 w-full">
                @if($inventoryData->isEmpty())
                    <div class="absolute inset-0 flex items-center justify-center text-gray-400">Not enough data</div>
                @else
                    <canvas id="inventoryChart"></canvas>
                @endif
            </div>
        </x-card>
    </div>
</div>

<!-- Include Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Colors from Tailwind config
        const colors = ['#2D5016', '#D4A574', '#10B981', '#3B82F6', '#F59E0B', '#EF4444'];
        
        // 1. Crop Distribution Chart
        @if(!$cropDistribution->isEmpty())
        const ctxCrop = document.getElementById('cropChart').getContext('2d');
        const cropData = @json($cropDistribution);
        
        new Chart(ctxCrop, {
            type: 'doughnut',
            data: {
                labels: cropData.map(c => c.name),
                datasets: [{
                    data: cropData.map(c => c.count),
                    backgroundColor: colors,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { padding: 20, usePointStyle: true, font: { family: "'Inter', sans-serif" } }
                    }
                },
                cutout: '70%'
            }
        });
        @endif

        // 2. Inventory Chart
        @if(!$inventoryData->isEmpty())
        const ctxInv = document.getElementById('inventoryChart').getContext('2d');
        const invData = @json($inventoryData);
        
        new Chart(ctxInv, {
            type: 'bar',
            data: {
                labels: invData.map(i => i.name),
                datasets: [{
                    label: 'Quantity in Stock',
                    data: invData.map(i => i.quantity),
                    backgroundColor: '#D4A574', // agro-gold
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6', drawBorder: false }
                    },
                    x: {
                        grid: { display: false, drawBorder: false }
                    }
                }
            }
        });
        @endif

        // 3. Harvest Timeline Chart
        @if(!$harvestTimeline->isEmpty())
        const ctxHarv = document.getElementById('harvestChart').getContext('2d');
        const harvData = @json($harvestTimeline);
        
        new Chart(ctxHarv, {
            type: 'line',
            data: {
                labels: harvData.map(h => h.month),
                datasets: [{
                    label: 'Harvests Count',
                    data: harvData.map(h => h.count),
                    borderColor: '#2D5016', // agro-green
                    backgroundColor: 'rgba(45, 80, 22, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2D5016',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        grid: { color: '#f3f4f6', drawBorder: false }
                    },
                    x: {
                        grid: { display: false, drawBorder: false }
                    }
                }
            }
        });
        @endif
    });
</script>
@endsection
