# Yandex Metrica Tracking — Отслеживание событий форм и квизов

Отправка параметров визита и параметров посетителя в Яндекс Метрику при взаимодействии с формами и квизами.

Используй этот скилл когда нужно:
1. Добавить отслеживание форм / квизов в Яндекс Метрику
2. Отправлять параметры визита при заполнении формы
3. Передавать параметры посетителя при успешной отправке
4. Настроить цели Яндекс Метрики для форм
5. Пользователь говорит "добавь метрику", "отслеживание формы", "цели метрики", "yandex metrica", "трекинг формы"

## Какие события отслеживаются

| Событие | Когда срабатывает | Что отправляется |
|---------|-------------------|------------------|
| Начало заполнения | Пользователь кликнул/сфокусировался на первом поле формы | Параметр визита + параметр посетителя |
| Ошибка валидации | Номер телефона не прошёл валидацию при попытке отправки | Параметр визита + параметр посетителя |
| Успешная отправка | Форма отправлена, номер прошёл валидацию | Параметр визита + параметр посетителя + номер телефона |

## API Яндекс Метрики

### Параметры визита
```javascript
ym(COUNTER_ID, 'params', { ключ: "значение" });
```

### Параметры посетителя
```javascript
ym(COUNTER_ID, 'userParams', { ключ: "значение" });
```

### Достижение цели
```javascript
ym(COUNTER_ID, 'reachGoal', 'TARGET_NAME', { params_object });
```

### Получение Client ID
```javascript
ym(COUNTER_ID, 'getClientID', function(clientID) { /* ... */ });
```

## Скрипт отслеживания

Добавь этот скрипт **перед закрывающим тегом `</body>`**, ПОСЛЕ скрипта счётчика Яндекс Метрики.

**ВАЖНО**: Замени `COUNTER_ID` на реальный номер счётчика Яндекс Метрики клиента.

```html
<script>
(function() {
  var COUNTER = COUNTER_ID; // Заменить на реальный ID счётчика

  // Флаги для отслеживания начала заполнения (по каждой форме)
  var formStartTracked = {};

  // === Событие: Начало заполнения формы ===
  document.addEventListener('focusin', function(e) {
    var field = e.target;
    if (!field || !field.closest) return;
    var form = field.closest('form');
    if (!form) return;
    // Только для полей ввода
    var tag = field.tagName;
    if (tag !== 'INPUT' && tag !== 'TEXTAREA' && tag !== 'SELECT') return;
    if (field.type === 'submit' || field.type === 'button' || field.type === 'hidden') return;

    var formId = form.getAttribute('data-form-id') || form.id || 'unknown_form';
    if (formStartTracked[formId]) return;
    formStartTracked[formId] = true;

    // Параметр визита
    ym(COUNTER, 'params', {
      form_events: {
        [formId]: {
          form_start: 'yes',
          timestamp: new Date().toISOString()
        }
      }
    });

    // Параметр посетителя
    ym(COUNTER, 'userParams', {
      last_form_interaction: formId,
      last_form_event: 'form_start',
      last_event_time: new Date().toISOString()
    });

    // Цель
    ym(COUNTER, 'reachGoal', 'form_start', {
      form_id: formId
    });
  });

  // === Валидация российского номера ===
  function isValidRussianPhone(phone) {
    var cleaned = phone.replace(/[\s\-\(\)]/g, '');
    // Допустимые форматы: +7XXXXXXXXXX, 8XXXXXXXXXX, 7XXXXXXXXXX
    return /^(\+7|7|8)\d{10}$/.test(cleaned);
  }

  // === Обработка попытки отправки формы ===
  document.addEventListener('submit', function(e) {
    var form = e.target;
    if (!form || form.tagName !== 'FORM') return;

    var formId = form.getAttribute('data-form-id') || form.id || 'unknown_form';
    var phoneField = form.querySelector('input[name="phone"]') || form.querySelector('input[type="tel"]');
    var phoneValue = phoneField ? phoneField.value : '';

    // Проверяем валидацию телефона
    if (phoneField && !isValidRussianPhone(phoneValue)) {
      // === Событие: Ошибка валидации ===
      e.preventDefault();

      // Параметр визита
      ym(COUNTER, 'params', {
        form_events: {
          [formId]: {
            form_error: 'invalid_phone',
            error_value: phoneValue,
            timestamp: new Date().toISOString()
          }
        }
      });

      // Параметр посетителя
      ym(COUNTER, 'userParams', {
        last_form_interaction: formId,
        last_form_event: 'form_error',
        last_event_time: new Date().toISOString()
      });

      // Цель
      ym(COUNTER, 'reachGoal', 'form_error', {
        form_id: formId,
        error_type: 'invalid_phone'
      });

      // Показать пользователю ошибку
      if (phoneField) {
        phoneField.classList.add('error');
        var errEl = form.querySelector('.phone-error');
        if (!errEl) {
          errEl = document.createElement('div');
          errEl.className = 'phone-error';
          errEl.style.cssText = 'color:#e74c3c;font-size:13px;margin-top:4px;';
          phoneField.parentNode.insertBefore(errEl, phoneField.nextSibling);
        }
        errEl.textContent = 'Введите корректный российский номер телефона';
      }

      return false;
    }

    // === Событие: Успешная отправка ===

    // Параметр визита (включая номер телефона)
    ym(COUNTER, 'params', {
      form_events: {
        [formId]: {
          form_success: 'yes',
          phone: phoneValue,
          timestamp: new Date().toISOString()
        }
      }
    });

    // Параметр посетителя (включая номер телефона)
    ym(COUNTER, 'userParams', {
      last_form_interaction: formId,
      last_form_event: 'form_success',
      phone: phoneValue,
      last_event_time: new Date().toISOString()
    });

    // Цель
    ym(COUNTER, 'reachGoal', 'form_success', {
      form_id: formId,
      phone: phoneValue
    });
  });
})();
</script>
```

## Отслеживание квизов

Для квизов с несколькими шагами — отслеживай каждый шаг. Начало заполнения уже отслеживается автоматически (focusin). Для шагов квиза вызывай вручную:

```javascript
// Переход на следующий шаг квиза
function trackQuizStep(formId, stepNumber, stepData) {
  ym(COUNTER_ID, 'params', {
    form_events: {
      [formId]: {
        quiz_step: stepNumber,
        step_data: stepData,
        timestamp: new Date().toISOString()
      }
    }
  });

  ym(COUNTER_ID, 'userParams', {
    last_form_interaction: formId,
    last_form_event: 'quiz_step_' + stepNumber,
    last_event_time: new Date().toISOString()
  });

  ym(COUNTER_ID, 'reachGoal', 'quiz_step', {
    form_id: formId,
    step: stepNumber
  });
}

// Пример вызова при переходе на шаг 2
trackQuizStep('quiz_main', 2, 'selected_option_a');
```

## Структура данных в Метрике

### Параметры визита (отчёт "Параметры визитов")
```
form_events
  └── {form_id}
       ├── form_start: "yes"
       ├── form_error: "invalid_phone"
       ├── form_success: "yes"
       ├── phone: "+79871234567"
       ├── quiz_step: 2
       └── timestamp: "2026-03-06T12:00:00.000Z"
```

### Параметры посетителя (отчёт "Параметры посетителей")
```
last_form_interaction: "callback_hero"
last_form_event: "form_success"
phone: "+79871234567"
last_event_time: "2026-03-06T12:00:00.000Z"
```

## Цели для настройки в интерфейсе Яндекс Метрики

Создай следующие JavaScript-цели в настройках счётчика:

| Идентификатор цели | Описание |
|---------------------|----------|
| `form_start` | Начало заполнения формы |
| `form_error` | Ошибка валидации при отправке формы |
| `form_success` | Успешная отправка формы |
| `quiz_step` | Переход на следующий шаг квиза |

## Правила использования

1. **Скрипт вставляется ПОСЛЕ счётчика Яндекс Метрики** — иначе функция `ym()` не будет доступна.
2. **Замени `COUNTER_ID`** на реальный номер счётчика (число, не строку).
3. **Каждая форма должна иметь `data-form-id`** — по нему идентифицируются события.
4. **Номер телефона отправляется только при успешной отправке** — когда номер прошёл валидацию.
5. **Начало заполнения отслеживается один раз** — повторный фокус на ту же форму не вызывает повторного события.
6. **Скрипт совместим со скриптом form-webhook** — они работают параллельно, не конфликтуют.
7. **Для квизов** — финальный шаг (отправка) обрабатывается как обычная форма (submit), промежуточные шаги — через `trackQuizStep()`.
