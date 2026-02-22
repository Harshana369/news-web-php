#!/bin/bash
################################################################################
# SSL Certificate Setup Script using Let's Encrypt (Certbot)
# Run this after your domain is pointing to your server
################################################################################

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Configuration
APP_ROOT="/var/www/news"
DOMAIN=""

echo -e "${GREEN}=== SSL Certificate Setup ===${NC}"
echo ""

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    echo -e "${RED}Please run this script with sudo:${NC}"
    echo "sudo bash deploy/setup-ssl.sh"
    exit 1
fi

# Prompt for domain
read -p "Enter your domain name (e.g., example.com): " DOMAIN

if [ -z "$DOMAIN" ]; then
    echo -e "${RED}Error: Domain name is required${NC}"
    exit 1
fi

# Check if domain resolves to this server
SERVER_IP=$(curl -s ifconfig.me)
DOMAIN_IP=$(dig +short $DOMAIN | head -n1)

if [ "$SERVER_IP" != "$DOMAIN_IP" ]; then
    echo -e "${YELLOW}Warning: Domain $DOMAIN resolves to $DOMAIN_IP, but server IP is $SERVER_IP${NC}"
    echo "Please make sure your DNS is configured correctly before continuing."
    read -p "Continue anyway? (y/n): " CONTINUE
    if [ "$CONTINUE" != "y" ]; then
        exit 0
    fi
fi

# Install Certbot
echo -e "${YELLOW}[1/4] Installing Certbot...${NC}"
apt update
apt install -y certbot python3-certbot-apache

# Obtain certificate
echo -e "${YELLOW}[2/4] Obtaining SSL certificate...${NC}"
certbot --apache -d $DOMAIN --non-interactive --agree-tos --email admin@$DOMAIN --redirect

# Update .env with new base URL
echo -e "${YELLOW}[3/4] Updating application configuration...${NC}"
if [ -f "$APP_ROOT/.env" ]; then
    sed -i "s|app.baseURL = 'http://.*'|app.baseURL = 'https://$DOMAIN'|g" "$APP_ROOT/.env"
    echo -e "${GREEN}Base URL updated to https://$DOMAIN${NC}"
else
    echo -e "${YELLOW}Warning: .env file not found at $APP_ROOT/.env${NC}"
fi

# Setup auto-renewal
echo -e "${YELLOW}[4/4] Setting up auto-renewal...${NC}"
(crontab -l 2>/dev/null; echo "0 0 * * * certbot renew --quiet") | crontab -

# Test renewal
certbot renew --dry-run

echo ""
echo -e "${GREEN}=== SSL Setup Complete! ===${NC}"
echo ""
echo -e "${YELLOW}Your site is now accessible at:${NC}"
echo "  https://$DOMAIN"
echo ""
echo -e "${YELLOW}Certificate will auto-renew. To check status:${NC}"
echo "  sudo certbot certificates"
echo ""
