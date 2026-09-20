@extends('layouts.base')

@section('body-classes', 'flex flex-col md:flex-row')

@section('layout')
    <!-- SideNavBar (Admin) -->
    <nav class="fixed left-0 top-0 h-full w-[240px] z-40 bg-surface/90 dark:bg-inverse-surface/90 backdrop-blur-xl border-r border-outline-variant flex flex-col py-gutter px-4 hidden md:flex">
        <div class="mb-8 px-4 mt-2">
            <h1 class="font-display-lg text-headline-md font-bold text-primary truncate">EstatePortal</h1>
            <p class="font-body-sm text-on-surface-variant">Administrator</p>
        </div>
        
        <ul class="flex-1 space-y-2">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'text-primary border-l-4 border-primary bg-primary-fixed/30' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined" {!! request()->routeIs('admin.dashboard') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>dashboard</span>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.estates.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg font-bold transition-all {{ request()->routeIs('admin.estates.*') ? 'text-primary border-l-4 border-primary bg-primary-fixed/30' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined" {!! request()->routeIs('admin.estates.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>domain</span>
                    <span>Estates</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.plots.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg font-bold transition-all {{ request()->routeIs('admin.plots.*') ? 'text-primary border-l-4 border-primary bg-primary-fixed/30' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined" {!! request()->routeIs('admin.plots.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>grid_on</span>
                    <span>Plots Map</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg font-bold transition-all {{ request()->routeIs('admin.customers.*') ? 'text-primary border-l-4 border-primary bg-primary-fixed/30' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined" {!! request()->routeIs('admin.customers.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>groups</span>
                    <span>Customers</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.allocations.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg font-bold transition-all {{ request()->routeIs('admin.allocations.*') ? 'text-primary border-l-4 border-primary bg-primary-fixed/30' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined" {!! request()->routeIs('admin.allocations.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>add_location_alt</span>
                    <span>Allocations</span>
                </a>
            </li>
        </ul>
        
        <ul class="mt-auto space-y-2 pt-4 border-t border-outline-variant/50">
            <li>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container-low transition-colors duration-200">
                    <span class="material-symbols-outlined">settings</span>
                    <span>Settings</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container-low transition-colors duration-200">
                        <span class="material-symbols-outlined">logout</span>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
    
    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 md:ml-[240px]">
        <!-- TopAppBar -->
        <header class="docked full-width top-0 sticky z-30 bg-surface/80 backdrop-blur-xl border-b border-outline-variant flex justify-between items-center w-full px-gutter h-16 max-w-full">
            <div class="flex items-center flex-1">
                <!-- Search Bar Placeholder -->
                <div class="relative w-64 hidden sm:block">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                    <input type="text" class="w-full bg-surface-container-low border-none rounded-full py-2 pl-10 pr-4 font-body-sm text-on-surface focus:ring-1 focus:ring-primary focus:bg-surface transition-all" placeholder="Search...">
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <button class="text-on-surface-variant hover:bg-surface-container-high rounded-full p-2 transition-all relative">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full border-2 border-surface hidden"></span>
                </button>
                
                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold shrink-0 ml-2 shadow-sm font-label-caps">
                    {{ substr(Auth::user()->first_name ?? 'A', 0, 1) }}{{ substr(Auth::user()->last_name ?? 'D', 0, 1) }}
                </div>
            </div>
        </header>
        
        <!-- Main Canvas -->
        <main class="flex-1 p-gutter overflow-y-auto space-y-8 max-w-container-max mx-auto w-full pb-12">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                  <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                  <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            
            @yield('content')
        </main>
    </div>
@endsection
