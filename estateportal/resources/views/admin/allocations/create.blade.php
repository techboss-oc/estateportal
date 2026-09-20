@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-on-surface">New Plot Allocation</h2>
        <p class="font-body-md text-on-surface-variant">Assign a specific plot to a registered customer.</p>
    </div>
    <a href="{{ route('admin.allocations.index') }}">
        <x-button variant="outline" icon="arrow_back">Back to Allocations</x-button>
    </a>
</div>

<div class="max-w-3xl">
    <x-card>
        {{-- Duplicate / validation errors --}}
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
                <span class="material-symbols-outlined text-red-600 text-[20px] mt-0.5">error</span>
                <div>
                    <p class="font-bold text-red-800 text-sm">Allocation Failed</p>
                    @foreach($errors->all() as $error)
                        <p class="text-error text-sm mt-1">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="{{ route('admin.allocations.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Selection -->
                <div class="flex flex-col gap-1">
                    <label for="user_id" class="font-label-md text-on-surface">Select Customer</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-3 text-on-surface-variant pointer-events-none">person</span>
                        <select id="user_id" name="user_id" required class="w-full pl-10 pr-4 py-2 bg-surface text-on-surface border border-outline focus:border-primary focus:ring-1 focus:ring-primary rounded-lg appearance-none cursor-pointer">
                            <option value="">-- Choose Customer --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }} ({{ $customer->email }})</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-3 text-on-surface-variant pointer-events-none">arrow_drop_down</span>
                    </div>
                </div>

                <!-- Plot Selection -->
                <div class="flex flex-col gap-1">
                    <label for="plot_id" class="font-label-md text-on-surface">Select Available Plot</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-3 text-on-surface-variant pointer-events-none">domain</span>
                        <select id="plot_id" name="plot_id" required class="w-full pl-10 pr-4 py-2 bg-surface text-on-surface border border-outline focus:border-primary focus:ring-1 focus:ring-primary rounded-lg appearance-none cursor-pointer">
                            <option value="">-- Choose Plot --</option>
                            @foreach($plots as $plot)
                                <option value="{{ $plot->id }}">{{ $plot->estate->name }} - Plot {{ $plot->plot_number }} (₦{{ number_format($plot->price) }})</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-3 text-on-surface-variant pointer-events-none">arrow_drop_down</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col gap-1">
                    <label for="amount_paid" class="font-label-md text-on-surface">Initial Deposit / Amount Paid (₦)</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-3 text-on-surface-variant pointer-events-none">payments</span>
                        <input type="number" id="amount_paid" name="amount_paid" value="0" step="0.01" required class="w-full pl-10 pr-4 py-2 bg-surface text-on-surface border border-outline focus:border-primary focus:ring-1 focus:ring-primary rounded-lg">
                    </div>
                </div>
                
                <div class="flex flex-col gap-1">
                    <label for="payment_status" class="font-label-md text-on-surface">Payment Status</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-3 text-on-surface-variant pointer-events-none">receipt_long</span>
                        <select id="payment_status" name="payment_status" required class="w-full pl-10 pr-4 py-2 bg-surface text-on-surface border border-outline focus:border-primary focus:ring-1 focus:ring-primary rounded-lg appearance-none cursor-pointer">
                            <option value="pending">Pending</option>
                            <option value="partial">Partial Payment</option>
                            <option value="completed">Completed</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-3 text-on-surface-variant pointer-events-none">arrow_drop_down</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-1">
                <label for="allocation_date" class="font-label-md text-on-surface">Allocation Date</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-3 text-on-surface-variant pointer-events-none">calendar_today</span>
                    <input type="date" id="allocation_date" name="allocation_date" value="{{ date('Y-m-d') }}" required class="w-full pl-10 pr-4 py-2 bg-surface text-on-surface border border-outline focus:border-primary focus:ring-1 focus:ring-primary rounded-lg">
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <x-button type="submit" variant="primary" icon="check_circle">Allocate Plot</x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
