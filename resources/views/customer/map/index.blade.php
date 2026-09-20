@extends('layouts.customer')

@section('content')
<div class="mb-4">
    <h2 class="font-headline-lg text-headline-lg text-on-surface">Estate Map Explorer</h2>
    <p class="font-body-md text-on-surface-variant">Explore interactively designed layout plans for our estates.</p>
</div>

<!-- Using the shared reusable map component we created -->
<x-plot-map sidebarOpen="true">
    <x-slot name="toolbar">
        <button class="p-2 rounded-lg bg-primary-fixed/50 text-primary hover:bg-primary-fixed transition-colors">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">near_me</span>
        </button>
        <button class="p-2 rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors">
            <span class="material-symbols-outlined">zoom_in</span>
        </button>
        <button class="p-2 rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors">
            <span class="material-symbols-outlined">zoom_out</span>
        </button>
    </x-slot>
    
    <x-slot name="canvasInfo">
        <span>Estate: Potter's House</span>
        <span class="w-px h-3 bg-outline-variant"></span>
        <span>Showing All Layouts</span>
    </x-slot>

    <!-- Canvas SVG overlay -->
    <div class="absolute inset-0 pointer-events-none opacity-10 bg-[url('https://lh3.googleusercontent.com/aida-public/AB6AXuCbe2rFh8T7dJuXZPKq55IVBwtSXb0UfQoD_fywd_VyHpokD-1HcieQfVbzAibc9r3kC_qxkYUtStuh3eUGO2SG1IieznDrPuvaXoPqClcscUQnKstJqhm-DIBB_pFpFsde8Ob-iaXDzxUylobA8lD2n_90tKyaVmOH7CBT9Dvq__7B2pUddsIp96yg_rtqR8SW4Q9Z-Tp_BKtN9mtkSezTro4EnhTK0btmQFDJCNcU1o_FhsFPh24i')] bg-cover bg-center"></div>
    
    <svg class="absolute inset-0 z-0 h-full w-full" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <pattern height="10" id="allocated-pattern" patternunits="userSpaceOnUse" width="10">
                <line opacity="0.5" stroke="#c3c6d5" stroke-width="1" x1="0" x2="10" y1="10" y2="0"></line>
            </pattern>
        </defs>
        
        @foreach($estates as $estate)
            @foreach($estate->plots as $plot)
                @if($plot->coordinates)
                    @php 
                        $coordsString = '';
                        $coords = json_decode($plot->coordinates, true);
                        if(is_array($coords)) {
                            foreach($coords as $point) {
                                $coordsString .= $point[0] . ',' . $point[1] . ' ';
                            }
                        }
                    @endphp
                    @if($plot->status === 'allocated')
                        <polygon points="{{ $coordsString }}" fill="url(#allocated-pattern)" stroke="#ffffff" stroke-width="1" class="cursor-not-allowed hover:opacity-80 transition-opacity"></polygon>
                        <text fill="#737784" font-family="JetBrains Mono" font-size="12" text-anchor="middle" x="{{ $coords[0][0]+30 ?? 0 }}" y="{{ $coords[0][1]+30 ?? 0 }}">{{ $plot->plot_number }}</text>
                    @else
                        <!-- Available -->
                        <polygon points="{{ $coordsString }}" fill="#e0e3e5" stroke="#ffffff" stroke-width="1" class="cursor-pointer hover:fill-primary-fixed hover:-translate-y-px transition-all"></polygon>
                        <text fill="#434653" font-family="JetBrains Mono" font-size="12" text-anchor="middle" x="{{ $coords[0][0]+30 ?? 0 }}" y="{{ $coords[0][1]+30 ?? 0 }}">{{ $plot->plot_number }}</text>
                    @endif
                @endif
            @endforeach
        @endforeach
    </svg>

    <x-slot name="sidebar">
        <div class="px-6 py-5 border-b border-outline-variant flex justify-between items-center bg-surface/50">
            <div>
                <h2 class="font-headline-md text-headline-md text-on-surface">Estate Explorer</h2>
                <p class="text-label-caps text-on-surface-variant mt-1">Select a plot</p>
            </div>
            <button class="text-on-surface-variant hover:text-on-surface rounded-full p-1 hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6">
            <p class="text-on-surface-variant text-body-md text-center py-8">Click on an available plot on the map to see its details and make an inquiry.</p>
        </div>
    </x-slot>
</x-plot-map>
@endsection
