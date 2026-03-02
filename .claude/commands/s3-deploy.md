# S3 Deploy — Загрузка сайтов на S3 Beget

Загрузка HTML/CSS/JS/изображений на S3 хранилище Beget и регистрация в Supabase.

Используй этот скилл когда нужно:
1. Загрузить сайт/лендинг/страницу на хостинг
2. Задеплоить HTML файл
3. Опубликовать сайт
4. Загрузить второстепенную страницу/таблицу к существующему сайту
5. Пользователь говорит "загрузи сайт", "задеплой", "опубликуй", "залей на хостинг", "deploy site", "upload to s3"

## Конфигурация S3

- **Endpoint**: https://s3.ru1.storage.beget.cloud
- **Bucket**: 76ae0220f799-proficient-naida
- **Access Key**: YZU8L57451SHREI5F4RG
- **Secret Key**: hbjJoHpyBseFKnJOz4xkm731ZUjhE6MSfxRvccfU

## Конфигурация Supabase

- **API URL**: https://sugbffcgdjwekktzavra.supabase.co
- **Service Role Key**: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InN1Z2JmZmNnZGp3ZWtrdHphdnJhIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc2OTcyMzQzMywiZXhwIjoyMDg1Mjk5NDMzfQ.KYs6CbqP3Kuv_3fCSfhFIz4qoJLUU2C2iglX6Puc2c0

## Структура файлов на S3

```
76ae0220f799-proficient-naida/
└── sites/
    ├── my-project-xkqmr/
    │   ├── index.html          ← основной файл сайта
    │   ├── pricing.html        ← второстепенная страница
    │   └── assets/             ← картинки, CSS, JS если нужно
    └── ...
```

## Базовый URL сайтов

```
https://s3.ru1.storage.beget.cloud/76ae0220f799-proficient-naida/sites/{project_name}-{5_random_letters}/index.html
```

Скрипт автоматически добавляет 5 случайных английских букв к имени проекта для уникальности.

## Процедура загрузки

### Шаг 1: Подготовка

1. Определить имя проекта (project_name) — спросить у пользователя или определить из контекста
2. Определить имя файла:
   - Основной сайт: `index.html`
   - Второстепенная страница: осмысленное имя (pricing.html, faq.html и т.д.)
3. Убедиться что файл создан и готов к загрузке

### Шаг 2: Загрузка на S3

Использовать скрипт `scripts/s3_upload.py`:

```bash
python3 scripts/s3_upload.py <local_file_path> <project_name> [filename]
```

Аргументы:
- `local_file_path` — путь к файлу на диске
- `project_name` — имя проекта (папка в sites/)
- `filename` — (опционально) имя файла на S3

Примеры:

```bash
# Основной сайт
python3 scripts/s3_upload.py /home/claude/site.html my-landing

# Второстепенная таблица
python3 scripts/s3_upload.py /home/claude/pricing.html my-landing pricing.html

# Целая директория
python3 scripts/s3_upload.py /home/claude/my-site/ my-landing --dir
```

### Шаг 3: Регистрация в Supabase

Скрипт автоматически вставляет запись в таблицу `sites` с полями:
- `project_name` — имя проекта
- `filename` — имя файла
- `link` — полный URL файла
- `description` — краткое описание

### Шаг 4: Выдать ссылку

После успешной загрузки — обязательно выдать пользователю итоговую ссылку.

## КРИТИЧЕСКИ ВАЖНО

1. Всегда используй `signature_version='s3'` (v2) — v4 не работает с Beget S3
2. Всегда ставь `ACL='public-read'` чтобы файлы были публично доступны
3. Правильные Content-Type:
   - `.html` → `text/html; charset=utf-8`
   - `.css` → `text/css`
   - `.js` → `application/javascript`
   - `.json` → `application/json`
   - `.png` → `image/png`
   - `.jpg/.jpeg` → `image/jpeg`
   - `.svg` → `image/svg+xml`
   - `.webp` → `image/webp`
   - `.woff2` → `font/woff2`
4. Имя проекта — латиница, строчные буквы, дефисы вместо пробелов
5. Всегда выдавай ссылку после загрузки
6. Второстепенные файлы — осмысленные имена, не index.html
