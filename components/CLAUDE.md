# UI-компоненты

Инструкции для работы с React-компонентами в `components/`.

## Структура компонентов

- `ChatWindow.tsx` — главный чат-виджет (объединяет ввод, сообщения, скролл)
- `ChatMessageBubble.tsx` — пузырёк сообщения (AI/пользователь)
- `ChatInput` (в ChatWindow.tsx) — форма ввода сообщения
- `ChatLayout` (в ChatWindow.tsx) — лейаут с авто-скроллом вниз
- `IntermediateStep.tsx` — отображение промежуточных шагов агента
- `UploadDocumentsForm.tsx` — загрузка документов для RAG
- `Navbar.tsx` — навигация
- `ui/` — shadcn/ui примитивы (button, checkbox, dialog, popover и т.д.)

## Правила создания компонентов

1. Все компоненты — **функциональные** с хуками React
2. Клиентские компоненты помечать `"use client"` в начале файла
3. Стилизация — **только Tailwind CSS классы** через `className`
4. Для объединения классов использовать утилиту `cn()` из `@/utils/cn`
5. Иконки — из библиотеки `lucide-react`
6. Тосты/уведомления — через `sonner` (функция `toast`)
7. Типизация пропсов — inline в параметрах функции или через `interface`

## shadcn/ui

- Конфигурация: `components.json` (стиль "new-york", цвет "zinc")
- Компоненты в `components/ui/` — их **не модифицировать** напрямую
- Для кастомизации — оборачивать в свои компоненты или использовать `className`

## Паттерны чата

```typescript
// Хук для чат-функциональности
const chat = useChat({
  api: "/api/chat/endpoint",
  streamMode: "text",
  onResponse(response) { /* обработка заголовков */ },
  onError: (e) => toast.error("Ошибка", { description: e.message }),
});
```

- Стриминг через `useChat` из `ai/react` (Vercel AI SDK)
- Источники передаются через заголовок `x-sources` (base64 JSON)
- Промежуточные шаги агентов отображаются как system-сообщения

## Импорты

```typescript
// Компоненты проекта
import { ChatMessageBubble } from "@/components/ChatMessageBubble";
// shadcn/ui
import { Button } from "@/components/ui/button";
// Иконки
import { ArrowDown, LoaderCircle } from "lucide-react";
// Утилиты
import { cn } from "@/utils/cn";
```
