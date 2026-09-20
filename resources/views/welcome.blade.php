<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A premier estate management portal for plot allocation, tenant management, and real estate operations.">
    <title>{{ config('app.name', 'EstatePortal') }} — Premium Estate Management</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --gold:        #C9A84C;
            --gold-light:  #E8C97A;
            --navy:        #0D1B2A;
            --navy-mid:    #122137;
            --navy-light:  #1A3050;
            --cream:       #F5F0E8;
            --slate:       #8A9BB0;
            --white:       #FFFFFF;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--navy);
            color: var(--white);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ─── SCROLLBAR ──────────────────────── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--navy); }
        ::-webkit-scrollbar-thumb { background: var(--gold); border-radius: 3px; }

        /* ─── NAVBAR ─────────────────────────── */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            padding: 0 2rem;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background 0.4s, border-bottom 0.4s;
        }
        .navbar.scrolled {
            background: rgba(13, 27, 42, 0.96);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(201, 168, 76, 0.2);
        }
        .navbar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .navbar-logo-icon {
            width: 40px; height: 40px;
            border: 1.5px solid var(--gold);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .navbar-logo-icon svg { width: 22px; height: 22px; color: var(--gold); }
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            color: var(--white);
            font-weight: 700;
            letter-spacing: 0.02em;
        }
        .navbar-links {
            display: flex;
            align-items: center;
            gap: 2.5rem;
            list-style: none;
        }
        .navbar-links a {
            font-size: 0.825rem;
            font-weight: 500;
            color: var(--slate);
            text-decoration: none;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            transition: color 0.2s;
        }
        .navbar-links a:hover { color: var(--gold-light); }
        .navbar-actions { display: flex; align-items: center; gap: 1rem; }
        .btn-login {
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--gold);
            border: 1.5px solid var(--gold);
            padding: 0.55rem 1.4rem;
            border-radius: 3px;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
            display: inline-block;
        }
        .btn-login:hover { background: var(--gold); color: var(--navy); }
        .btn-primary {
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--navy);
            background: var(--gold);
            border: none;
            padding: 0.65rem 1.6rem;
            border-radius: 3px;
            text-decoration: none;
            transition: background 0.2s, transform 0.2s;
            display: inline-block;
        }
        .btn-primary:hover { background: var(--gold-light); transform: translateY(-1px); }

        /* ─── HERO ───────────────────────────── */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .hero-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
        }
        .hero-bg img {
            width: 100%; height: 100%;
            object-fit: cover;
            object-position: center;
        }
        .hero-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to right,
                rgba(13, 27, 42, 0.93) 0%,
                rgba(13, 27, 42, 0.72) 55%,
                rgba(13, 27, 42, 0.35) 100%
            );
        }
        .hero-inner {
            position: relative;
            z-index: 1;
            max-width: 1280px;
            margin: 0 auto;
            padding: 8rem 2rem 6rem;
            width: 100%;
        }
        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 1.8rem;
        }
        .hero-eyebrow::before {
            content: '';
            width: 32px; height: 1px;
            background: var(--gold);
            display: block;
        }
        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.6rem, 5vw, 4.4rem);
            font-weight: 700;
            line-height: 1.12;
            color: var(--white);
            max-width: 640px;
            margin-bottom: 1.5rem;
            letter-spacing: -0.01em;
        }
        .hero-title span { color: var(--gold); }
        .hero-sub {
            font-size: 1.05rem;
            line-height: 1.8;
            color: var(--slate);
            max-width: 520px;
            margin-bottom: 2.8rem;
            font-weight: 300;
        }
        .hero-cta {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            flex-wrap: wrap;
        }
        .btn-outline-gold {
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--gold);
            border: 1.5px solid var(--gold);
            padding: 0.65rem 1.6rem;
            border-radius: 3px;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
        }
        .btn-outline-gold:hover { background: var(--gold); color: var(--navy); }

        /* ─── HERO STATS BAR ─────────────────── */
        .stats-bar {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            z-index: 2;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            border-top: 1px solid rgba(201, 168, 76, 0.2);
            background: rgba(13, 27, 42, 0.88);
            backdrop-filter: blur(12px);
        }
        .stat-item {
            padding: 1.6rem 2.5rem;
            border-right: 1px solid rgba(201, 168, 76, 0.15);
        }
        .stat-item:last-child { border-right: none; }
        .stat-number {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--gold);
            line-height: 1;
            margin-bottom: 0.3rem;
        }
        .stat-label {
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--slate);
        }

        /* ─── SECTION COMMONS ────────────────── */
        .section { padding: 7rem 2rem; }
        .section-inner { max-width: 1280px; margin: 0 auto; }
        .section-eyebrow {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
        }
        .section-eyebrow::before {
            content: '';
            width: 28px; height: 1px;
            background: var(--gold);
        }
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.9rem, 3.5vw, 2.9rem);
            font-weight: 700;
            line-height: 1.2;
            color: var(--white);
            margin-bottom: 1rem;
        }
        .section-sub {
            font-size: 1rem;
            line-height: 1.8;
            color: var(--slate);
            max-width: 520px;
            font-weight: 300;
        }

        /* ─── ABOUT / PITCH ──────────────────── */
        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6rem;
            align-items: center;
        }
        .about-image-wrap {
            position: relative;
        }
        .about-image-main {
            width: 100%;
            border-radius: 4px;
            object-fit: cover;
            height: 480px;
            display: block;
        }
        .about-image-badge {
            position: absolute;
            bottom: -2rem;
            right: -2rem;
            background: var(--gold);
            color: var(--navy);
            border-radius: 4px;
            padding: 1.4rem 1.8rem;
            text-align: center;
        }
        .about-badge-number {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 700;
            line-height: 1;
        }
        .about-badge-text {
            font-size: 0.72rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-weight: 600;
            margin-top: 0.2rem;
        }
        .about-corner-accent {
            position: absolute;
            top: -1.2rem; left: -1.2rem;
            width: 80px; height: 80px;
            border-top: 3px solid var(--gold);
            border-left: 3px solid var(--gold);
        }
        .about-right { }
        .checklist { list-style: none; margin-top: 2rem; display: flex; flex-direction: column; gap: 1rem; }
        .checklist li {
            display: flex;
            align-items: flex-start;
            gap: 0.8rem;
            font-size: 0.95rem;
            color: var(--slate);
            line-height: 1.6;
        }
        .check-icon {
            flex-shrink: 0;
            margin-top: 3px;
            width: 18px; height: 18px;
            background: rgba(201, 168, 76, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .check-icon svg { width: 10px; height: 10px; color: var(--gold); }

        /* ─── FEATURES ───────────────────────── */
        #features { background: var(--navy-mid); }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5px;
            margin-top: 4rem;
            border: 1.5px solid rgba(201, 168, 76, 0.15);
        }
        .feature-card {
            background: var(--navy-mid);
            padding: 2.8rem 2.2rem;
            transition: background 0.25s;
            border: 1.5px solid transparent;
        }
        .feature-card:hover {
            background: rgba(201, 168, 76, 0.05);
            border-color: rgba(201, 168, 76, 0.2);
        }
        .feature-icon {
            width: 52px; height: 52px;
            border: 1.5px solid rgba(201, 168, 76, 0.3);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.6rem;
        }
        .feature-icon svg { width: 24px; height: 24px; color: var(--gold); }
        .feature-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 0.8rem;
        }
        .feature-desc {
            font-size: 0.88rem;
            line-height: 1.8;
            color: var(--slate);
            font-weight: 300;
        }

        /* ─── HOW IT WORKS ────────────────────── */
        .steps-wrap {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
            margin-top: 4rem;
            position: relative;
        }
        .steps-wrap::after {
            content: '';
            position: absolute;
            top: 26px; left: 10%; right: 10%;
            height: 1px;
            background: linear-gradient(to right, transparent, var(--gold), transparent);
        }
        .step-item { text-align: center; padding: 0 1.5rem; }
        .step-number-wrap {
            width: 52px; height: 52px;
            border-radius: 50%;
            border: 1.5px solid var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.4rem;
            background: var(--navy);
            position: relative;
            z-index: 1;
        }
        .step-number {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--gold);
        }
        .step-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--white);
            margin-bottom: 0.5rem;
            letter-spacing: 0.02em;
        }
        .step-desc {
            font-size: 0.82rem;
            color: var(--slate);
            line-height: 1.7;
            font-weight: 300;
        }

        /* ─── TESTIMONIAL / CTA BAND ─────────── */
        .cta-band {
            background: var(--gold);
            padding: 5rem 2rem;
        }
        .cta-band-inner {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
        }
        .cta-band-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.6rem, 3vw, 2.4rem);
            font-weight: 700;
            color: var(--navy);
            max-width: 520px;
            line-height: 1.25;
        }
        .btn-navy {
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--gold);
            background: var(--navy);
            border: none;
            padding: 1rem 2.2rem;
            border-radius: 3px;
            text-decoration: none;
            white-space: nowrap;
            transition: background 0.2s;
            display: inline-block;
        }
        .btn-navy:hover { background: var(--navy-mid); }

        /* ─── FOOTER ─────────────────────────── */
        .footer {
            background: var(--navy-mid);
            border-top: 1px solid rgba(201, 168, 76, 0.15);
            padding: 3.5rem 2rem;
        }
        .footer-inner {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        .footer-links { display: flex; gap: 2rem; flex-wrap: wrap; }
        .footer-links a {
            font-size: 0.78rem;
            color: var(--slate);
            text-decoration: none;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            transition: color 0.2s;
        }
        .footer-links a:hover { color: var(--gold); }
        .footer-copy {
            font-size: 0.75rem;
            color: rgba(138, 155, 176, 0.6);
        }

        /* ─── DIVIDER ────────────────────────── */
        .gold-divider {
            width: 48px; height: 2px;
            background: var(--gold);
            margin: 1.2rem 0 1.8rem;
        }

        /* ─── RESPONSIVE ─────────────────────── */
        @media (max-width: 1024px) {
            .about-grid { grid-template-columns: 1fr; gap: 4rem; }
            .about-image-wrap { display: none; }
            .features-grid { grid-template-columns: repeat(2, 1fr); }
            .steps-wrap { grid-template-columns: repeat(2, 1fr); }
            .steps-wrap::after { display: none; }
            .step-item { margin-bottom: 2rem; }
            .stats-bar { grid-template-columns: repeat(2, 1fr); }
            .stat-item:nth-child(2) { border-right: none; }
        }
        @media (max-width: 768px) {
            .navbar-links { display: none; }
            .navbar { padding: 0 1rem; }
            .navbar-brand { font-size: 1rem; }
            .features-grid { grid-template-columns: 1fr; }
            .steps-wrap { grid-template-columns: 1fr; }
            .cta-band-inner { flex-direction: column; text-align: center; }
            /* Stats bar: come out of absolute, sit below hero content */
            .stats-bar {
                position: static;
                grid-template-columns: repeat(2, 1fr);
                border-top: 1px solid rgba(201, 168, 76, 0.2);
            }
            .stat-item {
                padding: 1.2rem 1rem;
                text-align: center;
                border-right: 1px solid rgba(201, 168, 76, 0.15);
                border-bottom: 1px solid rgba(201, 168, 76, 0.1);
            }
            .stat-item:nth-child(2) { border-right: none; }
            .stat-item:nth-child(3) { border-bottom: none; }
            .stat-item:nth-child(4) { border-right: none; border-bottom: none; }
            .stat-number { font-size: 1.5rem; }
            .stat-label { font-size: 0.65rem; }
            .hero {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                padding-bottom: 0;
            }
            .hero-inner { padding: 6rem 1.2rem 3rem; flex: 1; }
            .hero-title { font-size: 2rem; }
            .hero-sub { font-size: 0.95rem; }
            .section { padding: 4rem 1.2rem; }
            .hero-bg::after {
                background: linear-gradient(
                    to bottom,
                    rgba(13, 27, 42, 0.9) 0%,
                    rgba(13, 27, 42, 0.75) 100%
                );
            }
        }
        @media (max-width: 420px) {
            .stat-number { font-size: 1.3rem; }
            .btn-login { font-size: 0.72rem; padding: 0.5rem 1rem; }
            .btn-primary { font-size: 0.72rem; padding: 0.5rem 1rem; }
        }
    </style>
</head>
<body>

<!-- ═══════════════ NAVBAR ═══════════════ -->
<nav class="navbar" id="main-nav">
    <a class="navbar-logo" href="/">
        <div class="navbar-logo-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </div>
        <span class="navbar-brand">{{ config('app.name', 'EstatePortal') }}</span>
    </a>

    <ul class="navbar-links">
        <li><a href="#about">About</a></li>
        <li><a href="#features">Features</a></li>
        <li><a href="#how-it-works">Process</a></li>
        <li><a href="{{ url('/faq') ?? '#' }}">FAQ</a></li>
    </ul>

    <div class="navbar-actions">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-login">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary">Register Now</a>
                @endif
            @endauth
        @endif
    </div>
</nav>

<!-- ═══════════════ HERO ═══════════════════ -->
<section class="hero">
    <div class="hero-bg">
        <img src="{{ asset('images/hero_bg.png') }}" alt="Luxury Estate">
    </div>

    <div class="hero-inner">
        <p class="hero-eyebrow">Premium Estate Management</p>
        <h1 class="hero-title">
            Where Property <span>Management</span><br>Meets Excellence
        </h1>
        <div class="gold-divider"></div>
        <p class="hero-sub">
            A unified platform built for estate developers, administrators, and property owners.
            Manage plots, allocations, and tenants with precision and ease.
        </p>
        <div class="hero-cta">
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn-primary" style="padding: 0.85rem 2rem;">Create an Account</a>
            @endif
            <a href="#features" class="btn-outline-gold" style="padding: 0.85rem 2rem;">Explore Platform</a>
        </div>
    </div>

    <!-- Stats bar -->
    <div class="stats-bar">
        <div class="stat-item">
            <div class="stat-number">10,000+</div>
            <div class="stat-label">Plots Managed</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">99.9%</div>
            <div class="stat-label">System Uptime</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">50+</div>
            <div class="stat-label">Estate Developments</div>
        </div>
        <div class="stat-item" style="border-right:none;">
            <div class="stat-number">24 / 7</div>
            <div class="stat-label">Dedicated Support</div>
        </div>
    </div>
</section>

<!-- ═══════════════ ABOUT ══════════════════ -->
<section class="section" id="about">
    <div class="section-inner">
        <div class="about-grid">
            <div class="about-image-wrap">
                <div class="about-corner-accent"></div>
                <img class="about-image-main" src="{{ asset('images/hero_bg.png') }}" alt="Estate Overview">
                <div class="about-image-badge">
                    <div class="about-badge-number">12+</div>
                    <div class="about-badge-text">Years of Excellence</div>
                </div>
            </div>

            <div class="about-right">
                <p class="section-eyebrow">Who We Are</p>
                <h2 class="section-title">Built for the Modern Real Estate Professional</h2>
                <div class="gold-divider"></div>
                <p class="section-sub">
                    {{ config('app.name', 'EstatePortal') }} is a comprehensive digital solution purpose-built for estate management companies, property developers, and real estate administrators who demand accuracy, transparency, and control at every level.
                </p>
                <ul class="checklist">
                    <li>
                        <span class="check-icon">
                            <svg fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                        </span>
                        Real-time plot allocation and availability tracking with interactive maps
                    </li>
                    <li>
                        <span class="check-icon">
                            <svg fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                        </span>
                        Secure, role-based access for administrators, staff, and customers
                    </li>
                    <li>
                        <span class="check-icon">
                            <svg fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                        </span>
                        Automated payment processing and financial reporting dashboards
                    </li>
                    <li>
                        <span class="check-icon">
                            <svg fill="none" viewBox="0 0 12 12" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                        </span>
                        Full audit trail and document management for every transaction
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════ FEATURES ═══════════════ -->
<section class="section" id="features">
    <div class="section-inner">
        <div style="text-align:center; margin-bottom:0;">
            <p class="section-eyebrow" style="justify-content:center;">Platform Capabilities</p>
            <h2 class="section-title" style="margin:0 auto 0.6rem;">Everything Your Estate Operation Needs</h2>
            <div class="gold-divider" style="margin:1.2rem auto 0;"></div>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                </div>
                <h3 class="feature-title">Interactive Estate Mapping</h3>
                <p class="feature-desc">Upload your site plans and map every plot with precision using our layered SVG overlay system. Real-time colour coding shows status at a glance.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="feature-title">Customer & Tenant Portal</h3>
                <p class="feature-desc">Give buyers and tenants a professional, secure portal to view their allocation, payment schedules, and important documents any time.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="feature-title">Financial Management</h3>
                <p class="feature-desc">Generate invoices, track instalmental payments, automate receipts, and export comprehensive financial reports for your accounts team.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h3 class="feature-title">Role-Based Access Control</h3>
                <p class="feature-desc">Fine-grained permission management ensures administrators, staff, and customers each see exactly what they need — nothing more, nothing less.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="feature-title">Reports & Analytics</h3>
                <p class="feature-desc">Executive dashboards provide real-time KPIs on occupancy rates, revenue collected, pending payments, and plot availability across all estates.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <h3 class="feature-title">Communication Hub</h3>
                <p class="feature-desc">Built-in messaging, automated notifications, and announcement broadcasts keep every stakeholder informed at every stage of the journey.</p>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════ HOW IT WORKS ═══════════ -->
<section class="section" id="how-it-works">
    <div class="section-inner">
        <div style="text-align:center; margin-bottom:0;">
            <p class="section-eyebrow" style="justify-content:center;">Our Process</p>
            <h2 class="section-title" style="margin:0 auto 0.4rem;">From Onboarding to Operations in 4 Steps</h2>
            <div class="gold-divider" style="margin:1.2rem auto 0;"></div>
        </div>

        <div class="steps-wrap">
            <div class="step-item">
                <div class="step-number-wrap"><span class="step-number">01</span></div>
                <h4 class="step-title">Create Your Account</h4>
                <p class="step-desc">Register your company and configure your estate management profile with branding and preferences.</p>
            </div>
            <div class="step-item">
                <div class="step-number-wrap"><span class="step-number">02</span></div>
                <h4 class="step-title">Upload Estate Plan</h4>
                <p class="step-desc">Import your site blueprint and map every plot using our coordinate-accurate SVG overlay tools.</p>
            </div>
            <div class="step-item">
                <div class="step-number-wrap"><span class="step-number">03</span></div>
                <h4 class="step-title">Onboard Customers</h4>
                <p class="step-desc">Invite buyers and tenants to their personal portal where they can track their properties and payments.</p>
            </div>
            <div class="step-item">
                <div class="step-number-wrap"><span class="step-number">04</span></div>
                <h4 class="step-title">Manage &amp; Scale</h4>
                <p class="step-desc">Use real-time dashboards to manage operations, resolve issues, and grow your portfolio with confidence.</p>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════ CTA BAND ════════════════ -->
<div class="cta-band">
    <div class="cta-band-inner">
        <h2 class="cta-band-title">Ready to transform how you manage your properties?</h2>
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn-navy">Get Started Today</a>
        @endif
    </div>
</div>

<!-- ═══════════════ FOOTER ══════════════════ -->
<footer class="footer">
    <div class="footer-inner">
        <a class="navbar-logo" href="/" style="text-decoration:none;">
            <div class="navbar-logo-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <span class="navbar-brand" style="font-size:1rem;">{{ config('app.name', 'EstatePortal') }}</span>
        </a>

        <div class="footer-links">
            <a href="{{ url('/faq') ?? '#faq' }}">FAQ</a>
            <a href="#features">Features</a>
            @if (Route::has('login'))
                <a href="{{ route('login') }}">Sign In</a>
            @endif
            @if (Route::has('register'))
                <a href="{{ route('register') }}">Register</a>
            @endif
        </div>

        <div style="text-align:right;">
            <p class="footer-copy">&copy; {{ date('Y') }} {{ config('app.name', 'EstatePortal') }}. All rights reserved.</p>
            <p class="footer-copy" style="margin-top:0.4rem;">
                Designed &amp; Developed by
                <a href="https://wa.me/2348072703028" target="_blank" rel="noopener noreferrer"
                   style="color:var(--gold); text-decoration:none; font-weight:600; transition:opacity 0.2s;"
                   onmouseover="this.style.opacity='0.75'" onmouseout="this.style.opacity='1'">
                    smikedigital
                </a>
            </p>
        </div>
    </div>
</footer>

<script>
    // Sticky nav scroll effect
    const nav = document.getElementById('main-nav');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 60) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });
</script>
</body>
</html>
