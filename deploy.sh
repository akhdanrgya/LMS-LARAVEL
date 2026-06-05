#!/bin/bash
#================================================================
# deploy.sh — One-click deploy script untuk Cloud VM (Azure/GCP)
#================================================================
# Cara pakai:
#   1. SSH ke GCP VM
#   2. Clone/upload repo ke VM
#   3. chmod +x deploy.sh && ./deploy.sh
#================================================================
set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

echo -e "${CYAN}"
echo "╔══════════════════════════════════════════╗"
echo "║     LMS TUBES — Deploy Script            ║"
echo "╚══════════════════════════════════════════╝"
echo -e "${NC}"

# ── Step 1: Check Docker ────────────────────────────────────────
echo -e "${YELLOW}[1/6] Checking Docker installation...${NC}"
if ! command -v docker &> /dev/null; then
    echo -e "${RED}Docker not found! Installing...${NC}"
    curl -fsSL https://get.docker.com | sh
    sudo usermod -aG docker $USER
    echo -e "${GREEN}Docker installed. You may need to log out and back in.${NC}"
fi

if ! command -v docker compose &> /dev/null && ! docker compose version &> /dev/null; then
    echo -e "${RED}Docker Compose not found!${NC}"
    echo "Install it with: sudo apt-get install docker-compose-plugin"
    exit 1
fi

echo -e "${GREEN}  ✓ Docker is ready${NC}"

# ── Step 2: Create .env if not exists ───────────────────────────
echo -e "${YELLOW}[2/6] Setting up environment...${NC}"
if [ ! -f .env ]; then
    cp docker/.env.docker .env
    echo -e "${YELLOW}  ⚠ Created .env from template. EDIT IT NOW:${NC}"
    echo -e "${YELLOW}    nano .env${NC}"
    echo ""
    echo -e "${RED}  WAJIB diisi:${NC}"
    echo "    - APP_KEY (generate setelah build pertama)"
    echo "    - APP_URL (IP/domain VM Azure/GCP lo)"
    echo "    - DB_PASSWORD (password yang kuat)"
    echo "    - DB_ROOT_PASSWORD (password root yang kuat)"
    echo ""
    read -p "  Udah edit .env? (y/n): " confirm
    if [ "$confirm" != "y" ]; then
        echo "  Edit .env dulu, lalu jalankan ulang script ini."
        exit 0
    fi
else
    echo -e "${GREEN}  ✓ .env already exists${NC}"
fi

# ── Step 3: Create necessary directories ────────────────────────
echo -e "${YELLOW}[3/6] Creating directories...${NC}"
mkdir -p storage/app/public storage/framework/{cache/data,sessions,views} storage/logs bootstrap/cache
echo -e "${GREEN}  ✓ Directories ready${NC}"

# ── Step 4: Build & Start containers ────────────────────────────
echo -e "${YELLOW}[4/6] Building and starting containers...${NC}"
docker compose down 2>/dev/null || true
docker compose up -d --build

# ── Step 5: Generate APP_KEY if empty ────────────────────────────
echo -e "${YELLOW}[5/6] Checking APP_KEY...${NC}"
APP_KEY_VALUE=$(grep "^APP_KEY=" .env | cut -d'=' -f2)
if [ -z "$APP_KEY_VALUE" ]; then
    echo "  Generating APP_KEY..."
    NEW_KEY=$(docker compose exec -T app php artisan key:generate --show)
    # Update .env with the new key
    sed -i "s|^APP_KEY=.*|APP_KEY=${NEW_KEY}|" .env
    echo -e "${GREEN}  ✓ APP_KEY generated: ${NEW_KEY}${NC}"
    echo "  Restarting app to apply new key..."
    docker compose restart app
else
    echo -e "${GREEN}  ✓ APP_KEY already set${NC}"
fi

# ── Step 6: Health check ────────────────────────────────────────
echo -e "${YELLOW}[6/6] Running health check...${NC}"
sleep 10

# Check if containers are running
if docker compose ps | grep -q "Up"; then
    echo -e "${GREEN}  ✓ Containers are running${NC}"
else
    echo -e "${RED}  ✗ Some containers failed to start${NC}"
    docker compose logs --tail=50
    exit 1
fi

# Check HTTP health endpoint
APP_PORT=$(grep "^APP_PORT=" .env | cut -d'=' -f2)
APP_PORT=${APP_PORT:-8080}

if curl -sf "http://localhost:${APP_PORT}/health" > /dev/null 2>&1; then
    echo -e "${GREEN}  ✓ Health check passed${NC}"
else
    echo -e "${YELLOW}  ⚠ Health check pending (container may still be initializing)${NC}"
    echo "  Check logs: docker compose logs -f app"
fi

# ── Done ─────────────────────────────────────────────────────────
echo ""
echo -e "${CYAN}╔══════════════════════════════════════════╗"
echo -e "║           🚀 Deploy Complete!             ║"
echo -e "╚══════════════════════════════════════════╝${NC}"
echo ""
echo -e "  App URL:        ${GREEN}http://localhost:${APP_PORT}${NC}"
echo -e "  Containers:     ${GREEN}docker compose ps${NC}"
echo -e "  Logs:           ${GREEN}docker compose logs -f${NC}"
echo -e "  phpMyAdmin:     ${GREEN}docker compose --profile debug up -d${NC}"
echo ""
echo -e "${YELLOW}Tips:${NC}"
echo "  • Seed database:    Set DB_SEED=true di .env, lalu docker compose restart app"
echo "  • Setup firewall:   sudo ufw allow ${APP_PORT}/tcp"
echo "  • SSL/HTTPS:        Pasang reverse proxy (Nginx/Caddy) di depan"
echo ""
