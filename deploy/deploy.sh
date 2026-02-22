#!/bin/bash
################################################################################
# Linux/Mac One-Click Deployment Script
# Runs complete deployment including server setup and app configuration
################################################################################

# Configuration - EDIT THESE VALUES
SERVER_IP="your-lightsail-ip"
SERVER_USER="ubuntu"
KEY_FILE="$HOME/.ssh/lightsail-key.pem"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo ""
echo -e "${GREEN}=== News/CMS One-Click Deployment ===${NC}"
echo ""

# Prompt for server IP
if [ "$SERVER_IP" = "your-lightsail-ip" ]; then
    read -p "Enter your Lightsail server IP address: " SERVER_IP
fi

# Check if key file exists
if [ ! -f "$KEY_FILE" ]; then
    echo -e "${YELLOW}Key file not found: $KEY_FILE${NC}"
    read -p "Enter path to your SSH key file: " KEY_FILE
    if [ ! -f "$KEY_FILE" ]; then
        echo -e "${RED}Error: Key file not found!${NC}"
        exit 1
    fi
fi

echo ""
echo -e "${YELLOW}This will:${NC}"
echo "  1. Run server setup (install Apache, PHP, MySQL)"
echo "  2. Configure database and Apache"
echo "  3. Upload application files"
echo "  4. Configure the application"
echo ""
echo -e "${YELLOW}Server: $SERVER_IP${NC}"
echo ""
read -p "Continue? (y/n): " CONFIRM

if [ "$CONFIRM" != "y" ]; then
    echo "Deployment cancelled."
    exit 0
fi

SCRIPT_DIR="$(dirname "$0")"

# Create deploy directory on server
echo ""
echo -e "${YELLOW}[1/5] Creating deploy directory on server...${NC}"
ssh -i "$KEY_FILE" "$SERVER_USER@$SERVER_IP" "mkdir -p ~/deploy" || {
    echo -e "${RED}Failed to create deploy directory${NC}"
    exit 1
}

# Upload setup scripts
echo ""
echo -e "${YELLOW}[2/5] Uploading setup scripts...${NC}"
scp -i "$KEY_FILE" "$SCRIPT_DIR/server-setup.sh" "$SERVER_USER@$SERVER_IP":~/deploy/
scp -i "$KEY_FILE" "$SCRIPT_DIR/app-deploy.sh" "$SERVER_USER@$SERVER_IP":~/deploy/

# Run server setup
echo ""
echo -e "${YELLOW}[3/5] Running server setup (this may take 5-10 minutes)...${NC}"
ssh -i "$KEY_FILE" "$SERVER_USER@$SERVER_IP" "bash ~/deploy/server-setup.sh" || {
    echo -e "${RED}Server setup failed!${NC}"
    exit 1
}

# Upload application files
echo ""
echo -e "${YELLOW}[4/5] Uploading application files...${NC}"
bash "$SCRIPT_DIR/upload.sh" || {
    echo -e "${RED}File upload failed!${NC}"
    exit 1
}

# Configure application
echo ""
echo -e "${YELLOW}[5/5] Configuring application...${NC}"
ssh -i "$KEY_FILE" "$SERVER_USER@$SERVER_IP" "bash ~/deploy/app-deploy.sh" || {
    echo -e "${RED}Application configuration failed!${NC}"
    exit 1
}

echo ""
echo -e "${GREEN}=== Deployment Complete! ===${NC}"
echo ""
echo -e "${YELLOW}Your application should be accessible at:${NC}"
echo "  http://$SERVER_IP"
echo ""
echo -e "${YELLOW}Don't forget to:${NC}"
echo "  - Delete /var/www/news/.deploy-credentials.txt"
echo "  - Run the web installer to complete setup"
echo ""
