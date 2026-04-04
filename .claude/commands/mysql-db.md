# MySQL Database Skill

## Конфигурация подключения

```
Host: nankickonsiter.beget.app
Port: 3306
Database: default-db
Username: default-db
Password: Roman987!
MySQL Version: 8.x
Hosting: Beget
```

## Работа с базой

Для ВСЕХ операций с базой использовать скрипт `scripts/mysql_query.py`.

### Выполнение запросов

```bash
python3 scripts/mysql_query.py "SELECT * FROM table_name LIMIT 10"
```

Скрипт автоматически:
- Подключается к базе с правильными credentials
- Выполняет запрос
- Выводит результат в читаемом формате (таблица для SELECT, affected rows для DML)
- Обрабатывает ошибки

### Получение схемы базы

```bash
python3 scripts/mysql_schema.py
```

Выводит все таблицы, их колонки, типы, индексы и внешние ключи. Всегда запускать первым при работе с незнакомой структурой.

### Экспорт данных

```bash
# В CSV
python3 scripts/mysql_export.py csv "SELECT * FROM users" /output/users.csv

# В JSON
python3 scripts/mysql_export.py json "SELECT * FROM users" /output/users.json

# SQL dump таблицы
python3 scripts/mysql_export.py sql users /output/users.sql
```

## Схема базы данных

Полная схема со всеми таблицами, колонками и комментариями: **`references/schema.md`**

**Таблицы (6):** belie, clients, clietssuper, prod, promts, svalka

Перед написанием запросов — прочитать `references/schema.md` чтобы знать точные имена колонок и типы. Если схема могла измениться — запустить `scripts/mysql_schema.py` для актуальной версии.

## Порядок работы

1. **Перед любой операцией** — установить pymysql: `pip install pymysql --break-system-packages -q`
2. **Прочитать `references/schema.md`** — чтобы знать структуру таблиц
3. **Для чтения** — использовать `mysql_query.py` с SELECT
4. **Для изменения данных** — использовать `mysql_query.py` с INSERT/UPDATE/DELETE (скрипт делает autocommit)
5. **Для DDL** — использовать `mysql_query.py` с CREATE/ALTER/DROP
6. **Для экспорта** — использовать `mysql_export.py`
7. **Если схема изменилась** — запустить `mysql_schema.py` для актуализации

## Особенности Beget хостинга

- Внешние подключения к MySQL могут быть ограничены по IP — если не подключается, база доступна из скриптов на самом хостинге
- Максимальный размер базы зависит от тарифа
- Кодировка по умолчанию: utf8mb4

## Интеграция в приложения

### Next.js / Node.js
```bash
npm install mysql2
```

```typescript
import mysql from 'mysql2/promise';

const pool = mysql.createPool({
  host: 'nankickonsiter.beget.app',
  port: 3306,
  user: 'default-db',
  password: 'Roman987!',
  database: 'default-db',
  waitForConnections: true,
  connectionLimit: 10,
});

// Использование
const [rows] = await pool.execute('SELECT * FROM users WHERE id = ?', [userId]);
```

### Python
```python
import pymysql

conn = pymysql.connect(
    host='nankickonsiter.beget.app',
    port=3306,
    user='default-db',
    password='Roman987!',
    database='default-db',
    charset='utf8mb4',
    cursorclass=pymysql.cursors.DictCursor
)
```

## Безопасность

- ВСЕГДА использовать параметризованные запросы (prepared statements) в коде приложений
- НИКОГДА не вставлять пользовательский ввод напрямую в SQL строки
- При генерации кода для приложений — использовать `?` плейсхолдеры (mysql2) или `%s` (pymysql)
