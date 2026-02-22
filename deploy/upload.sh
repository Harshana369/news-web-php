#!/bin/bash
################################################################################
# Linux/Mac Upload Script for News/CMS Application
# Uploads files to AWS Lightsail instance via SCP
################################################################################

# Default configuration - EDIT THESE VALUES
SERVER_IP="your-lightsail-ip"
SERVER_USER="ubuntu"
KEY_FILE="$HOME/.ssh/lightsail-key.pem"
SOURCE_DIR="$(dirname "$0")/../Upload"
REMOTE_PATH="/var/www/news"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo ""
echo -e "${GREEN}=== News/CMS Application Upload Script ===${NC}"
echo ""

# Check if source directory exists
if [ ! -d "$SOURCE_DIR" ]; then
    echo -e "${RED}Error: Source directory not found: $SOURCE_DIR${NC}"
    exit 1
fi

# Prompt for server IP if not set
if [ "$SERVER_IP" = "your-lightsail-ip" ]; then
    read -p "Enter your Lightsail server IP address: " SERVER_IP
fi

# Check if key file exists
if [ ! -f "$KEY_FILE" ]; then
    echo -e "${YELLOW}Key file not found: $KEY_FILE${NC}"
    read -p "Upload with password instead? (y/n): " USE_PASSWORD
    if [ "$USE_PASSWORD" = "y" ]; then
        USE_KEY=0
    else
        read -p "Enter path to your SSH key file: " KEY_FILE
        if [ ! -f "$KEY_FILE" ]; then
            echo -e "${RED}Error: Key file not found: $KEY_FILE${NC}"
            exit 1
        fi
        USE_KEY=1
    fi
else
    USE_KEY=1
fi

# Create temporary archive
echo -e "${YELLOW}[1/3] Creating deployment archive...${NC}"
TEMP_ARCHIVE=$(mktemp /tmp/news-upload-XXXX.tar.gz)
tar -czf "$TEMP_ARCHIVE" -C "$SOURCE_DIR" .

# Upload to server
echo -e "${YELLOW}[2/3] Uploading files to server...${NC}"
echo "Server: $SERVER_USER@$SERVER_IP"
echo "Destination: $REMOTE_PATH"
echo ""

if [ $USE_KEY -eq 1 ]; then
    scp -i "$KEY_FILE" "$TEMP_ARCHIVE" "$SERVER_USER@$SERVER_IP:/tmp/"
else
    scp "$TEMP_ARCHIVE" "$SERVER_USER@$SERVER_IP:/tmp/"
fi

if [ $? -ne 0 ]; then
    echo -e "${RED}Error: Upload failed!${NC}"
    rm "$TEMP_ARCHIVE"
    exit 1
fi

echo -e "${GREEN}Upload complete!${NC}"

# Extract and setup on server
echo ""
echo -e "${YELLOW}[3/3] Extracting files on server...${NC}"

if [ $USE_KEY -eq 1 ]; then
    ssh -i "$KEY_FILE" "$SERVER_USER@$SERVER_IP" "sudo mkdir -p $REMOTE_PATH && cd $REMOTE_PATH && sudo rm -rf * && sudo tar -xzf /tmp/news-upload-*.tar.gz && sudo rm /tmp/news-upload-*.tar.gz"
else
    ssh "$SERVER_USER@$SERVER_IP" "sudo mkdir -p $REMOTE_PATH && cd $REMOTE_PATH && sudo rm -rf * && sudo tar -xzf /tmp/news-upload-*.tar.gz && sudo rm /tmp/news-upload-*.tar.gz"
fi

if [ $? -ne 0 ]; then
    echo -e "${RED}Error: Extraction failed!${NC}"
    echo "You may need to manually extract the archive on the server."
    rm "$TEMP_ARCHIVE"
    exit 1
fi

# Cleanup
rm "$TEMP_ARCHIVE"

echo ""
echo -e "${GREEN}=== Upload Complete! ===${NC}"
echo ""
echo -e "${YELLOW}Next steps:${NC}"
echo "  1. SSH into your server: ssh $SERVER_USER@$SERVER_IP"
echo "  2. Run configuration: bash deploy/app-deploy.sh"
echo "  3. Access your site: http://$SERVER_IP"
echo ""
