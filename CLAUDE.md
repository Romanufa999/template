# CLAUDE.md

## Проект

LangChain + Next.js starter template — шаблон для AI чат-ботов.

- **Стек**: Next.js 15, React 18, TypeScript, Tailwind CSS, LangChain.js, Supabase
- **Node**: >= 18
- **Пакетный менеджер**: yarn 3.5.1

## Команды

| Команда | Описание |
|---------|----------|
| `yarn dev` | Запуск dev-сервера |
| `yarn build` | Сборка проекта |
| `yarn start` | Запуск production-сервера |
| `yarn lint` | Линтинг (ESLint) |
| `yarn format` | Форматирование (Prettier) |

## Структура

- `app/` — страницы и API-роуты (Next.js App Router)
- `app/api/chat/` — API эндпоинты (chat, agents, retrieval, structured_output)
- `components/` — React-компоненты
- `utils/` — утилиты
- `data/` — данные
- `public/` — статика

## Важно

Этот репозиторий — **набор навыков (skills)** для Claude Code. Сайты, которые мы создаём с помощью этих навыков, **НЕ** являются частью кода этого проекта. Никогда не добавляй сгенерированные сайты, HTML-файлы, секции и прочие артефакты в этот репозиторий. Все сайты собираются во временных директориях (например `/tmp/`) и деплоятся на S3.

## Навыки (Skills)

Список доступных навыков. При необходимости используй их автоматически, без команды от пользователя.

| Навык | Описание | Файл |
|-------|----------|------|
| imagegen | Генерация изображений для сайта через webhook n8n | [.claude/commands/imagegen.md](.claude/commands/imagegen.md) |
| s3-deploy | Загрузка сайтов (HTML/CSS/JS) на S3 Beget + регистрация в Supabase | [.claude/commands/s3-deploy.md](.claude/commands/s3-deploy.md) |
| form-webhook | Перехват форм и отправка данных на вебхук (статика: GET с клиента, SSR: серверная функция) | [.claude/commands/form-webhook.md](.claude/commands/form-webhook.md) |
| yandex-metrica | Отслеживание событий форм/квизов в Яндекс Метрике (параметры визита, посетителя, цели) | [.claude/commands/yandex-metrica.md](.claude/commands/yandex-metrica.md) |
