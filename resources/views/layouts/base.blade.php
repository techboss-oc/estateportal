<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EstatePortal')</title>
    
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@0,100..900;1,100..900&amp;family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&amp;family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&amp;display=swap" rel="stylesheet"/>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              colors: {
                  "inverse-on-surface": "#eff1f3",
                  "inverse-surface": "#2d3133",
                  "secondary-fixed": "#dfe2eb",
                  "on-surface": "#191c1e",
                  "on-tertiary-container": "#bfd0ea",
                  "on-tertiary": "#ffffff",
                  "primary": "#003c90",
                  "inverse-primary": "#b0c6ff",
                  "surface-variant": "#e0e3e5",
                  "surface-tint": "#1d59c1",
                  "on-secondary": "#ffffff",
                  "surface-bright": "#f7f9fb",
                  "on-tertiary-fixed-variant": "#38485d",
                  "on-secondary-fixed": "#181c22",
                  "surface-container-lowest": "#ffffff",
                  "surface-dim": "#d8dadc",
                  "on-tertiary-fixed": "#0b1c30",
                  "tertiary-fixed-dim": "#b7c8e1",
                  "primary-fixed": "#d9e2ff",
                  "surface": "#f7f9fb",
                  "secondary-container": "#dfe2eb",
                  "tertiary-fixed": "#d3e4fe",
                  "outline-variant": "#c3c6d5",
                  "tertiary-container": "#49596f",
                  "on-error": "#ffffff",
                  "on-primary": "#ffffff",
                  "surface-container": "#eceef0",
                  "primary-fixed-dim": "#b0c6ff",
                  "on-secondary-container": "#61646c",
                  "surface-container-low": "#f2f4f6",
                  "secondary": "#5b5e66",
                  "outline": "#737784",
                  "primary-container": "#0f52ba",
                  "secondary-fixed-dim": "#c3c6cf",
                  "on-surface-variant": "#434653",
                  "tertiary": "#324257",
                  "on-primary-container": "#bcceff",
                  "on-primary-fixed-variant": "#00419c",
                  "error": "#ba1a1a",
                  "surface-container-highest": "#e0e3e5",
                  "background": "#f7f9fb",
                  "on-primary-fixed": "#001945",
                  "error-container": "#ffdad6",
                  "on-secondary-fixed-variant": "#43474e",
                  "on-error-container": "#93000a",
                  "on-background": "#191c1e",
                  "surface-container-high": "#e6e8ea"
              },
              borderRadius: {
                  "DEFAULT": "0.25rem",
                  "lg": "0.5rem",
                  "xl": "0.75rem",
                  "full": "9999px"
              },
              spacing: {
                  "gutter": "24px",
                  "unit": "8px",
                  "container-max": "1440px",
                  "margin-desktop": "40px",
                  "margin-mobile": "16px"
              },
              fontFamily: {
                  "display-lg": ["Hanken Grotesk"],
                  "body-sm": ["Inter"],
                  "body-md": ["Inter"],
                  "headline-lg": ["Hanken Grotesk"],
                  "label-caps": ["JetBrains Mono"],
                  "headline-md": ["Hanken Grotesk"],
                  "body-lg": ["Inter"],
                  "headline-lg-mobile": ["Hanken Grotesk"]
              },
              fontSize: {
                  "display-lg": ["48px", { "lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                  "body-sm": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                  "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                  "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                  "label-caps": ["12px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "500" }],
                  "headline-md": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
                  "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }],
                  "headline-lg-mobile": ["24px", { "lineHeight": "32px", "fontWeight": "600" }]
              }
            }
          }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 4px 6px -1px rgba(0, 60, 144, 0.05), 0 2px 4px -1px rgba(0, 60, 144, 0.03);
        }
        
        .shadow-soft {
            box-shadow: 0 4px 20px -2px rgba(0, 60, 144, 0.08);
        }

        .text-gradient {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-image: linear-gradient(90deg, #003c90, #1d59c1);
        }
        
        @layer base {
            html, body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-background text-on-background font-body-md min-h-screen @yield('body-classes', 'flex')">
    @yield('layout')
    
    @stack('scripts')
</body>
</html>
