@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-on-surface">Plots Management Map</h2>
        <p class="font-body-md text-on-surface-variant">View and manage the status of all inventoried plots across estates.</p>
    </div>
    <a href="{{ route('admin.estates.index') }}">
        <x-button variant="outline" icon="map">Manage Estate Layouts</x-button>
    </a>
</div>

<x-card>
    <div class="mb-4 flex flex-col md:flex-row gap-4 items-center justify-between border-b border-outline-variant/30 pb-4">
        <!-- Plot Summary Counters -->
        <div class="flex gap-4 w-full md:w-auto">
            <div class="bg-surface-container-low px-4 py-2 rounded-lg flex-1">
                <div class="text-on-surface-variant text-xs font-label-md">Total Plots</div>
                <div class="font-headline-md">{{ $plots->total() ?? 0 }}</div>
            </div>
            <div class="bg-green-50 border border-green-200 px-4 py-2 rounded-lg flex-1">
                <div class="text-green-800 text-xs font-label-md">Available</div>
                <div class="font-headline-md text-green-900">{{ $plots->where('status', 'available')->count() ?? 0 }}</div>
            </div>
        </div>
        
        <!-- Filter -->
        <div class="w-full md:w-64 relative">
            <span class="material-symbols-outlined absolute left-3 top-2 text-on-surface-variant">filter_list</span>
            <select class="w-full pl-10 pr-4 py-2 bg-surface text-on-surface border border-outline focus:border-primary focus:ring-1 focus:ring-primary rounded-lg appearance-none text-sm cursor-pointer">
                <option value="">All Estates</option>
                <!-- Estate loop here if passed in view -->
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left font-body-sm text-on-surface">
            <thead class="bg-surface border-b border-outline-variant/30 text-on-surface-variant font-label-caps">
                <tr>
                    <th class="px-6 py-4 font-medium">Plot Number</th>
                    <th class="px-6 py-4 font-medium">Estate</th>
                    <th class="px-6 py-4 font-medium">Size / Price</th>
                    <th class="px-6 py-4 font-medium text-center">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @forelse($plots as $plot)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-6 py-4 font-medium text-on-surface">
                            Plot {{ $plot->plot_number }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $plot->estate->name }}
                        </td>
                        <td class="px-6 py-4">
                            <div>{{ number_format($plot->size) }} Sqm</div>
                            <div class="text-primary font-medium text-xs">₦{{ number_format($plot->price) }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusColors = [
                                    'available' => 'bg-green-100 text-green-800',
                                    'reserved' => 'bg-amber-100 text-amber-800',
                                    'allocated' => 'bg-primary-container text-on-primary-container',
                                    'sold' => 'bg-surface-container-highest text-on-surface-variant',
                                ];
                                $colorClass = $statusColors[$plot->status] ?? 'bg-surface-container-high text-on-surface';
                            @endphp
                            <span class="inline-flex px-2 py-1 rounded text-[10px] font-label-caps uppercase tracking-wider {{ $colorClass }}">
                                {{ $plot->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="p-2 text-primary hover:bg-primary-fixed/30 rounded-lg transition-colors border border-outline-variant/50 mr-1" title="Edit Plot Details">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl block mb-2 opacity-50">grid_off</span>
                            No plots mapped onto the platform yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(method_exists($plots, 'links'))
        <div class="mt-4 border-t border-outline-variant/30 pt-4">
            {{ $plots->links() }}
        </div>
    @endif
</x-card>
@endsection
