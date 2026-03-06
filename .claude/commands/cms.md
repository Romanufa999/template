# CMS — Система управления контентом сайта

Создание CMS для статического сайта: схема в Supabase, админ-панель romanadmin.html, загрузка файлов.

Используй когда: пользователь просит "сделай CMS", "добавь управление контентом", "админка для сайта".

## Конфигурация Supabase

- **Project ID**: sugbffcgdjwekktzavra
- **API URL**: https://sugbffcgdjwekktzavra.supabase.co
- **Anon Key**: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InN1Z2JmZmNnZGp3ZWtrdHphdnJhIiwicm9sZSI6ImFub24iLCJpYXQiOjE3Njk3MjM0MzMsImV4cCI6MjA4NTI5OTQzM30.YywiLZPiQ8Cr1KIAr6Zi5_D1wQtU4e-ZPjjMB1DWMSQ
- **Service Role Key**: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InN1Z2JmZmNnZGp3ZWtrdHphdnJhIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc2OTcyMzQzMywiZXhwIjoyMDg1Mjk5NDMzfQ.KYs6CbqP3Kuv_3fCSfhFIz4qoJLUU2C2iglX6Puc2c0

## Модель данных — КЛЮЧЕВОЕ

Каждая секция сайта — это **всегда поля + опционально коллекция**. Не "или-или".

Пример секции "Услуги":
```
Поля секции (content):
  - title: "Наши услуги"
  - subtitle: "Более 10 лет опыта"
  - background_image: "https://..."

Коллекция карточек (collection_items):
  - {title: "Ремонт", description: "...", image: "...", price: "от 5000"}
  - {title: "Дизайн", description: "...", image: "...", price: "от 3000"}
```

Пример секции "Hero" (без коллекции):
```
Поля секции (content):
  - title: "Ремонт квартир"
  - subtitle: "Под ключ за 30 дней"
  - background_image: "https://..."
  - button_text: "Заказать"
```

### Таблицы (в схеме `cms_{SITE}`)

```sql
-- Секции. collection_schema = NULL → нет коллекции.
CREATE TABLE sections (
  id SERIAL PRIMARY KEY,
  section_key TEXT UNIQUE NOT NULL,
  title TEXT NOT NULL,
  fields_schema JSONB DEFAULT '{"fields":[]}',       -- схема полей секции
  collection_schema JSONB DEFAULT NULL,               -- схема полей карточки (NULL = нет коллекции)
  sort_order INTEGER DEFAULT 0,
  is_active BOOLEAN DEFAULT true,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

-- Поля секции (есть у КАЖДОЙ секции)
CREATE TABLE content (
  id SERIAL PRIMARY KEY,
  section_id INTEGER REFERENCES sections(id) ON DELETE CASCADE,
  field_key TEXT NOT NULL,
  field_type TEXT NOT NULL,  -- text, textarea, html, image, link, number, boolean
  field_value TEXT,
  sort_order INTEGER DEFAULT 0,
  UNIQUE(section_id, field_key)
);

-- Карточки коллекции (только у секций где collection_schema IS NOT NULL)
CREATE TABLE collection_items (
  id SERIAL PRIMARY KEY,
  section_id INTEGER REFERENCES sections(id) ON DELETE CASCADE,
  data JSONB DEFAULT '{}',
  sort_order INTEGER DEFAULT 0,
  is_active BOOLEAN DEFAULT true
);

-- Файлы
CREATE TABLE files (
  id SERIAL PRIMARY KEY,
  filename TEXT NOT NULL,
  storage_path TEXT NOT NULL,
  url TEXT NOT NULL,
  created_at TIMESTAMPTZ DEFAULT now()
);
```

### fields_schema и collection_schema — формат

```json
{"fields": [
  {"key": "title", "type": "text", "label": "Заголовок"},
  {"key": "description", "type": "textarea", "label": "Описание"},
  {"key": "image", "type": "image", "label": "Фото"},
  {"key": "price", "type": "text", "label": "Цена"}
]}
```

Типы полей: `text`, `textarea`, `html`, `image`, `link`, `number`, `boolean`.

## Авторизация

- Пароль хранится bcrypt-хешем в `public.cms_admins (schema_name, password_hash)`
- Админка шлёт заголовки `x-cms-site` и `x-cms-password` с каждым запросом
- Все RPC-функции вызывают `cms_check_auth()` (SECURITY DEFINER), которая проверяет пароль через заголовки
- Прямой доступ к схеме `cms_{SITE}` закрыт для anon/authenticated — только через RPC
- Публичные RPC (для фронтенда сайта) не требуют пароля — `cms_public_content(p_site)`, `cms_public_items(p_site, p_section_key)`
- Storage бакет `cms-{SITE}`: чтение публичное, загрузка/удаление по паролю через `verify_cms_storage_auth()`

## Админ-страница (romanadmin.html) — СТРУКТУРА

Технологии: Tailwind CSS CDN + Supabase JS CDN. Один HTML-файл, всё inline.

### Экран входа
Поле пароля → проверка через RPC `cms_auth_check()` → сохранение в cookie `cms_pwd`.

### Дашборд — ВДУМЧИВО ПО СЕКЦИЯМ

Слева сайдбар со списком секций. Справа — редактор выбранной секции.

**Редактор секции — единая страница, сверху вниз:**

1. **Заголовок секции** (название)
2. **Блок "Поля секции"** — форма с полями из `fields_schema`:
   - text → input
   - textarea/html → textarea
   - image → превью + input URL + кнопка загрузки файла
   - boolean → checkbox
   - Кнопка "Сохранить поля"
3. **Блок "Коллекция"** (только если `collection_schema IS NOT NULL`):
   - Таблица карточек с основными полями
   - Кнопка "+ Добавить"
   - Каждая карточка: кнопки "Ред." и "Удалить"
   - Добавление/редактирование — в модальном окне, поля из `collection_schema`

То есть админ видит секцию целиком: и её общие настройки (заголовок, фон), и её коллекцию карточек на одном экране. Не надо переключаться.

### Загрузка файлов
Загрузка в Supabase Storage бакет `cms-{SITE}`. URL вставляется в поле image.

## Процедура создания CMS

### 1. Спросить имя схемы
Только `[a-z0-9_]`. Предложить на основе имени проекта.

### 2. Анализ сайта
Прочитать HTML. Для каждой секции определить:
- `section_key` (hero, services, about, faq...)
- `fields_schema` — какие поля у самой секции (заголовок, подзаголовок, фон...)
- `collection_schema` — если есть повторяющиеся карточки, какие поля у карточки. Если нет — NULL.
- Текущие значения полей и элементов коллекций.

### 3. Инфраструктура (один раз на проект)
Проверить `public.cms_admins`. Если нет — создать через `apply_migration`:
- `CREATE EXTENSION IF NOT EXISTS pgcrypto`
- Таблица `public.cms_admins`
- Функции: `cms_check_auth()`, `cms_auth_check()`, `verify_cms_storage_auth()`
- RPC для админки: `cms_get_sections()`, `cms_get_content(section_id)`, `cms_update_content(updates)`, `cms_get_items(section_id)`, `cms_add_item(section_id, data)`, `cms_update_item(item_id, data)`, `cms_delete_item(item_id)`
- Публичные RPC: `cms_public_content(site)`, `cms_public_items(site, section_key)`

Все RPC используют `format('%I', 'cms_' || site)` для динамического обращения к нужной схеме. Все admin-RPC вызывают `cms_check_auth()` первой строкой.

### 4. Создание схемы сайта
Через `apply_migration`: создать схему `cms_{SITE}`, таблицы, закрыть доступ (`REVOKE ALL ... FROM anon, authenticated`).

### 5. Storage бакет
Через `execute_sql`: создать бакет `cms-{SITE}` (public read), политики на upload/delete с проверкой пароля.

### 6. Пароль
Сгенерировать: `python3 -c "import secrets; print(secrets.token_urlsafe(16))"`.
Вставить: `INSERT INTO public.cms_admins (schema_name, password_hash) VALUES ('{SITE}', crypt('{PWD}', gen_salt('bf')))`.

### 7. Начальные данные
INSERT секции, поля (content) и карточки (collection_items) на основе анализа сайта.

### 8. Создать romanadmin.html
По описанию выше. Плейсхолдеры: `{SUPABASE_URL}`, `{SUPABASE_ANON_KEY}`, `{SITE_NAME}`, `{SITE_DISPLAY_NAME}`.

### 9. Интеграция CMS в сайт
Добавить в HTML сайта перед `</body>` JS-загрузчик CMS:
- Вызывает `cms_public_content(site)` → подставляет значения в элементы с `data-cms="section.field"`
- Вызывает `cms_public_items(site, section_key)` → рендерит карточки в контейнерах с `data-cms-collection="section_key"` по шаблону `[data-cms-template]` с плейсхолдерами `{{field_key}}`
- Статический контент остаётся как fallback

### 10. Деплой и credentials
Задеплоить romanadmin.html + обновлённый сайт (скилл s3-deploy). Вывести в чат URL админки и пароль.

## Известные подводные камни

### 1. supabase-js НЕ передаёт кастомные заголовки в PostgreSQL

`sb.rpc()` из supabase-js v2 не прокидывает кастомные заголовки (вроде `x-cms-site`, `x-cms-password`) в PostgreSQL через `current_setting('request.header.x-cms-site')`. Поэтому авторизация через `cms_check_auth()` не работает с supabase-js.

**Решение:** В romanadmin.html НЕ использовать `supabase.createClient()`. Вместо этого — прямые fetch-запросы к PostgREST API:

```javascript
// Вместо sb.rpc('cms_auth_check', {p_site: site})
const res = await fetch(`${SUPABASE_URL}/rest/v1/rpc/cms_auth_check`, {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'apikey': ANON_KEY,
    'Authorization': 'Bearer ' + ANON_KEY,
    'x-cms-site': site,
    'x-cms-password': password
  },
  body: JSON.stringify({p_site: site})
});
```

Аналогично для Storage — использовать fetch к `/storage/v1/object/` вместо `sb.storage.from().upload()`.

### 2. pgcrypto установлен в схеме `extensions`, а не `public`

В Supabase расширение pgcrypto устанавливается в схему `extensions`. Если функция имеет `SET search_path TO 'public'` — вызовы `crypt()` и `gen_salt()` падают с ошибкой "function does not exist".

**Решение:** Все CMS-функции (SECURITY DEFINER) должны иметь:

```sql
SET search_path TO 'public', 'extensions'
```

Это касается: `cms_check_auth`, `cms_auth_check`, `cms_get_sections`, `cms_get_content`, `cms_update_content`, `cms_get_items`, `cms_add_item`, `cms_update_item`, `cms_delete_item`, `cms_public_content`, `cms_public_items`, `verify_cms_storage_auth`.

## Правила

1. Имя схемы — только `[a-z0-9_]`
2. Все CMS в одном Supabase-проекте, но в разных схемах `cms_{SITE}`
3. Пароль всегда выводить в чат при создании
4. Секция = поля + опционально коллекция. НЕ "или-или"
5. Прямой доступ к схеме закрыт. Только SECURITY DEFINER RPC
6. Файлы сайтов и CMS — во временных директориях, НЕ в этом репозитории
7. В romanadmin.html — только прямые fetch, НЕ supabase-js (см. подводный камень #1)
8. Все SECURITY DEFINER функции — `SET search_path TO 'public', 'extensions'` (см. подводный камень #2)
