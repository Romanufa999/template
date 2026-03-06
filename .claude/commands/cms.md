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

## Два режима редактирования

CMS поддерживает два способа управления контентом. Оба деплоятся вместе с сайтом.

### Режим 1: Админ-панель (romanadmin.html)

Отдельная страница для полного управления. Подходит для массового редактирования, работы с коллекциями, загрузки файлов.

Технологии: Tailwind CSS CDN. Один HTML-файл, всё inline. Только прямые fetch (без supabase-js).

#### Дизайн админки — ВДУМЧИВО

Не торопись с дизайном админки. Продумай каждый экран по очереди, не делай всё за один проход:
1. Сначала — экран входа (минимализм, поле пароля, кнопка)
2. Потом — сайдбар (список секций, навигация между страницами сайта)
3. Потом — редактор секции (поля + коллекция)
4. Потом — модальные окна (редактирование карточки, загрузка файлов)
5. Каждый экран проверь визуально перед тем как идти дальше

#### Экран входа
Три поля: адрес базы (Supabase URL), API ключ (anon key), пароль → проверка через RPC `cms_auth_check()` → сохранение в cookie `cms_url`, `cms_key`, `cms_pwd`.

#### Дашборд

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

#### Загрузка файлов
Загрузка в Supabase Storage бакет `cms-{SITE}`. URL вставляется в поле image.

### Режим 2: Inline-редактирование прямо на сайте

Визуальное редактирование контента прямо на страницах сайта. Администратор видит сайт как обычный посетитель, но может редактировать секции наведя на них.

#### Принцип безопасности — КРИТИЧЕСКИ ВАЖНО

**В исходном коде страницы НЕТ никаких данных Supabase.** Ни URL базы, ни anon key, ни имени сайта — ничего. Адрес базы известен только во время сборки (когда Claude встраивает CMS-загрузчик), но НЕ зашивается в HTML.

Администратор сам вводит адрес базы и пароль при входе. Без этих данных страница — обычный статичный сайт без каких-либо признаков CMS:
- Нет кнопок редактирования, рамок, подсветки
- CSS-стили и DOM-элементы редактора НЕ создаются
- В исходном коде — только кнопка-замочек и минимальный JS для показа формы входа

#### Поток авторизации

```
1. В cookie есть cms_url + cms_pwd?
   ├─ НЕТ → кнопка-замочек, по клику форма с двумя полями
   └─ ДА → отправляем fetch к URL из cookie:
            POST {cms_url}/rest/v1/rpc/cms_auth_check
            с x-cms-site и x-cms-password в заголовках
            ├─ Ответ false / ошибка → показываем форму входа заново
            └─ Ответ true → ТОЛЬКО ТОГДА инициализируем редактор
```

#### UI — кнопка-замочек и состояния

**Кнопка-замочек** — маленькая круглая кнопка в правом нижнем углу, видна всегда, но ненавязчива (полупрозрачная, ~40px, иконка замка). Единственный элемент CMS до авторизации.

**Три состояния:**

1. **Замок (не залогинен)** — нет cookie или пароль не прошёл проверку. По клику → модальное окно с **двумя полями**:
   - **Адрес базы** (URL Supabase, например `https://xxxxx.supabase.co`)
   - **Пароль**
   После успешной проверки через Supabase → оба значения в cookie (max-age 30 дней):
   - `cms_url` — адрес базы
   - `cms_pwd` — пароль
   Кнопка переключается в состояние 2.

2. **Карандаш (залогинен, режим просмотра)** — пароль проверен, редактирование выключено. Сайт выглядит обычно. По клику → включается режим редактирования (состояние 3). Рядом кнопка "Выйти" (крестик) — удаляет cookie, возвращает в состояние 1.

3. **Карандаш активный (режим редактирования)** — кнопка подсвечена. По клику → выключает режим, возвращает в состояние 2.

#### Режим редактирования (состояние 3)

Когда режим редактирования включён:
- При **наведении** на секцию (`data-cms-section="section_key"`) — **синяя пунктирная рамка**
- В **правом верхнем углу** секции — кнопка **"Править"**
- По клику "Править" → **боковая панель справа** с полями секции:
  - Поля из `fields_schema` (text → input, textarea → textarea, image → превью + загрузка)
  - Коллекция — карточки с кнопками добавить/удалить/редактировать
  - "Сохранить" → данные в Supabase → контент обновляется на странице без перезагрузки
  - "Отмена" → панель закрывается

#### Реализация — встраиваемый скрипт

Перед `</body>` каждой страницы — минимальный JS. **Никаких URL/ключей в коде:**

```javascript
(function(){
  // Имя сайта зашито (не секрет — это просто идентификатор схемы)
  var SITE = 'site_name';

  // 1. Создаём кнопку-замочек
  var btn = createLockButton();

  // 2. Читаем cookie
  var url = getCookie('cms_url');
  var pwd = getCookie('cms_pwd');

  if (!url || !pwd) {
    // Нет данных — замок, по клику форма с двумя полями
    btn.onclick = function(){ showLoginModal(SITE); };
    return;
  }

  // 3. Проверяем пароль — URL берём из cookie, не из кода
  // anon key тоже получаем динамически (см. ниже)
  fetch(url + '/rest/v1/rpc/cms_auth_check', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'apikey': getCookie('cms_key'),
      'Authorization': 'Bearer ' + getCookie('cms_key'),
      'x-cms-site': SITE,
      'x-cms-password': pwd
    },
    body: JSON.stringify({p_site: SITE})
  }).then(r => r.json()).then(function(ok) {
    if (ok !== true) {
      clearCmsCookies();
      btn.onclick = function(){ showLoginModal(SITE); };
      return;
    }
    // Авторизован — карандаш
    switchToPencil(btn);
    btn.onclick = function(){ toggleEditMode(url, getCookie('cms_key'), SITE, pwd); };
    addLogoutButton();
  });
})();
```

#### Форма входа — три поля

Модальное окно при клике на замочек:

| Поле | Cookie | Описание |
|------|--------|----------|
| Адрес базы | `cms_url` | URL Supabase (например `https://xxxxx.supabase.co`) |
| API ключ | `cms_key` | Anon key Supabase |
| Пароль | `cms_pwd` | Пароль администратора CMS |

Все три значения сохраняются в cookie на 30 дней после успешной проверки. В исходном коде страницы их нет — только имя сайта (SITE), которое само по себе бесполезно без URL и ключа.

#### Работа на всех страницах сайта

Скрипт встраивается в КАЖДУЮ HTML-страницу сайта. Cookie общие для домена — вошёл на одной странице → можешь редактировать все.

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
