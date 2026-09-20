@extends('layouts.customer')

@section('title', 'My Estates')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="font-headline-md text-headline-md text-on-surface font-bold">My Estates</h2>
        <p class="font-body-sm text-on-surface-variant">All estates where you have active plot allocations.</p>
    </div>

    @if($estates->isEmpty())
        <div class="glass-panel rounded-xl p-12 text-center border border-outline-variant">
            <span class="material-symbols-outlined text-5xl text-on-surface-variant mb-4 block">domain_disabled</span>
            <h3 class="font-headline-sm font-semibold text-on-surface mb-2">No Estates Yet</h3>
            <p class="text-on-surface-variant text-sm">You don't have any plot allocations. Contact the administrator to get started.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($estates as $estate)
                <a href="{{ route('customer.estates.show', $estate) }}" class="glass-panel rounded-xl overflow-hidden hover:shadow-lg transition-all duration-200 group border border-outline-variant">
                    @if($estate->layout_file && strtolower($estate->layout_type) !== 'pdf')
                        <div class="h-40 overflow-hidden bg-surface-container">
                            <img src="{{ asset('storage/' . $estate->layout_file) }}" alt="{{ $estate->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                    @else
                        <div class="h-40 bg-gradient-to-br from-primary/10 to-primary-container/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-5xl text-primary" style="font-variation-settings:'FILL' 1;">domain</span>
                        </div>
                    @endif
                    <div class="p-5">
                        <h3 class="font-headline-sm font-bold text-on-surface group-hover:text-primary transition-colors">{{ $estate->name }}</h3>
                        <p class="text-on-surface-variant text-sm mt-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">location_on</span>
                            {{ $estate->location }}
                        </p>
                        <div class="mt-3 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div>
                                    <p class="text-xs text-on-surface-variant">My Plots</p>
                                    <p class="font-bold text-primary">{{ $estate->plots->count() }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-on-surface-variant">Status</p>
                                    <p class="font-medium text-emerald-600 text-sm">{{ ucfirst($estate->status) }}</p>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-primary group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
