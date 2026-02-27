#!/bin/bash
# Script to verify Supabase access and readiness

set -e

echo "=== Supabase Access Check ==="
echo ""

# 1. Check environment variables
echo "[1/4] Checking environment variables..."
if [ -z "$SUPABASE_URL" ]; then
  echo "  FAIL: SUPABASE_URL is not set"
  exit 1
else
  echo "  OK: SUPABASE_URL is set ($SUPABASE_URL)"
fi

if [ -z "$SUPABASE_PRIVATE_KEY" ]; then
  echo "  FAIL: SUPABASE_PRIVATE_KEY is not set"
  exit 1
else
  echo "  OK: SUPABASE_PRIVATE_KEY is set (hidden)"
fi

# 2. Check REST API connectivity
echo ""
echo "[2/4] Checking Supabase REST API connectivity..."
HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" \
  -H "apikey: ${SUPABASE_PRIVATE_KEY}" \
  -H "Authorization: Bearer ${SUPABASE_PRIVATE_KEY}" \
  "${SUPABASE_URL}/rest/v1/" 2>&1)

if [ "$HTTP_STATUS" = "200" ]; then
  echo "  OK: REST API is accessible (HTTP $HTTP_STATUS)"
else
  echo "  FAIL: REST API returned HTTP $HTTP_STATUS"
  exit 1
fi

# 3. Check 'documents' table
echo ""
echo "[3/4] Checking 'documents' table..."
RESPONSE=$(curl -s -w "\n%{http_code}" \
  -H "apikey: ${SUPABASE_PRIVATE_KEY}" \
  -H "Authorization: Bearer ${SUPABASE_PRIVATE_KEY}" \
  -H "Prefer: count=exact" \
  -H "Range: 0-0" \
  "${SUPABASE_URL}/rest/v1/documents?select=count" 2>&1)

TABLE_STATUS=$(echo "$RESPONSE" | tail -1)
if [ "$TABLE_STATUS" = "200" ] || [ "$TABLE_STATUS" = "206" ]; then
  echo "  OK: 'documents' table exists"
else
  echo "  WARN: 'documents' table not found (HTTP $TABLE_STATUS)"
  echo "  You need to create the table. See README.md for instructions."
fi

# 4. Check 'match_documents' RPC function
echo ""
echo "[4/4] Checking 'match_documents' RPC function..."
RESPONSE=$(curl -s -w "\n%{http_code}" \
  -H "apikey: ${SUPABASE_PRIVATE_KEY}" \
  -H "Authorization: Bearer ${SUPABASE_PRIVATE_KEY}" \
  -H "Content-Type: application/json" \
  -X POST -d '{}' \
  "${SUPABASE_URL}/rest/v1/rpc/match_documents" 2>&1)

RPC_STATUS=$(echo "$RESPONSE" | tail -1)
if [ "$RPC_STATUS" = "200" ]; then
  echo "  OK: 'match_documents' function exists"
else
  echo "  WARN: 'match_documents' function not found (HTTP $RPC_STATUS)"
  echo "  You need to create the function. See README.md for instructions."
fi

echo ""
echo "=== Check Complete ==="
