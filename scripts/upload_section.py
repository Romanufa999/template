#!/usr/bin/env python3
"""Upload a section HTML to S3 and register in Supabase saity.ui table via SQL."""
import boto3
import requests
import sys
import os
import json
from botocore.config import Config

S3_ENDPOINT = "https://s3.ru1.storage.beget.cloud"
S3_BUCKET = "76ae0220f799-proficient-naida"
S3_ACCESS_KEY = "YZU8L57451SHREI5F4RG"
S3_SECRET_KEY = "hbjJoHpyBseFKnJOz4xkm731ZUjhE6MSfxRvccfU"
S3_BASE_URL = f"https://s3.ru1.storage.beget.cloud/{S3_BUCKET}"

SUPABASE_URL = "https://sugbffcgdjwekktzavra.supabase.co"
SUPABASE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InN1Z2JmZmNnZGp3ZWtrdHphdnJhIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc2OTcyMzQzMywiZXhwIjoyMDg1Mjk5NDMzfQ.KYs6CbqP3Kuv_3fCSfhFIz4qoJLUU2C2iglX6Puc2c0"


def upload(local_path, project_folder, filename):
    # Upload to S3
    s3 = boto3.client('s3', endpoint_url=S3_ENDPOINT,
                      aws_access_key_id=S3_ACCESS_KEY,
                      aws_secret_access_key=S3_SECRET_KEY,
                      config=Config(signature_version='s3'))

    s3_key = f"sites/{project_folder}/{filename}"
    with open(local_path, 'rb') as f:
        s3.put_object(Bucket=S3_BUCKET, Key=s3_key, Body=f.read(),
                      ContentType='text/html; charset=utf-8', ACL='public-read')

    url = f"{S3_BASE_URL}/{s3_key}"
    print(f"S3_URL={url}")

    # Register in Supabase saity.ui via pg_net or direct SQL
    section_name = filename.replace('.html', '')
    sql = f"INSERT INTO saity.ui (section_name, project_name, link, filename) VALUES ('{section_name}', '{project_folder}', '{url}', '{filename}');"

    headers = {
        "apikey": SUPABASE_KEY,
        "Authorization": f"Bearer {SUPABASE_KEY}",
        "Content-Type": "application/json",
    }

    # Use the Supabase SQL endpoint (pg-meta)
    for proxy_setting in [{}, {"proxies": {"http": None, "https": None}}]:
        try:
            r = requests.post(
                f"{SUPABASE_URL}/pg/query",
                headers=headers,
                json={"query": sql},
                timeout=15,
                **proxy_setting
            )
            if r.status_code in [200, 201]:
                print("SUPABASE=OK")
                return url
        except Exception:
            continue

    print("SUPABASE=FAIL_API (will use MCP later)")
    return url


if __name__ == "__main__":
    if len(sys.argv) < 4:
        print("Usage: upload_section.py <local_path> <project_folder> <filename>")
        sys.exit(1)
    upload(sys.argv[1], sys.argv[2], sys.argv[3])
