@extends('layouts.customer')

@section('content')
<div class="mb-6">
    <h2 class="font-headline-lg text-headline-lg text-on-surface">Account Settings</h2>
    <p class="font-body-md text-on-surface-variant">Update your personal Profile details and secure your account.</p>
</div>

<div class="max-w-3xl">
    <x-card>
        <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-input name="first_name" label="First Name" required="true" :value="$user->first_name" icon="person" />
                <x-input name="last_name" label="Last Name" required="true" :value="$user->last_name" icon="badge" />
            </div>
            
            <x-input name="email" label="Email Address" type="email" required="true" :value="$user->email" icon="mail" />
            
            <x-input name="phone" label="Phone Number" type="tel" :value="$user->phone" icon="call" />
            
            <div class="pt-6 border-t border-outline-variant/30">
                <h3 class="font-headline-md text-on-surface mb-4">Change Password</h3>
                <p class="text-body-sm text-on-surface-variant mb-4">Leave fields blank if you don't wish to change the password.</p>
                
                <div class="space-y-4">
                    <x-input name="password" label="New Password" type="password" icon="lock" />
                    <x-input name="password_confirmation" label="Confirm New Password" type="password" icon="lock" />
                </div>
            </div>
            
            <div class="pt-4 border-t border-outline-variant/30 flex justify-end gap-3">
                <x-button type="submit" variant="primary" icon="save">Save Changes</x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
