@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-on-surface">Edit Estate</h2>
        <p class="font-body-md text-on-surface-variant">Update details for {{ $estate->name }}.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.layouts.edit', $estate->id) }}">
            <x-button variant="secondary" icon="map">Manage Map Layout</x-button>
        </a>
        <a href="{{ route('admin.estates.index') }}">
            <x-button variant="outline" icon="arrow_back">Back to Estates</x-button>
        </a>
    </div>
</div>

<div class="max-w-3xl">
    <x-card>
        <form action="{{ route('admin.estates.update', $estate->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <h3 class="font-headline-sm text-on-surface mb-2 border-b border-outline-variant/30 pb-2">Estate Details</h3>
            
            <x-input name="name" label="Estate Name" required="true" icon="domain" :value="$estate->name" />
            
            <div class="flex flex-col gap-1">
                <label for="description" class="font-label-md text-on-surface">Description</label>
                <textarea id="description" name="description" rows="3" class="w-full p-3 bg-surface text-on-surface border border-outline focus:border-primary focus:ring-1 focus:ring-primary rounded-lg transition-colors">{{ old('description', $estate->description) }}</textarea>
            </div>
            
            <x-input name="location" label="Physical Location" required="true" icon="location_on" :value="$estate->location" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-input name="total_area" label="Total Land Area (Sqm)" type="number" required="true" icon="square_foot" :value="$estate->total_area" />
                
                <div class="flex flex-col gap-1">
                    <label for="status" class="font-label-md text-on-surface">Status</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-3 text-on-surface-variant pointer-events-none">toggle_on</span>
                        <select id="status" name="status" class="w-full pl-10 pr-4 py-2 bg-surface text-on-surface border border-outline focus:border-primary focus:ring-1 focus:ring-primary rounded-lg appearance-none cursor-pointer">
                            <option value="active" {{ $estate->status === 'active' ? 'selected' : '' }}>Active (Selling)</option>
                            <option value="inactive" {{ $estate->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="completed" {{ $estate->status === 'completed' ? 'selected' : '' }}>Completed (Sold Out)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-outline-variant/30 flex justify-end">
                <x-button type="submit" variant="primary" icon="save">Update Estate</x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
