@extends('layouts.customer')

@section('content')
<div class="mb-4">
    <h2 class="font-headline-lg text-headline-lg text-on-surface">Welcome back, {{ Auth::user()->first_name }}</h2>
    <p class="font-body-md text-on-surface-variant">Here's an overview of your properties and recent activity.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <x-stat-card title="TOTAL PROPERTIES" :value="count($allocations)" icon="domain" color="primary" />
    <x-stat-card title="OUTSTANDING PAYMENTS" value="₦0.00" icon="payments" color="secondary" />
    <x-stat-card title="DOCUMENTS" value="2" icon="description" color="tertiary" />
</div>

<h3 class="font-headline-md text-headline-md text-on-surface mb-4">My Allocated Properties</h3>
<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($allocations as $allocation)
        <x-card class="hover:border-primary/50 transition-colors">
            <x-slot name="header">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-headline-md text-[18px] text-on-surface">{{ $allocation->plot->estate->name }}</h4>
                        <p class="font-body-sm text-on-surface-variant mt-1">{{ $allocation->plot->estate->location }}</p>
                    </div>
                </div>
            </x-slot>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-outline-variant/30">
                    <span class="font-label-caps text-on-surface-variant">Plot Number</span>
                    <span class="font-body-md font-medium">{{ $allocation->plot->plot_number }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-outline-variant/30">
                    <span class="font-label-caps text-on-surface-variant">Size</span>
                    <span class="font-body-md">{{ number_format($allocation->plot->size) }} Sqm</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-outline-variant/30">
                    <span class="font-label-caps text-on-surface-variant">Payment Status</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded font-label-caps text-xs uppercase">{{ $allocation->payment_status }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="font-label-caps text-on-surface-variant">Allocated On</span>
                    <span class="font-body-md">{{ \Carbon\Carbon::parse($allocation->allocation_date)->format('M d, Y') }}</span>
                </div>
            </div>
            
            <x-slot name="footer">
                <div class="flex gap-3">
                    <a href="{{ route('customer.properties.show', $allocation->id) }}" class="flex-1">
                        <x-button variant="primary" class="w-full">View Details</x-button>
                    </a>
                </div>
            </x-slot>
        </x-card>
    @empty
        <div class="col-span-full">
            <x-card class="text-center py-12">
                <span class="material-symbols-outlined text-4xl text-outline mb-4">home_work</span>
                <h4 class="font-headline-md text-on-surface mb-2">No properties yet</h4>
                <p class="text-on-surface-variant font-body-md mb-6">You don't have any allocated properties at the moment.</p>
                <a href="{{ route('customer.map.index') }}">
                    <x-button variant="primary" icon="map">Explore Estates</x-button>
                </a>
            </x-card>
        </div>
    @endforelse
</div>
@endsection
