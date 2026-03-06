# CMS — Система управления контентом сайта

Создание CMS для статического сайта: схема в Supabase, админ-панель romanadmin.html, загрузка файлов.

Используй когда: пользователь просит "сделай CMS", "добавь управление контентом", "админка для сайта".

## ВАЖНО: CMS и сайт — независимые процессы

CMS **не связана** с деплоем сайта. Это отдельная сущность, которая создаётся и деплоится независимо:

1. **Сайт** — генерируется, собирается и деплоится на S3 через навык `s3-deploy`. Это обычная статика (HTML/CSS/JS).
2. **CMS** — схема в Supabase + админка (`romanadmin.html`) + inline-скрипт. Создаётся **отдельно**, деплоится **отдельно**.

**Порядок работы:**
- Сначала сайт создаётся и деплоится **без CMS** — как обычная статика
- Затем, если нужна CMS — она создаётся отдельным процессом: схема в Supabase, анализ HTML, генерация админки
- При деплое CMS: `romanadmin.html` деплоится на S3 рядом с сайтом, страницы пересобираются с контентом из Supabase и передеплоятся
- **Суть разделения:** сайт работает без CMS. CMS — это надстройка, которая ничего не ломает если её убрать. На страницах сайта нет никакого CMS-кода — управление только через отдельную админку.

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
- Storage бакет `cms-{SITE}`: чтение публичное, загрузка/удаление через админку по паролю (`verify_cms_storage_auth()`)

## Админ-панель (romanadmin.html)

Отдельная страница для полного управления контентом. Деплоится на S3 рядом с файлами сайта. Это **единственный** интерфейс управления — на самих страницах сайта никакого редактора нет.

Технологии: Tailwind CSS CDN. Один HTML-файл, всё inline. Только прямые fetch (без supabase-js).

### Дизайн админки — ВДУМЧИВО

Не торопись с дизайном админки. Продумай каждый экран по очереди, не делай всё за один проход:
1. Сначала — экран входа (минимализм, поле пароля, кнопка)
2. Потом — сайдбар (список секций, навигация между страницами сайта)
3. Потом — редактор секции (поля + коллекция)
4. Потом — модальные окна (редактирование карточки, загрузка файлов)
5. Каждый экран проверь визуально перед тем как идти дальше

### Экран входа
Три поля: адрес базы (Supabase URL), API ключ (anon key), пароль → проверка через RPC `cms_auth_check()` → сохранение в cookie `cms_url`, `cms_key`, `cms_pwd`.

### Дашборд

Слева сайдбар со списком секций. **Все страницы сайта, не только главная** — если у сайта несколько HTML-файлов, каждый анализируется и его секции добавляются в CMS с привязкой к странице (поле `page` в таблице sections).

Справа — редактор выбранной секции:

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

### 9. Сборка: вписать контент из CMS в статику

Во время сборки Claude **читает контент из Supabase** через публичные RPC и **вписывает его прямо в HTML**. Это происходит ДО деплоя — на этапе подготовки файлов.

**Порядок:**
1. Вызвать `cms_public_content(site)` → получить все поля всех секций
2. Вызвать `cms_public_items(site, section_key)` для каждой секции с коллекцией → получить карточки
3. Подставить значения в HTML:
   - Элементы с `data-cms="section_key.field_key"` → заменить `textContent` / `src` / `href` на значение из БД
   - Контейнеры с `data-cms-collection="section_key"` → отрендерить карточки по шаблону `[data-cms-template]`
4. Результат — **готовый статичный HTML** с актуальным контентом. Никаких runtime-запросов к Supabase для отображения.

**Почему так:** сайт на S3 — это статика. Контент должен быть в HTML, а не загружаться через JS. Это быстрее, лучше для SEO, и работает без JS у посетителя.

**Когда пересобирать:** после того как администратор изменил контент через админку, нужна пересборка и передеплой чтобы изменения попали в статику.

### 10. Деплой и credentials
Задеплоить romanadmin.html + все страницы сайта (скилл s3-deploy). Вывести в чат URL админки, пароль, URL Supabase и anon key (админ их вводит при входе).

### 11. Зафиксировать привязку CMS в CLAUDE.md проекта

После создания CMS **обязательно** добавить в `CLAUDE.md` проекта (тот что в рабочей директории сайта, НЕ в этом репозитории) секцию с данными CMS:

```markdown
## CMS

- **Схема**: cms_{SITE}
- **Supabase Project ID**: sugbffcgdjwekktzavra
- **Supabase URL**: https://sugbffcgdjwekktzavra.supabase.co
- **Пароль CMS**: {сгенерированный пароль}
- **Админка**: https://{домен}/romanadmin.html
- **Тип сборки**: static (prebuild контент из Supabase → HTML)
```

**Зачем:** чтобы в следующих сессиях Claude знал:
- Какая схема в Supabase привязана к этому сайту
- Какой пароль для CMS (нужен при пересборке/обновлении)
- Что сайт использует CMS и контент нужно подтягивать из Supabase при сборке

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
9. **CMS и сайт — раздельные процессы.** Сайт деплоится независимо от CMS. CMS — надстройка (romanadmin.html на S3 + схема в Supabase). На страницах сайта нет CMS-кода — управление только через админку.
