@extends('layouts.customer')

@section('content')
<div class="mb-6">
    <a href="{{ route('customer.properties.show', $allocation->id) }}" class="inline-flex items-center text-primary font-body-sm hover:underline mb-2">
        <span class="material-symbols-outlined text-[18px] mr-1">arrow_back</span>
        Back to Property Details
    </a>
    <h2 class="font-headline-lg text-headline-lg text-on-surface">Deposit / Payment</h2>
    <p class="font-body-md text-on-surface-variant">Complete your payment for Plot {{ $allocation->plot->plot_number }} securely.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <x-card class="max-w-xl">
        <x-slot name="header">
            <h3 class="font-headline-md text-xl">Payment Details</h3>
        </x-slot>
        
        <form action="{{ route('customer.payments.store', $allocation->id) }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="bg-surface-container-low p-4 rounded-lg mb-4">
                <div class="flex justify-between items-center py-2 border-b border-outline-variant/30">
                    <span class="font-body-md">Total Amount Due</span>
                    <span class="font-headline-md text-primary">₦{{ number_format($allocation->plot->price) }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="font-body-sm text-on-surface-variant">Property Reference</span>
                    <span class="font-body-sm font-medium">{{ $allocation->plot->estate->name }} (Plot {{ $allocation->plot->plot_number }})</span>
                </div>
            </div>

            <x-input name="card_number" label="Card Number" type="text" placeholder="0000 0000 0000 0000" icon="credit_card" />
            
            <div class="grid grid-cols-2 gap-4">
                <x-input name="expiry" label="Expiry Date" type="text" placeholder="MM/YY" icon="calendar_month" />
                <x-input name="cvv" label="CVV" type="text" placeholder="123" icon="lock" />
            </div>

            <x-button type="submit" variant="primary" class="w-full justify-center">Pay ₦{{ number_format($allocation->plot->price) }}</x-button>
        </form>
    </x-card>

    <div>
        <div class="bg-secondary-container/30 border border-secondary-container rounded-xl p-6">
            <div class="flex items-center gap-3 text-on-secondary-container mb-4">
                <span class="material-symbols-outlined text-3xl">shield_lock</span>
                <h4 class="font-headline-md">Secure Payment Gateway</h4>
            </div>
            <p class="text-body-md text-on-surface-variant">
                Your payment information is encrypted and securely processed. We do not store your full card details on our servers.
            </p>
        </div>
    </div>
</div>
@endsection
