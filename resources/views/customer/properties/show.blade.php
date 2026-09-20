@extends('layouts.customer')

@section('content')
<div class="mb-4">
    <a href="{{ route('customer.properties.index') }}" class="inline-flex items-center text-primary font-body-sm font-medium hover:underline mb-2">
        <span class="material-symbols-outlined text-[18px] mr-1">arrow_back</span>
        Back to Properties
    </a>
    <h2 class="font-headline-lg text-headline-lg text-on-surface">Property Details: {{ $allocation->plot->plot_number }}</h2>
    <p class="font-body-md text-on-surface-variant">{{ $allocation->plot->estate->name }} - {{ $allocation->plot->estate->location }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <x-card headerClass="pb-4">
            <x-slot name="header">
                <h3 class="font-headline-md text-[20px] text-on-surface">Property Overview</h3>
            </x-slot>
            
            <div class="grid grid-cols-2 gap-y-6 gap-x-4">
                <div>
                    <span class="block font-label-caps text-on-surface-variant mb-1">Estate</span>
                    <span class="font-body-md">{{ $allocation->plot->estate->name }}</span>
                </div>
                <div>
                    <span class="block font-label-caps text-on-surface-variant mb-1">Plot ID</span>
                    <span class="font-body-md">{{ $allocation->plot->plot_number }}</span>
                </div>
                <div>
                    <span class="block font-label-caps text-on-surface-variant mb-1">Size</span>
                    <span class="font-body-md">{{ number_format($allocation->plot->size) }} Sqm</span>
                </div>
                <div>
                    <span class="block font-label-caps text-on-surface-variant mb-1">Price</span>
                    <span class="font-body-md">₦{{ number_format($allocation->plot->price) }}</span>
                </div>
                <div>
                    <span class="block font-label-caps text-on-surface-variant mb-1">Payment Status</span>
                    <span class="font-body-md inline-flex px-2 py-1 {{ $allocation->payment_status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-primary-container text-on-primary-container' }} rounded text-sm uppercase">{{ $allocation->payment_status }}</span>
                </div>
                <div>
                    <span class="block font-label-caps text-on-surface-variant mb-1">Allocation Date</span>
                    <span class="font-body-md">{{ \Carbon\Carbon::parse($allocation->allocation_date)->format('M d, Y') }}</span>
                </div>
            </div>
        </x-card>

        <x-card headerClass="pb-4">
            <x-slot name="header">
                <h3 class="font-headline-md text-[20px] text-on-surface">Associated Documents</h3>
            </x-slot>
            
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 border border-outline-variant/30 rounded-lg bg-surface">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">description</span>
                        <div>
                            <span class="block font-body-sm font-medium">Allocation Letter</span>
                            <span class="block font-label-caps text-on-surface-variant">PDF • 2MB</span>
                        </div>
                    </div>
                    <x-button variant="secondary" icon="download">Download</x-button>
                </div>
                <!-- Additional documents later -->
            </div>
        </x-card>
    </div>
    
    <div class="space-y-6">
        <x-card headerClass="pb-4">
            <x-slot name="header">
                <h3 class="font-headline-md text-[20px] text-on-surface">Quick Actions</h3>
            </x-slot>
            
            <div class="flex flex-col gap-3">
                <a href="{{ route('customer.map.index', ['plot_id' => $allocation->plot_id]) }}">
                    <x-button variant="primary" icon="location_on" class="w-full justify-start">View on Map</x-button>
                </a>
                <a href="{{ route('customer.payments.create', $allocation->id) }}">
                    <x-button variant="secondary" icon="payments" class="w-full justify-start">Make Payment</x-button>
                </a>
                <a href="{{ route('customer.support.index', ['subject' => 'Inquiry for ' . $allocation->plot->plot_number]) }}">
                    <x-button variant="outline" icon="support_agent" class="w-full justify-start text-on-surface">Request Support</x-button>
                </a>
            </div>
        </x-card>
    </div>
</div>
@endsection
