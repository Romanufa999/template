---
name: supabase-skills-lookup
description: "ОБЯЗАТЕЛЬНЫЙ скилл. Используй ВСЕГДА при ЛЮБОМ запросе пользователя. Перед выполнением любой задачи Claude ОБЯЗАН получить список скилов из Supabase (проект sugbffcgdjwekktzavra) через REST API одним curl-запросом, найти подходящий по description и, если найден, догрузить инструкцию/скрипты/ref_docs родителя вместе с метой его субскилов. Тело конкретного субскила догружается по требованию по id. Это первый шаг ЛЮБОЙ задачи. Даже если запрос кажется простым — ВСЕГДА сначала проверь базу скиллов."
---

# Lookup скиллов в Supabase (REST API)

## Приоритет
Выполняется **ПЕРВЫМ** при каждом запросе, **ДО** начала работы.

## Почему REST, а не MCP
PostgREST отдаёт список за ~230мс одним HTTP-вызовом. MCP `execute_sql` — это минимум 3 инструментальных вызова с оверхедом роутинга. REST — быстрее.

## Принцип нагрузки на контекст
Тянем в контекст **ровно столько, сколько нужно для принятия решения на текущем шаге**. Тело субскила — только когда до него дошла очередь.

## Константы

```
SUPABASE_URL = https://sugbffcgdjwekktzavra.supabase.co
SUPABASE_KEY = sb_publishable_uiwca15B8EF1f_xO-8HDkg_Oi2W7uoN
```

Ключ публичный (publishable/anon), читает через RLS. В коде/логах светить можно.

## Процедура — 3 фазы

### Фаза 1 — список (всегда)

```bash
curl -s "$SUPABASE_URL/rest/v1/skills?select=id,name,description&status=eq.active&order=name" \
  -H "apikey: $SUPABASE_KEY"
```

Ответ — `[{id, name, description}, ...]`, только `active`.
По `description` решить, матчится ли хоть один. Если нет — работать как обычно + проверить локальные `/mnt/skills/`.

### Фаза 2 — родитель + мета субскилов (при матче)

**Одним запросом через embedded:**

```bash
curl -s "$SUPABASE_URL/rest/v1/skills?\
select=instruction,scripts,ref_docs,sub_skills(id,name,description,sort_order)\
&id=eq.<uuid>\
&sub_skills.status=eq.active\
&sub_skills.order=sort_order.asc" \
  -H "apikey: $SUPABASE_KEY"
```

Возвращает:
- полное тело родителя (`instruction`, `scripts`, `ref_docs`)
- **только мету** субскилов (`id`, `name`, `description`, `sort_order`) — без инструкций и скриптов

По мете субскилов понятно, какие есть шаги и какой из них нужен сейчас. Если субскилов нет — массив пустой, работаем только с инструкцией родителя.

### Фаза 3 — тело конкретного субскила (по требованию)

Когда по инструкции родителя или по ходу задачи дошла очередь до конкретного субскила — тянуть его тело по `id`:

```bash
curl -s "$SUPABASE_URL/rest/v1/sub_skills?\
select=name,instruction,scripts,ref_docs\
&id=eq.<sub_uuid>" \
  -H "apikey: $SUPABASE_KEY"
```

**Когда тянуть:**
- конвейерный скилл — перед выполнением каждого шага по порядку `sort_order`
- библиотечный скилл — только тот субскил, который соответствует текущей подзадаче

**Когда НЕ тянуть:**
- все субскилы подряд впрок — это перегружает контекст
- субскилы, которые по мете явно не относятся к текущему шагу

## Данные

- **instruction** — markdown-инструкция, следуй пошагово
- **scripts** — `[{name, language, code}]` → сохрани в файл, выполни через bash
- **ref_docs** — `[{name, content}]` → справочный контекст
- **sub_skills (в Фазе 2)** — только мета для навигации, отсортированы по `sort_order`

## Fallback

Если REST не ответил (сеть, 5xx) — откатись на Supabase MCP `execute_sql` с `project_id="sugbffcgdjwekktzavra"` и теми же запросами в SQL.

## Таблицы

**`skills`**: id, name (unique), description, instruction, scripts, ref_docs, status

**`sub_skills`**: id, skill_id → skills.id, name, description, instruction, scripts, ref_docs, sort_order, status

## Правила

1. Читать **только active** (и у родителей, и у субскилов)
2. Скилл из базы имеет приоритет над импровизацией
3. Этот скилл живёт только локально — в базе его нет
4. Субскилы в Фазе 2 — без тел, только мета для навигации
5. Тело субскила тянуть точечно по id в Фазе 3, когда до него дошла очередь
