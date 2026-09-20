---
name: Aetheris Management System
colors:
  surface: '#f7f9fb'
  surface-dim: '#d8dadc'
  surface-bright: '#f7f9fb'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f2f4f6'
  surface-container: '#eceef0'
  surface-container-high: '#e6e8ea'
  surface-container-highest: '#e0e3e5'
  on-surface: '#191c1e'
  on-surface-variant: '#434653'
  inverse-surface: '#2d3133'
  inverse-on-surface: '#eff1f3'
  outline: '#737784'
  outline-variant: '#c3c6d5'
  surface-tint: '#1d59c1'
  primary: '#003c90'
  on-primary: '#ffffff'
  primary-container: '#0f52ba'
  on-primary-container: '#bcceff'
  inverse-primary: '#b0c6ff'
  secondary: '#5b5e66'
  on-secondary: '#ffffff'
  secondary-container: '#dfe2eb'
  on-secondary-container: '#61646c'
  tertiary: '#324257'
  on-tertiary: '#ffffff'
  tertiary-container: '#49596f'
  on-tertiary-container: '#bfd0ea'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#d9e2ff'
  primary-fixed-dim: '#b0c6ff'
  on-primary-fixed: '#001945'
  on-primary-fixed-variant: '#00419c'
  secondary-fixed: '#dfe2eb'
  secondary-fixed-dim: '#c3c6cf'
  on-secondary-fixed: '#181c22'
  on-secondary-fixed-variant: '#43474e'
  tertiary-fixed: '#d3e4fe'
  tertiary-fixed-dim: '#b7c8e1'
  on-tertiary-fixed: '#0b1c30'
  on-tertiary-fixed-variant: '#38485d'
  background: '#f7f9fb'
  on-background: '#191c1e'
  surface-variant: '#e0e3e5'
typography:
  display-lg:
    fontFamily: Hanken Grotesk
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Hanken Grotesk
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 40px
    letterSpacing: -0.01em
  headline-lg-mobile:
    fontFamily: Hanken Grotesk
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  headline-md:
    fontFamily: Hanken Grotesk
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-sm:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-caps:
    fontFamily: JetBrains Mono
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
    letterSpacing: 0.05em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  unit: 8px
  container-max: 1440px
  gutter: 24px
  margin-mobile: 16px
  margin-desktop: 40px
---

## Brand & Style

The design system is engineered for a high-stakes Real Estate Fintech environment. It balances the weight of physical assets with the fluid precision of modern digital finance. The personality is **authoritative yet visionary**, utilizing a **Modern-Minimalist** foundation augmented by **Glassmorphism** to signify technological sophistication.

The interface prioritizes clarity and high-value data visualization. It avoids decorative clutter in favor of structural elegance, using expansive white space and a rigid grid to evoke a sense of premium architectural planning. The emotional response should be one of absolute security and forward-thinking professionalism.

## Colors

This design system utilizes a **Deep Azure** primary palette to establish trust and financial stability. The neutral foundation relies on **Slate Grays** and **Deep Charcoals** to create a high-contrast, premium environment.

- **Primary (Deep Azure):** Used for primary actions, active navigation states, and brand highlights.
- **Secondary (Obsidian):** Reserved for deep backgrounds, headers, and primary text to ensure maximum legibility.
- **Surface Tints:** Use subtle shifts in slate-gray for container nesting rather than heavy lines.
- **Status Indicators:** Saturated, medium-chroma colors provide clear differentiation for property states without appearing "neon" or distracting from the premium aesthetic.

## Typography

The typography strategy leverages **Hanken Grotesk** for its sharp, contemporary geometry in headings, and **Inter** for its systematic clarity in data-dense body areas. 

- **Data Presentation:** Use **JetBrains Mono** for property IDs, financial figures, and technical labels to lean into the "Fintech" narrative.
- **Hierarchy:** Maintain a strict contrast between display headings and body text. 
- **Formatting:** Use generous line-heights (1.5x for body) to ensure readability during long management sessions.

## Layout & Spacing

The design system employs a **12-column fluid grid** for desktop and a **4-column grid** for mobile. The layout philosophy is "Information Density with Breathability."

- **Sidebar:** The Admin portal features a 280px fixed-width sidebar, while the Customer portal uses a 240px variant.
- **Section Spacing:** Use 80px - 120px of vertical space between major dashboard sections to maintain a premium, uncluttered feel.
- **Module Spacing:** Components are separated by a consistent 24px gutter. For internal card padding, use a 32px standard to reinforce the high-end aesthetic.

## Elevation & Depth

Depth is established through **Tonal Layering** and **Glassmorphism**, avoiding traditional heavy shadows.

- **Surface Levels:** 
  - Level 0: Background (`#F8FAFC`).
  - Level 1: Main content cards (White, 1px Slate-200 border).
  - Level 2: Interactive elements (Soft 15% opacity primary-tinted shadow).
- **Glassmorphism:** Use for overlays, side-drawers, and navigation bars. Apply a `backdrop-filter: blur(12px)` with a `20%` white translucent fill. This creates a "frosted glass" look that feels futuristic and tech-driven.
- **Borders:** Use ultra-thin `1px` borders in `Slate-200` to define edges without adding visual weight.

## Shapes

The shape language is **Professional-Rounded**. 

- **Primary Containers:** 16px (1rem) corner radius for property cards and main dashboard panels.
- **Interactive Elements:** 8px (0.5rem) for buttons and input fields.
- **Status Tags:** Fully pill-shaped (999px) to contrast against the structured rectangular grid of the property data.
- **Map Parcels:** Follow the geometric plot lines but apply a 2px "micro-round" to eliminate harsh technological sharpness.

## Components

### Sidebars & Navigation
- **Structure:** Multi-level vertical navigation. Active states use a "Primary-Light" background tint and a 4px left-accent border in the Primary color.
- **Collapse State:** Icons only with tooltips to maximize workspace for data tables.

### Property Cards
- **Visuals:** Top-aligned image with a 16:9 ratio. Overlaid "Status Pills" in the top-right corner using the glassmorphic style.
- **Data:** Use a 2-column grid within the card for property stats (e.g., Sq Ft, Price) using the Label-Caps typographic style.

### Interactive Maps
- **States:** Default parcels are light gray with thin white borders. On hover, apply the primary color at 20% opacity. 
- **Selection:** Selected plots feature a 2px primary border and an "Inner Glow" to indicate focus.

### High-Density Tables
- **Styling:** Borderless rows with a subtle `Slate-50` background on hover. 
- **Cells:** Vertical alignment centered. Use the monospaced font for all numerical and ID columns to ensure decimal alignment.

### Form Elements
- **Inputs:** Focused states use a 1px Primary border with a 4px soft Primary-glow outer ring.
- **Progress Indicators:** Horizontal steppers with "Connecting Lines" that transition from Slate to Primary as steps are completed.

### Modals & Drawers
- **Side-Drawers:** Anchor to the right side. Occupy 400px of width. Use the Glassmorphism specification for the background to maintain context with the dashboard underneath.