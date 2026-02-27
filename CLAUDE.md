# Project: LangChain Next.js Template

## Supabase

URL: `https://sugbffcgdjwekktzavra.supabase.co`
Auth: env vars `SUPABASE_URL` и `SUPABASE_PRIVATE_KEY` (service_role)

### Схема `public`
| Таблица | Описание |
|---|---|
| `sites` | Сайты: `id`, `project_name`, `filename`, `link`, `description`, `created_at` |
| `kit` | UI Kit дизайнов: `id`, `name`, `text`, `created_at` |
| `portal_view` | Вью порталов: `id`, `name`, `page`, `section`, `text`, `super`, `edit`, `paper`, `created_at` |
| `ui_view` | Вью UI: `id`, `portal_id` (FK→portal_view), `site`, `page`, `section`, `style`, `jsx_code`, `edit`, `status`, `html` |

### Схема `saity`
| Таблица | Описание |
|---|---|
| `sites` | Сайты: `id`, `project_name`, `filename`, `link`, `description`, `created_at` |
| `portal` | Контент секций: `id`, `name`, `page`, `section`, `text`, `super`, `edit`, `paper`, `created_at` |
| `ui` | Варианты дизайнов: `id`, `portal_id` (FK→portal), `site`, `page`, `section`, `style`, `jsx_code`, `edit`, `status`, `html` |
| `page_content` | CMS RomanAI: `id`, `page_slug`, `section_key`, `content_value`, `content_type`, `sort_order`, `is_published` |

### Доступ через REST
```bash
curl -H "apikey: $SUPABASE_PRIVATE_KEY" \
     -H "Authorization: Bearer $SUPABASE_PRIVATE_KEY" \
     "$SUPABASE_URL/rest/v1/<table>"

# Для схемы saity добавить заголовок:
# -H "Accept-Profile: saity"
```

### JS-клиент
```ts
import { createClient } from '@supabase/supabase-js'

// Схема public
const client = createClient(process.env.SUPABASE_URL!, process.env.SUPABASE_PRIVATE_KEY!)

// Схема saity
const saityClient = createClient(process.env.SUPABASE_URL!, process.env.SUPABASE_PRIVATE_KEY!, {
  db: { schema: 'saity' }
})
```

## Stack
- Next.js 13+ (App Router)
- LangChain / LangGraph
- OpenAI Embeddings
- Supabase (`@supabase/supabase-js ^2.32.0`)
