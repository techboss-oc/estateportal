@extends('layouts.admin')

@section('title', 'Administrator Dashboard')

@section('content')
<!-- Header & Quick Actions -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-on-surface mb-1">Administrator Dashboard</h2>
        <p class="font-body-md text-on-surface-variant">System overview and high-level operations</p>
    </div>
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.estates.create') }}" class="flex items-center gap-2 bg-surface-container text-on-surface font-body-sm py-2 px-4 rounded-lg hover:bg-surface-container-high transition-colors border border-outline-variant/30 shadow-soft">
            <span class="material-symbols-outlined text-[18px]">domain_add</span>
            Add Estate
        </a>
        <a href="{{ route('admin.customers.create') }}" class="flex items-center gap-2 bg-surface-container text-on-surface font-body-sm py-2 px-4 rounded-lg hover:bg-surface-container-high transition-colors border border-outline-variant/30 shadow-soft">
            <span class="material-symbols-outlined text-[18px]">person_add</span>
            Add Customer
        </a>
        <a href="{{ route('admin.allocations.create') }}" class="flex items-center gap-2 bg-primary text-on-primary font-body-sm py-2 px-4 rounded-lg hover:opacity-90 transition-opacity shadow-soft">
            <span class="material-symbols-outlined text-[18px]">add_location_alt</span>
            Allocate Plot
        </a>
    </div>
</div>

<!-- Stats Bento Grid -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
    <x-stat-card title="TOTAL ESTATES" :value="$stats['total_estates']" icon="real_estate_agent" color="primary" />
    <x-stat-card title="TOTAL PLOTS" :value="$stats['total_plots']" icon="grid_on" color="secondary" />
    <x-stat-card title="ALLOCATED" :value="$stats['allocated_plots']" icon="check_circle" color="primary" />
    <x-stat-card title="AVAILABLE" :value="$stats['available_plots']" icon="radio_button_unchecked" color="secondary" />
    <x-stat-card title="RESERVED" :value="$stats['reserved_plots']" icon="bookmark" color="tertiary" />
    <x-stat-card title="CUSTOMERS" :value="$stats['total_customers']" icon="groups" color="primary" />
</div>

<!-- Visuals Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Estate Performance -->
    <x-card headerClass="pb-4" contentClass="p-0">
        <x-slot name="header">
            <h3 class="font-headline-md text-[20px] text-on-surface">Estate Performance</h3>
            <a href="{{ route('admin.estates.index') }}" class="text-primary font-body-sm hover:underline">View All</a>
        </x-slot>
        
        <div class="space-y-4 p-6">
            @forelse($estates as $estate)
            @php
                $allocated = $estate->plots()->where('status', 'allocated')->count();
                $total = $estate->plots_count ?: 1;
                $percent = round(($allocated / $total) * 100);
            @endphp
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-surface-container overflow-hidden shrink-0">
                    <div class="w-full h-full bg-primary/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">domain</span>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between mb-1">
                        <span class="font-body-md font-medium text-on-surface truncate">{{ $estate->name }}</span>
                        <span class="font-label-caps text-primary">{{ $percent }}%</span>
                    </div>
                    <div class="w-full bg-surface-variant rounded-full h-1.5">
                        <div class="bg-primary h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
            </div>
            @empty
                <p class="text-on-surface-variant">No estates added yet.</p>
            @endforelse
        </div>
    </x-card>
    
    <!-- Recent Allocations Table -->
    <x-card headerClass="pb-4" contentClass="p-0 overflow-x-auto" class="lg:col-span-2">
        <x-slot name="header">
            <h3 class="font-headline-md text-[20px] text-on-surface">Recent Allocations</h3>
        </x-slot>
        
        <table class="w-full text-left font-body-sm text-on-surface">
            <thead class="bg-surface font-label-caps text-on-surface-variant border-b border-outline-variant/30">
                <tr>
                    <th class="px-6 py-4 font-medium">Customer Name</th>
                    <th class="px-6 py-4 font-medium">Estate</th>
                    <th class="px-6 py-4 font-medium">Plot ID</th>
                    <th class="px-6 py-4 font-medium">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @forelse($recentAllocations as $allocation)
                <tr class="hover:bg-surface-container-low/50 transition-colors">
                    <td class="px-6 py-4 font-medium">{{ $allocation->customer->first_name }} {{ $allocation->customer->last_name }}</td>
                    <td class="px-6 py-4">{{ $allocation->plot->estate->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 font-label-caps text-on-surface-variant">{{ $allocation->plot->plot_number ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-on-surface-variant">{{ $allocation->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-on-surface-variant">No allocations found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </x-card>
</div>
@endsection
