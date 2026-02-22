#!/bin/bash
################################################################################
# Restore Script for News/CMS Application
# Restores database and application files from backup
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

echo -e "${GREEN}=== News/CMS Restore ===${NC}"
echo ""

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    echo -e "${RED}Please run this script with sudo:${NC}"
    echo "sudo bash deploy/restore.sh"
    exit 1
fi

# List available backups
echo -e "${YELLOW}Available backups:${NC}"
ls -1t "$BACKUP_DIR"/db_*.sql.gz 2>/dev/null | head -10 | while read f; do
    echo "  $(basename $f | sed 's/db_//' | sed 's/.sql.gz//')"
done

echo ""
read -p "Enter backup timestamp to restore (or press Enter for latest): " TIMESTAMP

if [ -z "$TIMESTAMP" ]; then
    TIMESTAMP=$(ls -1t "$BACKUP_DIR"/db_*.sql.gz 2>/dev/null | head -1 | sed 's/.*db_//' | sed 's/.sql.gz//')
fi

# Verify backup exists
if [ ! -f "$BACKUP_DIR/db_$TIMESTAMP.sql.gz" ]; then
    echo -e "${RED}Error: Backup not found for timestamp: $TIMESTAMP${NC}"
    exit 1
fi

# Get database credentials
echo ""
read -p "Database name [$DB_NAME]: " INPUT_DB_NAME
read -p "Database user [$DB_USER]: " INPUT_DB_USER
read -sp "Database password: " INPUT_DB_PASS
echo ""

DB_NAME=${INPUT_DB_NAME:-$DB_NAME}
DB_USER=${INPUT_DB_USER:-$DB_USER}
DB_PASS=${INPUT_DB_PASS:-$DB_PASS}

# Confirm restore
echo ""
echo -e "${YELLOW}WARNING: This will REPLACE current data!${NC}"
echo "  Database: $DB_NAME"
echo "  Application: $APP_ROOT"
echo "  Backup: $TIMESTAMP"
echo ""
read -p "Continue with restore? (type 'yes' to confirm): " CONFIRM

if [ "$CONFIRM" != "yes" ]; then
    echo "Restore cancelled."
    exit 0
fi

# Restore database
echo ""
echo -e "${YELLOW}[1/3] Restoring database...${NC}"
gunzip < "$BACKUP_DIR/db_$TIMESTAMP.sql.gz" | mysql -u"$DB_USER" -p"$DB_PASS" "$DB_NAME"
echo -e "${GREEN}Database restored!${NC}"

# Restore application files
echo -e "${YELLOW}[2/3] Restoring application files...${NC}"
if [ -f "$BACKUP_DIR/files_$TIMESTAMP.tar.gz" ]; then
    tar -xzf "$BACKUP_DIR/files_$TIMESTAMP.tar.gz" -C "$APP_ROOT"
    echo -e "${GREEN}Files restored!${NC}"
else
    echo -e "${YELLOW}Files backup not found, skipping...${NC}"
fi

# Restore configuration
echo -e "${YELLOW}[3/3] Restoring configuration...${NC}"
if [ -f "$BACKUP_DIR/config_$TIMESTAMP.tar.gz" ]; then
    tar -xzf "$BACKUP_DIR/config_$TIMESTAMP.tar.gz" -C "$APP_ROOT"
    echo -e "${GREEN}Configuration restored!${NC}"
else
    echo -e "${YELLOW}Configuration backup not found, skipping...${NC}"
fi

# Fix permissions
echo ""
echo -e "${YELLOW}Fixing permissions...${NC}"
chown -R www-data:www-data "$APP_ROOT"
chmod -R 755 "$APP_ROOT"
chmod -R 777 "$APP_ROOT/writable"

# Restart Apache
echo -e "${YELLOW}Restarting Apache...${NC}"
systemctl restart apache2

echo ""
echo -e "${GREEN}=== Restore Complete! ===${NC}"
echo ""
echo "Please verify your site is working correctly."
echo ""
