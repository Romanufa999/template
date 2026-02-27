# API-эндпоинты

Инструкции для работы с API-роутами в `app/api/`.

## Архитектура

Каждый эндпоинт — это Next.js Route Handler в своей папке:
- `chat/route.ts` — простой чат (LangChain → prompt → model → stream)
- `chat/agents/route.ts` — агент с инструментами (SerpAPI и т.д.)
- `chat/structured_output/route.ts` — LLM с Zod-схемой ответа
- `chat/retrieval/route.ts` — RAG (вопрос по документам через vector store)
- `chat/retrieval_agents/route.ts` — RAG + агенты
- `retrieval/ingest/route.ts` — загрузка и эмбеддинг документов

## Правила создания новых эндпоинтов

1. Использовать `export const runtime = "edge"` для стриминг-эндпоинтов
2. Экспортировать асинхронную функцию `POST` (или `GET` при необходимости)
3. Входные данные: `NextRequest`, парсить тело через `req.json()`
4. Ответы со стримингом: использовать `StreamingTextResponse` из `ai`
5. Ответы без стриминга: `NextResponse.json(data, { status })`
6. Обработка ошибок — блок try/catch, возвращать `{ error: e.message }` с корректным статусом

## Паттерн LangChain-цепочки

```typescript
// Стандартный паттерн: prompt → model → outputParser
const chain = prompt.pipe(model).pipe(outputParser);
const stream = await chain.stream({ ...variables });
return new StreamingTextResponse(stream);
```

## Модели

- По умолчанию: `ChatOpenAI` из `@langchain/openai`
- Альтернатива: `ChatAnthropic` из `@langchain/anthropic`
- Температура для чата: 0.8, для structured output: 0
- Стриминг: использовать `HttpResponseOutputParser` из `langchain/output_parsers`

## Важно

- **НЕ** хардкодить API-ключи — только через `process.env`
- Для `LANGCHAIN_CALLBACKS_BACKGROUND` ставить `"false"` в edge runtime
- При добавлении нового эндпоинта — добавить соответствующую страницу в `app/`
