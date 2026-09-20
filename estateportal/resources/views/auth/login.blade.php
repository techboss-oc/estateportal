@extends('layouts.auth')

@section('title', 'Customer Login')

@section('content')
<div class="w-full min-h-screen flex flex-col md:flex-row bg-surface">
    <!-- Left Side: Visual/Branding (Hidden on Mobile) -->
    <div class="hidden md:flex md:w-1/2 relative bg-surface-container-high overflow-hidden items-center justify-center">
        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-center z-0" data-alt="..." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAx4yZJdsbAupN1eL-VexNaCGDLd2nM86rjnOhDOT7hPC1U_lswIhlXXJ8_qt3okuaDjbObajTFG0VG4l_alS06-fXVFSj5bfwMNC5a_NUPyxn0Fd3ZBqUcBzmIkBH1GOp0RQo4efI3kj2fq4x1Uyn_-tRtsb9wGS8I7XGKXXvxv3GYCyA42Ig8vVbzS1j29zaEP7gvYKXUawQAV8UZywHAYwf4aWp8Q6YVHD5y-NqZ_SmDGDhXvGE1')">
        </div>
        <!-- Gradient Overlay for depth -->
        <div class="absolute inset-0 bg-gradient-to-r from-primary/80 to-transparent z-10 mix-blend-multiply"></div>
        <!-- Content over Image -->
        <div class="relative z-20 p-12 text-on-primary flex flex-col justify-end h-full w-full bg-gradient-to-t from-black/60 to-transparent">
            <div class="glass-panel p-8 rounded-xl max-w-md border-l-4 border-l-primary-fixed">
                <h2 class="font-headline-lg text-headline-lg mb-4 text-white">The Future of Estate Management</h2>
                <p class="font-body-lg text-body-lg text-white/90">Experience unparalleled control and visibility over your premium property portfolio with our advanced fintech integrations.</p>
            </div>
        </div>
    </div>
    
    <!-- Right Side: Login Form -->
    <div class="w-full md:w-1/2 flex items-center justify-center p-6 md:p-12 lg:p-24 bg-surface-container-lowest z-10 relative shadow-[-10px_0_30px_rgba(0,0,0,0.05)]">
        <div class="w-full max-w-md space-y-8">
            <!-- Header/Logo -->
            <div class="text-center sm:text-left">
                <div class="flex items-center justify-center sm:justify-start gap-3 mb-6">
                    <span class="material-symbols-outlined text-primary text-4xl" data-weight="fill" style="font-variation-settings: 'FILL' 1;">domain</span>
                    <h1 class="font-display-lg text-headline-md font-bold text-primary">EstatePortal</h1>
                </div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface mb-2">Welcome Back</h2>
                <p class="font-body-md text-body-md text-on-surface-variant flex items-center justify-center sm:justify-start gap-2">
                    <span class="material-symbols-outlined text-sm text-primary">lock</span> Secure Login
                </p>
            </div>
            
            <!-- Error messages -->
            @if(session('error'))
            <div class="bg-error-container text-on-error-container p-4 rounded-lg text-sm mb-4">
                {{ session('error') }}
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

            <!-- Form -->
            <form action="{{ route('login.post') }}" class="space-y-6 mt-8" method="POST">
                @csrf
                <!-- Using one field for ID (email or username) -->
                <div>
                    <label class="block font-label-caps text-label-caps text-on-surface mb-2 uppercase" for="login_id">Email or Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-on-surface-variant">person</span>
                        </div>
                        <input autocomplete="username" class="appearance-none block w-full pl-10 pr-3 py-3 border border-outline-variant rounded-lg bg-surface placeholder-on-surface-variant text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm transition-all duration-200" id="login_id" name="login_id" placeholder="name@company.com or username" required type="text" value="{{ old('login_id') }}"/>
                    </div>
                </div>
                <!-- Password Field -->
                <div>
                    <label class="block font-label-caps text-label-caps text-on-surface mb-2 uppercase" for="password">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-on-surface-variant">key</span>
                        </div>
                        <input autocomplete="current-password" class="appearance-none block w-full pl-10 pr-10 py-3 border border-outline-variant rounded-lg bg-surface placeholder-on-surface-variant text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm transition-all duration-200" id="password" name="password" placeholder="••••••••" required type="password"/>
                        <button class="absolute inset-y-0 right-0 pr-3 flex items-center text-on-surface-variant hover:text-primary transition-colors focus:outline-none" onclick="togglePassword()" type="button">
                            <span class="material-symbols-outlined" id="password-toggle-icon">visibility</span>
                        </button>
                    </div>
                </div>
                <!-- Options Row -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input class="h-4 w-4 text-primary focus:ring-primary border-outline-variant rounded bg-surface" id="remember-me" name="remember" type="checkbox"/>
                        <label class="ml-2 block font-body-sm text-body-sm text-on-surface-variant" for="remember-me">
                            Remember me
                        </label>
                    </div>
                    <div class="text-sm">
                        <a class="font-body-sm text-body-sm font-semibold text-primary hover:text-primary-container transition-colors" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    </div>
                </div>
                <!-- Submit Button -->
                <div>
                    <button class="group relative w-full flex justify-center py-3 px-4 border border-transparent rounded-lg text-white bg-primary hover:bg-primary-container focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary font-headline-md text-body-md shadow-md hover:shadow-lg transition-all duration-200" type="submit">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <span class="material-symbols-outlined text-primary-fixed group-hover:text-white transition-colors duration-200">login</span>
                        </span>
                        Sign In
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Micro-interaction Script -->
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('password-toggle-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.textContent = 'visibility_off';
        } else {
            passwordInput.type = 'password';
            toggleIcon.textContent = 'visibility';
        }
    }
</script>
@endsection
