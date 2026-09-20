@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<div class="flex flex-col md:flex-row w-full min-h-screen">
    <!-- Left Panel: Visual/Brand (Hidden on mobile) -->
    <div class="hidden md:flex md:w-1/2 lg:w-[55%] relative overflow-hidden bg-surface-container-high items-end justify-start p-margin-desktop">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img alt="Secure Property Management Visual" class="w-full h-full object-cover object-center" data-alt="..." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBapEYw7KPf8xyZJ1TBP6V0NoF1pRCqUyxhjFKFB-x31PJ8FYBuMnAvR87UT3OIvKh0PFbIw6NG9FwCh1IBhE3hgNjay3v7J1cLsxwjqZsGjANvgpx8ijsdgcWRJjvWLhLoMBd__nyC-I99HP3_jB2-RljO8hbz226ARbTQXWmgVp93EpWBl8Jwd2zgL68A2I_u33Dii4W8MSOAMEE_SIgfF5wWR6ab1TpvOgBFaSVmgGC42CPrEI_2"/>
            <!-- Overlay Gradient -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
        </div>
        <!-- Content over image -->
        <div class="relative z-10 w-full max-w-lg glass-panel p-8 rounded-xl z-20 bg-gradient-to-t from-black/60 to-transparent border-l-4 border-l-primary-fixed">
            <div class="flex items-center gap-3 mb-6">
                <span class="material-symbols-outlined text-white text-3xl" style="font-variation-settings: 'FILL' 1;">domain</span>
                <h2 class="font-headline-md text-headline-md text-white tracking-tight">EstatePortal</h2>
            </div>
            <h1 class="font-display-lg text-display-lg text-white mb-4">Secure Property Management</h1>
            <p class="font-body-lg text-body-lg text-white/90">
                Advanced digital finance and asset management engineered for high-stakes environments.
            </p>
        </div>
    </div>
    
    <!-- Right Panel: Auth Form -->
    <div class="w-full md:w-1/2 lg:w-[45%] flex flex-col justify-center px-4 md:px-10 py-12 bg-surface-container-lowest z-10 relative">
        <!-- Mobile Brand Header -->
        <div class="md:hidden flex items-center gap-3 mb-12 justify-center w-full">
            <span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings: 'FILL' 1;">domain</span>
            <h2 class="font-headline-md text-headline-md text-primary tracking-tight font-bold">EstatePortal</h2>
        </div>
        
        <!-- Form Container -->
        <div class="w-full max-w-md mx-auto">
            <div class="mb-10 text-center md:text-left">
                <h1 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg text-on-surface mb-2">Password Reset</h1>
                <p class="font-body-md text-body-md text-on-surface-variant">Enter your email address and we'll send you a link to securely reset your password.</p>
            </div>
            
            @if (session('status'))
                <div class="bg-primary-container text-on-primary-container p-4 rounded-lg text-sm mb-4">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-error-container text-on-error-container p-4 rounded-lg text-sm mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('password.email') }}" class="space-y-6" method="POST">
                @csrf
                <!-- Input Group -->
                <div class="space-y-2">
                    <label class="font-label-caps text-label-caps text-on-surface-variant uppercase block" for="email">Email Address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-on-surface-variant group-focus-within:text-primary transition-colors">mail</span>
                        </div>
                        <input class="w-full pl-11 pr-4 py-3 bg-surface border border-outline-variant rounded-lg font-body-md text-body-md text-on-surface placeholder-on-surface-variant focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200" id="email" name="email" placeholder="admin@estateportal.com" required type="email" value="{{ old('email') }}"/>
                    </div>
                </div>
                
                <!-- Action Button -->
                <button class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-primary text-white rounded-lg font-body-md text-body-md font-semibold hover:bg-primary-container shadow-md hover:shadow-lg transition-all duration-200" type="submit">
                    Send Reset Link
                    <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                </button>
                
                <!-- Back Link -->
                <div class="pt-6 text-center">
                    <a class="inline-flex items-center gap-2 font-body-sm text-body-sm text-primary hover:text-primary-container font-medium transition-colors" href="{{ route('login') }}">
                        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                        Back to Login
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Footer details -->
        <div class="absolute bottom-6 left-0 w-full text-center md:text-left md:pl-10 px-4">
            <p class="font-label-caps text-label-caps text-outline uppercase tracking-widest text-xs text-on-surface-variant">
                &copy; {{ date('Y') }} EstatePortal Systems
            </p>
        </div>
    </div>
</div>
@endsection
