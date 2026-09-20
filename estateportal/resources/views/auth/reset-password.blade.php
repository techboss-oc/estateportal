@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="flex flex-col w-full min-h-screen">
    <div class="w-full min-h-screen grid grid-cols-1 lg:grid-cols-2">
        <!-- Left Panel: Architectural Brand Showcase -->
        <div class="relative hidden lg:flex flex-col justify-between p-12 lg:p-16 overflow-hidden bg-surface-container-highest">
            <!-- Background Image -->
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-1000 scale-105" data-alt="..." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCbpTJLgb9pofnLsGfN0pPyq3oIeWeRLdyYNfEEla_1XROdQ6CewkSbZhMUwQiwcmdmM-tfrCIZdQX2ObQkNy3FEze4D1omVw4Usx4J_RhWApXsamDPMXwmof_QW1scAYt8YtGSM7ZuFDYDUv0sGqN2PAayT6OI3Nny5kczkhggWj7vPQtuWDoSFO2lqmxuG7pKHkz6b_RLGeSff7lYKt-mteK0EhALQYBPfGd4zuiQ7X1faDLem8jr')">
            </div>
            <!-- Sophisticated Gradient Scrim -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent mix-blend-multiply opacity-85"></div>
            <div class="absolute inset-0 bg-primary/20 backdrop-blur-[2px]"></div>

            <!-- Top Branding -->
            <div class="relative z-10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-white/90 backdrop-blur-md flex items-center justify-center shadow-lg">
                    <span class="material-symbols-outlined text-primary text-2xl" style="font-variation-settings: 'FILL' 1;">domain</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-headline-md text-headline-md text-white tracking-tight font-bold leading-none">EstatePortal</span>
                    <span class="font-label-caps text-label-caps text-primary-fixed uppercase tracking-wider mt-1">Institutional Custody</span>
                </div>
            </div>

            <!-- Glassmorphic Feature Showcase Card -->
            <div class="relative z-10 w-full max-w-lg rounded-xl p-8 bg-white/10 backdrop-blur-xl shadow-2xl border-l-4 border-l-primary-fixed">
                <div class="flex items-center gap-2 mb-4">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">shield</span>
                    </span>
                    <span class="font-label-caps text-label-caps text-primary-fixed uppercase tracking-widest">Protocol Level v4.2</span>
                </div>
                <h2 class="font-headline-md text-headline-md font-semibold tracking-tight text-white mb-3">
                  Fortified Property Asset Security
                </h2>
                <p class="font-body-md text-body-md text-white/90 leading-relaxed">
                  Advanced multi-factor encryption protecting title deeds, cadastral surveys, and real estate ownership records.
                </p>
                <div class="grid grid-cols-2 gap-4 mt-6 pt-6 bg-black/10 rounded-lg p-4 text-white">
                    <div>
                        <p class="font-label-caps text-label-caps uppercase text-white/80">Custody Volume</p>
                        <p class="font-body-lg text-body-lg font-bold mt-0.5 tracking-tight">$14.8B USD</p>
                    </div>
                    <div>
                        <p class="font-label-caps text-label-caps uppercase text-white/80">Cryptographic Audit</p>
                        <p class="font-body-lg text-body-lg font-bold mt-0.5 tracking-tight">Zero-Knowledge</p>
                    </div>
                </div>
            </div>
            <!-- Left Footer Metadata -->
            <div class="relative z-10 flex items-center justify-between text-white/80">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-primary-fixed animate-pulse"></span>
                    <span class="font-label-caps text-label-caps tracking-wider uppercase">Ledger Active: Node SEC-09</span>
                </div>
                <span class="font-label-caps text-label-caps opacity-80">ISO 27001 Verified</span>
            </div>
        </div>
        
        <!-- Right Panel: Password Reset Form -->
        <div class="flex flex-col justify-between p-8 sm:p-12 lg:p-16 xl:p-20 bg-surface-container-lowest">
            <!-- Top Navigation -->
            <div class="w-full flex justify-between items-center">
                <!-- Mobile Logo fallback -->
                <div class="flex items-center gap-2 lg:hidden">
                    <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-lg">domain</span>
                    </div>
                    <span class="font-headline-md text-body-lg font-bold text-on-surface">EstatePortal</span>
                </div>
                <a class="inline-flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors py-2 px-3 rounded-lg hover:bg-surface-container-low ml-auto lg:ml-0" href="{{ route('login') }}">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    <span class="font-body-sm text-body-sm font-medium">Back to Login</span>
                </a>
            </div>
            
            <!-- Form Center Container -->
            <div class="w-full max-w-md mx-auto my-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="w-12 h-12 rounded-xl bg-surface-container-low flex items-center justify-center text-primary mb-4 shadow-sm">
                        <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">lock_reset</span>
                    </div>
                    <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold mb-2">
                        Set New Password
                    </h1>
                    <p class="font-body-md text-body-md text-on-surface-variant">
                        Your new password must be secure and different from previously used passwords.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="bg-error-container text-on-error-container p-4 rounded-lg text-sm mb-4">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <!-- Form Elements -->
                <form action="{{ route('password.store') }}" class="space-y-6" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email Input -->
                    <div class="space-y-2">
                        <label class="block font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">
                            Email Address
                        </label>
                        <div class="relative flex items-center">
                            <span class="absolute left-4 text-on-surface-variant pointer-events-none">
                                <span class="material-symbols-outlined text-xl">mail</span>
                            </span>
                            <input class="w-full pl-12 pr-4 py-3.5 bg-surface rounded-xl border border-outline-variant text-on-surface font-body-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-outline" type="email" name="email" value="{{ $email ?? old('email') }}" required autofocus />
                        </div>
                    </div>

                    <!-- New Password Input -->
                    <div class="space-y-2">
                        <label class="block font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">
                          New Master Password
                        </label>
                        <div class="relative flex items-center">
                            <span class="absolute left-4 text-on-surface-variant pointer-events-none">
                                <span class="material-symbols-outlined text-xl">key</span>
                            </span>
                            <input class="w-full pl-12 pr-12 py-3.5 bg-surface rounded-xl border border-outline-variant text-on-surface font-body-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-outline" id="password-field" name="password" placeholder="Enter password" type="password" required />
                            <button aria-label="Toggle password visibility" class="absolute right-3 p-1.5 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors focus:outline-none" id="toggle-password" type="button">
                                <span class="material-symbols-outlined text-xl" id="eye-icon">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="space-y-2 pt-2">
                        <label class="block font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">
                          Confirm New Master Password
                        </label>
                        <div class="relative flex items-center">
                            <span class="absolute left-4 text-on-surface-variant pointer-events-none">
                                <span class="material-symbols-outlined text-xl">lock</span>
                            </span>
                            <input class="w-full pl-12 pr-12 py-3.5 bg-surface rounded-xl border border-outline-variant text-on-surface font-body-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-outline" name="password_confirmation" placeholder="Confirm password" type="password" required />
                        </div>
                    </div>

                    <!-- Primary Submit Button -->
                    <button class="w-full py-4 px-6 rounded-xl bg-primary text-white font-body-md font-semibold tracking-wide shadow-md hover:bg-primary-container hover:shadow-lg active:scale-[0.99] transition-all flex items-center justify-center gap-2 group cursor-pointer" type="submit">
                        <span>Reset Password &amp; Sign In</span>
                        <span class="material-symbols-outlined text-xl transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </button>
                </form>
            </div>
            
            <!-- Security Trust & Footer Information -->
            <div class="w-full max-w-md mx-auto pt-8 border-t border-surface-container-high space-y-4 text-center">
                <!-- Security Badge -->
                <div class="inline-flex items-center justify-center gap-2 py-2 px-3.5 rounded-full bg-surface-container-low text-on-surface-variant text-center">
                    <span class="material-symbols-outlined text-base text-primary" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                    <span class="font-label-caps text-label-caps tracking-tight">256-Bit SSL End-to-End Encryption</span>
                </div>
                <!-- Footer Copyright -->
                <p class="font-body-sm text-body-sm text-on-surface-variant/75">
                  &copy; {{ date('Y') }} EstatePortal Systems. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</div>
<script>
  (function() {
    const toggleBtn = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password-field');
    const eyeIcon = document.getElementById('eye-icon');

    if (toggleBtn && passwordInput && eyeIcon) {
      toggleBtn.addEventListener('click', function() {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        eyeIcon.textContent = isPassword ? 'visibility_off' : 'visibility';
      });
    }
  })();
</script>
@endsection
