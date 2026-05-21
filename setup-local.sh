#!/bin/bash
# Setup local WordPress environment with Docker
set -e

SQL_FILE="../he341e3e01_embedded_1779370473.sql"
LOCAL_URL="http://localhost:9000"
PROD_URL="https://embedded.io.vn"

echo "==> Starting containers..."
docker compose up -d db wordpress wpcli

echo "==> Waiting for MySQL to be ready..."
until docker exec embedded_db mysqladmin ping -h localhost -u root -prootlocal --silent 2>/dev/null; do
    printf '.'
    sleep 2
done
echo " MySQL ready!"

echo "==> Importing database (116MB — takes ~1-2 minutes)..."
docker exec -i embedded_db mysql \
    -u root -prootlocal \
    he341e3e01_embedded < "$SQL_FILE"
echo " Database imported!"

echo "==> Search-replace URL: $PROD_URL → $LOCAL_URL"
docker exec embedded_wpcli wp search-replace \
    "$PROD_URL" "$LOCAL_URL" \
    --all-tables --skip-columns=guid --allow-root

echo "==> Flushing cache..."
docker exec embedded_wpcli wp cache flush --allow-root 2>/dev/null || true

echo ""
echo "======================================"
echo " Site ready at: $LOCAL_URL"
echo " WP Admin:      $LOCAL_URL/wp-admin"
echo "======================================"
