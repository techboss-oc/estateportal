@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-on-surface">New Customer</h2>
        <p class="font-body-md text-on-surface-variant">Register a new customer profile into the system.</p>
    </div>
    <a href="{{ route('admin.customers.index') }}">
        <x-button variant="outline" icon="arrow_back">Back to Customers</x-button>
    </a>
</div>

<div class="max-w-3xl">
    <x-card>
        <form action="{{ route('admin.customers.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <h3 class="font-headline-sm text-on-surface mb-2 border-b border-outline-variant/30 pb-2">Personal Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-input name="first_name" label="First Name" required="true" icon="person" />
                <x-input name="last_name" label="Last Name" required="true" icon="badge" />
                
                <x-input name="email" label="Email Address" type="email" required="true" icon="mail" />
                <x-input name="phone" label="Phone Number" type="tel" icon="call" />
            </div>

            <h3 class="font-headline-sm text-on-surface mb-2 border-b border-outline-variant/30 pb-2 pt-4">Account Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-input name="username" label="Username" required="true" icon="account_circle" />
                <x-input name="password" label="Temporary Password" type="password" required="true" icon="lock" />
            </div>

            <div class="pt-4 border-t border-outline-variant/30 flex justify-end">
                <x-button type="submit" variant="primary" icon="person_add">Create Customer</x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
