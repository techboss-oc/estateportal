@extends('layouts.base')

@section('body-classes', 'flex flex-col md:flex-row')

@section('layout')
<div x-data="{ mobileMenuOpen: false }" class="flex flex-col md:flex-row min-h-screen w-full">

    <!-- Mobile Menu Backdrop -->
    <div
        x-show="mobileMenuOpen"
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="mobileMenuOpen = false"
        class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm md:hidden"
        x-cloak
    ></div>

    <!-- Mobile Slide-out Drawer -->
    <div
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed left-0 top-0 h-full w-[280px] z-60 bg-surface/95 backdrop-blur-xl flex flex-col py-gutter px-4 md:hidden transform"
        style="z-index: 60;"
        x-cloak
    >
        <!-- Drawer Header -->
        <div class="mb-6 px-4 mt-2 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center border border-outline-variant">
                    <span class="material-symbols-outlined text-primary text-[20px]" style="font-variation-settings: 'FILL' 1;">domain</span>
                </div>
                <h1 class="font-display-lg text-headline-md font-bold text-primary tracking-tight">EstatePortal</h1>
            </div>
            <!-- Close Button -->
            <button @click="mobileMenuOpen = false" class="p-2 rounded-full hover:bg-surface-container-high text-on-surface-variant transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- User Info -->
        <div class="mx-4 mb-5 p-3 rounded-xl bg-surface-container-low flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-primary-fixed text-primary flex items-center justify-center font-bold tracking-wider text-sm border border-outline-variant shrink-0">
                {{ substr(Auth::user()->first_name ?? 'C', 0, 1) }}{{ substr(Auth::user()->last_name ?? 'U', 0, 1) }}
            </div>
            <div class="min-w-0">
                <p class="text-body-sm font-semibold text-on-surface truncate">{{ Auth::user()->first_name ?? '' }} {{ Auth::user()->last_name ?? 'Customer' }}</p>
                <p class="text-label-caps text-on-surface-variant truncate">{{ Auth::user()->email ?? '' }}</p>
            </div>
        </div>

        <!-- Drawer Navigation -->
        <nav class="flex-1 flex flex-col gap-1">
            <a href="{{ route('customer.dashboard') }}" @click="mobileMenuOpen = false"
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-colors duration-150 {{ request()->routeIs('customer.dashboard') ? 'text-primary border-l-4 border-primary bg-primary-fixed/30' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-low' }}">
                <span class="material-symbols-outlined" {!! request()->routeIs('customer.dashboard') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>dashboard</span>
                <span class="font-body-md text-body-md">Dashboard</span>
            </a>
            <a href="{{ route('customer.properties.index') }}" @click="mobileMenuOpen = false"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors duration-200 {{ request()->routeIs('customer.properties.*') ? 'text-primary font-bold border-l-4 border-primary bg-primary-fixed/30' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-low' }}">
                <span class="material-symbols-outlined" {!! request()->routeIs('customer.properties.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>domain</span>
                <span class="font-body-md text-body-md">My Properties</span>
            </a>
            <a href="{{ route('customer.map.index') }}" @click="mobileMenuOpen = false"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors duration-200 {{ request()->routeIs('customer.map.*') ? 'text-primary font-bold border-l-4 border-primary bg-primary-fixed/30' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-low' }}">
                <span class="material-symbols-outlined" {!! request()->routeIs('customer.map.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>map</span>
                <span class="font-body-md text-body-md">Estate Map</span>
            </a>
            <a href="{{ route('customer.documents.index') }}" @click="mobileMenuOpen = false"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors duration-200 {{ request()->routeIs('customer.documents.*') ? 'text-primary font-bold border-l-4 border-primary bg-primary-fixed/30' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-low' }}">
                <span class="material-symbols-outlined" {!! request()->routeIs('customer.documents.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>description</span>
                <span class="font-body-md text-body-md">Documents</span>
            </a>
            <a href="{{ route('customer.support.index') }}" @click="mobileMenuOpen = false"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors duration-200 {{ request()->routeIs('customer.support.*') ? 'text-primary font-bold border-l-4 border-primary bg-primary-fixed/30' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-low' }}">
                <span class="material-symbols-outlined">support_agent</span>
                <span class="font-body-md text-body-md">Support</span>
            </a>
        </nav>

        <!-- Drawer Footer -->
        <div class="mt-auto pt-4 flex flex-col gap-1 border-t border-outline-variant/50">
            <a href="{{ route('customer.profile.edit') }}" @click="mobileMenuOpen = false"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-on-surface-variant text-body-sm hover:bg-surface-container-low transition-colors">
                <span class="material-symbols-outlined text-[20px]">settings</span>
                Settings
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-error text-body-sm hover:bg-error-container/50 hover:text-error transition-colors">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- SideNavBar (Desktop only) -->
    <aside class="fixed left-0 top-0 h-full w-[240px] z-40 border-r border-outline-variant dark:border-outline bg-surface/90 dark:bg-inverse-surface/90 backdrop-blur-xl flex flex-col py-gutter px-4 hidden md:flex">
        <div class="mb-8 px-4 mt-2 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center border border-outline-variant">
                <span class="material-symbols-outlined text-primary text-[20px]" style="font-variation-settings: 'FILL' 1;">domain</span>
            </div>
            <div>
                <h1 class="font-display-lg text-headline-md font-bold text-primary dark:text-inverse-primary tracking-tight">EstatePortal</h1>
            </div>
        </div>
        
        <nav class="flex-1 flex flex-col gap-2">
            <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg font-bold transition-transform duration-150 {{ request()->routeIs('customer.dashboard') ? 'text-primary dark:text-inverse-primary border-l-4 border-primary bg-primary-fixed/30 dark:bg-primary-container/20' : 'text-on-surface-variant dark:text-surface-variant hover:text-primary dark:hover:text-inverse-primary hover:bg-surface-container-low dark:hover:bg-surface-container-highest' }}">
                <span class="material-symbols-outlined" {!! request()->routeIs('customer.dashboard') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>dashboard</span>
                <span class="font-body-md text-body-md">Dashboard</span>
            </a>
            <a href="{{ route('customer.properties.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('customer.properties.*') ? 'text-primary dark:text-inverse-primary font-bold border-l-4 border-primary bg-primary-fixed/30' : 'text-on-surface-variant dark:text-surface-variant hover:text-primary dark:hover:text-inverse-primary hover:bg-surface-container-low dark:hover:bg-surface-container-highest' }}">
                <span class="material-symbols-outlined" {!! request()->routeIs('customer.properties.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>domain</span>
                <span class="font-body-md text-body-md">My Properties</span>
            </a>
            <a href="{{ route('customer.map.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('customer.map.*') ? 'text-primary dark:text-inverse-primary font-bold border-l-4 border-primary bg-primary-fixed/30' : 'text-on-surface-variant dark:text-surface-variant hover:text-primary hover:bg-surface-container-low' }}">
                <span class="material-symbols-outlined" {!! request()->routeIs('customer.map.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>map</span>
                <span class="font-body-md text-body-md">Estate Map</span>
            </a>
            <a href="{{ route('customer.documents.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('customer.documents.*') ? 'text-primary dark:text-inverse-primary font-bold border-l-4 border-primary bg-primary-fixed/30' : 'text-on-surface-variant dark:text-surface-variant hover:text-primary hover:bg-surface-container-low' }}">
                <span class="material-symbols-outlined" {!! request()->routeIs('customer.documents.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>description</span>
                <span class="font-body-md text-body-md">Documents</span>
            </a>
            <a href="{{ route('customer.support.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-on-surface-variant dark:text-surface-variant hover:text-primary dark:hover:text-inverse-primary hover:bg-surface-container-low dark:hover:bg-surface-container-highest transition-colors duration-200 {{ request()->routeIs('customer.support.*') ? 'text-primary' : '' }}">
                <span class="material-symbols-outlined">support_agent</span>
                <span class="font-body-md text-body-md">Support</span>
            </a>
        </nav>
        
        <div class="mt-auto pt-6 flex flex-col gap-4 border-t border-outline-variant/50">
            <div class="flex flex-col gap-1">
                <a href="{{ route('customer.profile.edit') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-on-surface-variant text-body-sm hover:bg-surface-container-low transition-colors">
                    <span class="material-symbols-outlined text-[20px]">settings</span>
                    Settings
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 rounded-lg text-error text-body-sm hover:bg-error-container/50 hover:text-error transition-colors">
                        <span class="material-symbols-outlined text-[20px]">logout</span>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>
    
    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col min-h-screen md:ml-[240px]">
        <!-- TopAppBar -->
        <header class="docked full-width top-0 sticky z-30 border-b border-outline-variant bg-surface/80 backdrop-blur-xl flex items-center w-full px-4 h-16 gap-3">

            <!-- Mobile: Hamburger (top left) + Logo -->
            <div class="flex items-center gap-3 md:hidden">
                <button
                    @click="mobileMenuOpen = true"
                    class="flex items-center justify-center w-10 h-10 rounded-xl bg-primary text-white shadow-sm active:scale-95 transition-all"
                    aria-label="Open navigation menu"
                >
                    <span class="material-symbols-outlined text-[22px]">menu</span>
                </button>
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-surface-container flex items-center justify-center border border-outline-variant">
                        <span class="material-symbols-outlined text-primary text-[18px]" style="font-variation-settings: 'FILL' 1;">domain</span>
                    </div>
                    <span class="font-bold text-primary text-[15px] tracking-tight">EstatePortal</span>
                </div>
            </div>

            <!-- Desktop: Search Bar -->
            <div class="flex-1 items-center max-w-md hidden md:flex">
                <div class="relative w-full">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                    <input class="w-full bg-surface-container-low border-none rounded-full py-2 pl-10 pr-4 text-body-sm font-body-sm focus:ring-2 focus:ring-primary/20 transition-all" placeholder="Search properties..." type="text">
                </div>
            </div>

            <!-- Right side: notifications + avatar -->
            <div class="flex items-center gap-2 ml-auto">
                <button class="text-on-surface-variant hover:bg-surface-container-high rounded-full p-2 transition-all relative">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full border-2 border-surface hidden"></span>
                </button>

                <div class="w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center font-bold tracking-wider text-sm border-2 border-primary/30 cursor-pointer shadow-sm">
                    {{ substr(Auth::user()->first_name ?? 'C', 0, 1) }}{{ substr(Auth::user()->last_name ?? 'U', 0, 1) }}
                </div>
            </div>
        </header>
        
        <!-- Dashboard Canvas -->
        <div class="flex-1 p-gutter max-w-container-max mx-auto w-full flex flex-col gap-6 lg:gap-8 pb-12">
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
        </div>
    </main>
</div>
@endsection
