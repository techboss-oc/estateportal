@props(['sidebarOpen' => true])

<div class="flex-1 flex overflow-hidden bg-surface-container-lowest relative min-h-[600px] h-[calc(100vh-8rem)] rounded-xl border border-outline-variant/50 shadow-soft">
    <!-- Tool Ribbon (Left) -->
    <aside class="w-16 bg-surface border-r border-outline-variant flex flex-col items-center py-4 gap-4 z-10 shadow-sm relative">
        {{ $toolbar ?? '' }}
    </aside>
    
    <!-- Main Canvas Area -->
    <div {{ $attributes->merge(['class' => 'flex-1 relative overflow-hidden canvas-grid cursor-crosshair w-full h-full']) }} id="editor-canvas">
        {{ $slot }}
        
        <!-- Floating Canvas Info -->
        @if(isset($canvasInfo))
        <div class="absolute bottom-4 left-4 glass-panel px-3 py-1.5 rounded-lg text-label-caps text-on-surface-variant border border-outline-variant/30 flex items-center gap-4 shadow-sm backdrop-blur-md bg-surface/80">
            {{ $canvasInfo }}
        </div>
        @endif
    </div>
    
    <!-- Plot Details Sidebar (Right) -->
    @if(isset($sidebar))
    <aside class="w-full sm:w-[350px] lg:w-[400px] border-l border-outline-variant shadow-2xl flex flex-col z-20 absolute right-0 top-0 h-full glass-panel bg-surface/95 backdrop-blur-xl transition-transform duration-300 {{ $sidebarOpen ? 'translate-x-0' : 'translate-x-full' }}">
        {{ $sidebar }}
    </aside>
    @endif
</div>
