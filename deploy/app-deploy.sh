#!/bin/bash
################################################################################
# Application Deployment Script
# Run this AFTER uploading files to configure the application
################################################################################

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Configuration
APP_ROOT="/var/www/news"
DB_CONFIG="$APP_ROOT/app/Config/Database.php"
ENV_FILE="$APP_ROOT/.env"

echo -e "${GREEN}=== News/CMS Application Configuration ===${NC}"
echo ""

################################################################################
# Check if files exist
################################################################################
if [ ! -d "$APP_ROOT" ]; then
    echo -e "${RED}Error: Application directory not found!${NC}"
    echo "Please upload files first using upload.bat or upload.sh"
    exit 1
fi

################################################################################
# Load credentials if saved
################################################################################
if [ -f "$APP_ROOT/.deploy-credentials.txt" ]; then
    source <(grep -E "Database (Name|User|Password):" "$APP_ROOT/.deploy-credentials.txt" | sed 's/.*: //')
fi

################################################################################
# Prompt for database credentials
################################################################################
echo -e "${YELLOW}Enter database credentials:${NC}"
read -p "Database Name [$DB_NAME]: " INPUT_DB_NAME
read -p "Database User [$DB_USER]: " INPUT_DB_USER
read -sp "Database Password: " INPUT_DB_PASS
echo ""

DB_NAME=${INPUT_DB_NAME:-$DB_NAME}
DB_USER=${INPUT_DB_USER:-$DB_USER}
DB_PASS=${INPUT_DB_PASS:-$DB_PASS}

################################################################################
# Update Database Configuration
################################################################################
echo -e "${YELLOW}Updating database configuration...${NC}"

# Backup original config
if [ -f "$DB_CONFIG" ]; then
    sudo cp "$DB_CONFIG" "$DB_CONFIG.bak"
fi

# Update database configuration using PHP
sudo tee /tmp/update-db-config.php > /dev/null <<'PHPEOF'
<?php
$configFile = $argv[1];
$dbName = $argv[2];
$dbUser = $argv[3];
$dbPass = $argv[4];

$content = file_get_contents($configFile);
$content = preg_replace("/'database'\s*=>\s*'[^']*'/", "'database' => '$dbName'", $content);
$content = preg_replace("/'username'\s*=>\s*'[^']*'/", "'username' => '$dbUser'", $content);
$content = preg_replace("/'password'\s*=>\s*'[^']*'/", "'password' => '$dbPass'", $content);
file_put_contents($configFile, $content);
PHPEOF

php /tmp/update-db-config.php "$DB_CONFIG" "$DB_NAME" "$DB_USER" "$DB_PASS"
rm /tmp/update-db-config.php

echo -e "${GREEN}Database configuration updated!${NC}"

################################################################################
# Set Environment to Production
################################################################################
echo -e "${YELLOW}Configuring production environment...${NC}"

if [ ! -f "$ENV_FILE" ]; then
    sudo tee "$ENV_FILE" > /dev/null <<EOF
#--------------------------------------------------------------------
# ENVIRONMENT
#--------------------------------------------------------------------
CI_ENVIRONMENT = production

#--------------------------------------------------------------------
# APP
#--------------------------------------------------------------------
app.baseURL = 'http://$(curl -s ifconfig.me)'

#--------------------------------------------------------------------
# LICENSE (not used, but required by installer)
#--------------------------------------------------------------------
PURCHASE_CODE = bypassed
LICENSE_KEY = bypassed

#--------------------------------------------------------------------
# COOKIE
#--------------------------------------------------------------------
cookie.prefix = 'inf_'

#--------------------------------------------------------------------
# DATABASE (auto-configured)
#--------------------------------------------------------------------
database.default.hostname = localhost
database.default.database = $DB_NAME
database.default.username = $DB_USER
database.default.password = $DB_PASS
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
database.default.charset = utf8
database.default.DBCollat = utf8_general_ci
EOF
    echo -e "${GREEN}.env file created!${NC}"
else
    echo -e "${YELLOW}.env file already exists, skipping...${NC}"
fi

################################################################################
# Final Permission Setup
################################################################################
echo -e "${YELLOW}Setting final permissions...${NC}"

sudo chown -R www-data:www-data $APP_ROOT
sudo chmod -R 755 $APP_ROOT
sudo chmod -R 777 $APP_ROOT/writable

################################################################################
# Create .htaccess for pretty URLs
################################################################################
echo -e "${YELLOW}Configuring URL rewriting...${NC}"

sudo tee "$APP_ROOT/public/.htaccess" > /dev/null <<'EOF'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>

<FilesMatch "\.(php|phtml)$">
    Order Allow,Deny
    Allow from all
</FilesMatch>
EOF

################################################################################
# Restart Apache
################################################################################
echo -e "${YELLOW}Restarting Apache...${NC}"
sudo systemctl restart apache2

################################################################################
# Display Summary
################################################################################
echo ""
echo -e "${GREEN}=== Deployment Complete! ===${NC}"
echo ""
echo -e "${YELLOW}Your application should be accessible at:${NC}"
echo "  http://$(curl -s ifconfig.me)"
echo ""
echo -e "${YELLOW}Admin login (if setup completed):${NC}"
echo "  http://$(curl -s ifconfig.me)/admin"
echo ""
echo -e "${YELLOW}To check logs:${NC}"
echo "  sudo tail -f /var/log/apache2/news_error.log"
echo ""
