#!/bin/bash
################################################################################
# Backup Script for News/CMS Application
# Creates backups of database and application files
################################################################################

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Configuration
APP_ROOT="/var/www/news"
BACKUP_DIR="/var/backups/news"
DB_NAME="news_db"
DB_USER="news_user"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
RETENTION_DAYS=30

echo -e "${GREEN}=== News/CMS Backup ===${NC}"
echo ""

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    echo -e "${RED}Please run this script with sudo:${NC}"
    echo "sudo bash deploy/backup.sh"
    exit 1
fi

# Create backup directory
mkdir -p "$BACKUP_DIR"

# Backup database
echo -e "${YELLOW}[1/3] Backing up database...${NC}"

# Load credentials from .deploy-credentials.txt if exists
if [ -f "$APP_ROOT/.deploy-credentials.txt" ]; then
    eval $(grep -E "Database (Name|User|Password):" "$APP_ROOT/.deploy-credentials.txt" | sed 's/.*: /DB_/;s/: /=/' | tr '\n' ' ')
fi

read -p "Database name [$DB_NAME]: " INPUT_DB_NAME
read -p "Database user [$DB_USER]: " INPUT_DB_USER
read -sp "Database password: " INPUT_DB_PASS
echo ""

DB_NAME=${INPUT_DB_NAME:-$DB_NAME}
DB_USER=${INPUT_DB_USER:-$DB_USER}
DB_PASS=${INPUT_DB_PASS:-$DB_PASS}

mysqldump -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" | gzip > "$BACKUP_DIR/db_$TIMESTAMP.sql.gz"
echo -e "${GREEN}Database backed up: db_$TIMESTAMP.sql.gz${NC}"

# Backup application files (excluding writable/cache)
echo -e "${YELLOW}[2/3] Backing up application files...${NC}"
tar -czf "$BACKUP_DIR/files_$TIMESTAMP.tar.gz" \
    -C "$APP_ROOT" \
    --exclude='writable/cache' \
    --exclude='writable/logs' \
    --exclude='writable/session' \
    --exclude='vendor' \
    --exclude='.git' \
    .

echo -e "${GREEN}Files backed up: files_$TIMESTAMP.tar.gz${NC}"

# Backup .env and database config
echo -e "${YELLOW}[3/3] Backing up configuration...${NC}"
tar -czf "$BACKUP_DIR/config_$TIMESTAMP.tar.gz" \
    -C "$APP_ROOT" \
    .env \
    app/Config/Database.php \
    2>/dev/null || true

echo -e "${GREEN}Configuration backed up: config_$TIMESTAMP.tar.gz${NC}"

# Clean old backups
echo -e "${YELLOW}Cleaning up old backups (older than $RETENTION_DAYS days)...${NC}"
find "$BACKUP_DIR" -name "*.sql.gz" -mtime +$RETENTION_DAYS -delete
find "$BACKUP_DIR" -name "*.tar.gz" -mtime +$RETENTION_DAYS -delete

# Summary
echo ""
echo -e "${GREEN}=== Backup Complete! ===${NC}"
echo ""
echo "Backup location: $BACKUP_DIR"
echo "Files created:"
ls -lh "$BACKUP_DIR"/*$TIMESTAMP*
echo ""
echo "To restore, use: sudo bash deploy/restore.sh $TIMESTAMP"
echo ""
