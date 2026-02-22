#!/bin/bash
################################################################################
# AWS Lightsail Server Setup Script for News/CMS Application
# This script sets up a fresh Ubuntu instance with all required dependencies
################################################################################

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
DB_NAME="news_db"
DB_USER="news_user"
DB_PASS=$(openssl rand -base64 12)
APP_ROOT="/var/www/news"
BACKUP_DIR="/var/backups/news"

echo -e "${GREEN}=== News/CMS Application - Server Setup ===${NC}"
echo ""

################################################################################
# Step 1: System Update
################################################################################
echo -e "${YELLOW}[1/9] Updating system packages...${NC}"
sudo apt update && sudo apt upgrade -y

################################################################################
# Step 2: Install Apache, PHP, and Extensions
################################################################################
echo -e "${YELLOW}[2/9] Installing Apache and PHP 8.1+...${NC}"
sudo apt install -y \
    apache2 \
    php8.1 \
    php8.1-cli \
    php8.1-common \
    php8.1-curl \
    php8.1-gd \
    php8.1-intl \
    php8.1-mbstring \
    php8.1-mysql \
    php8.1-xml \
    php8.1-zip \
    php8.1-bcmath \
    unzip \
    git \
    curl \
    wget

################################################################################
# Step 3: Install MySQL Server
################################################################################
echo -e "${YELLOW}[3/9] Installing MySQL Server...${NC}"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password rootpass123"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password rootpass123"
sudo apt install -y mysql-server

# Start MySQL service
sudo systemctl start mysql
sudo systemctl enable mysql

################################################################################
# Step 4: Configure Apache
################################################################################
echo -e "${YELLOW}[4/9] Configuring Apache...${NC}"

# Enable required modules
sudo a2enmod rewrite
sudo a2enmod headers
sudo a2enmod ssl

# Create application directory
sudo mkdir -p $APP_ROOT

# Configure Virtual Host
sudo tee /etc/apache2/sites-available/news.conf > /dev/null <<EOF
<VirtualHost *:80>
    ServerAdmin admin@localhost
    DocumentRoot $APP_ROOT/public
    ServerName _default_

    <Directory $APP_ROOT/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/news_error.log
    CustomLog \${APACHE_LOG_DIR}/news_access.log combined
</VirtualHost>
EOF

# Disable default site and enable news site
sudo a2dissite 000-default.conf 2>/dev/null || true
sudo a2ensite news.conf
sudo systemctl restart apache2

################################################################################
# Step 5: Create Database and User
################################################################################
echo -e "${YELLOW}[5/9] Creating MySQL database and user...${NC}"

sudo mysql -u root -prootpass123 <<EOF
CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
EOF

echo -e "${GREEN}Database created:${NC}"
echo "  Database: $DB_NAME"
echo "  Username: $DB_USER"
echo "  Password: $DB_PASS"

################################################################################
# Step 6: Set Permissions
################################################################################
echo -e "${YELLOW}[6/9] Setting up directory permissions...${NC}"

sudo mkdir -p $APP_ROOT
sudo chown -R www-data:www-data $APP_ROOT
sudo chmod -R 755 $APP_ROOT

# Create writable directories with proper permissions
sudo mkdir -p $APP_ROOT/writable/{cache,logs,uploads,session}
sudo chmod -R 777 $APP_ROOT/writable
sudo chmod -R 777 $APP_ROOT/writable/cache
sudo chmod -R 777 $APP_ROOT/writable/logs
sudo chmod -R 777 $APP_ROOT/writable/uploads
sudo chmod -R 777 $APP_ROOT/writable/session

################################################################################
# Step 7: Install Composer (Optional)
################################################################################
echo -e "${YELLOW}[7/9] Installing Composer...${NC}"
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

################################################################################
# Step 8: Save Credentials
################################################################################
echo -e "${YELLOW}[8/9] Saving installation credentials...${NC}"

sudo tee $APP_ROOT/.deploy-credentials.txt > /dev/null <<EOF
=== News/CMS Deployment Credentials ===
Generated: $(date)

DATABASE:
  Database Name: $DB_NAME
  Database User: $DB_USER
  Database Password: $DB_PASS
  Database Host: localhost

APPLICATION:
  Path: $APP_ROOT
  URL: http://$(curl -s ifconfig.me)

IMPORTANT: Delete this file after setup!
EOF

sudo chmod 600 $APP_ROOT/.deploy-credentials.txt

################################################################################
# Step 9: Display Setup Summary
################################################################################
echo ""
echo -e "${GREEN}=== Setup Complete! ===${NC}"
echo ""
echo -e "${YELLOW}Next Steps:${NC}"
echo "1. Upload application files using: upload.bat (Windows) or upload.sh (Linux/Mac)"
echo "2. Update database config: $APP_ROOT/app/Config/Database.php"
echo "3. Run the web installer at: http://$(curl -s ifconfig.me)"
echo ""
echo -e "${YELLOW}Save these credentials:${NC}"
echo "  Database: $DB_NAME"
echo "  Username: $DB_USER"
echo "  Password: $DB_PASS"
echo ""
echo -e "${YELLOW}Credentials saved to:${NC} $APP_ROOT/.deploy-credentials.txt"
echo ""
echo -e "${GREEN}Server is ready for deployment!${NC}"
