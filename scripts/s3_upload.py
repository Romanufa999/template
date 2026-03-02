#!/usr/bin/env python3
"""
S3 Deploy Script — загрузка файлов на S3 Beget + регистрация в Supabase.
Использование:
  python3 s3_upload.py <local_path> <project_name> [filename] [--dir] [--desc "описание"]
Примеры:
  python3 s3_upload.py /home/claude/site.html my-landing
  python3 s3_upload.py /home/claude/site.html my-landing index.html --desc "Лендинг для продукта X"
  python3 s3_upload.py /home/claude/pricing.html my-landing pricing.html --desc "Таблица цен"
  python3 s3_upload.py /home/claude/my-site/ my-landing --dir --desc "Полный сайт"
"""
import boto3
import requests
import json
import sys
import os
import random
import string
import mimetypes
from botocore.config import Config
from pathlib import Path

# ===================== КОНФИГУРАЦИЯ =====================
S3_ENDPOINT = "https://s3.ru1.storage.beget.cloud"
S3_BUCKET = "76ae0220f799-proficient-naida"
S3_ACCESS_KEY = "YZU8L57451SHREI5F4RG"
S3_SECRET_KEY = "hbjJoHpyBseFKnJOz4xkm731ZUjhE6MSfxRvccfU"
S3_BASE_URL = f"https://s3.ru1.storage.beget.cloud/{S3_BUCKET}"

SUPABASE_URL = "https://sugbffcgdjwekktzavra.supabase.co"
SUPABASE_SERVICE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InN1Z2JmZmNnZGp3ZWtrdHphdnJhIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc2OTcyMzQzMywiZXhwIjoyMDg1Mjk5NDMzfQ.KYs6CbqP3Kuv_3fCSfhFIz4qoJLUU2C2iglX6Puc2c0"

# ===================== CONTENT TYPES =====================
CONTENT_TYPES = {
    '.html': 'text/html; charset=utf-8',
    '.htm': 'text/html; charset=utf-8',
    '.css': 'text/css; charset=utf-8',
    '.js': 'application/javascript; charset=utf-8',
    '.json': 'application/json',
    '.png': 'image/png',
    '.jpg': 'image/jpeg',
    '.jpeg': 'image/jpeg',
    '.gif': 'image/gif',
    '.svg': 'image/svg+xml',
    '.webp': 'image/webp',
    '.ico': 'image/x-icon',
    '.woff': 'font/woff',
    '.woff2': 'font/woff2',
    '.ttf': 'font/ttf',
    '.eot': 'application/vnd.ms-fontobject',
    '.mp4': 'video/mp4',
    '.webm': 'video/webm',
    '.pdf': 'application/pdf',
    '.xml': 'application/xml',
    '.txt': 'text/plain; charset=utf-8',
    '.md': 'text/markdown; charset=utf-8',
}


def get_content_type(filepath: str) -> str:
    """Определить Content-Type по расширению файла."""
    ext = Path(filepath).suffix.lower()
    if ext in CONTENT_TYPES:
        return CONTENT_TYPES[ext]
    mime, _ = mimetypes.guess_type(filepath)
    return mime or 'application/octet-stream'


def get_s3_client():
    """Создать S3 клиент с правильной конфигурацией для Beget."""
    return boto3.client(
        's3',
        endpoint_url=S3_ENDPOINT,
        aws_access_key_id=S3_ACCESS_KEY,
        aws_secret_access_key=S3_SECRET_KEY,
        config=Config(signature_version='s3')  # ВАЖНО: v2 подпись для Beget
    )


def upload_file(s3_client, local_path: str, s3_key: str) -> str:
    """
    Загрузить один файл на S3.

    Returns:
        Публичный URL файла
    """
    content_type = get_content_type(local_path)

    with open(local_path, 'rb') as f:
        s3_client.put_object(
            Bucket=S3_BUCKET,
            Key=s3_key,
            Body=f.read(),
            ContentType=content_type,
            ACL='public-read'
        )

    url = f"{S3_BASE_URL}/{s3_key}"
    print(f"  ✅ Загружен: {s3_key} ({content_type})")
    return url


def upload_directory(s3_client, local_dir: str, project_name: str) -> list:
    """
    Загрузить всю директорию на S3.

    Returns:
        Список (s3_key, url) загруженных файлов
    """
    uploaded = []
    local_dir = Path(local_dir)

    for filepath in sorted(local_dir.rglob('*')):
        if filepath.is_file():
            relative = filepath.relative_to(local_dir)
            s3_key = f"sites/{project_name}/{relative}"
            url = upload_file(s3_client, str(filepath), s3_key)
            uploaded.append((str(relative), url))

    return uploaded


def _supabase_request(method: str, path: str, json_data=None, params: str = ""):
    """
    Универсальный запрос к Supabase с обходом прокси.
    Пробует несколько стратегий подключения.
    """
    url = f"{SUPABASE_URL}{path}"
    if params:
        url += f"?{params}"

    headers = {
        "apikey": SUPABASE_SERVICE_KEY,
        "Authorization": f"Bearer {SUPABASE_SERVICE_KEY}",
        "Content-Type": "application/json",
        "Prefer": "return=representation"
    }

    strategies = [
        {"name": "proxy", "kwargs": {}},
        {"name": "no-proxy", "kwargs": {"proxies": {"http": None, "https": None}}},
    ]

    for strategy in strategies:
        try:
            if method.upper() == "GET":
                r = requests.get(url, headers=headers, timeout=10, **strategy["kwargs"])
            elif method.upper() == "POST":
                r = requests.post(url, headers=headers, json=json_data, timeout=10, **strategy["kwargs"])
            elif method.upper() == "PATCH":
                r = requests.patch(url, headers=headers, json=json_data, timeout=10, **strategy["kwargs"])
            else:
                continue
            return r
        except Exception:
            continue

    return None


def ensure_sites_table():
    """Проверить/создать таблицу sites в Supabase."""
    r = _supabase_request("GET", "/rest/v1/sites", params="select=id&limit=1")
    if r is not None and r.status_code == 200:
        return True

    sql = """
    CREATE TABLE IF NOT EXISTS public.sites (
        id SERIAL PRIMARY KEY,
        project_name TEXT NOT NULL,
        filename TEXT NOT NULL,
        link TEXT NOT NULL,
        description TEXT,
        created_at TIMESTAMPTZ DEFAULT NOW()
    );
    ALTER TABLE public.sites ENABLE ROW LEVEL SECURITY;
    DO $$ BEGIN
        IF NOT EXISTS (
            SELECT 1 FROM pg_policies WHERE tablename = 'sites' AND policyname = 'Allow all for service role'
        ) THEN
            CREATE POLICY "Allow all for service role" ON public.sites FOR ALL USING (true);
        END IF;
    END $$;
    """

    r = _supabase_request("POST", "/rest/v1/rpc/exec_sql", json_data={"query": sql})
    if r is not None and r.status_code in [200, 201]:
        print("  ✅ Таблица sites создана в Supabase")
        return True

    print("  ⚠️  Supabase недоступен. Запись будет сохранена локально.")
    return False


def register_in_supabase(project_name: str, filename: str, link: str, description: str = ""):
    """Зарегистрировать загруженный файл в Supabase."""
    data = {
        "project_name": project_name,
        "filename": filename,
        "link": link,
        "description": description
    }

    r = _supabase_request("POST", "/rest/v1/sites", json_data=data)

    if r is not None and r.status_code in [200, 201]:
        try:
            result = r.json()
            record_id = result[0].get('id') if isinstance(result, list) and result else '?'
        except Exception:
            record_id = '?'
        print(f"  ✅ Записано в Supabase (id: {record_id})")
        return True

    # Fallback: сохраняем запись в локальный JSON файл
    fallback_file = os.path.join(os.path.dirname(os.path.abspath(__file__)), "pending_supabase.json")
    pending = []
    if os.path.exists(fallback_file):
        try:
            with open(fallback_file, 'r') as f:
                pending = json.load(f)
        except Exception:
            pending = []

    pending.append(data)
    try:
        with open(fallback_file, 'w') as f:
            json.dump(pending, f, indent=2, ensure_ascii=False)
        print(f"  ⚠️  Supabase недоступен. Запись сохранена в {fallback_file}")
        print(f"     (Всего ожидающих записей: {len(pending)})")
    except Exception as e:
        print(f"  ⚠️  Supabase недоступен и не удалось сохранить локально: {e}")

    return False


def main():
    import argparse

    parser = argparse.ArgumentParser(description='Загрузка сайтов на S3 Beget')
    parser.add_argument('local_path', help='Путь к файлу или директории')
    parser.add_argument('project_name', help='Имя проекта (папка в sites/)')
    parser.add_argument('filename', nargs='?', default=None, help='Имя файла на S3 (по умолчанию — из local_path)')
    parser.add_argument('--dir', action='store_true', help='Загрузить всю директорию')
    parser.add_argument('--desc', default='', help='Описание сайта/страницы')
    parser.add_argument('--no-supabase', action='store_true', help='Не регистрировать в Supabase')

    args = parser.parse_args()

    # Валидация
    if not os.path.exists(args.local_path):
        print(f"❌ Файл не найден: {args.local_path}")
        sys.exit(1)

    # Нормализуем имя проекта и добавляем 5 случайных букв
    base_name = args.project_name.lower().strip().replace(' ', '-')
    random_suffix = ''.join(random.choices(string.ascii_lowercase, k=5))
    project_name = f"{base_name}-{random_suffix}"

    print(f"\n📦 Загрузка в S3: sites/{project_name}/")
    print(f"{'='*50}")

    s3 = get_s3_client()

    if args.dir or os.path.isdir(args.local_path):
        # Загрузка директории
        uploaded = upload_directory(s3, args.local_path, project_name)

        if not args.no_supabase:
            ensure_sites_table()
            main_file = None
            for fname, url in uploaded:
                if fname == 'index.html':
                    main_file = (fname, url)
                    break
            if not main_file and uploaded:
                main_file = uploaded[0]

            if main_file:
                register_in_supabase(project_name, main_file[0], main_file[1], args.desc)

        print(f"\n{'='*50}")
        print(f"✅ Загружено {len(uploaded)} файлов")
        main_url = f"{S3_BASE_URL}/sites/{project_name}/index.html"
        print(f"\n🔗 Сайт: {main_url}")

    else:
        # Загрузка одного файла
        filename = args.filename or os.path.basename(args.local_path)
        s3_key = f"sites/{project_name}/{filename}"

        url = upload_file(s3, args.local_path, s3_key)

        if not args.no_supabase:
            ensure_sites_table()
            register_in_supabase(project_name, filename, url, args.desc)

        print(f"\n{'='*50}")
        print(f"\n🔗 Ссылка: {url}")

    return 0


if __name__ == "__main__":
    sys.exit(main())
