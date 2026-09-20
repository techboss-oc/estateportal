@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-on-surface">Allocation Details</h2>
        <p class="font-body-md text-on-surface-variant">Reference: {{ $allocation->allocation_reference }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.allocations.index') }}">
            <x-button variant="outline" icon="arrow_back">Back to List</x-button>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <x-card>
            <h3 class="font-headline-sm text-on-surface mb-4 border-b border-outline-variant/30 pb-2">Property Information</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <div class="text-on-surface-variant">Estate</div>
                    <div class="font-medium text-on-surface">{{ $allocation->plot->estate->name ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-on-surface-variant">Plot Number</div>
                    <div class="font-medium text-on-surface">{{ $allocation->plot->plot_number }}</div>
                </div>
                <div>
                    <div class="text-on-surface-variant">Size</div>
                    <div class="font-medium text-on-surface">{{ $allocation->plot->size_sqm }} sqm</div>
                </div>
                <div>
                    <div class="text-on-surface-variant">Allocation Status</div>
                    <div class="mt-1">
                        <span class="inline-flex px-2 py-1 rounded text-[10px] font-label-caps uppercase tracking-wider {{ $allocation->status === 'active' ? 'bg-primary-container text-on-primary-container' : 'bg-error-container text-on-error-container' }}">
                            {{ $allocation->status }}
                        </span>
                    </div>
                </div>
                <div>
                    <div class="text-on-surface-variant">Allocated On</div>
                    <div class="font-medium text-on-surface">{{ $allocation->allocated_at?->format('M j, Y g:i A') ?? 'N/A' }}</div>
                </div>
            </div>
        </x-card>

        <x-card>
            <h3 class="font-headline-sm text-on-surface mb-4 border-b border-outline-variant/30 pb-2">Customer Profile</h3>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-lg">
                    {{ substr($allocation->customer->first_name ?? '', 0, 1) }}{{ substr($allocation->customer->last_name ?? '', 0, 1) }}
                </div>
                <div>
                    <div class="font-medium text-on-surface">{{ $allocation->customer->full_name ?? 'N/A' }}</div>
                    <div class="text-sm text-on-surface-variant">{{ $allocation->customer->email ?? 'N/A' }}</div>
                </div>
            </div>
        </x-card>
    </div>

    <div class="space-y-6">
        <x-card>
            <h3 class="font-headline-sm text-on-surface mb-4 border-b border-outline-variant/30 pb-2">Payment Details</h3>
            <div class="space-y-4 text-sm">
                <div>
                    <div class="text-on-surface-variant mb-1">Status</div>
                    <span class="inline-flex px-2 py-1 rounded text-[10px] font-label-caps uppercase tracking-wider {{ $allocation->payment_status === 'completed' ? 'bg-primary-container text-on-primary-container' : 'bg-surface-container-high text-on-surface' }}">
                        {{ $allocation->payment_status ?? 'pending' }}
                    </span>
                </div>
                <div>
                    <div class="text-on-surface-variant">Amount Paid</div>
                    <div class="font-medium text-on-surface">₦{{ number_format($allocation->amount_paid ?? 0, 2) }}</div>
                </div>
            </div>
        </x-card>

        <x-card>
            <h3 class="font-headline-sm text-error mb-4 border-b border-error/20 pb-2">Danger Zone</h3>
            <p class="text-sm text-on-surface-variant mb-4">Revoking this allocation will cancel the customer's ownership and return the plot to available status in the estate map.</p>
            <form action="{{ route('admin.allocations.destroy', $allocation->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to completely revoke this plot allocation?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-2 px-4 bg-error text-on-error hover:bg-error/90 rounded-lg font-medium transition-colors">
                    Revoke Allocation
                </button>
            </form>
        </x-card>
    </div>
</div>
@endsection
