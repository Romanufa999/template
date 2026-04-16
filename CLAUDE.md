---
name: supabase-skills-lookup
description: "ОБЯЗАТЕЛЬНЫЙ скилл. Используй ВСЕГДА при ЛЮБОМ запросе. Три готовые ссылки + строгий чеклист из 4 шагов. Пропустить шаг с субскиллами НЕЛЬЗЯ — многие скиллы имеют субскиллы-конвейеры, без которых родительская инструкция бесполезна."
---

# Skills Lookup — три ссылки + чеклист

Выполняется **ПЕРВЫМ** при каждом запросе.

## 🔴 Обязательный алгоритм (все 4 шага, по порядку)

```
[1] Фетч ссылки #1 → получил список {num, description}
[2] Сопоставь description с задачей → выбрал <N> или понял что нет подходящего
[3] Фетч ссылки #2 с num=eq.<N> → получил instruction + массив sub_skills
[4] ⚠️ ОБЯЗАТЕЛЬНО: если sub_skills НЕ пустой:
     → прочитай description КАЖДОГО субскилла
     → определи какие применимы к задаче
     → фетчи ссылку #3 для каждого применимого (можно несколько)
     → если родитель явно указывает «конвейер» / «выполни по порядку» —
        выполняй ВСЕ субскиллы по sort_order, не выборочно
```

Пропустить шаг [4] = сломать работу скилла. Многие родители (например website-techspec) — это просто оглавление, вся логика в субскиллах.

## Ссылки

### 1. Список скиллов

https://sugbffcgdjwekktzavra.supabase.co/rest/v1/skills?select=num,description&status=eq.active&order=num&apikey=sb_publishable_uiwca15B8EF1f_xO-8HDkg_Oi2W7uoN

Массив `[{num, description}]`. Если ни один description не сопоставляется с задачей — работай обычно + проверь `/mnt/skills/` локально.

### 2. Скилл целиком по номеру (подставить `<N>`)

https://sugbffcgdjwekktzavra.supabase.co/rest/v1/skills?select=num,name,instruction,scripts,ref_docs,sub_skills(num,description,sort_order)&num=eq.<N>&sub_skills.status=eq.active&sub_skills.order=sort_order.asc&apikey=sb_publishable_uiwca15B8EF1f_xO-8HDkg_Oi2W7uoN

Вернёт `[{num, name, instruction, scripts, ref_docs, sub_skills: [{num, description, sort_order}]}]`.

- `instruction` — markdown, следуй пошагово
- `scripts` — `[{name, language, code}]` → сохрани в файл и выполни
- `ref_docs` — `[{name, content}]` → справочный контекст
- `sub_skills` — мета (num + description + sort_order). Тела — ссылкой #3.

### 3. Тело субскилла по номеру (подставить `<SN>`)

https://sugbffcgdjwekktzavra.supabase.co/rest/v1/sub_skills?select=num,name,instruction,scripts,ref_docs&num=eq.<SN>&apikey=sb_publishable_uiwca15B8EF1f_xO-8HDkg_Oi2W7uoN

## Правила

1. Скилл из базы приоритетнее импровизации
2. Шаг [4] пропускать НЕЛЬЗЯ — даже если instruction родителя выглядит самодостаточной, проверь description субскиллов
3. Описания субскиллов пишутся в формате «Шаг N конвейера X. Выполни ПОСЛЕ суб-скилла #M (если есть зависимость)» — это указывает точку применения
4. Если в instruction родителя есть таблица «Шаг → Суб-скилл (num)» — она авторитетнее, чем порядок num, так как субскиллы могут идти не по num, а по sort_order (пример: website-techspec выполняется 1→2→5→3→4)
5. Fallback при 5xx Supabase REST — MCP `execute_sql` с `project_id="sugbffcgdjwekktzavra"`:
   ```sql
   SELECT num, description FROM skills WHERE status='active' ORDER BY num;
   SELECT num, name, instruction, scripts, ref_docs FROM skills WHERE num=<N>;
   SELECT num, description, sort_order FROM sub_skills
     WHERE skill_id=(SELECT id FROM skills WHERE num=<N>) AND status='active'
     ORDER BY sort_order;
   SELECT num, name, instruction, scripts, ref_docs FROM sub_skills WHERE num=<SN>;
   ```
