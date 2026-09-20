@extends('layouts.customer')

@section('content')
<div class="mb-6">
    <h2 class="font-headline-lg text-headline-lg text-on-surface">Customer Support</h2>
    <p class="font-body-md text-on-surface-variant">We're here to help! Fill out the form below and an agent will reach out.</p>
</div>

<x-card class="max-w-2xl">
    <form action="{{ route('customer.support.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <x-input name="subject" label="Inquiry Subject" type="text" :value="$subject" required="true" icon="subject" />
        
        <div class="flex flex-col gap-1">
            <label for="message" class="font-label-md text-on-surface">Detailed Message</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-3 text-on-surface-variant pointer-events-none">chat</span>
                <textarea id="message" name="message" rows="5" required class="w-full pl-10 pr-4 py-2 bg-surface text-on-surface border border-outline focus:border-primary focus:ring-1 focus:ring-primary rounded-lg transition-colors"></textarea>
            </div>
        </div>
        
        <div class="pt-4 border-t border-outline-variant/30 flex justify-end">
            <x-button type="submit" variant="primary" icon="send">Submit Ticket</x-button>
        </div>
    </form>
</x-card>
@endsection
