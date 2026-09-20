@extends('layouts.customer')

@section('title', $estate->name . ' — Estate Map')

@push('styles')
<style>
    /* ── Two-Layer Map Architecture ──────────────────────────────────── */
    .map-container {
        position: relative;
        overflow: hidden;
        background: #e0e3e5;
        border-radius: 0.75rem;
        border: 1px solid #c3c6d5;
        cursor: grab;
        user-select: none;
    }
    .map-container:active { cursor: grabbing; }

    /* Layer 1: Estate blueprint image */
    #estate-blueprint {
        display: block;
        width: 100%;
        height: auto;
        pointer-events: none;
        draggable: false;
    }

    /* Layer 2: SVG polygon overlay — absolute, fills container */
    #plot-overlay {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        pointer-events: none; /* SVG itself passes clicks through */
    }

    /* Individual plot polygons — pointer events re-enabled */
    #plot-overlay .plot-poly {
        pointer-events: all;
        cursor: pointer;
        transition: all 0.25s ease;
        stroke: #ffffff;
        stroke-width: 1.5;
    }
    #plot-overlay .plot-poly:hover { opacity: 0.85; }

    /* Status colour treatments (match Stitch design) */
    .plot-poly.available  { fill: #d3e4fe; }
    .plot-poly.allocated  { fill: #c3c6cf; }
    .plot-poly.reserved   { fill: #dfe2eb; }
    .plot-poly.unavailable{ fill: #aaaaaa; }

    /* MY PLOT — strongest visual element */
    .plot-poly.owned {
        fill: rgba(0, 99, 235, 0.6) !important; /* Vivid translucent blue */
        stroke: #ffffff !important;
        stroke-width: 3 !important;
    }

    .plot-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        font-weight: 600;
        fill: #434653;
        pointer-events: none;
        text-anchor: middle;
        dominant-baseline: middle;
    }
    .plot-label.owned-label { fill: #ffffff; }

    /* No-blueprint placeholder */
    .no-blueprint {
        min-height: 500px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: repeating-linear-gradient(
            45deg,
            #f2f4f6,
            #f2f4f6 10px,
            #eceef0 10px,
            #eceef0 20px
        );
        border-radius: 0.75rem;
        border: 2px dashed #c3c6d5;
        color: #737784;
    }
    .glass-panel { background: rgba(255,255,255,0.8); backdrop-filter: blur(12px); border: 1px solid #c3c6d5; }

    /* Plot detail panel slide-in */
    #plot-panel { transition: transform 0.3s ease, opacity 0.3s ease; }
    #plot-panel.hidden-panel { transform: translateX(100%); opacity: 0; pointer-events: none; }
</style>
@endpush

@section('content')
<div
    x-data="estateMap({
        estateId: {{ $estate->id }},
        mapDataUrl: '{{ route('customer.estates.map-data', $estate) }}',
        plotDetailBase: '{{ url('customer/estates/' . $estate->id . '/plots') }}'
    })"
    class="flex flex-col gap-6 w-full"
>
    {{-- ── Page Header ──────────────────────────────────────────────── --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-on-surface-variant text-sm mb-1">
                <a href="{{ route('customer.estates.index') }}" class="hover:text-primary transition-colors">My Estates</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-surface font-medium">{{ $estate->name }}</span>
            </div>
            <h2 class="font-headline-md text-headline-md text-on-surface font-bold">{{ $estate->name }}</h2>
            <p class="font-body-sm text-on-surface-variant flex items-center gap-1.5 mt-0.5">
                <span class="material-symbols-outlined text-[14px] text-primary">location_on</span>
                {{ $estate->location }}
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button
                @click="showMyPlots()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90 transition-colors shadow-sm"
            >
                <span class="material-symbols-outlined text-[18px]">my_location</span>
                Show My Plots
            </button>
            <button
                @click="showAllPlots()"
                class="inline-flex items-center gap-2 px-4 py-2 border border-outline-variant text-on-surface-variant rounded-lg text-sm font-medium hover:bg-surface-container transition-colors"
            >
                <span class="material-symbols-outlined text-[18px]">map</span>
                Show All
            </button>
        </div>
    </div>

    {{-- ── Stats Strip ─────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="glass-panel p-4 rounded-xl">
            <span class="text-xs font-label-caps text-on-surface-variant uppercase tracking-wider">Total Plots</span>
            <p class="text-headline-md font-bold text-on-surface mt-1" x-text="plots.length || '...'"></p>
        </div>
        <div class="glass-panel p-4 rounded-xl">
            <span class="text-xs font-label-caps text-on-surface-variant uppercase tracking-wider">My Plots</span>
            <p class="text-headline-md font-bold text-primary mt-1" x-text="myPlots.length || 0"></p>
        </div>
        <div class="glass-panel p-4 rounded-xl">
            <span class="text-xs font-label-caps text-on-surface-variant uppercase tracking-wider">Available</span>
            <p class="text-headline-md font-bold text-on-surface mt-1" x-text="plots.filter(p=>p.status==='available').length"></p>
        </div>
        <div class="glass-panel p-4 rounded-xl">
            <span class="text-xs font-label-caps text-on-surface-variant uppercase tracking-wider">Estate Status</span>
            <p class="text-sm font-bold text-emerald-600 mt-1 flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block animate-pulse"></span> Active
            </p>
        </div>
    </div>

    {{-- ── Main Map + Side Panel Layout ───────────────────────────── --}}
    <div class="flex flex-col lg:flex-row gap-6 items-start">

        {{-- ── Interactive Map Canvas ─────────────────────────────── --}}
        <div class="flex-1 min-w-0">
            {{-- Toolbar --}}
            <div class="glass-panel rounded-t-xl px-4 py-2.5 flex justify-between items-center border-b border-outline-variant">
                <h3 class="font-body-lg font-semibold text-on-surface text-sm">Interactive Site Plan</h3>
                <div class="flex gap-1.5">
                    <button @click="zoomIn()" title="Zoom In" class="p-1.5 rounded bg-surface hover:bg-surface-container border border-outline-variant text-on-surface-variant transition-colors">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                    </button>
                    <button @click="zoomOut()" title="Zoom Out" class="p-1.5 rounded bg-surface hover:bg-surface-container border border-outline-variant text-on-surface-variant transition-colors">
                        <span class="material-symbols-outlined text-[16px]">remove</span>
                    </button>
                    <button @click="resetView()" title="Fit View" class="p-1.5 rounded bg-surface hover:bg-surface-container border border-outline-variant text-on-surface-variant transition-colors">
                        <span class="material-symbols-outlined text-[16px]">fit_screen</span>
                    </button>
                </div>
            </div>

            {{-- Map Canvas --}}
            <div
                id="map-canvas"
                class="map-container rounded-b-xl"
                style="min-height: 520px;"
                @mousedown="startPan($event)"
                @mousemove="onMouseMove($event)"
                @mouseup="endPan()"
                @mouseleave="endPan()"
                @wheel.prevent="onWheel($event)"
                @touchstart.prevent="startPan($event)"
                @touchmove.prevent="onMouseMove($event)"
                @touchend="endPan()"
            >
                {{-- Loading state --}}
                <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-surface/80 z-20 rounded-xl">
                    <div class="flex flex-col items-center gap-3 text-on-surface-variant">
                        <div class="w-8 h-8 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
                        <span class="text-sm">Loading estate map…</span>
                    </div>
                </div>

                {{-- Map transformer --}}
                <div
                    id="map-transform"
                    :style="`transform: translate(${pan.x}px, ${pan.y}px) scale(${zoom}); transform-origin: top left; position: relative;`"
                >
                    @if($estate->layout_file)
                        {{-- Inner wrapper: tightly bounds the image so SVG overlays exactly the image --}}
                        <div style="position:relative; display:block; line-height:0;">
                            {{-- Layer 1: Estate Blueprint Image --}}
                            @if(strtolower($estate->layout_type) === 'pdf')
                                <embed
                                    src="{{ asset('storage/' . $estate->layout_file) }}#toolbar=0&navpanes=0&scrollbar=0"
                                    type="application/pdf"
                                    id="estate-blueprint"
                                    style="width:100%; min-height:600px; display:block; pointer-events:none;"
                                />
                            @else
                                <img
                                    src="{{ asset('storage/' . $estate->layout_file) }}"
                                    id="estate-blueprint"
                                    alt="{{ $estate->name }} Layout"
                                    draggable="false"
                                    style="display:block; width:100%; height:auto; pointer-events:none;"
                                />
                            @endif

                            {{-- Layer 2: SVG overlay — covers exactly the image, not the container --}}
                            {{-- viewBox 0 0 1 1 + preserveAspectRatio=none stretches perfectly to match image --}}
                            <svg
                                id="plot-overlay"
                                viewBox="0 0 1 1"
                                preserveAspectRatio="none"
                                xmlns="http://www.w3.org/2000/svg"
                                x-html="renderPlots()"
                                style="position:absolute;top:0;left:0;width:100%;height:100%;overflow:visible;"
                            >
                            </svg>
                        </div>
                    @else
                        {{-- No blueprint fallback --}}
                        <div class="no-blueprint" style="min-height:520px;">
                            <span class="material-symbols-outlined text-6xl mb-3">map</span>
                            <p class="font-headline-sm text-lg font-semibold">No blueprint uploaded yet</p>
                            <p class="text-sm mt-1">The administrator will upload the estate layout soon.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Map Legend --}}
            <div class="glass-panel rounded-xl mt-3 px-4 py-3 flex flex-wrap gap-5 justify-center">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-sm" style="background: rgba(0, 99, 235, 0.6); border: 2px solid #fff;"></div>
                    <span class="font-body-sm text-sm text-on-surface-variant font-bold">Your Property ★</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-sm bg-[#c3c6cf]"></div>
                    <span class="font-body-sm text-sm text-on-surface-variant">Allocated</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-sm bg-[#d3e4fe]"></div>
                    <span class="font-body-sm text-sm text-on-surface-variant">Available</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-sm bg-[#dfe2eb]"></div>
                    <span class="font-body-sm text-sm text-on-surface-variant">Reserved</span>
                </div>
            </div>
        </div>

        {{-- ── Right Side Panel (Plot Details) ───────────────────── --}}
        <aside id="plot-panel" class="w-full lg:w-[340px] shrink-0" :class="selectedPlot ? '' : 'hidden-panel lg:block'" style="display:none;" x-show="true">
            {{-- Default state: My plots list --}}
            <div x-show="!selectedPlot" class="glass-panel rounded-xl overflow-hidden">
                <div class="p-4 border-b border-outline-variant bg-surface-bright/60">
                    <h3 class="font-headline-sm font-bold text-on-surface">My Allocated Plots</h3>
                    <p class="text-xs text-on-surface-variant mt-0.5">Click a ★ plot on the map to view details</p>
                </div>
                <div class="divide-y divide-outline-variant/50">
                    <template x-for="plot in myPlots" :key="plot.id">
                        <div @click="selectPlot(plot)" class="p-4 hover:bg-primary-fixed/20 cursor-pointer transition-colors flex items-center justify-between group">
                            <div>
                                <p class="font-bold text-primary text-sm" x-text="plot.plot_number"></p>
                                <p class="text-xs text-on-surface-variant mt-0.5" x-text="`${plot.size_sqm} sqm · Allocated`"></p>
                            </div>
                            <span class="material-symbols-outlined text-primary group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </div>
                    </template>
                    <div x-show="myPlots.length === 0" class="p-6 text-center">
                        <span class="material-symbols-outlined text-3xl text-on-surface-variant">draw</span>
                        <p class="text-sm font-semibold text-on-surface mt-2">Boundaries Not Yet Mapped</p>
                        <p class="text-xs text-on-surface-variant mt-1 leading-relaxed">The administrator needs to trace the exact plot boundaries over the uploaded estate image in the Layout Manager. Once mapped, your allocated plots will be highlighted directly on the map.</p>
                    </div>
                </div>
            </div>

            {{-- Selected plot detail panel --}}
            <div x-show="selectedPlot" class="glass-panel rounded-xl overflow-hidden">
                {{-- Header Image (if estate has blueprint) --}}
                @if($estate->layout_file)
                    <div class="h-36 relative bg-surface-container overflow-hidden">
                        @if(strtolower($estate->layout_type) !== 'pdf')
                            <img src="{{ asset('storage/' . $estate->layout_file) }}" class="w-full h-full object-cover opacity-50" />
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-4">
                            <div>
                                <h3 class="text-white font-headline-md font-bold text-xl" x-text="selectedPlot?.plot_number"></h3>
                                <p class="text-white/80 text-xs" x-text="selectedPlot?.is_mine ? '★ Your Property' : '{{ $estate->name }}'"></p>
                            </div>
                        </div>
                        <div x-show="selectedPlot?.is_mine" class="absolute top-3 right-3 bg-primary/90 backdrop-blur text-white text-xs font-bold px-2.5 py-1 rounded-full flex items-center gap-1 shadow">
                            <span class="animate-pulse">●</span> YOUR PLOT
                        </div>
                    </div>
                @endif

                <div class="p-5 space-y-4">
                    {{-- Key details --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <span class="text-[10px] uppercase tracking-wider text-on-surface-variant font-bold">Plot Size</span>
                            <p class="font-medium text-on-surface mt-0.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px] text-outline">square_foot</span>
                                <span x-text="selectedPlot ? selectedPlot.size_sqm + ' sqm' : ''"></span>
                            </p>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase tracking-wider text-on-surface-variant font-bold">Status</span>
                            <p class="font-medium mt-0.5" :class="selectedPlot?.is_mine ? 'text-primary' : 'text-on-surface'" x-text="selectedPlot?.is_mine ? 'Allocated ✓' : (selectedPlot?.status?.charAt(0)?.toUpperCase() + selectedPlot?.status?.slice(1))"></p>
                        </div>
                    </div>

                    {{-- My plot: full allocation info --}}
                    <template x-if="selectedPlot?.is_mine && selectedPlot?.allocation">
                        <div class="space-y-3 border-t border-outline-variant pt-3">
                            <div>
                                <span class="text-[10px] uppercase tracking-wider text-on-surface-variant font-bold">Reference ID</span>
                                <p class="font-label-caps bg-surface-container-low px-2 py-1 rounded mt-1 text-primary tracking-wider text-sm inline-block" x-text="selectedPlot.allocation.reference"></p>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase tracking-wider text-on-surface-variant font-bold">Allocation Date</span>
                                <p class="text-on-surface font-medium mt-0.5 text-sm" x-text="selectedPlot.allocation.allocated_at || selectedPlot.allocation.allocation_date"></p>
                            </div>
                            <div class="flex flex-col gap-2 pt-1">
                                <a
                                    :href="`/customer/properties`"
                                    class="w-full flex items-center justify-center gap-2 bg-primary text-white py-2.5 rounded-lg text-sm font-bold hover:bg-primary/90 transition-colors shadow-sm"
                                >
                                    <span class="material-symbols-outlined text-[16px]">description</span>
                                    View Property Details
                                </a>
                                <button
                                    @click="selectedPlot = null"
                                    class="w-full flex items-center justify-center gap-2 border border-outline-variant text-on-surface-variant py-2 rounded-lg text-sm hover:bg-surface-container transition-colors"
                                >
                                    <span class="material-symbols-outlined text-[16px]">close</span>
                                    Close
                                </button>
                            </div>
                        </div>
                    </template>

                    {{-- Other plots: safe public info only --}}
                    <template x-if="selectedPlot && !selectedPlot.is_mine">
                        <div class="border-t border-outline-variant pt-3">
                            <div class="bg-surface-container-low rounded-lg p-3 text-sm text-on-surface-variant mb-3">
                                <span class="material-symbols-outlined text-[14px] align-middle mr-1">info</span>
                                This plot belongs to another customer.
                            </div>
                            <button
                                @click="selectedPlot = null"
                                class="w-full flex items-center justify-center gap-2 border border-outline-variant text-on-surface-variant py-2 rounded-lg text-sm hover:bg-surface-container transition-colors"
                            >
                                <span class="material-symbols-outlined text-[16px]">close</span>
                                Close
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </aside>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('estateMap', ({ estateId, mapDataUrl, plotDetailBase }) => ({
        loading: true,
        plots: [],
        selectedPlot: null,
        dimmed: false,
        zoom: 1,
        pan: { x: 0, y: 0 },
        isPanning: false,
        _startX: 0, _startY: 0,
        svgW: 800,
        svgH: 500,

        get myPlots() {
            return this.plots.filter(p => p.is_mine);
        },
        get visiblePlots() {
            return this.plots.filter(p => p.coordinates && p.coordinates.length >= 3);
        },

        async init() {
            window.addEventListener('map-select-plot', (e) => this.selectPlotById(e.detail));
            await this.loadMapData();
            this.$nextTick(() => this.calibrateSvgSize());
        },

        selectPlotById(id) {
            const plot = this.plots.find(p => p.id === id);
            if (plot) this.selectPlot(plot);
        },

        renderPlots() {
            let svg = '';
            for (let plot of this.visiblePlots) {
                // Coordinates are already 0.0-1.0 ratios — use directly
                const points = plot.coordinates.map(p => `${p.x},${p.y}`).join(' ');
                const cx = plot.coordinates.reduce((s, p) => s + p.x, 0) / plot.coordinates.length;
                const cy = plot.coordinates.reduce((s, p) => s + p.y, 0) / plot.coordinates.length;
                const opacity = this.dimmed && !plot.is_mine ? 'opacity:0.15;' : '';
                const fillStyle = plot.is_mine
                    ? 'fill:rgba(0,99,235,0.65);stroke:#ffffff;stroke-width:0.008;'
                    : plot.status === 'available'
                        ? 'fill:rgba(211,228,254,0.5);stroke:#ffffff;stroke-width:0.004;'
                        : plot.status === 'allocated'
                            ? 'fill:rgba(195,198,207,0.5);stroke:#ffffff;stroke-width:0.004;'
                            : 'fill:rgba(223,226,235,0.5);stroke:#ffffff;stroke-width:0.004;';
                
                let g = `<g onclick="document.dispatchEvent(new CustomEvent('map-select-plot',{detail:${plot.id}}))" style="cursor:pointer;${opacity}">
                    <polygon points="${points}" style="${fillStyle}" />
                    <text x="${cx}" y="${cy}" text-anchor="middle" dominant-baseline="middle" style="font-size:0.022px;font-weight:600;fill:${plot.is_mine ? '#fff' : '#333'};pointer-events:none;">${plot.plot_number}</text>`;
                
                if (plot.is_mine) {
                    g += `<text x="${cx}" y="${cy + 0.025}" text-anchor="middle" dominant-baseline="middle" style="font-size:0.016px;font-weight:bold;fill:#fff;pointer-events:none;">★ MINE</text>`;
                }
                g += `</g>`;
                svg += g;
            }
            return svg;
        },

        async loadMapData() {
            try {
                const res = await fetch(mapDataUrl, {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await res.json();
                this.plots = data.plots || [];
            } catch (e) {
                console.error('Map data load failed:', e);
            } finally {
                this.loading = false;
            }
        },

        calibrateSvgSize() {
            // Coordinates are stored as 0.0-1.0 ratios so no calibration needed.
            // SVG uses viewBox="0 0 1 1" with preserveAspectRatio="none" so it
            // always stretches exactly over the blueprint image.
        },

        toSvgPoints(coords) {
            // Coordinates are normalized 0.0-1.0; use directly in viewBox 0 0 1 1
            if (!coords || coords.length === 0) return '';
            return coords.map(p => `${p.x},${p.y}`).join(' ');
        },

        centroid(coords) {
            if (!coords || coords.length === 0) return { x: 0.5, y: 0.5 };
            const cx = coords.reduce((s, p) => s + p.x, 0) / coords.length;
            const cy = coords.reduce((s, p) => s + p.y, 0) / coords.length;
            return { x: cx, y: cy };
        },

        selectPlot(plot) {
            this.selectedPlot = plot;
            // Scroll right panel into view on mobile
            this.$nextTick(() => {
                document.getElementById('plot-panel')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            });
        },

        showMyPlots() {
            this.dimmed = true;
            this.selectedPlot = null;
            if (this.myPlots.length > 0) {
                // Zoom and center to first owned plot
                const p = this.myPlots[0];
                if (p.coordinates?.length > 0) {
                    const cx = p.coordinates.reduce((s,c) => s + c.x, 0) / p.coordinates.length;
                    const cy = p.coordinates.reduce((s,c) => s + c.y, 0) / p.coordinates.length;
                    const canvas = document.getElementById('map-canvas');
                    const targetX = canvas.offsetWidth / 2 - cx * this.svgW * 2;
                    const targetY = canvas.offsetHeight / 2 - cy * this.svgH * 2;
                    this.zoom = 2;
                    this.pan = { x: targetX, y: targetY };
                }
            }
        },

        showAllPlots() {
            this.dimmed = false;
            this.selectedPlot = null;
            this.resetView();
        },

        zoomIn()  { this.zoom = Math.min(this.zoom + 0.25, 5); },
        zoomOut() { this.zoom = Math.max(this.zoom - 0.25, 0.3); },

        resetView() {
            this.zoom = 1;
            this.pan = { x: 0, y: 0 };
        },

        onWheel(e) {
            if (e.ctrlKey || e.metaKey) {
                e.deltaY < 0 ? this.zoomIn() : this.zoomOut();
            } else {
                this.pan.x -= (e.deltaX || 0);
                this.pan.y -= (e.deltaY || 0);
            }
        },

        startPan(e) {
            this.isPanning = true;
            const t = e.touches ? e.touches[0] : e;
            this._startX = t.clientX - this.pan.x;
            this._startY = t.clientY - this.pan.y;
        },

        onMouseMove(e) {
            if (!this.isPanning) return;
            const t = e.touches ? e.touches[0] : e;
            this.pan.x = t.clientX - this._startX;
            this.pan.y = t.clientY - this._startY;
        },

        endPan() { this.isPanning = false; }
    }));
});
</script>
@endpush
@endsection
