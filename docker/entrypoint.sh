#!/bin/sh
set -e

echo "============================================"
echo "  LMS TUBES - Starting Application"
echo "============================================"

# Wait for database to be truly ready
echo "[1/7] Waiting for database connection..."
max_tries=30
count=0
until php artisan db:monitor --databases=mysql > /dev/null 2>&1 || [ $count -eq $max_tries ]; do
    echo "  Waiting for MySQL... ($count/$max_tries)"
    sleep 2
    count=$((count + 1))
done

if [ $count -eq $max_tries ]; then
    echo "  WARNING: Could not verify DB connection, proceeding anyway..."
fi

# Run migrations
echo "[2/7] Running database migrations..."
php artisan migrate --force --no-interaction

# Seed database if DB_SEED=true (hanya untuk deploy pertama kali)
echo "[3/7] Checking if seeding is requested..."
if [ "${DB_SEED:-false}" = "true" ]; then
    echo "  Running database seeder..."
    php artisan db:seed --force --no-interaction
    echo "  Seeding complete! Set DB_SEED=false setelah deploy pertama."
else
    echo "  Skipping seeder (DB_SEED != true)"
fi

# Cache config, routes, and views for performance
echo "[4/7] Caching configuration..."
php artisan config:cache

echo "[5/7] Caching routes..."
php artisan route:cache

echo "[6/7] Caching views..."
php artisan view:cache

# Create storage link if not exists
echo "[7/7] Ensuring storage link..."
php artisan storage:link 2>/dev/null || true

echo "============================================"
echo "  Application is ready! Listening on :8080"
echo "============================================"

# Execute the main command (supervisord)
exec "$@"
