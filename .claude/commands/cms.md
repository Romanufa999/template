# CMS — Система управления контентом сайта

Создание CMS для статического сайта: отдельная схема в Supabase, админ-панель, загрузка файлов, интеграция с сайтом.

Используй этот скилл когда нужно:
1. Создать CMS / систему управления контентом для сайта
2. Добавить админ-панель для редактирования контента
3. Сделать управление секциями, каталогом, коллекциями
4. Пользователь говорит "сделай CMS", "добавь управление контентом", "админка для сайта", "CMS для лендинга"

## Конфигурация Supabase

- **Project ID**: sugbffcgdjwekktzavra
- **API URL**: https://sugbffcgdjwekktzavra.supabase.co
- **Anon Key**: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InN1Z2JmZmNnZGp3ZWtrdHphdnJhIiwicm9sZSI6ImFub24iLCJpYXQiOjE3Njk3MjM0MzMsImV4cCI6MjA4NTI5OTQzM30.YywiLZPiQ8Cr1KIAr6Zi5_D1wQtU4e-ZPjjMB1DWMSQ
- **Service Role Key**: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InN1Z2JmZmNnZGp3ZWtrdHphdnJhIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc2OTcyMzQzMywiZXhwIjoyMDg1Mjk5NDMzfQ.KYs6CbqP3Kuv_3fCSfhFIz4qoJLUU2C2iglX6Puc2c0

## Процедура

### Шаг 1: Спросить имя схемы

Спросить у пользователя как назвать CMS-схему (предложить имя на основе проекта сайта).
Имя должно: только строчные латинские буквы, цифры, подчёркивание. Пример: `mysite`, `landing_remont`, `shop_flowers`.

### Шаг 2: Анализ сайта

Прочитать HTML-файлы сайта и определить:

1. **Секции сайта** — по тегам `<section>`, `<div>` с ID/классами, семантической структуре
2. **Тип каждой секции**:
   - `single` — единичный блок (hero, about, contacts). Содержит фиксированный набор полей.
   - `collection` — коллекция элементов (услуги, портфолио, команда, отзывы, FAQ). Содержит повторяющиеся карточки.
3. **Поля каждой секции** — какие данные содержит:
   - `text` — короткий текст (заголовок, кнопка, имя)
   - `textarea` — длинный текст (описание, абзац)
   - `html` — HTML-контент (форматированный текст)
   - `image` — изображение (URL)
   - `link` — ссылка
   - `number` — число (цена, количество)
   - `boolean` — флаг (вкл/выкл)
4. **Текущие значения** — извлечь текст, URL картинок, ссылки для начального заполнения CMS

#### Пример анализа

```
Секция: hero (single)
  - title (text): "Ремонт квартир под ключ"
  - subtitle (textarea): "Делаем качественный ремонт..."
  - background_image (image): "https://...hero-bg.webp"
  - button_text (text): "Оставить заявку"
  - button_link (link): "#contact"

Секция: services (collection)
  - Каждый элемент: title (text), description (textarea), image (image), price (text)
  - Элементы: "Косметический ремонт", "Капитальный ремонт", ...

Секция: about (single)
  - title (text): "О компании"
  - text (html): "<p>Мы работаем с 2010 года...</p>"
  - image (image): "https://...about.webp"
```

### Шаг 3: Одноразовая инфраструктура

**Выполнить один раз** (при создании первой CMS). Проверить, существует ли таблица `public.cms_admins` — если нет, выполнить SQL из секции "SQL: Одноразовая инфраструктура".

Использовать `apply_migration` для DDL и `execute_sql` для проверок.

### Шаг 4: Создание схемы и таблиц для сайта

Выполнить SQL из секции "SQL: Схема сайта", заменив `{SITE}` на имя схемы.

### Шаг 5: Создание storage-бакета

Выполнить SQL из секции "SQL: Storage бакет", заменив `{SITE}` на имя схемы.

### Шаг 6: Генерация пароля и сохранение

1. Сгенерировать пароль: `python3 -c "import secrets; print(secrets.token_urlsafe(16))"`
2. Вставить в `cms_admins` (SQL из секции "SQL: Пароль администратора")
3. Запомнить пароль для вывода в чат

### Шаг 7: Заполнение начальных данных

На основе анализа из Шага 2, выполнить INSERT'ы:

1. Секции → `cms_{SITE}.sections`
2. Контент единичных секций → `cms_{SITE}.content`
3. Элементы коллекций → `cms_{SITE}.collection_items`

Использовать `execute_sql`.

### Шаг 8: Создание admin-страницы

Создать файл `romanadmin.html` на основе шаблона из секции "HTML: Шаблон admin-страницы".
Заменить плейсхолдеры: `{SUPABASE_URL}`, `{SUPABASE_ANON_KEY}`, `{SITE_NAME}`, `{SITE_DISPLAY_NAME}`.

### Шаг 9: Интеграция CMS в сайт

Добавить в HTML сайта перед `</body>` скрипт CMS-загрузчика из секции "JS: CMS-загрузчик".
Добавить `data-cms` атрибуты к элементам сайта.

### Шаг 10: Обновление CLAUDE.md сайта

Добавить секцию CMS в CLAUDE.md сайта (если есть) по шаблону из секции "Шаблон CLAUDE.md".

### Шаг 11: Деплой и вывод credentials

1. Задеплоить `romanadmin.html` на S3 (скилл s3-deploy)
2. Задеплоить обновлённый сайт на S3
3. Вывести в чат:
   - URL админ-панели
   - Пароль для входа

---

## SQL: Одноразовая инфраструктура

Выполнить **один раз** при создании первой CMS на проекте. Каждый блок — отдельный `apply_migration`.

### Миграция 1: Расширения и таблица администраторов

```sql
CREATE EXTENSION IF NOT EXISTS pgcrypto;

CREATE TABLE IF NOT EXISTS public.cms_admins (
  id SERIAL PRIMARY KEY,
  schema_name TEXT UNIQUE NOT NULL,
  password_hash TEXT NOT NULL,
  created_at TIMESTAMPTZ DEFAULT now()
);

ALTER TABLE public.cms_admins ENABLE ROW LEVEL SECURITY;
-- Без политик = нет прямого доступа. Только SECURITY DEFINER функции.
```

### Миграция 2: Функция проверки авторизации

```sql
CREATE OR REPLACE FUNCTION public.cms_check_auth()
RETURNS TEXT
LANGUAGE plpgsql
SECURITY DEFINER
AS $$
DECLARE
  v_site TEXT;
  v_pwd TEXT;
BEGIN
  v_site := coalesce(
    current_setting('request.headers', true)::json->>'x-cms-site', ''
  );
  v_pwd := coalesce(
    current_setting('request.headers', true)::json->>'x-cms-password', ''
  );

  IF v_site = '' OR v_pwd = '' THEN
    RAISE EXCEPTION 'CMS: отсутствуют учётные данные';
  END IF;

  IF v_site !~ '^[a-z0-9_]+$' THEN
    RAISE EXCEPTION 'CMS: недопустимое имя сайта';
  END IF;

  IF NOT EXISTS(
    SELECT 1 FROM public.cms_admins
    WHERE schema_name = v_site
    AND password_hash = crypt(v_pwd, password_hash)
  ) THEN
    RAISE EXCEPTION 'CMS: неверный пароль';
  END IF;

  RETURN v_site;
END;
$$;

CREATE OR REPLACE FUNCTION public.cms_auth_check()
RETURNS BOOLEAN
LANGUAGE plpgsql
SECURITY DEFINER
AS $$
DECLARE
  v_site TEXT;
BEGIN
  v_site := public.cms_check_auth();
  RETURN true;
END;
$$;

CREATE OR REPLACE FUNCTION public.verify_cms_storage_auth()
RETURNS BOOLEAN
LANGUAGE plpgsql
SECURITY DEFINER
AS $$
DECLARE
  v_site TEXT;
  v_pwd TEXT;
BEGIN
  v_site := coalesce(current_setting('request.headers', true)::json->>'x-cms-site', '');
  v_pwd := coalesce(current_setting('request.headers', true)::json->>'x-cms-password', '');

  IF v_site = '' OR v_pwd = '' THEN RETURN false; END IF;
  IF v_site !~ '^[a-z0-9_]+$' THEN RETURN false; END IF;

  RETURN EXISTS(
    SELECT 1 FROM public.cms_admins
    WHERE schema_name = v_site
    AND password_hash = crypt(v_pwd, password_hash)
  );
EXCEPTION WHEN OTHERS THEN
  RETURN false;
END;
$$;
```

### Миграция 3: RPC-функции для админки (CRUD)

```sql
-- Получить все секции
CREATE OR REPLACE FUNCTION public.cms_get_sections()
RETURNS TABLE(id INT, section_key TEXT, title TEXT, section_type TEXT, fields_schema TEXT, sort_order INT, is_active BOOLEAN)
LANGUAGE plpgsql SECURITY DEFINER
AS $$
DECLARE v_site TEXT;
BEGIN
  v_site := public.cms_check_auth();
  RETURN QUERY EXECUTE format(
    'SELECT id::int, section_key::text, title::text, section_type::text, fields_schema::text, sort_order::int, is_active::boolean FROM %I.sections ORDER BY sort_order',
    'cms_' || v_site
  );
END;
$$;

-- Получить контент секции (single)
CREATE OR REPLACE FUNCTION public.cms_get_content(p_section_id INT)
RETURNS TABLE(id INT, field_key TEXT, field_type TEXT, field_value TEXT, sort_order INT)
LANGUAGE plpgsql SECURITY DEFINER
AS $$
DECLARE v_site TEXT;
BEGIN
  v_site := public.cms_check_auth();
  RETURN QUERY EXECUTE format(
    'SELECT id::int, field_key::text, field_type::text, field_value::text, sort_order::int FROM %I.content WHERE section_id = $1 ORDER BY sort_order',
    'cms_' || v_site
  ) USING p_section_id;
END;
$$;

-- Обновить контент (batch)
CREATE OR REPLACE FUNCTION public.cms_update_content(p_updates JSONB)
RETURNS VOID
LANGUAGE plpgsql SECURITY DEFINER
AS $$
DECLARE
  v_site TEXT;
  v_update JSONB;
BEGIN
  v_site := public.cms_check_auth();
  FOR v_update IN SELECT * FROM jsonb_array_elements(p_updates)
  LOOP
    EXECUTE format(
      'UPDATE %I.content SET field_value = $1, updated_at = now() WHERE id = $2',
      'cms_' || v_site
    ) USING (v_update->>'new_value'), (v_update->>'content_id')::int;
  END LOOP;
END;
$$;

-- Получить элементы коллекции
CREATE OR REPLACE FUNCTION public.cms_get_items(p_section_id INT)
RETURNS TABLE(id INT, title TEXT, data JSONB, sort_order INT, is_active BOOLEAN)
LANGUAGE plpgsql SECURITY DEFINER
AS $$
DECLARE v_site TEXT;
BEGIN
  v_site := public.cms_check_auth();
  RETURN QUERY EXECUTE format(
    'SELECT id::int, title::text, data::jsonb, sort_order::int, is_active::boolean FROM %I.collection_items WHERE section_id = $1 ORDER BY sort_order',
    'cms_' || v_site
  ) USING p_section_id;
END;
$$;

-- Добавить элемент коллекции
CREATE OR REPLACE FUNCTION public.cms_add_item(p_section_id INT, p_title TEXT, p_data JSONB)
RETURNS INT
LANGUAGE plpgsql SECURITY DEFINER
AS $$
DECLARE
  v_site TEXT;
  v_id INT;
  v_max INT;
BEGIN
  v_site := public.cms_check_auth();
  EXECUTE format('SELECT COALESCE(MAX(sort_order),0) FROM %I.collection_items WHERE section_id=$1', 'cms_'||v_site) INTO v_max USING p_section_id;
  EXECUTE format('INSERT INTO %I.collection_items(section_id,title,data,sort_order) VALUES($1,$2,$3,$4) RETURNING id', 'cms_'||v_site) INTO v_id USING p_section_id, p_title, p_data, v_max+1;
  RETURN v_id;
END;
$$;

-- Обновить элемент коллекции
CREATE OR REPLACE FUNCTION public.cms_update_item(p_item_id INT, p_title TEXT, p_data JSONB)
RETURNS VOID
LANGUAGE plpgsql SECURITY DEFINER
AS $$
DECLARE v_site TEXT;
BEGIN
  v_site := public.cms_check_auth();
  EXECUTE format('UPDATE %I.collection_items SET title=$1, data=$2, updated_at=now() WHERE id=$3', 'cms_'||v_site) USING p_title, p_data, p_item_id;
END;
$$;

-- Удалить элемент коллекции
CREATE OR REPLACE FUNCTION public.cms_delete_item(p_item_id INT)
RETURNS VOID
LANGUAGE plpgsql SECURITY DEFINER
AS $$
DECLARE v_site TEXT;
BEGIN
  v_site := public.cms_check_auth();
  EXECUTE format('DELETE FROM %I.collection_items WHERE id=$1', 'cms_'||v_site) USING p_item_id;
END;
$$;
```

### Миграция 4: Публичные RPC-функции (для фронтенда сайта, без пароля)

```sql
-- Публичный контент сайта
CREATE OR REPLACE FUNCTION public.cms_public_content(p_site TEXT)
RETURNS TABLE(section_key TEXT, field_key TEXT, field_type TEXT, field_value TEXT)
LANGUAGE plpgsql SECURITY DEFINER
AS $$
BEGIN
  IF p_site !~ '^[a-z0-9_]+$' THEN RAISE EXCEPTION 'Invalid site'; END IF;
  RETURN QUERY EXECUTE format(
    'SELECT s.section_key, c.field_key, c.field_type, c.field_value
     FROM %I.sections s JOIN %I.content c ON c.section_id = s.id
     WHERE s.is_active ORDER BY s.sort_order, c.sort_order',
    'cms_' || p_site, 'cms_' || p_site
  );
END;
$$;

-- Публичные элементы коллекции
CREATE OR REPLACE FUNCTION public.cms_public_items(p_site TEXT, p_section_key TEXT)
RETURNS TABLE(id INT, title TEXT, data JSONB, sort_order INT)
LANGUAGE plpgsql SECURITY DEFINER
AS $$
BEGIN
  IF p_site !~ '^[a-z0-9_]+$' THEN RAISE EXCEPTION 'Invalid site'; END IF;
  RETURN QUERY EXECUTE format(
    'SELECT ci.id::int, ci.title::text, ci.data::jsonb, ci.sort_order::int
     FROM %I.collection_items ci JOIN %I.sections s ON s.id = ci.section_id
     WHERE s.section_key = $1 AND s.is_active AND ci.is_active
     ORDER BY ci.sort_order',
    'cms_' || p_site, 'cms_' || p_site
  ) USING p_section_key;
END;
$$;
```

---

## SQL: Схема сайта

Заменить `{SITE}` на имя схемы. Выполнить через `apply_migration`.

```sql
CREATE SCHEMA IF NOT EXISTS cms_{SITE};

CREATE TABLE cms_{SITE}.sections (
  id SERIAL PRIMARY KEY,
  section_key TEXT UNIQUE NOT NULL,
  title TEXT NOT NULL,
  section_type TEXT NOT NULL CHECK (section_type IN ('single', 'collection')),
  fields_schema JSONB DEFAULT '{"fields":[]}',
  sort_order INTEGER DEFAULT 0,
  is_active BOOLEAN DEFAULT true,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE cms_{SITE}.content (
  id SERIAL PRIMARY KEY,
  section_id INTEGER REFERENCES cms_{SITE}.sections(id) ON DELETE CASCADE,
  field_key TEXT NOT NULL,
  field_type TEXT NOT NULL CHECK (field_type IN ('text','textarea','html','image','link','number','boolean')),
  field_value TEXT,
  sort_order INTEGER DEFAULT 0,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now(),
  UNIQUE(section_id, field_key)
);

CREATE TABLE cms_{SITE}.collection_items (
  id SERIAL PRIMARY KEY,
  section_id INTEGER REFERENCES cms_{SITE}.sections(id) ON DELETE CASCADE,
  title TEXT,
  data JSONB DEFAULT '{}',
  sort_order INTEGER DEFAULT 0,
  is_active BOOLEAN DEFAULT true,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE cms_{SITE}.files (
  id SERIAL PRIMARY KEY,
  filename TEXT NOT NULL,
  original_name TEXT,
  mime_type TEXT,
  size_bytes INTEGER,
  storage_path TEXT NOT NULL,
  url TEXT NOT NULL,
  created_at TIMESTAMPTZ DEFAULT now()
);

-- Закрыть прямой доступ к схеме
REVOKE ALL ON SCHEMA cms_{SITE} FROM anon, authenticated;
REVOKE ALL ON ALL TABLES IN SCHEMA cms_{SITE} FROM anon, authenticated;
```

---

## SQL: Storage бакет

Выполнить через `execute_sql`.

```sql
INSERT INTO storage.buckets (id, name, public, file_size_limit, allowed_mime_types)
VALUES (
  'cms-{SITE}', 'cms-{SITE}', true, 5242880,
  ARRAY['image/jpeg','image/png','image/webp','image/svg+xml','image/gif']
)
ON CONFLICT (id) DO NOTHING;

-- Политика чтения (публичная)
CREATE POLICY "cms_read_{SITE}" ON storage.objects
FOR SELECT USING (bucket_id = 'cms-{SITE}');

-- Политика загрузки (по паролю)
CREATE POLICY "cms_upload_{SITE}" ON storage.objects
FOR INSERT WITH CHECK (
  bucket_id = 'cms-{SITE}' AND public.verify_cms_storage_auth()
);

-- Политика удаления (по паролю)
CREATE POLICY "cms_delete_{SITE}" ON storage.objects
FOR DELETE USING (
  bucket_id = 'cms-{SITE}' AND public.verify_cms_storage_auth()
);
```

---

## SQL: Пароль администратора

Заменить `{SITE}` и `{PASSWORD}`. Выполнить через `execute_sql`.

```sql
INSERT INTO public.cms_admins (schema_name, password_hash)
VALUES ('{SITE}', crypt('{PASSWORD}', gen_salt('bf')));
```

---

## SQL: Начальные данные (шаблон)

Адаптировать под конкретный сайт. Выполнить через `execute_sql`.

```sql
-- Секция single
INSERT INTO cms_{SITE}.sections (section_key, title, section_type, fields_schema, sort_order)
VALUES ('hero', 'Главный блок', 'single', '{"fields":[
  {"key":"title","type":"text","label":"Заголовок"},
  {"key":"subtitle","type":"textarea","label":"Подзаголовок"},
  {"key":"background_image","type":"image","label":"Фоновое изображение"},
  {"key":"button_text","type":"text","label":"Текст кнопки"}
]}', 1);

-- Контент для single секции
INSERT INTO cms_{SITE}.content (section_id, field_key, field_type, field_value, sort_order) VALUES
((SELECT id FROM cms_{SITE}.sections WHERE section_key='hero'), 'title', 'text', 'Текущий заголовок', 1),
((SELECT id FROM cms_{SITE}.sections WHERE section_key='hero'), 'subtitle', 'textarea', 'Текущий подзаголовок', 2),
((SELECT id FROM cms_{SITE}.sections WHERE section_key='hero'), 'background_image', 'image', 'https://...', 3),
((SELECT id FROM cms_{SITE}.sections WHERE section_key='hero'), 'button_text', 'text', 'Оставить заявку', 4);

-- Секция collection
INSERT INTO cms_{SITE}.sections (section_key, title, section_type, fields_schema, sort_order)
VALUES ('services', 'Услуги', 'collection', '{"fields":[
  {"key":"title","type":"text","label":"Название"},
  {"key":"description","type":"textarea","label":"Описание"},
  {"key":"image","type":"image","label":"Фото"},
  {"key":"price","type":"text","label":"Цена"}
]}', 2);

-- Элементы коллекции
INSERT INTO cms_{SITE}.collection_items (section_id, title, data, sort_order) VALUES
((SELECT id FROM cms_{SITE}.sections WHERE section_key='services'), 'Услуга 1', '{"title":"Услуга 1","description":"Описание...","image":"https://...","price":"от 5000 руб."}', 1),
((SELECT id FROM cms_{SITE}.sections WHERE section_key='services'), 'Услуга 2', '{"title":"Услуга 2","description":"Описание...","image":"https://...","price":"от 10000 руб."}', 2);
```

---

## HTML: Шаблон admin-страницы (romanadmin.html)

Заменить плейсхолдеры: `{SUPABASE_URL}`, `{SUPABASE_ANON_KEY}`, `{SITE_NAME}`, `{SITE_DISPLAY_NAME}`.

```html
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CMS — {SITE_DISPLAY_NAME}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
  <style>
    .field-group { margin-bottom: 1rem; }
    .field-label { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem; }
    .field-input { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; }
    .field-input:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,0.2); }
    .btn { padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; cursor: pointer; transition: all 0.15s; }
    .btn-primary { background: #2563eb; color: white; }
    .btn-primary:hover { background: #1d4ed8; }
    .btn-danger { background: #ef4444; color: white; }
    .btn-danger:hover { background: #dc2626; }
    .btn-ghost { background: transparent; color: #6b7280; }
    .btn-ghost:hover { background: #f3f4f6; }
    .toast { position: fixed; bottom: 1rem; right: 1rem; padding: 0.75rem 1.25rem; border-radius: 0.5rem; color: white; font-size: 0.875rem; z-index: 100; transition: opacity 0.3s; }
    .section-btn { width: 100%; text-align: left; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-size: 0.875rem; cursor: pointer; transition: background 0.15s; }
    .section-btn:hover { background: #eff6ff; }
    .section-btn.active { background: #dbeafe; color: #1d4ed8; font-weight: 500; }
    .img-preview { width: 8rem; height: 8rem; object-fit: cover; border-radius: 0.375rem; margin-bottom: 0.5rem; }
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 50; }
    .modal-box { background: white; border-radius: 0.75rem; box-shadow: 0 20px 60px rgba(0,0,0,0.3); width: 100%; max-width: 32rem; margin: 1rem; max-height: 90vh; overflow-y: auto; }
  </style>
</head>
<body class="bg-gray-100 min-h-screen">

  <!-- Login -->
  <div id="login-screen" class="flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-xl shadow-lg p-8 w-96">
      <h1 class="text-2xl font-bold mb-2 text-center text-gray-800">CMS Admin</h1>
      <p class="text-gray-500 text-sm text-center mb-6">{SITE_DISPLAY_NAME}</p>
      <div class="field-group">
        <label class="field-label">Пароль</label>
        <input id="login-pwd" type="password" class="field-input" placeholder="Введите пароль CMS">
      </div>
      <div id="login-err" class="text-red-500 text-sm mb-3 hidden"></div>
      <button id="login-btn" class="btn btn-primary w-full">Войти</button>
    </div>
  </div>

  <!-- Dashboard -->
  <div id="dashboard" class="hidden">
    <header class="bg-white shadow-sm border-b px-6 py-3 flex items-center justify-between">
      <h1 class="text-lg font-semibold text-gray-800">CMS — {SITE_DISPLAY_NAME}</h1>
      <button id="logout-btn" class="btn btn-ghost text-sm">Выйти</button>
    </header>
    <div class="flex">
      <aside class="w-60 bg-white shadow-sm min-h-[calc(100vh-52px)] border-r p-4">
        <div class="text-xs text-gray-400 uppercase tracking-wider mb-3">Секции</div>
        <nav id="sections-nav" class="space-y-1"></nav>
      </aside>
      <main id="content-area" class="flex-1 p-6">
        <div class="text-gray-400 text-center mt-20">Выберите секцию для редактирования</div>
      </main>
    </div>
  </div>

  <!-- Modal -->
  <div id="modal" class="hidden">
    <div class="modal-overlay" onclick="closeModal(event)">
      <div class="modal-box" onclick="event.stopPropagation()">
        <div class="p-6">
          <h2 id="modal-title" class="text-lg font-semibold mb-4"></h2>
          <div id="modal-fields"></div>
          <div class="flex justify-end gap-3 mt-6">
            <button onclick="closeModal()" class="btn btn-ghost">Отмена</button>
            <button id="modal-save" class="btn btn-primary">Сохранить</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Toast -->
  <div id="toast" class="toast hidden" style="opacity:0"></div>

<script>
const SUPABASE_URL = '{SUPABASE_URL}';
const ANON_KEY = '{SUPABASE_ANON_KEY}';
const SITE_NAME = '{SITE_NAME}';
let sb = null, sections = [], curSection = null, curItems = [];

// Cookies
function setC(n,v){document.cookie=n+'='+encodeURIComponent(v)+';max-age='+60*60*24*30+';path=/';}
function getC(n){const m=document.cookie.match(new RegExp('(?:^|; )'+n+'=([^;]*)'));return m?decodeURIComponent(m[1]):null;}
function delC(n){document.cookie=n+'=;max-age=0;path=/';}

// Toast
function toast(msg,err){
  const t=document.getElementById('toast');
  t.textContent=msg;
  t.style.background=err?'#ef4444':'#16a34a';
  t.classList.remove('hidden');
  t.style.opacity='1';
  setTimeout(()=>{t.style.opacity='0';setTimeout(()=>t.classList.add('hidden'),300);},2500);
}

// Init
function initSB(pwd){
  sb = window.supabase.createClient(SUPABASE_URL, ANON_KEY, {
    global:{headers:{'x-cms-site':SITE_NAME,'x-cms-password':pwd}}
  });
}

// Login
async function login(pwd){
  initSB(pwd);
  const{error}=await sb.rpc('cms_auth_check');
  if(error){
    document.getElementById('login-err').textContent='Неверный пароль';
    document.getElementById('login-err').classList.remove('hidden');
    return false;
  }
  setC('cms_pwd',pwd);
  return true;
}

function showDashboard(){
  document.getElementById('login-screen').classList.add('hidden');
  document.getElementById('dashboard').classList.remove('hidden');
  loadSections();
}

// Sections
async function loadSections(){
  const{data,error}=await sb.rpc('cms_get_sections');
  if(error){toast('Ошибка загрузки секций',1);return;}
  sections=data||[];
  renderNav();
}

function renderNav(){
  const nav=document.getElementById('sections-nav');
  nav.innerHTML=sections.map(s=>`
    <button class="section-btn ${curSection&&curSection.id===s.id?'active':''}" onclick="selectSection(${s.id})">
      ${s.title}
      <span class="text-xs text-gray-400 ml-1">${s.section_type==='collection'?'[...]':'—'}</span>
    </button>
  `).join('');
}

async function selectSection(id){
  curSection=sections.find(s=>s.id===id);
  renderNav();
  if(curSection.section_type==='single') await loadSingle();
  else await loadCollection();
}

// ===== SINGLE SECTION =====
async function loadSingle(){
  const{data,error}=await sb.rpc('cms_get_content',{p_section_id:curSection.id});
  if(error){toast('Ошибка',1);return;}
  const schema=curSection.fields_schema?JSON.parse(curSection.fields_schema):{fields:[]};
  const area=document.getElementById('content-area');
  let h=`<div class="max-w-2xl"><h2 class="text-xl font-semibold mb-6">${curSection.title}</h2><form id="sf" class="space-y-4">`;
  (data||[]).forEach(item=>{
    const lbl=schema.fields?.find(f=>f.key===item.field_key)?.label||item.field_key;
    h+=renderField(item.field_key,item.field_type,item.field_value,item.id,lbl);
  });
  h+=`<button type="submit" class="btn btn-primary mt-4">Сохранить</button></form></div>`;
  area.innerHTML=h;
  document.getElementById('sf').onsubmit=async e=>{
    e.preventDefault();
    const updates=[];
    (data||[]).forEach(item=>{
      const el=document.querySelector(`[data-cid="${item.id}"]`);
      if(!el)return;
      const nv=el.type==='checkbox'?String(el.checked):el.value;
      if(nv!==String(item.field_value||''))updates.push({content_id:item.id,new_value:nv});
    });
    if(!updates.length){toast('Нет изменений');return;}
    const{error}=await sb.rpc('cms_update_content',{p_updates:updates});
    if(error){toast('Ошибка: '+error.message,1);return;}
    toast('Сохранено!');
    await loadSingle();
  };
}

function renderField(key,type,value,id,label){
  const safe=v=>(v||'').replace(/"/g,'&quot;').replace(/</g,'&lt;');
  switch(type){
    case 'textarea':case 'html':
      return `<div class="field-group"><label class="field-label">${label}</label><textarea data-cid="${id}" rows="${type==='html'?6:3}" class="field-input">${safe(value)}</textarea></div>`;
    case 'image':
      return `<div class="field-group"><label class="field-label">${label}</label>${value?`<img src="${safe(value)}" class="img-preview">`:''}
        <input type="url" data-cid="${id}" value="${safe(value)}" class="field-input mb-1" placeholder="URL изображения">
        <input type="file" accept="image/*" class="text-sm text-gray-500" onchange="uploadImg(this,'${id}')"></div>`;
    case 'boolean':
      return `<div class="field-group flex items-center gap-2"><input type="checkbox" data-cid="${id}" ${value==='true'?'checked':''}><label class="field-label" style="margin:0">${label}</label></div>`;
    case 'number':
      return `<div class="field-group"><label class="field-label">${label}</label><input type="number" data-cid="${id}" value="${safe(value)}" class="field-input"></div>`;
    default:
      return `<div class="field-group"><label class="field-label">${label}</label><input type="text" data-cid="${id}" value="${safe(value)}" class="field-input"></div>`;
  }
}

// ===== COLLECTION SECTION =====
async function loadCollection(){
  const{data,error}=await sb.rpc('cms_get_items',{p_section_id:curSection.id});
  if(error){toast('Ошибка',1);return;}
  curItems=data||[];
  const schema=curSection.fields_schema?JSON.parse(curSection.fields_schema):{fields:[]};
  const fields=schema.fields||[];
  const area=document.getElementById('content-area');
  let h=`<div><div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-semibold">${curSection.title}</h2>
    <button onclick="openModal()" class="btn btn-primary">+ Добавить</button>
  </div><div class="bg-white rounded-lg shadow overflow-hidden"><table class="w-full text-sm">
    <thead class="bg-gray-50"><tr><th class="px-4 py-2 text-left w-10">#</th>`;
  fields.slice(0,4).forEach(f=>{h+=`<th class="px-4 py-2 text-left">${f.label}</th>`;});
  h+=`<th class="px-4 py-2 text-right w-28">Действия</th></tr></thead><tbody>`;
  curItems.forEach((item,i)=>{
    h+=`<tr class="border-t hover:bg-gray-50"><td class="px-4 py-2 text-gray-400">${i+1}</td>`;
    fields.slice(0,4).forEach(f=>{
      const v=item.data?.[f.key]||'';
      if(f.type==='image')h+=`<td class="px-4 py-2">${v?`<img src="${v}" class="w-10 h-10 object-cover rounded">`:'-'}</td>`;
      else h+=`<td class="px-4 py-2">${v.length>50?v.slice(0,50)+'...':v}</td>`;
    });
    h+=`<td class="px-4 py-2 text-right">
      <button onclick="openModal(${item.id})" class="text-blue-600 hover:underline text-xs mr-2">Ред.</button>
      <button onclick="delItem(${item.id})" class="text-red-500 hover:underline text-xs">Удалить</button>
    </td></tr>`;
  });
  h+=`</tbody></table></div></div>`;
  area.innerHTML=h;
}

// ===== MODAL =====
function openModal(itemId){
  const item=itemId?curItems.find(i=>i.id===itemId):null;
  const schema=curSection.fields_schema?JSON.parse(curSection.fields_schema):{fields:[]};
  const isEdit=!!item;
  document.getElementById('modal-title').textContent=isEdit?'Редактировать':'Добавить';
  const box=document.getElementById('modal-fields');
  box.innerHTML=(schema.fields||[]).map(f=>{
    const v=isEdit?(item.data?.[f.key]||''):'';
    return renderField(f.key,f.type,v,`m_${f.key}`,f.label);
  }).join('');
  document.getElementById('modal').classList.remove('hidden');
  document.getElementById('modal-save').onclick=async()=>{
    const d={};
    (schema.fields||[]).forEach(f=>{
      const el=document.querySelector(`[data-cid="m_${f.key}"]`);
      if(el)d[f.key]=el.type==='checkbox'?String(el.checked):el.value;
    });
    if(isEdit){
      const{error}=await sb.rpc('cms_update_item',{p_item_id:item.id,p_title:d.title||'',p_data:d});
      if(error){toast('Ошибка: '+error.message,1);return;}
      toast('Обновлено!');
    }else{
      const{error}=await sb.rpc('cms_add_item',{p_section_id:curSection.id,p_title:d.title||'',p_data:d});
      if(error){toast('Ошибка: '+error.message,1);return;}
      toast('Добавлено!');
    }
    closeModal();
    await loadCollection();
  };
}

function closeModal(e){
  if(e&&e.target!==e.currentTarget)return;
  document.getElementById('modal').classList.add('hidden');
}

async function delItem(id){
  if(!confirm('Удалить этот элемент?'))return;
  const{error}=await sb.rpc('cms_delete_item',{p_item_id:id});
  if(error){toast('Ошибка',1);return;}
  toast('Удалено');
  await loadCollection();
}

// ===== FILE UPLOAD =====
async function uploadImg(input,cid){
  const file=input.files[0];
  if(!file)return;
  const ext=file.name.split('.').pop();
  const fname=Date.now()+'_'+Math.random().toString(36).slice(2,8)+'.'+ext;
  toast('Загрузка...');
  const{data,error}=await sb.storage.from('cms-'+SITE_NAME).upload(fname,file,{cacheControl:'3600',upsert:false});
  if(error){toast('Ошибка загрузки: '+error.message,1);return;}
  const{data:u}=sb.storage.from('cms-'+SITE_NAME).getPublicUrl(fname);
  const urlInput=document.querySelector(`[data-cid="${cid}"][type="url"]`);
  if(urlInput){urlInput.value=u.publicUrl;const img=urlInput.parentElement.querySelector('img');if(img)img.src=u.publicUrl;}
  toast('Загружено!');
}

// ===== INIT =====
document.getElementById('login-btn').onclick=async()=>{
  const p=document.getElementById('login-pwd').value;
  if(!p)return;
  if(await login(p))showDashboard();
};
document.getElementById('login-pwd').onkeydown=e=>{if(e.key==='Enter')document.getElementById('login-btn').click();};
document.getElementById('logout-btn').onclick=()=>{delC('cms_pwd');location.reload();};

(async()=>{
  const saved=getC('cms_pwd');
  if(saved&&await login(saved))showDashboard();
})();
</script>
</body>
</html>
```

---

## JS: CMS-загрузчик для сайта

Добавить перед `</body>` в HTML сайта. Заменить `{SUPABASE_URL}`, `{ANON_KEY}`, `{SITE_NAME}`.

```html
<script>
(function(){
  var SB_URL='{SUPABASE_URL}';
  var SB_KEY='{ANON_KEY}';
  var SITE='{SITE_NAME}';

  function rpc(fn,params){
    return fetch(SB_URL+'/rest/v1/rpc/'+fn,{
      method:'POST',
      headers:{'Content-Type':'application/json','apikey':SB_KEY,'Authorization':'Bearer '+SB_KEY},
      body:JSON.stringify(params)
    }).then(function(r){return r.json();});
  }

  function applyCMS(){
    // Одиночные поля
    rpc('cms_public_content',{p_site:SITE}).then(function(data){
      if(!Array.isArray(data))return;
      data.forEach(function(item){
        var key=item.section_key+'.'+item.field_key;
        document.querySelectorAll('[data-cms="'+key+'"]').forEach(function(el){
          if(item.field_type==='image'){el.src=item.field_value;}
          else if(item.field_type==='html'){el.innerHTML=item.field_value;}
          else{el.textContent=item.field_value;}
        });
      });
    }).catch(function(){});

    // Коллекции
    document.querySelectorAll('[data-cms-collection]').forEach(function(container){
      var sectionKey=container.getAttribute('data-cms-collection');
      var tpl=container.querySelector('[data-cms-template]');
      if(!tpl)return;
      var tplHTML=tpl.outerHTML.replace('data-cms-template','');
      rpc('cms_public_items',{p_site:SITE,p_section_key:sectionKey}).then(function(items){
        if(!Array.isArray(items)||!items.length)return;
        container.innerHTML='';
        items.forEach(function(item){
          var html=tplHTML;
          var d=item.data||{};
          for(var k in d){
            html=html.replace(new RegExp('\\{\\{'+k+'\\}\\}','g'),d[k]||'');
          }
          html=html.replace(/\{\{title\}\}/g,item.title||'');
          container.insertAdjacentHTML('beforeend',html);
        });
      }).catch(function(){});
    });
  }

  if(document.readyState==='loading'){document.addEventListener('DOMContentLoaded',applyCMS);}
  else{applyCMS();}
})();
</script>
```

### Разметка data-cms в HTML сайта

Для единичных полей:
```html
<h1 data-cms="hero.title">Статический заголовок</h1>
<p data-cms="hero.subtitle">Статический текст</p>
<img data-cms="hero.background_image" src="static.jpg">
```

Для коллекций:
```html
<div data-cms-collection="services">
  <div data-cms-template class="card">
    <img src="{{image}}" alt="{{title}}">
    <h3>{{title}}</h3>
    <p>{{description}}</p>
    <span>{{price}}</span>
  </div>
</div>
```

---

## Шаблон CLAUDE.md секции

Добавить в CLAUDE.md проекта сайта:

```markdown
## CMS

Сайт подключён к CMS (система управления контентом).

- **Supabase URL**: https://sugbffcgdjwekktzavra.supabase.co
- **Schema**: cms_{SITE}
- **Storage Bucket**: cms-{SITE}
- **Admin Page**: romanadmin.html

### Секции CMS

| Секция | Тип | Поля |
|--------|-----|------|
| {section_key} | {single/collection} | {field1, field2, ...} |

### CMS-атрибуты в HTML

- `data-cms="section_key.field_key"` — поле контента
- `data-cms-collection="section_key"` — контейнер коллекции
- `data-cms-template` — шаблон элемента коллекции

### Обновление структуры CMS

При изменении секций сайта — обновлять таблицы в cms_{SITE}:
- Новая секция → INSERT в sections + content
- Новое поле → INSERT в content / обновить fields_schema
- Удалённая секция → DELETE из sections
```

---

## Правила

1. **Имя схемы** — только `[a-z0-9_]`, без дефисов и спецсимволов
2. **Один проект Supabase** — все CMS живут в одном проекте `sugbffcgdjwekktzavra`, но в разных схемах
3. **Пароль** — генерировать `secrets.token_urlsafe(16)`, всегда выводить в чат при создании
4. **fields_schema** — JSONB в секции, определяет доступные поля. Используется админкой для рендера форм.
5. **Безопасность** — все данные CMS доступны только через SECURITY DEFINER функции. Прямой доступ к схеме закрыт для anon/authenticated.
6. **Файлы** — загружаются в Storage бакет `cms-{SITE}`, доступны публично на чтение, загрузка только по паролю.
7. **Одноразовые миграции** — перед первым CMS проверять наличие `public.cms_admins`. Если нет — выполнить всю одноразовую инфраструктуру.
8. **Деплой** — romanadmin.html деплоится на S3 вместе с сайтом (скилл s3-deploy).
9. **CMS-загрузчик** — встраивается в сайт для динамической подгрузки контента. Статический контент остаётся как fallback.
10. **Не добавлять в этот репозиторий** — все файлы сайтов и CMS создаются во временных директориях и деплоятся на S3.
