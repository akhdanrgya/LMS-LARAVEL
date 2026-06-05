#================================================================
# Makefile — Shortcut commands untuk Docker deployment
#================================================================

.PHONY: help build up down restart logs shell db-shell seed fresh status clean

help: ## Tampilkan daftar command
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

build: ## Build ulang semua container
	docker compose build --no-cache

up: ## Start semua container (background)
	docker compose up -d --build

down: ## Stop semua container
	docker compose down

restart: ## Restart semua container
	docker compose restart

logs: ## Lihat logs (follow mode)
	docker compose logs -f

logs-app: ## Lihat logs app saja
	docker compose logs -f app

logs-db: ## Lihat logs database saja
	docker compose logs -f db

shell: ## Masuk ke shell container app
	docker compose exec app sh

db-shell: ## Masuk ke MySQL CLI
	docker compose exec db mysql -u$$(grep DB_USERNAME .env | cut -d= -f2) -p$$(grep DB_PASSWORD .env | cut -d= -f2) $$(grep DB_DATABASE .env | cut -d= -f2)

seed: ## Jalankan database seeder
	docker compose exec app php artisan db:seed --force

fresh: ## Fresh migrate + seed (HATI-HATI: hapus semua data!)
	docker compose exec app php artisan migrate:fresh --seed --force

migrate: ## Jalankan migration
	docker compose exec app php artisan migrate --force

key: ## Generate APP_KEY baru
	docker compose exec app php artisan key:generate --show

cache: ## Clear & rebuild semua cache
	docker compose exec app php artisan config:cache
	docker compose exec app php artisan route:cache
	docker compose exec app php artisan view:cache

cache-clear: ## Clear semua cache
	docker compose exec app php artisan config:clear
	docker compose exec app php artisan route:clear
	docker compose exec app php artisan view:clear
	docker compose exec app php artisan cache:clear

status: ## Cek status container
	docker compose ps

pma: ## Start phpMyAdmin (debug profile)
	docker compose --profile debug up -d phpmyadmin
	@echo "phpMyAdmin: http://localhost:$$(grep PMA_PORT .env | cut -d= -f2 || echo 8888)"

clean: ## Hapus semua container, volume, dan image (HATI-HATI!)
	docker compose down -v --rmi all
	@echo "Semua container, volume, dan image sudah dihapus."

backup-db: ## Backup database ke file SQL
	@mkdir -p backups
	docker compose exec db mysqldump -uroot -p$$(grep DB_ROOT_PASSWORD .env | cut -d= -f2) $$(grep DB_DATABASE .env | cut -d= -f2) > backups/backup_$$(date +%Y%m%d_%H%M%S).sql
	@echo "Backup saved to backups/"

restore-db: ## Restore database dari backup (usage: make restore-db FILE=backups/file.sql)
	@test -n "$(FILE)" || (echo "Usage: make restore-db FILE=backups/file.sql" && exit 1)
	docker compose exec -T db mysql -uroot -p$$(grep DB_ROOT_PASSWORD .env | cut -d= -f2) $$(grep DB_DATABASE .env | cut -d= -f2) < $(FILE)
	@echo "Database restored from $(FILE)"
