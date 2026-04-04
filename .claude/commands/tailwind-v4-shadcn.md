# Tailwind v4 + shadcn/ui Production Stack

**Versions**: tailwindcss@4.1.18, @tailwindcss/vite@4.1.18

## Quick Start (Exact Order)

```bash
# 1. Install dependencies
pnpm add tailwindcss @tailwindcss/vite
pnpm add -D @types/node tw-animate-css
pnpm dlx shadcn@latest init

# 2. Delete v3 config if exists
rm tailwind.config.ts  # v4 doesn't use this file
```

**vite.config.ts**:
```typescript
import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import tailwindcss from '@tailwindcss/vite'
import path from 'path'

export default defineConfig({
  plugins: [react(), tailwindcss()],
  resolve: { alias: { '@': path.resolve(__dirname, './src') } }
})
```

**components.json** (CRITICAL):
```json
{
  "tailwind": {
    "config": "",
    "css": "src/index.css",
    "baseColor": "slate",
    "cssVariables": true
  }
}
```

## The Four-Step Architecture (MANDATORY)

Skipping steps will break theme. Follow exactly:

### Step 1: Define CSS Variables at Root

```css
/* src/index.css */
@import "tailwindcss";
@import "tw-animate-css";

:root {
  --background: hsl(0 0% 100%);      /* hsl() wrapper required */
  --foreground: hsl(222.2 84% 4.9%);
  --primary: hsl(221.2 83.2% 53.3%);
  /* ... all light mode colors */
}

.dark {
  --background: hsl(222.2 84% 4.9%);
  --foreground: hsl(210 40% 98%);
  --primary: hsl(217.2 91.2% 59.8%);
  /* ... all dark mode colors */
}
```

**Critical**: Define at root level (NOT inside `@layer base`). Use `hsl()` wrapper.

### Step 2: Map Variables to Tailwind Utilities

```css
@theme inline {
  --color-background: var(--background);
  --color-foreground: var(--foreground);
  --color-primary: var(--primary);
  /* ... map ALL CSS variables */
}
```

Without this, utilities like `bg-background`, `text-primary` won't exist.

### Step 3: Apply Base Styles

```css
@layer base {
  body {
    background-color: var(--background);  /* NO hsl() wrapper here */
    color: var(--foreground);
  }
}
```

Reference variables directly. Never double-wrap: `hsl(var(--background))`.

### Step 4: Result - Automatic Dark Mode

```tsx
<div className="bg-background text-foreground">
  {/* No dark: variants needed - theme switches automatically */}
</div>
```

## Dark Mode Setup

**1. Create ThemeProvider** — standard React context that toggles `.dark` class on `<html>`.

**2. Wrap App**:
```typescript
import { ThemeProvider } from '@/components/theme-provider'

ReactDOM.createRoot(document.getElementById('root')!).render(
  <ThemeProvider defaultTheme="dark" storageKey="vite-ui-theme">
    <App />
  </ThemeProvider>
)
```

**3. Add Theme Toggle**: `pnpm dlx shadcn@latest add dropdown-menu`

## Critical Rules

### Always Do:
1. Wrap colors with `hsl()` in `:root`/`.dark`: `--bg: hsl(0 0% 100%);`
2. Use `@theme inline` to map all CSS variables
3. Set `"tailwind.config": ""` in components.json
4. Delete `tailwind.config.ts` if exists
5. Use `@tailwindcss/vite` plugin (NOT PostCSS)

### Never Do:
1. Put `:root`/`.dark` inside `@layer base` (cascade issues)
2. Use `.dark { @theme { } }` (v4 doesn't support nested @theme)
3. Double-wrap colors: `hsl(var(--background))`
4. Use `tailwind.config.ts` for theme (v4 ignores it)
5. Use `@apply` with `@layer base`/`@layer components` classes (use `@utility` instead)
6. Use `dark:` variants for semantic colors (auto-handled)
7. Wrap styles in `@layer base` without understanding CSS layer ordering

## Quick Diagnostics

| Symptom | Cause | Fix |
|---------|-------|-----|
| `bg-primary` doesn't work | Missing `@theme inline` | Add `@theme inline` block |
| Colors all black/white | Double `hsl()` wrapping | Use `var(--color)` not `hsl(var(--color))` |
| Dark mode not switching | Missing ThemeProvider | Wrap app in `<ThemeProvider>` |
| Build fails | `tailwind.config.ts` exists | Delete file |
| Animation errors | Using `tailwindcss-animate` | Install `tw-animate-css` |
| `@apply` broken | v4 breaking change | Use `@utility` instead of `@layer components` |
| Base styles ignored | CSS layer ordering | Define at root level, not in `@layer base` |

**For detailed error solutions**: See [references/common-errors.md](references/common-errors.md)

**For v3 -> v4 migration**: See [references/migration-guide.md](references/migration-guide.md)

## Tailwind v4 Plugins

Use `@plugin` directive (NOT `require()` or `@import`):

```css
/* v4 syntax */
@plugin "@tailwindcss/typography";

/* v3 syntax - DO NOT USE */
@import "@tailwindcss/typography";
```

Available: `@tailwindcss/typography` (prose), `@tailwindcss/forms`. Container queries and line-clamp are now built-in.

## OKLCH Color Space

v4 replaced default palette with OKLCH — perceptually uniform, wider gamut, better gradients. Browser support: 93.1%. Tailwind generates sRGB fallbacks automatically.

```css
@theme {
  --color-brand: oklch(0.7 0.15 250);  /* preferred */
  --color-brand: hsl(240 80% 60%);     /* still works */
}
```

## @theme inline vs @theme

**Use `@theme inline`** (default): Single theme + dark mode toggle (shadcn/ui standard).

**Use `@theme` (without inline)**: Multi-theme systems (data-theme="blue"|"green"). See [references/common-errors.md](references/common-errors.md) error #6.

## Setup Checklist

- [ ] `@tailwindcss/vite` installed (NOT postcss)
- [ ] `vite.config.ts` uses `tailwindcss()` plugin
- [ ] `components.json` has `"config": ""`
- [ ] NO `tailwind.config.ts` exists
- [ ] `src/index.css` follows 4-step pattern
- [ ] ThemeProvider wraps app
- [ ] Theme toggle works

## Official Docs

- shadcn/ui Vite: https://ui.shadcn.com/docs/installation/vite
- shadcn/ui Tailwind v4: https://ui.shadcn.com/docs/tailwind-v4
- Tailwind v4: https://tailwindcss.com/docs
