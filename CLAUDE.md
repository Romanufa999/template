# Проект: langchain-nextjs-template

Шаблон чат-приложения на LangChain.js + Next.js для построения AI-интерфейсов.

## Стек технологий

- **Фреймворк:** Next.js 15 (App Router)
- **Язык:** TypeScript (strict mode)
- **UI:** React 18, Tailwind CSS, shadcn/ui (стиль New York, цветовая схема zinc)
- **AI/LLM:** LangChain.js, @langchain/openai, @langchain/langgraph, Vercel AI SDK
- **Векторная БД:** Supabase
- **Пакетный менеджер:** Yarn 3.5
- **Линтер:** ESLint (next/core-web-vitals)
- **Форматирование:** Prettier

## Команды

```bash
yarn dev        # Запуск dev-сервера
yarn build      # Сборка продакшена
yarn lint       # Линтинг
yarn format     # Форматирование кода
```

## Структура проекта

```
app/              — Next.js App Router (страницы, API-роуты, лейауты)
app/api/chat/     — API-эндпоинты чата (простой, агенты, RAG, structured output)
app/ai_sdk/       — Примеры с Vercel AI SDK (стриминг, инструменты)
app/langgraph/    — Определения LangGraph агентов
components/       — React-компоненты (чат, UI-библиотека shadcn)
data/             — Статические данные и тексты для RAG
utils/            — Утилиты (cn для tailwind-merge)
public/           — Статические ассеты (изображения)
```

## Правила кода

- Использовать алиас `@/*` для импортов от корня проекта
- Компоненты — функциональные, с хуками React
- API-роуты используют `export const runtime = "edge"` где возможно
- Стили — только Tailwind CSS классы, не использовать inline styles и CSS-модули
- Валидация данных — через Zod
- Тайпскрипт строгий: не использовать `any` без необходимости, предпочитать явные типы
- Обработка ошибок в API: возвращать `NextResponse.json({ error }, { status })` с корректным HTTP-кодом

## Связь с другими репозиториями

Это фронтенд-шаблон. Может использоваться совместно с бэкенд-сервисами из других подключённых репозиториев.

## Переменные окружения

Конфигурация через `.env.local` (по шаблону `.env.example`):
- `OPENAI_API_KEY` — обязательно для чат-примеров
- `SERPAPI_API_KEY` — для агентов с поиском
- `SUPABASE_PRIVATE_KEY`, `SUPABASE_URL` — для RAG
- Никогда не коммитить `.env*` файлы с секретами
