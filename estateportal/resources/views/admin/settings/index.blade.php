@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h2 class="font-headline-lg text-headline-lg text-on-surface">Portal Settings</h2>
    <p class="font-body-md text-on-surface-variant">Global configuration and platform preferences.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="md:col-span-1 space-y-2">
        <button class="w-full text-left px-4 py-3 bg-primary-fixed/30 text-primary font-bold border-l-4 border-primary rounded-r-lg">
            General Options
        </button>
        <button class="w-full text-left px-4 py-3 text-on-surface-variant hover:bg-surface-container-low rounded-lg transition-colors">
            Payment Gateways
        </button>
        <button class="w-full text-left px-4 py-3 text-on-surface-variant hover:bg-surface-container-low rounded-lg transition-colors">
            Security & Backup
        </button>
    </div>

    <div class="md:col-span-2 space-y-6">
        <x-card>
            <h3 class="font-headline-sm text-on-surface mb-6 border-b border-outline-variant/30 pb-2">Global System Settings</h3>
            
            <form action="#" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input name="platform_name" label="Platform Name" value="EstatePortal" icon="domain" />
                    <x-input name="support_email" label="Global Support Email" value="admin@estateportal.test" icon="mail" />
                </div>
                
                <div class="flex flex-col gap-1">
                    <label class="font-label-md text-on-surface">Allow Open Registration</label>
                    <div class="flex items-center gap-4 mt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="registration" class="text-primary focus:ring-primary h-4 w-4" checked>
                            <span class="text-body-sm">Yes, allow signups</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="registration" class="text-primary focus:ring-primary h-4 w-4">
                            <span class="text-body-sm">No, invite only</span>
                        </label>
                    </div>
                </div>

                <div class="pt-4 border-t border-outline-variant/30 flex justify-end">
                    <x-button type="submit" variant="primary" icon="save">Save Preferences</x-button>
                </div>
            </form>
        </x-card>
    </div>
</div>
@endsection
