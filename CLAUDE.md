# CLAUDE.md

## Режимы работы

Этот репозиторий используется в **двух режимах** — зависит от задачи пользователя:

1. **Разработка проекта** — работа над самим Next.js приложением (код в `app/`, `components/`, `utils/` и т.д.). Изменения коммитятся в этот репозиторий.
2. **Генерация и деплой страниц** — Claude Code генерирует HTML-страницы во временных директориях (например `/tmp/`) и деплоит на S3 + регистрирует в базе. Сгенерированные файлы **НЕ** добавляются в этот репозиторий.

Если из задачи непонятно, какой режим — **уточни у пользователя**.

## Структура

```
template/
├── .claude/commands/   — навыки (skills) для Claude Code
├── scripts/            — скрипты деплоя (s3_upload.py, upload_section.py)
└── CLAUDE.md           — инструкции для Claude Code
```

## Правила

- Никогда не добавляй сгенерированные страницы, HTML-файлы, секции и прочие артефакты в этот репозиторий. Все генерируемые сайты собираются во временных директориях (например `/tmp/`) и деплоятся на S3.
- **Тип сборки обязателен.** У каждого сайта/проекта должно быть явно указано: это **статичная сборка (static export)** или **серверный рендеринг (SSR)**. Если не указано — **уточни у пользователя** и зафиксируй в CLAUDE.md проекта.
- **SVG ВСЕГДА в отдельные компоненты.** В каждом проекте создаётся папка `components/svg/` для SVG-компонентов. Inline SVG запрещён.
- **Психология интерфейсов и микровзаимодействия обязательны.** Каждый лэндинг ДОЛЖЕН применять принципы из навыка `ui-psychology`.
- **Layout обязателен.** Каждый сайт ДОЛЖЕН иметь корневой layout (`layout.tsx`).

## Стек и стилизация проектов

**Базовый стек:** Next.js (App Router) + Tailwind CSS 4 + shadcn/ui

**Tailwind CSS 4 (CSS-first config):**
- Конфигурация через `@theme inline {}` в `globals.css` — БЕЗ `tailwind.config.ts`
- CSS-переменные в `:root` и `.dark`
- `@custom-variant dark (&:is(.dark *));`
- tw-animate-css для анимаций (НЕ tailwindcss-animate)

**Дизайн-система — Light Premium (Apple-inspired):**
- Light-first — основная тема светлая, мягкий тёплый фон
- Glassmorphism: `bg-white/70 backdrop-blur-xl border border-white/60`
- Gradient accents: `from-blue-500 via-purple-500 to-cyan-500`
- Bento Grid: ассиметричные сетки карточек
- Subtle shadows на карточках, hover lift
- Gradient mesh backgrounds для depth
- Micro-animations: hover scale/lift, stagger entrance

**Цветовая палитра:**
- Bg: `#F5F5F7` / white
- Text: `#1D1D1F` / `#6e6e73` / `#9ca3af`
- Accents: blue `#3b82f6`, purple `#8b5cf6`, cyan `#06b6d4`
- Borders: `black/5`, `white/60`

## Навыки (Skills) — Supabase

Навыки хранятся в **Supabase** (project: `sugbffcgdjwekktzavra`, таблица `skills` + `sub_skills`).

Локальные копии навыков в `.claude/commands/` могут быть устаревшими — **Supabase является источником истины**.

### Протокол загрузки скиллов

**ОБЯЗАТЕЛЬНО при каждом запросе:**

1. **Прочитать список скиллов:**
   ```sql
   SELECT id, name, description FROM skills WHERE status = 'active'
   ```

2. **Если описание скилла подходит под задачу — прочитать полную инструкцию:**
   ```sql
   SELECT id, instruction, scripts, ref_docs, sub_skills_hint FROM skills WHERE name = '...'
   ```

3. **Загрузить подскиллы:**
   ```sql
   SELECT name, description, instruction FROM sub_skills
   WHERE skill_id = '<id_главного_скилла>' AND status = 'active'
   ORDER BY sort_order
   ```

4. **Следовать инструкциям** главного скилла и всех его подскиллов.

### Когда читать скиллы автоматически (без запроса пользователя):
- Создание/редактирование лэндинга → `interaction-design`, `ui-psychology`, `form-webhook`, `yandex-metrica`
- Деплой на S3 → `s3-deploy`
- Генерация изображений → `imagegen`
- Создание CMS → `cms`
- Создание нового скилла → `skill-creator`
- Настройка Tailwind/shadcn → `tailwind-v4-shadcn`
- SEO/schema → `schema-markup`
- Работа с MySQL → `mysql-db`
- ТЗ на сайт → `website-techspec`
