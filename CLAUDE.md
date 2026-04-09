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
- **SVG ВСЕГДА в отдельные компоненты.** В каждом проекте создаётся папка `components/svg/` для SVG-компонентов. Любой SVG (иконка, анимация, декор, фон) — это отдельный файл-компонент в `components/svg/`, который затем импортируется. Inline SVG в секциях и других компонентах запрещён. Это правило ОБЯЗАТЕЛЬНО фиксировать в CLAUDE.md каждого нового проекта.
- **Психология интерфейсов и микровзаимодействия обязательны.** Каждый лэндинг ДОЛЖЕН применять принципы из навыка `ui-psychology`: модель Hook (триггер→действие→награда→инвестиция), «сочные» микровзаимодействия (spring hover, press depth, particle burst), удержание внимания между секциями (staggered entrance, pattern interruption, визуальные связи), игровые механики (прогресс, коллекционирование, разблокировка) и конверсионная психология форм (foot-in-the-door, glow-награды, pulse при входе в viewport). При создании/редактировании лэндинга — **читать навык ui-psychology** и следовать чеклисту.
- **Layout обязателен.** Каждый сайт ДОЛЖЕН иметь корневой layout (`layout.tsx` для Next.js, или общий HTML-шаблон для статики). В layout размещаются глобальные компоненты: аналитика (Яндекс Метрика), `WebhookListener`, `UtmPersist`, виджет обратного звонка и т.д. Без layout глобальный перехват форм и аналитика не будут работать.
- **Cache-busting обязателен.** После нового деплоя у посетителей с открытыми вкладками или закешированным HTML/JS возникают ошибки из-за несовпадения версий бандлов. Каждый Next.js сайт ДОЛЖЕН реализовать автоматический механизм сброса старого кеша по паттерну ниже. Этот раздел самодостаточен — копируй файлы прямо из него, ничего искать не надо.

  **Схема работы:**
  1. В `prebuild` генерится уникальный `BUILD_ID` (таймштамп + git sha) и пишется в `public/version.json` + `.env.production.local` как `NEXT_PUBLIC_BUILD_ID`. Next.js инлайнит эту переменную прямо в JS-бандл на сборке.
  2. Клиентский компонент `VersionChecker`, примонтированный в root layout, раз в 2 минуты (и при `visibilitychange` / `focus`) дёргает `/version.json` с `cache: 'no-store'` и сравнивает `buildId` из ответа с `process.env.NEXT_PUBLIC_BUILD_ID`. При рассинхроне → `window.location.reload()`.
  3. Защита от reload-loop — `sessionStorage`, не чаще раза в 60 секунд.
  4. В `<head>` layout.tsx добавить `<meta httpEquiv="Cache-Control" content="no-cache, no-store, must-revalidate" />` как подстраховку для HTML.
  5. `public/version.json` и `.env*.local` → в `.gitignore`.
  6. На хостинге/nginx (если есть доступ к конфигу): `/_next/static/**` → `Cache-Control: public, max-age=31536000, immutable`; `/version.json` → `no-store`; `*.html` → `no-cache, no-store, must-revalidate`.

  **Файл 1: `scripts/generate-build-info.mjs`** — подключить в `package.json` → `"prebuild": "... && node scripts/generate-build-info.mjs"`:

  ```js
  import fs from "fs";
  import path from "path";
  import { fileURLToPath } from "url";
  import { execSync } from "child_process";

  const __dirname = path.dirname(fileURLToPath(import.meta.url));
  const root = path.join(__dirname, "..");

  function getGitSha() {
    try {
      return execSync("git rev-parse --short HEAD", {
        cwd: root, encoding: "utf-8", stdio: ["ignore", "pipe", "ignore"],
      }).trim();
    } catch { return "nogit"; }
  }

  const timestamp = Date.now();
  const buildId = `${timestamp}-${getGitSha()}`;
  const builtAt = new Date(timestamp).toISOString();

  fs.mkdirSync(path.join(root, "public"), { recursive: true });
  fs.writeFileSync(
    path.join(root, "public", "version.json"),
    JSON.stringify({ buildId, builtAt }, null, 2) + "\n",
    "utf-8",
  );

  const envPath = path.join(root, ".env.production.local");
  let env = "";
  if (fs.existsSync(envPath)) {
    env = fs.readFileSync(envPath, "utf-8").split("\n")
      .filter((l) => l && !l.startsWith("NEXT_PUBLIC_BUILD_ID=")).join("\n");
    if (env && !env.endsWith("\n")) env += "\n";
  }
  env += `NEXT_PUBLIC_BUILD_ID=${buildId}\n`;
  fs.writeFileSync(envPath, env, "utf-8");
  console.log(`Build ID: ${buildId} (built at ${builtAt})`);
  ```

  **Файл 2: `src/components/ui/VersionChecker.tsx`** — примонтировать в body root layout:

  ```tsx
  "use client";
  import { useEffect, useRef } from "react";

  const BUILD_ID = process.env.NEXT_PUBLIC_BUILD_ID;
  const POLL_INTERVAL_MS = 2 * 60 * 1000;
  const INITIAL_DELAY_MS = 5 * 1000;
  const RELOAD_GUARD_KEY = "version-reload-ts";
  const RELOAD_COOLDOWN_MS = 60 * 1000;

  export default function VersionChecker() {
    const checkingRef = useRef(false);
    useEffect(() => {
      if (!BUILD_ID) return;
      let cancelled = false;
      let timer: ReturnType<typeof setTimeout> | null = null;

      async function check() {
        if (checkingRef.current || cancelled) return;
        checkingRef.current = true;
        try {
          const res = await fetch(`/version.json?t=${Date.now()}`, {
            cache: "no-store", headers: { "Cache-Control": "no-cache" },
          });
          if (!res.ok || cancelled) return;
          const data = (await res.json()) as { buildId?: string };
          if (cancelled || !data.buildId || data.buildId === BUILD_ID) return;
          let last = 0;
          try { last = Number(sessionStorage.getItem(RELOAD_GUARD_KEY) || "0"); } catch {}
          if (Date.now() - last < RELOAD_COOLDOWN_MS) return;
          try { sessionStorage.setItem(RELOAD_GUARD_KEY, String(Date.now())); } catch {}
          window.location.reload();
        } catch {} finally { checkingRef.current = false; }
      }

      function schedule() {
        if (cancelled) return;
        timer = setTimeout(async () => { await check(); schedule(); }, POLL_INTERVAL_MS);
      }
      function onVisible() {
        if (document.visibilityState === "visible") check();
      }

      timer = setTimeout(() => { check().finally(schedule); }, INITIAL_DELAY_MS);
      document.addEventListener("visibilitychange", onVisible);
      window.addEventListener("focus", onVisible);
      return () => {
        cancelled = true;
        if (timer) clearTimeout(timer);
        document.removeEventListener("visibilitychange", onVisible);
        window.removeEventListener("focus", onVisible);
      };
    }, []);
    return null;
  }
  ```

  **В `src/app/layout.tsx`:** импортировать `VersionChecker`, добавить мета-теги в `<head>` и `<VersionChecker />` в `<body>`. В `CLAUDE.md` нового проекта зафиксировать, что cache-busting включён и куда смотреть.

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

3. **Загрузить подскиллы.** Главный скилл содержит секцию `## Подскиллы` в своей инструкции, которая перечисляет вложенные подскиллы для загрузки. Загрузить их:
   ```sql
   SELECT name, description, instruction FROM sub_skills
   WHERE skill_id = '<id_главного_скилла>' AND status = 'active'
   ORDER BY sort_order
   ```
   Каждый подскилл из этого списка нужно прочитать и применить как часть главного скилла.

4. **Следовать инструкциям** главного скилла и всех его подскиллов.

### Формат секции «Подскиллы» в инструкции главного скилла

Каждый главный скилл, у которого есть вложенные подскиллы, ДОЛЖЕН содержать секцию в своём поле `instruction`:

```markdown
## Подскиллы

При активации этого скилла автоматически загружай следующие подскиллы:

| Подскилл | Описание | Когда применять |
|----------|----------|-----------------|
| `название-1` | Что делает | В каком контексте читать |
| `название-2` | Что делает | В каком контексте читать |

Подскиллы загружаются из таблицы `sub_skills` по `skill_id` этого скилла.
Если подскилл имеет `status = 'active'` — он обязателен.
```

### Правила работы с подскиллами

- **Подскиллы наследуют контекст** главного скилла. Не нужно дублировать общие инструкции.
- **Порядок важен** — подскиллы выполняются в порядке `sort_order`. Если один подскилл зависит от результата другого, это отражено в порядке.
- **Подскилл может быть опциональным** — в секции `## Подскиллы` указывается колонка «Когда применять». Если контекст задачи не совпадает — подскилл можно пропустить.
- **Подскиллы НЕ имеют своих подскиллов** — вложенность только один уровень (скилл → подскиллы). Это сделано для простоты и предсказуемости.

### Таблица `sub_skills` — структура

| Поле | Тип | Описание |
|------|-----|----------|
| `id` | uuid | PK |
| `skill_id` | uuid | FK → `skills.id` |
| `name` | text | Уникальное имя подскилла (kebab-case) |
| `description` | text | Краткое описание (1-2 предложения) |
| `instruction` | text | Полная инструкция подскилла (Markdown) |
| `sort_order` | int | Порядок выполнения |
| `status` | text | `active` / `inactive` |

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
