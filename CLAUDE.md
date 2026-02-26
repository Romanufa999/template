# DMITRY DOM — Project Instructions

## Supabase

- Supabase credentials are stored in `.env` file (never commit it)
- Use `$SUPABASE_URL` and `$SUPABASE_SERVICE_KEY` from environment variables
- Schema: `saity`, Table: `ui` — stores individual HTML sections
- When making REST API calls, use headers:
  - `Content-Profile: saity` (for POST/PATCH/DELETE)
  - `Accept-Profile: saity` (for GET)
- NEVER hardcode keys in source code or commits

## Design System

- Style: iOS/Apple Premium "Titanium & Glass" dark theme
- CSS: Tailwind CSS 4 only (no `<style>` tags)
- Icons: Lucide Icons (`<i data-lucide="icon-name">`)
- JS: Vanilla JS only (no frameworks)
- Fonts: Manrope (headings), Inter (labels/captions)

## Sections in Supabase (saity.ui)

| id  | section                | chars  |
|-----|------------------------|--------|
| 89  | 1header                | 11410  |
| 100 | 2hero                  | 12691  |
| 96  | 3benefits              | 4531   |
| 99  | 3services              | 11766  |
| 91  | 4catalog               | 27114  |
| 92  | 5portfolio             | 19468  |
| 93  | 6technology            | 12800  |
| 94  | 7reviews               | 12439  |
| 95  | 8about                 | 16862  |
| 98  | 9faq-contacts-footer   | 22409  |
