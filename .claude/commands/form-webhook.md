# Form Webhook — Перехват форм и отправка данных на вебхук

Универсальный перехватчик форм. При успешной отправке любой формы данные отправляются на вебхук.

**ВАЖНО: Вебхук отправляется ТОЛЬКО при успешном заполнении формы** — после того как валидация пройдена (телефон корректный, обязательные поля заполнены). Промежуточные события (клик на форму, начало ввода, ошибки валидации, промежуточные шаги квиза) НЕ должны вызывать отправку вебхука. Эти события можно отслеживать через Яндекс Метрику (см. скилл yandex-metrica), но не через вебхук.

Используй этот скилл когда нужно:
1. Добавить форму на сайт (контактная форма, заявка, обратный звонок и т.д.)
2. Настроить отправку данных формы на вебхук
3. Перехватить отправку формы и передать данные
4. Пользователь говорит "добавь форму", "сделай форму заявки", "перехват формы", "форма обратной связи", "add form", "contact form"

## Endpoint

```
GET https://gadugestok.beget.app/webhook/7f5337f3-d08d-4070-b539-7dabad4866ff
```

## Параметры вебхука

| Параметр | Обязательное | Описание |
|----------|-------------|----------|
| form_id | да | Уникальный ID формы (латиница, snake_case, например `contact_form`, `callback_hero`) |
| phone | нет | Телефон (если есть в форме) |
| name | нет | Имя (если есть в форме) |
| email | нет | Email (если есть в форме) |
| message | нет | Сообщение (если есть в форме) |
| page | да | URL страницы, на которой заполнена форма |
| ym_uid | да | Yandex Metrika User ID (из куки `_ym_uid`) |
| ym_client_id | нет | Yandex Metrika Client ID (если доступен) |
| ga_client_id | нет | Google Analytics Client ID (из куки `_ga`) |
| cookies | нет | Все куки страницы (document.cookie) |
| referrer | да | document.referrer — откуда пришёл пользователь |
| utm_source | нет | UTM-метка source |
| utm_medium | нет | UTM-метка medium |
| utm_campaign | нет | UTM-метка campaign |
| utm_term | нет | UTM-метка term |
| utm_content | нет | UTM-метка content |
| timestamp | да | Время отправки (ISO 8601) |

Допускаются произвольные дополнительные параметры из полей формы — все передаются как query-параметры GET-запроса.

## Архитектура: глобальный слушатель

**Формы НЕ отправляют вебхук напрямую.** Вместо этого используется глобальный слушатель:

1. **Форма** при успешной валидации диспатчит событие (CustomEvent или стандартный submit)
2. **Глобальный слушатель** (один на весь сайт) ловит это событие и отправляет вебхук
3. Это исключает дублирование запросов и централизует логику отправки

### Для React/Next.js проектов:
- Формы вызывают `emitFormSuccess({ form_id, phone, ... })` — это диспатчит CustomEvent
- Компонент `WebhookListener` монтируется один раз в `layout.tsx` и слушает эти события
- Формы **НИКОГДА** не вызывают fetch/sendWebhook напрямую

### Для статических HTML сайтов:
- Глобальный скрипт перехватчик вешается на `document.addEventListener('submit', ...)` один раз
- Формы используют стандартный submit — скрипт перехватывает, валидирует и отправляет вебхук
- Формы **НИКОГДА** не вызывают sendFormWebhook напрямую из своих обработчиков

---

## Три режима работы

### Режим 1: Статический сайт (HTML/JS) — глобальный перехватчик

Для статических сайтов, которые деплоятся на S3, вся логика работает на клиенте через JavaScript.

Добавь этот скрипт **перед закрывающим тегом `</body>`** на каждую страницу с формами:

```html
<script>
(function() {
  function getCookie(name) {
    var m = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([.$?*|{}()\[\]\\\/+^])/g,'\\$1') + '=([^;]*)'));
    return m ? decodeURIComponent(m[1]) : '';
  }

  function getUTM() {
    var params = new URLSearchParams(window.location.search);
    var utm = {};
    ['utm_source','utm_medium','utm_campaign','utm_term','utm_content'].forEach(function(k) {
      var v = params.get(k);
      if (v) utm[k] = v;
    });
    return utm;
  }

  function sendFormWebhook(formData) {
    var params = new URLSearchParams();
    params.set('form_id', formData.form_id || 'unknown');
    params.set('page', window.location.href);
    params.set('referrer', document.referrer || '');
    params.set('ym_uid', getCookie('_ym_uid'));
    params.set('ym_client_id', getCookie('_ym_d'));
    params.set('cookies', document.cookie);
    params.set('timestamp', new Date().toISOString());

    // GA Client ID
    var ga = getCookie('_ga');
    if (ga) {
      var parts = ga.split('.');
      if (parts.length >= 4) params.set('ga_client_id', parts[2] + '.' + parts[3]);
    }

    // UTM
    var utm = getUTM();
    for (var k in utm) params.set(k, utm[k]);

    // Поля формы
    for (var key in formData) {
      if (key !== 'form_id') params.set(key, formData[key]);
    }

    var url = 'https://gadugestok.beget.app/webhook/7f5337f3-d08d-4070-b539-7dabad4866ff?' + params.toString();
    fetch(url).catch(function(){});
  }

  // Валидация российского номера телефона
  function isValidRussianPhone(phone) {
    var cleaned = phone.replace(/[\s\-\(\)]/g, '');
    return /^(\+7|7|8)\d{10}$/.test(cleaned);
  }

  // Перехват всех форм на странице
  document.addEventListener('submit', function(e) {
    var form = e.target;
    if (!form || form.tagName !== 'FORM') return;

    // Валидация телефона перед отправкой
    var phoneField = form.querySelector('input[name="phone"]') || form.querySelector('input[type="tel"]');
    if (phoneField && !isValidRussianPhone(phoneField.value)) {
      e.preventDefault();
      phoneField.classList.add('error');
      var errEl = form.querySelector('.phone-error');
      if (!errEl) {
        errEl = document.createElement('div');
        errEl.className = 'phone-error';
        errEl.style.cssText = 'color:#e74c3c;font-size:13px;margin-top:4px;';
        phoneField.parentNode.insertBefore(errEl, phoneField.nextSibling);
      }
      errEl.textContent = 'Введите корректный российский номер телефона';
      return false;
    }
    // Убираем ошибку если была
    if (phoneField) {
      phoneField.classList.remove('error');
      var errEl = form.querySelector('.phone-error');
      if (errEl) errEl.textContent = '';
    }

    var data = {};
    data.form_id = form.getAttribute('data-form-id') || form.id || 'form_' + Date.now();

    var elements = form.elements;
    for (var i = 0; i < elements.length; i++) {
      var el = elements[i];
      if (el.name && el.value && el.type !== 'submit' && el.type !== 'button') {
        data[el.name] = el.value;
      }
    }

    sendFormWebhook(data);
  });

  // Экспорт для вызова вручную (например из кастомных обработчиков)
  window.sendFormWebhook = sendFormWebhook;
})();
</script>
```

**Важно для каждой формы:**
- Добавить атрибут `data-form-id` с уникальным ID: `<form data-form-id="callback_hero">`
- Или использовать `id` формы: `<form id="contact_form">`

### Режим 2: SSR через Docker Compose — серверная функция

Для SSR-проектов (Next.js, Express и т.д.) создай серверный эндпоинт, который проксирует вебхук.

#### Next.js (App Router) — `app/api/form-webhook/route.ts`:

```typescript
import { NextRequest, NextResponse } from 'next/server';

const WEBHOOK_URL = 'https://gadugestok.beget.app/webhook/7f5337f3-d08d-4070-b539-7dabad4866ff';

export async function POST(req: NextRequest) {
  try {
    const body = await req.json();

    const params = new URLSearchParams();

    // Все поля из body передаём как query-параметры
    for (const [key, value] of Object.entries(body)) {
      if (value != null) params.set(key, String(value));
    }

    // Добавляем timestamp если нет
    if (!params.has('timestamp')) {
      params.set('timestamp', new Date().toISOString());
    }

    const url = `${WEBHOOK_URL}?${params.toString()}`;
    const response = await fetch(url);

    return NextResponse.json({ success: true, status: response.status });
  } catch (error) {
    console.error('Form webhook error:', error);
    return NextResponse.json({ success: false, error: 'Internal error' }, { status: 500 });
  }
}
```

#### Клиентский скрипт для SSR-режима:

```html
<script>
(function() {
  function getCookie(name) {
    var m = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([.$?*|{}()\[\]\\\/+^])/g,'\\$1') + '=([^;]*)'));
    return m ? decodeURIComponent(m[1]) : '';
  }

  function getUTM() {
    var params = new URLSearchParams(window.location.search);
    var utm = {};
    ['utm_source','utm_medium','utm_campaign','utm_term','utm_content'].forEach(function(k) {
      var v = params.get(k);
      if (v) utm[k] = v;
    });
    return utm;
  }

  function sendFormWebhook(formData) {
    var payload = {
      form_id: formData.form_id || 'unknown',
      page: window.location.href,
      referrer: document.referrer || '',
      ym_uid: getCookie('_ym_uid'),
      ym_client_id: getCookie('_ym_d'),
      cookies: document.cookie,
      timestamp: new Date().toISOString()
    };

    // GA Client ID
    var ga = getCookie('_ga');
    if (ga) {
      var parts = ga.split('.');
      if (parts.length >= 4) payload.ga_client_id = parts[2] + '.' + parts[3];
    }

    // UTM
    var utm = getUTM();
    for (var k in utm) payload[k] = utm[k];

    // Поля формы
    for (var key in formData) {
      if (key !== 'form_id') payload[key] = formData[key];
    }

    fetch('/api/form-webhook', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    }).catch(function(){});
  }

  // Валидация российского номера телефона
  function isValidRussianPhone(phone) {
    var cleaned = phone.replace(/[\s\-\(\)]/g, '');
    return /^(\+7|7|8)\d{10}$/.test(cleaned);
  }

  document.addEventListener('submit', function(e) {
    var form = e.target;
    if (!form || form.tagName !== 'FORM') return;

    // Валидация телефона перед отправкой
    var phoneField = form.querySelector('input[name="phone"]') || form.querySelector('input[type="tel"]');
    if (phoneField && !isValidRussianPhone(phoneField.value)) {
      e.preventDefault();
      phoneField.classList.add('error');
      var errEl = form.querySelector('.phone-error');
      if (!errEl) {
        errEl = document.createElement('div');
        errEl.className = 'phone-error';
        errEl.style.cssText = 'color:#e74c3c;font-size:13px;margin-top:4px;';
        phoneField.parentNode.insertBefore(errEl, phoneField.nextSibling);
      }
      errEl.textContent = 'Введите корректный российский номер телефона';
      return false;
    }
    if (phoneField) {
      phoneField.classList.remove('error');
      var errEl = form.querySelector('.phone-error');
      if (errEl) errEl.textContent = '';
    }

    var data = {};
    data.form_id = form.getAttribute('data-form-id') || form.id || 'form_' + Date.now();

    var elements = form.elements;
    for (var i = 0; i < elements.length; i++) {
      var el = elements[i];
      if (el.name && el.value && el.type !== 'submit' && el.type !== 'button') {
        data[el.name] = el.value;
      }
    }

    sendFormWebhook(data);
  });

  window.sendFormWebhook = sendFormWebhook;
})();
</script>
```

Разница: вместо прямого GET на вебхук, отправляет POST на `/api/form-webhook`, где серверная функция проксирует запрос.

### Режим 3: Next.js (Static Export / React) — CustomEvent + WebhookListener

Для React/Next.js проектов (в т.ч. `output: 'export'`) используется паттерн emit/listen через CustomEvent.

#### 1. Утилита `src/utils/webhook.ts`:

```typescript
const WEBHOOK_URL = 'https://gadugestok.beget.app/webhook/7f5337f3-d08d-4070-b539-7dabad4866ff';
const EVENT_NAME = 'webhook:form-success';

export interface FormSuccessData {
  form_id: string;
  phone: string;
  answers?: string;
  gift?: string;
  step?: number;
  [key: string]: string | number | undefined;
}

// Хелперы для сбора данных (getYandexUid, getUtmParams, getDeviceInfo, persistUtm) — см. полную реализацию

/** Формы вызывают это — диспатчит CustomEvent */
export function emitFormSuccess(detail: FormSuccessData) {
  if (typeof window === 'undefined') return;
  window.dispatchEvent(new CustomEvent(EVENT_NAME, { detail }));
}

/** Отправка вебхука (вызывается ТОЛЬКО глобальным слушателем) */
function sendWebhook(data: FormSuccessData) {
  try {
    const query: Record<string, string> = {
      event: data.answers ? 'quiz_success' : 'form_success',
      form_id: data.form_id,
      time: new Date().toISOString(),
      page: window.location.pathname,
      referrer: document.referrer || '',
      ym_uid: getYandexUid(),
      yclid: getYclid(),
      ...getUtmParams(),
      ...getDeviceInfo(),
    };
    if (data.phone) query.phone = data.phone;
    if (data.answers) query.answers = data.answers;
    if (data.gift) query.gift = data.gift;
    if (data.step !== undefined) query.step = String(data.step);

    const url = `${WEBHOOK_URL}?${new URLSearchParams(query).toString()}`;
    fetch(url, { method: 'GET', keepalive: true }).catch(() => {});
  } catch { /* noop */ }
}

/** Инициализация глобального слушателя. Возвращает функцию очистки. */
export function initWebhookListener() {
  if (typeof window === 'undefined') return () => {};
  const handler = ((e: CustomEvent<FormSuccessData>) => {
    sendWebhook(e.detail);
  }) as EventListener;
  window.addEventListener(EVENT_NAME, handler);
  return () => window.removeEventListener(EVENT_NAME, handler);
}
```

#### 2. Компонент `src/components/ui/WebhookListener.tsx`:

```tsx
"use client";
import { useEffect } from "react";
import { initWebhookListener } from "@/utils/webhook";

export default function WebhookListener() {
  useEffect(() => {
    const cleanup = initWebhookListener();
    return cleanup;
  }, []);
  return null;
}
```

#### 3. Подключение в `layout.tsx`:

```tsx
import WebhookListener from "@/components/ui/WebhookListener";

// В body, рядом с другими глобальными компонентами:
<WebhookListener />
```

#### 4. Использование в формах:

```tsx
import { emitFormSuccess } from "@/utils/webhook";

// При успешной валидации:
if (isPhoneValid(phone)) {
  setSubmitted(true);
  ymGoal("callback_submit", phone, "callback_widget");
  emitFormSuccess({ form_id: "callback_widget", phone });
}
// НЕ вызывать emitFormSuccess при ошибках, кликах, начале ввода!
```

## Правила создания форм

1. **Каждая форма ОБЯЗАТЕЛЬНО имеет `data-form-id`** — уникальный идентификатор в snake_case. Без этого атрибута форму создавать НЕЛЬЗЯ:
   - `callback_hero` — форма обратного звонка в hero-секции
   - `contact_footer` — контактная форма в футере
   - `order_pricing` — форма заказа на странице цен
   - `quiz_step_final` — финальный шаг квиза
   - `modal_callback` — форма в модальном окне
   - `quiz_main` — основная форма квиза

2. **Поле телефона — обязательные требования**:
   - Атрибут `type="tel"` и `name="phone"`
   - Значение по умолчанию `value="+7"` — поле всегда предзаполнено с `+7`
   - Placeholder: `placeholder="+7 (___) ___-__-__"`
   - Валидация на российский номер (встроена в скрипт перехватчика)
   ```html
   <input type="tel" name="phone" value="+7" placeholder="+7 (___) ___-__-__" required>
   ```

3. **Валидация российского номера телефона** — скрипт перехватчика автоматически проверяет номер перед отправкой. Допустимые форматы:
   - `+7XXXXXXXXXX` (11 цифр после +)
   - `+7 (XXX) XXX-XX-XX` (с пробелами, скобками, дефисами)
   - `8XXXXXXXXXX` (начиная с 8)
   - Минимум 11 цифр в номере. Если валидация не прошла — форма не отправляется, пользователю показывается ошибка.

4. **Остальные поля формы должны иметь атрибут `name`** — именно по нему данные попадут в вебхук:
   ```html
   <input type="tel" name="phone" value="+7" placeholder="+7 (___) ___-__-__" required>
   <input type="text" name="name" placeholder="Ваше имя">
   <input type="email" name="email" placeholder="Email">
   ```

5. **Скрипт перехватчика вставляется один раз** перед `</body>` — он автоматически перехватывает ВСЕ формы на странице.

6. **Вебхук — только при успешной отправке через глобальный слушатель.** Формы НЕ отправляют вебхук напрямую. В статике — глобальный скрипт-перехватчик сам отправляет после валидации. В React — формы вызывают `emitFormSuccess()`, а `WebhookListener` в layout ловит и отправляет. Не отправлять вебхук при: клике на форму, начале ввода, ошибке валидации, промежуточных шагах квиза. Для промежуточных событий используй Яндекс Метрику.

7. **Для кастомных форм** (без стандартного submit) — вызвать `window.sendFormWebhook()` вручную:
   ```javascript
   window.sendFormWebhook({
     form_id: 'custom_modal',
     phone: '+79871234567',
     name: 'Иван'
   });
   ```

## Выбор режима

| Критерий | Статика (S3) | SSR (Docker) | React/Next.js (Static Export) |
|----------|-------------|--------------|-------------------------------|
| Тип сайта | HTML/CSS/JS на S3 | Next.js / Express в Docker | Next.js с `output: 'export'` |
| Вебхук | Прямой GET с клиента | POST на серверный эндпоинт | GET через CustomEvent + WebhookListener |
| URL вебхука | Виден в клиентском коде | Скрыт на сервере | Виден в клиентском коде |
| Зависимости | Нет | Серверный роут | WebhookListener в layout |
| Глобальный слушатель | `document.addEventListener('submit')` | `document.addEventListener('submit')` | `WebhookListener` компонент |

**По умолчанию** — используй режим "Статика" для HTML-сайтов на S3, режим "React" для Next.js проектов.

## Пример формы

```html
<form data-form-id="callback_hero" onsubmit="event.preventDefault(); alert('Спасибо! Мы перезвоним.');">
  <input type="tel" name="phone" value="+7" placeholder="+7 (___) ___-__-__" required>
  <input type="text" name="name" placeholder="Ваше имя">
  <button type="submit">Перезвоните мне</button>
</form>
```

Перехватчик автоматически отправит на вебхук: form_id, phone, name, page, referrer, ym_uid, cookies, utm-метки и timestamp.
