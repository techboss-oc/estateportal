@extends('layouts.admin')

@section('title', 'Layout Manager — ' . $estate->name)

@push('styles')
<style>
    /* ── Admin Layout Editor Styles ───────────────────────────────── */
    .map-viewport {
        position: relative;
        overflow: hidden;
        background: #e0e3e5;
        border-radius: 0.75rem;
        border: 1px solid #c3c6d5;
        cursor: crosshair;
        user-select: none;
        min-height: 520px;
    }
    .map-viewport.tool-pan { cursor: grab; }
    .map-viewport.tool-pan:active { cursor: grabbing; }
    .map-viewport.tool-select { cursor: pointer; }

    #editor-blueprint { display: block; width: 100%; height: auto; pointer-events: none; draggable: false; }

    #editor-overlay {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        overflow: visible;
    }

    .editor-poly { 
        cursor: pointer; 
        stroke: #003c90; 
        stroke-width: 1.5; 
        transition: opacity 0.2s;
    }
    .editor-poly.available  { fill: rgba(211,228,254,0.6); }
    .editor-poly.allocated  { fill: rgba(195,198,207,0.6); }
    .editor-poly.reserved   { fill: rgba(223,226,235,0.6); }
    .editor-poly.unavailable{ fill: rgba(170,170,170,0.5); }
    .editor-poly.selected   { stroke: #ba1a1a; stroke-width: 3; fill: rgba(186,26,26,0.15); }
    .editor-poly.drawing-new{ fill: rgba(0,60,144,0.2); stroke: #003c90; stroke-dasharray: 5,4; }

    .vertex-handle {
        fill: #003c90; 
        stroke: white; 
        stroke-width: 1.5; 
        cursor: move;
        transition: r 0.1s, fill 0.1s;
    }
    .vertex-handle:hover { fill: #ba1a1a; r: 7; }

    .draw-point { fill: #003c90; stroke: white; stroke-width: 1.5; cursor: crosshair; }
    .draw-cursor-line { stroke: #003c90; stroke-dasharray: 4,3; stroke-width: 1.5; }
    
    .plot-label-admin {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        font-weight: 600;
        fill: #191c1e;
        pointer-events: none;
        text-anchor: middle;
        dominant-baseline: middle;
    }

    .no-blueprint-admin {
        min-height: 520px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: repeating-linear-gradient(45deg,#f2f4f6,#f2f4f6 10px,#eceef0 10px,#eceef0 20px);
        border-radius: 0.75rem;
        border: 2px dashed #c3c6d5;
        color: #737784;
    }
</style>
@endpush

@section('content')
<div x-data="layoutEditor({
    estateId: {{ $estate->id }},
    mapDataUrl: '{{ route('admin.layouts.map-data', $estate) }}',
    savePlotUrl: '{{ route('admin.layouts.plots.save', $estate) }}',
    updateBase: '{{ url('admin/layouts/' . $estate->id . '/plots') }}',
    csrfToken: '{{ csrf_token() }}'
})" class="flex flex-col gap-5 w-full">

    {{-- ── Header ──────────────────────────────────────────────────── --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.estates.index') }}" class="text-xs text-on-surface-variant hover:text-primary flex items-center gap-1 mb-1">
                <span class="material-symbols-outlined text-[14px]">arrow_back</span> Back to Estates
            </a>
            <h2 class="font-headline-md text-on-surface font-bold">Layout Manager: {{ $estate->name }}</h2>
            <p class="text-sm text-on-surface-variant">Map plot boundaries over the uploaded estate blueprint.</p>
        </div>
        <div class="flex flex-wrap gap-2 items-center">
            {{-- Unsaved changes indicator --}}
            <span x-show="unsavedChanges" class="text-xs text-amber-600 font-medium flex items-center gap-1 bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-200">
                <span class="material-symbols-outlined text-[14px]">warning</span> Unsaved changes
            </span>

            <form action="{{ route('admin.layouts.upload', $estate->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center">
                @csrf
                <input type="file" name="blueprint" id="blueprint-upload" class="hidden" accept="image/*,.pdf" onchange="this.form.submit()">
                <label for="blueprint-upload" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 border border-outline-variant rounded-lg text-primary text-sm font-bold hover:bg-surface-container-low transition-colors whitespace-nowrap">
                    <span class="material-symbols-outlined text-[18px]">upload_file</span> Upload Blueprint
                </label>
            </form>

            <button @click="bulkSave()" :disabled="saving || !unsavedChanges" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap">
                <span class="material-symbols-outlined text-[18px]">save</span>
                <span x-text="saving ? 'Saving…' : 'Save Changes'"></span>
            </button>
        </div>
    </div>

    {{-- Feedback --}}
    <div x-show="feedback.message" x-transition :class="feedback.type === 'success' ? 'bg-green-50 text-green-800 border-green-200' : 'bg-red-50 text-red-800 border-red-200'" class="p-3 rounded-lg text-sm border flex items-center gap-2" style="display:none;">
        <span class="material-symbols-outlined text-[16px]" x-text="feedback.type === 'success' ? 'check_circle' : 'error'"></span>
        <span x-text="feedback.message"></span>
    </div>

    {{-- ── Main Editor Area ─────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-5">

        {{-- ── Left: Tools + Plot List ─────────────────────────────── --}}
        <div class="lg:col-span-1 flex flex-col gap-4">
            {{-- Tool Palette --}}
            <x-card class="p-4">
                <h3 class="text-sm font-bold text-on-surface mb-3">Mapper Tools</h3>
                <div class="grid grid-cols-2 gap-2">
                    <button @click="setTool('select')" :class="tool==='select' ? 'bg-primary-container text-on-primary-container border-primary/40' : 'bg-surface text-on-surface-variant border-outline-variant hover:bg-surface-container'" class="flex flex-col items-center py-2 px-1 rounded-lg border transition-all text-[10px] font-bold gap-1">
                        <span class="material-symbols-outlined text-[20px]">touch_app</span> Select
                    </button>
                    <button @click="setTool('draw')" :class="tool==='draw' ? 'bg-primary-container text-on-primary-container border-primary/40' : 'bg-surface text-on-surface-variant border-outline-variant hover:bg-surface-container'" class="flex flex-col items-center py-2 px-1 rounded-lg border transition-all text-[10px] font-bold gap-1">
                        <span class="material-symbols-outlined text-[20px]">draw</span> Add Plot
                    </button>
                    <button @click="zoomIn()" class="flex flex-col items-center py-2 px-1 bg-surface text-on-surface-variant rounded-lg border border-outline-variant hover:bg-surface-container transition-colors text-[10px] font-bold gap-1">
                        <span class="material-symbols-outlined text-[20px]">zoom_in</span> Zoom In
                    </button>
                    <button @click="zoomOut()" class="flex flex-col items-center py-2 px-1 bg-surface text-on-surface-variant rounded-lg border border-outline-variant hover:bg-surface-container transition-colors text-[10px] font-bold gap-1">
                        <span class="material-symbols-outlined text-[20px]">zoom_out</span> Zoom Out
                    </button>
                    <button @click="fitView()" class="flex flex-col items-center py-2 px-1 bg-surface text-on-surface-variant rounded-lg border border-outline-variant hover:bg-surface-container transition-colors text-[10px] font-bold gap-1">
                        <span class="material-symbols-outlined text-[20px]">fit_screen</span> Fit View
                    </button>
                    <button @click="resetView()" class="flex flex-col items-center py-2 px-1 bg-surface text-on-surface-variant rounded-lg border border-outline-variant hover:bg-surface-container transition-colors text-[10px] font-bold gap-1">
                        <span class="material-symbols-outlined text-[20px]">restart_alt</span> Reset
                    </button>
                </div>

                <div x-show="tool==='draw'" class="mt-3 text-xs text-on-surface-variant bg-surface-container-low rounded-lg p-2.5 border border-outline-variant/50">
                    <p class="font-bold text-primary mb-1">Drawing Mode</p>
                    <p>Click on the map to add polygon corners (min. 3).</p>
                    <p class="mt-1">Press <kbd class="bg-surface-container px-1 rounded">Enter</kbd> or click <b>Finish Polygon</b> when done.</p>
                </div>
            </x-card>

            {{-- Plots List --}}
            <x-card class="flex-1 p-4 flex flex-col gap-3">
                <div class="flex justify-between items-center">
                    <h3 class="text-sm font-bold text-on-surface">Mapped Plots</h3>
                    <span class="text-xs bg-surface-container-high px-2 py-0.5 rounded font-medium" x-text="plots.length"></span>
                </div>
                <input x-model="searchQuery" type="text" placeholder="Search plots…" class="w-full text-sm bg-surface-container-low border border-outline-variant/60 rounded-lg py-1.5 px-3 focus:border-primary focus:ring-1 focus:ring-primary">
                <div class="overflow-y-auto max-h-[400px] space-y-1.5 pr-0.5">
                    <template x-for="p in filteredPlots" :key="p.id">
                        <div @click="selectExistingPlot(p)" :class="selectedExistingPlot?.id === p.id ? 'bg-primary-fixed border-primary' : 'bg-surface border-outline-variant/50 hover:shadow-sm'" class="text-sm p-3 border rounded-lg cursor-pointer transition-all flex justify-between items-center">
                            <div>
                                <p class="font-bold text-on-surface text-sm" x-text="p.plot_number"></p>
                                <p class="text-[11px] text-on-surface-variant" x-text="`${p.size_sqm || '—'} sqm · ${p.status}`"></p>
                            </div>
                            <span :class="p.coordinates?.length >= 3 ? 'text-green-600' : 'text-on-surface-variant opacity-50'" class="material-symbols-outlined text-[18px]">
                                <span x-text="p.coordinates?.length >= 3 ? 'check_circle' : 'radio_button_unchecked'"></span>
                            </span>
                        </div>
                    </template>
                    <div x-show="filteredPlots.length === 0 && !loading" class="text-center py-4 text-sm text-on-surface-variant">No plots found.</div>
                </div>
            </x-card>
        </div>

        {{-- ── Map Canvas ───────────────────────────────────────────── --}}
        <div class="lg:col-span-2">
            <div
                id="editor-canvas"
                class="map-viewport"
                :class="`tool-${tool}`"
                @mousedown="onCanvasMouseDown($event)"
                @mousemove="onCanvasMouseMove($event)"
                @mouseup="onCanvasMouseUp($event)"
                @mouseleave="onCanvasMouseUp($event)"
                @wheel.prevent="onWheel($event)"
                @touchstart.prevent="onCanvasMouseDown($event)"
                @touchmove.prevent="onCanvasMouseMove($event)"
                @touchend="onCanvasMouseUp($event)"
                @dblclick="finishPolygon()"
                @keydown.enter.window="finishPolygon()"
                @keydown.escape.window="cancelDraw()"
            >
                {{-- Loading overlay --}}
                <div x-show="loading" class="absolute inset-0 bg-surface/80 flex items-center justify-center z-30 rounded-xl">
                    <div class="flex items-center gap-3 text-on-surface-variant">
                        <div class="w-7 h-7 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
                        <span class="text-sm">Loading map…</span>
                    </div>
                </div>

                <div id="editor-transform" :style="`transform: translate(${pan.x}px, ${pan.y}px) scale(${zoom}); transform-origin: top left; position: relative;`">
                    {{-- Layer 1: Blueprint --}}
                    @if($estate->layout_file)
                        @if(strtolower($estate->layout_type ?? '') === 'pdf')
                            <embed src="{{ asset('storage/' . $estate->layout_file) }}#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" id="editor-blueprint" style="width:100%;min-height:600px;pointer-events:none;" />
                        @else
                            <img src="{{ asset('storage/' . $estate->layout_file) }}" id="editor-blueprint" alt="Blueprint" draggable="false" @load="onBlueprintLoad($event)" style="display:block;width:100%;height:auto;pointer-events:none;" />
                        @endif
                    @else
                        <div class="no-blueprint-admin">
                            <span class="material-symbols-outlined text-6xl mb-3">map</span>
                            <p class="text-lg font-semibold">No blueprint uploaded</p>
                            <p class="text-sm mt-1">Upload a layout blueprint to begin mapping plots.</p>
                        </div>
                    @endif

                    {{-- Layer 2: SVG Overlay --}}
                    <svg id="editor-overlay" :viewBox="`0 0 ${svgW} ${svgH}`" preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg">

                        {{-- Saved Plots --}}
                        <template x-for="p in plots" :key="p.id">
                            <g x-show="p.coordinates && p.coordinates.length >= 3">
                                <polygon
                                    :points="toSvgPoints(p.coordinates)"
                                    :class="`editor-poly ${p.status} ${selectedExistingPlot?.id === p.id ? 'selected' : ''}`"
                                    @click.stop="selectExistingPlot(p)"
                                />
                                <text
                                    :x="centroid(p.coordinates).x"
                                    :y="centroid(p.coordinates).y"
                                    class="plot-label-admin"
                                    x-text="p.plot_number"
                                />
                            </g>
                        </template>

                        {{-- Drawing in progress --}}
                        <g x-show="drawingPoints.length > 0">
                            <polyline :points="drawingPoints.map(p => `${p.x},${p.y}`).join(' ')" class="editor-poly drawing-new" fill="rgba(0,60,144,0.1)" />
                            
                            <!-- Dashed preview line to mouse cursor -->
                            <line
                                x-show="cursorPos && drawingPoints.length > 0"
                                :x1="drawingPoints.length > 0 ? drawingPoints[drawingPoints.length-1].x : 0"
                                :y1="drawingPoints.length > 0 ? drawingPoints[drawingPoints.length-1].y : 0"
                                :x2="cursorPos ? cursorPos.x : 0" 
                                :y2="cursorPos ? cursorPos.y : 0"
                                class="draw-cursor-line"
                            ></line>

                            <!-- Render vertex circles -->
                            <template x-for="(pt, i) in drawingPoints" :key="i">
                                <circle :cx="pt.x" :cy="pt.y" r="5" class="draw-point" @click.stop="closePolygonAt(i)" />
                            </template>
                        </g>

                        {{-- Vertex handles for selected plot editing --}}
                        <g x-show="selectedExistingPlot && selectedExistingPlot.coordinates?.length >= 3">
                            <template x-for="(pt, i) in (selectedExistingPlot ? selectedExistingPlot.coordinates : [])" :key="i">
                                <circle
                                    :cx="pt.x * svgW" :cy="pt.y * svgH" r="6"
                                    class="vertex-handle"
                                    @mousedown.stop="startDragVertex(i, $event)"
                                />
                            </template>
                        </g>
                    </svg>
                </div>
            </div>

            {{-- Draw controls --}}
            <div x-show="tool==='draw' && drawingPoints.length >= 3" x-transition class="mt-2 flex justify-end gap-2" style="display:none;">
                <button @click="cancelDraw()" class="px-3 py-1.5 text-sm border border-outline-variant text-on-surface-variant rounded-lg hover:bg-surface-container transition-colors">
                    Cancel (Esc)
                </button>
                <button @click="finishPolygon()" class="px-4 py-1.5 text-sm bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors font-bold shadow-sm">
                    <span class="material-symbols-outlined text-[16px] align-middle">check</span>
                    Finish Polygon (<span x-text="drawingPoints.length"></span> pts)
                </button>
            </div>
        </div>

        {{-- ── Right: Plot Form ─────────────────────────────────────── --}}
        <div class="lg:col-span-1">
            {{-- New Plot Form (after finishing polygon) --}}
            <x-card x-show="showNewPlotForm" class="p-4" style="display:none;">
                <h3 class="text-sm font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[18px]">add_location_alt</span>
                    New Plot Details
                </h3>
                <form @submit.prevent="saveNewPlot()" class="space-y-3">
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wide">Plot Number *</label>
                        <input x-model="newPlot.plot_number" type="text" placeholder="e.g. LOT 7" class="w-full mt-1 text-sm bg-surface border border-outline-variant rounded-lg px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary" required>
                        <p x-show="newPlotErrors.plot_number" class="text-xs text-error mt-1" x-text="newPlotErrors.plot_number"></p>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wide">Size (sqm) *</label>
                        <input x-model="newPlot.size" type="number" step="0.01" min="0" placeholder="464" class="w-full mt-1 text-sm bg-surface border border-outline-variant rounded-lg px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary" required>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wide">Reference (optional)</label>
                        <input x-model="newPlot.plot_reference" type="text" placeholder="e.g. PH2-L7" class="w-full mt-1 text-sm bg-surface border border-outline-variant rounded-lg px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wide">Status</label>
                        <select x-model="newPlot.status" class="w-full mt-1 text-sm bg-surface border border-outline-variant rounded-lg px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary">
                            <option value="available">Available</option>
                            <option value="reserved">Reserved</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wide">Notes</label>
                        <textarea x-model="newPlot.notes" rows="2" class="w-full mt-1 text-sm bg-surface border border-outline-variant rounded-lg px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary"></textarea>
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button type="button" @click="cancelDraw()" class="flex-1 py-2 text-sm border border-outline-variant text-on-surface-variant rounded-lg hover:bg-surface-container transition-colors">Cancel</button>
                        <button type="submit" :disabled="savingPlot" class="flex-1 py-2 text-sm bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors font-bold shadow-sm disabled:opacity-50">
                            <span x-text="savingPlot ? 'Saving…' : 'Save Plot'"></span>
                        </button>
                    </div>
                </form>
            </x-card>

            {{-- Existing Plot Edit Panel --}}
            <x-card x-show="selectedExistingPlot && !showNewPlotForm" class="p-4" style="display:none;">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-sm font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[18px]">edit_location_alt</span>
                        Edit Plot
                    </h3>
                    <button @click="selectedExistingPlot = null" class="text-on-surface-variant hover:text-error transition-colors">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
                <form @submit.prevent="updateExistingPlot()" class="space-y-3">
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wide">Plot Number</label>
                        <input x-model="selectedExistingPlot.plot_number" type="text" class="w-full mt-1 text-sm bg-surface border border-outline-variant rounded-lg px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary" required>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wide">Size (sqm)</label>
                        <input x-model="selectedExistingPlot.size_sqm" type="number" step="0.01" class="w-full mt-1 text-sm bg-surface border border-outline-variant rounded-lg px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wide">Status</label>
                        <select x-model="selectedExistingPlot.status" class="w-full mt-1 text-sm bg-surface border border-outline-variant rounded-lg px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary">
                            <option value="available">Available</option>
                            <option value="reserved">Reserved</option>
                            <option value="allocated">Allocated</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>
                    <div class="text-xs text-on-surface-variant bg-surface-container-low rounded-lg p-2">
                        <p class="font-bold">Mapped Points:</p>
                        <p><span x-text="selectedExistingPlot.coordinates?.length || 0"></span> polygon vertices</p>
                        <p class="mt-1 text-primary" x-show="selectedExistingPlot.coordinates?.length >= 3">Drag handles to reposition vertices.</p>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" :disabled="savingPlot" class="flex-1 py-2 text-sm bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors font-bold shadow-sm disabled:opacity-50">Update</button>
                        <button type="button" @click="confirmDeletePlot()" class="px-3 py-2 text-sm bg-error/10 text-error border border-error/20 rounded-lg hover:bg-error/20 transition-colors">
                            <span class="material-symbols-outlined text-[16px]">delete</span>
                        </button>
                    </div>
                </form>
            </x-card>

            {{-- Default instructions --}}
            <x-card x-show="!selectedExistingPlot && !showNewPlotForm" class="p-4" style="display:block;">
                <h3 class="text-sm font-bold text-on-surface mb-3">Quick Guide</h3>
                <ol class="text-sm text-on-surface-variant space-y-2 list-decimal list-inside">
                    <li>Upload the estate <b>blueprint</b> image</li>
                    <li>Select <b>Add Plot</b> tool</li>
                    <li>Click on map corners to draw a polygon boundary</li>
                    <li>Double-click or press <b>Finish Polygon</b> to complete</li>
                    <li>Enter plot number, size, and details</li>
                    <li>Click <b>Save Plot</b></li>
                    <li>Repeat for all plots</li>
                    <li>Click <b>Save Changes</b> when done</li>
                </ol>
                <div class="mt-4 p-3 bg-primary-fixed/20 rounded-lg border border-primary/20 text-xs text-primary">
                    <p class="font-bold">Tip:</p>
                    <p>Coordinates are stored as normalized ratios (0.0–1.0) so the map remains accurate on any screen size.</p>
                </div>
            </x-card>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('layoutEditor', ({ estateId, mapDataUrl, savePlotUrl, updateBase, csrfToken }) => ({
        loading: true,
        tool: 'select',  // select | draw | pan
        plots: [],
        selectedExistingPlot: null,
        showNewPlotForm: false,
        searchQuery: '',

        // Drawing state
        drawingPoints: [],   // SVG pixel points while drawing
        finishedCoords: [],  // Normalized 0.0-1.0 coords after finish
        cursorPos: null,

        // Dragging vertex
        draggingVertex: null,

        // Map transform
        zoom: 1,
        pan: { x: 0, y: 0 },
        isPanning: false,
        _startX: 0, _startY: 0,
        svgW: 800, svgH: 500,

        // Form models
        newPlot: { plot_number: '', size: '', plot_reference: '', status: 'available', notes: '' },
        newPlotErrors: {},
        savingPlot: false,
        saving: false,
        unsavedChanges: false,
        feedback: { message: '', type: 'success' },

        get filteredPlots() {
            if (!this.searchQuery) return this.plots;
            return this.plots.filter(p => p.plot_number.toLowerCase().includes(this.searchQuery.toLowerCase()));
        },

        async init() {
            await this.loadMapData();
            this.$nextTick(() => this.calibrateSvg());
        },

        async loadMapData() {
            try {
                const r = await fetch(mapDataUrl, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } });
                const d = await r.json();
                this.plots = (d.plots || []).map(p => ({ ...p, coordinates: p.coordinates || [] }));
            } catch(e) { console.error(e); }
            finally { this.loading = false; }
        },

        calibrateSvg() {
            const img = document.getElementById('editor-blueprint');
            if (img) {
                const set = () => { this.svgW = img.naturalWidth || img.offsetWidth || 800; this.svgH = img.naturalHeight || img.offsetHeight || 500; };
                img.complete ? set() : img.addEventListener('load', set);
            }
        },

        onBlueprintLoad(e) {
            this.svgW = e.target.naturalWidth || e.target.offsetWidth || 800;
            this.svgH = e.target.naturalHeight || e.target.offsetHeight || 500;
        },

        setTool(t) {
            this.tool = t;
            if (t !== 'draw') { this.cancelDraw(); }
        },

        // ── Mouse/Touch Events ────────────────────────────────────────

        getEventCoords(e) {
            const t = e.touches ? e.touches[0] : e;
            return { clientX: t.clientX, clientY: t.clientY };
        },

        getSvgCoords(e) {
            const canvas = document.getElementById('editor-canvas');
            const rect = canvas.getBoundingClientRect();
            const { clientX, clientY } = this.getEventCoords(e);
            const rawX = (clientX - rect.left - this.pan.x) / this.zoom;
            const rawY = (clientY - rect.top  - this.pan.y) / this.zoom;
            return { x: rawX, y: rawY };
        },

        getNormalizedCoords(svgX, svgY) {
            return { x: Math.max(0, Math.min(1, svgX / this.svgW)), y: Math.max(0, Math.min(1, svgY / this.svgH)) };
        },

        onCanvasMouseDown(e) {
            if (this.draggingVertex !== null) return;
            if (this.tool === 'select' || this.tool === 'pan') {
                this.isPanning = true;
                const { clientX, clientY } = this.getEventCoords(e);
                this._startX = clientX - this.pan.x;
                this._startY = clientY - this.pan.y;
            } else if (this.tool === 'draw') {
                const { x, y } = this.getSvgCoords(e);
                this.drawingPoints = [...this.drawingPoints, { x, y }];
            }
        },

        onCanvasMouseMove(e) {
            const { x, y } = this.getSvgCoords(e);
            this.cursorPos = { x, y };

            if (this.isPanning && (this.tool === 'select' || this.tool === 'pan')) {
                const { clientX, clientY } = this.getEventCoords(e);
                this.pan.x = clientX - this._startX;
                this.pan.y = clientY - this._startY;
            }

            if (this.draggingVertex !== null && this.selectedExistingPlot) {
                const norm = this.getNormalizedCoords(x, y);
                this.selectedExistingPlot.coordinates[this.draggingVertex] = norm;
                this.unsavedChanges = true;
            }
        },

        onCanvasMouseUp() {
            this.isPanning = false;
            this.draggingVertex = null;
        },

        startDragVertex(index, e) {
            e.preventDefault();
            this.draggingVertex = index;
        },

        onWheel(e) {
            if (e.ctrlKey || e.metaKey) {
                e.deltaY < 0 ? this.zoomIn() : this.zoomOut();
            } else {
                this.pan.x -= (e.deltaX || 0);
                this.pan.y -= (e.deltaY || 0);
            }
        },

        zoomIn()  { this.zoom = Math.min(this.zoom + 0.25, 5); },
        zoomOut() { this.zoom = Math.max(this.zoom - 0.25, 0.2); },
        fitView() { this.zoom = 1; this.pan = { x: 0, y: 0 }; },
        resetView() { this.fitView(); },

        // ── Polygon Drawing ───────────────────────────────────────────

        finishPolygon() {
            if (this.drawingPoints.length < 3) return;
            // Convert SVG pixel coords → normalized
            this.finishedCoords = this.drawingPoints.map(p => this.getNormalizedCoords(p.x, p.y));
            this.drawingPoints = [];
            this.cursorPos = null;
            this.showNewPlotForm = true;
            this.newPlot = { plot_number: '', size: '', plot_reference: '', status: 'available', notes: '' };
            this.newPlotErrors = {};
            this.tool = 'select';
        },

        cancelDraw() {
            this.drawingPoints = [];
            this.cursorPos = null;
            this.finishedCoords = [];
            this.showNewPlotForm = false;
            this.newPlotErrors = {};
        },

        // ── Plot CRUD ─────────────────────────────────────────────────

        async saveNewPlot() {
            this.savingPlot = true;
            this.newPlotErrors = {};
            try {
                const body = {
                    ...this.newPlot,
                    coordinates: this.finishedCoords,
                };
                const r = await fetch(savePlotUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify(body)
                });
                const d = await r.json();
                if (!r.ok) {
                    if (d.errors) this.newPlotErrors = d.errors;
                    this.showFeedback(d.message || 'Validation failed.', 'error');
                    return;
                }
                // Add to local plots list
                this.plots.push({
                    id: d.plot.id,
                    plot_number: d.plot.plot_number,
                    size_sqm: d.plot.size,
                    status: d.plot.status,
                    coordinates: this.finishedCoords,
                    is_mine: false,
                });
                this.cancelDraw();
                this.showFeedback(d.message || 'Plot saved!', 'success');
            } catch(e) {
                this.showFeedback('Network error. Please try again.', 'error');
            } finally {
                this.savingPlot = false;
            }
        },

        selectExistingPlot(plot) {
            if (this.tool === 'draw') return;
            this.selectedExistingPlot = { ...plot };
            this.showNewPlotForm = false;
        },

        async updateExistingPlot() {
            this.savingPlot = true;
            try {
                const url = `${updateBase}/${this.selectedExistingPlot.id}`;
                const r = await fetch(url, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({
                        plot_number: this.selectedExistingPlot.plot_number,
                        size: this.selectedExistingPlot.size_sqm,
                        status:  this.selectedExistingPlot.status,
                        coordinates: this.selectedExistingPlot.coordinates,
                    })
                });
                const d = await r.json();
                if (!r.ok) { this.showFeedback(d.message || 'Update failed.', 'error'); return; }
                const idx = this.plots.findIndex(p => p.id === this.selectedExistingPlot.id);
                if (idx >= 0) this.plots[idx] = { ...this.plots[idx], ...this.selectedExistingPlot };
                this.showFeedback('Plot updated successfully.', 'success');
                this.unsavedChanges = false;
            } catch(e) { this.showFeedback('Network error.', 'error'); }
            finally { this.savingPlot = false; }
        },

        async confirmDeletePlot() {
            if (!confirm(`Delete ${this.selectedExistingPlot.plot_number}? This cannot be undone.`)) return;
            const url = `${updateBase}/${this.selectedExistingPlot.id}`;
            const r = await fetch(url, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const d = await r.json();
            if (!r.ok) { this.showFeedback(d.message, 'error'); return; }
            this.plots = this.plots.filter(p => p.id !== this.selectedExistingPlot.id);
            this.selectedExistingPlot = null;
            this.showFeedback(d.message, 'success');
        },

        async bulkSave() {
            this.saving = true;
            const url = `{{ route('admin.layouts.update', $estate) }}`;
            const payload = this.plots.filter(p => p.coordinates?.length >= 3).map(p => ({ id: p.id, coordinates: p.coordinates }));
            try {
                const r = await fetch(url, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ plots: payload })
                });
                const d = await r.json();
                this.showFeedback(d.message, r.ok ? 'success' : 'error');
                if (r.ok) this.unsavedChanges = false;
            } catch(e) { this.showFeedback('Save failed.', 'error'); }
            finally { this.saving = false; }
        },

        // ── SVG Helpers ───────────────────────────────────────────────

        toSvgPoints(coords) {
            if (!coords || coords.length === 0) return '';
            return coords.map(p => `${(p.x * this.svgW).toFixed(1)},${(p.y * this.svgH).toFixed(1)}`).join(' ');
        },

        centroid(coords) {
            if (!coords || coords.length === 0) return { x: 0, y: 0 };
            const cx = coords.reduce((s,p) => s + p.x, 0) / coords.length;
            const cy = coords.reduce((s,p) => s + p.y, 0) / coords.length;
            return { x: cx * this.svgW, y: cy * this.svgH };
        },

        showFeedback(msg, type='success') {
            this.feedback = { message: msg, type };
            setTimeout(() => { this.feedback.message = ''; }, 4000);
        }
    }));
});
</script>
@endpush
@endsection
