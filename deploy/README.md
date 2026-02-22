# News/CMS Deployment Scripts

Automated deployment scripts for hosting the News/CMS application on AWS Lightsail.

## Note: License Code

This version has the license verification bypassed in `install/functions.php`. During installation:
- Enter **any text** as the "License Code" (e.g., `bypassed` or `test123`)
- The installer will accept it and proceed
- The license is only checked during installation, not in the running application

## Prerequisites

1. **AWS Lightsail Instance** running Ubuntu 22.04+
2. **SSH Access** to your Lightsail instance
3. **For Windows**: OpenSSH Client (Windows 10+) or Git Bash / WSL

## Quick Start

### Option 1: One-Click Deployment (Windows)

```batch
# Edit deploy.bat with your server IP and key file, then run:
deploy\deploy.bat
```

### Option 2: Step-by-Step Deployment

#### Step 1: Initial Server Setup

**Windows:**
```batch
deploy\deploy-server.bat
```

**Linux/Mac:**
```bash
# Copy server-setup.sh to server and run:
scp deploy/server-setup.sh ubuntu@your-ip:~
ssh ubuntu@your-ip "bash server-setup.sh"
```

#### Step 2: Upload Application Files

**Windows:**
```batch
# Edit upload.bat with your server details, then:
deploy\upload.bat
```

**Linux/Mac:**
```bash
# Edit upload.sh with your server details, then:
bash deploy/upload.sh
```

#### Step 3: Configure Application

```bash
ssh ubuntu@your-ip
bash ~/deploy/app-deploy.sh
```

## Script Descriptions

| Script | Platform | Purpose |
|--------|----------|---------|
| `server-setup.sh` | Linux | Installs Apache, PHP, MySQL, creates database |
| `app-deploy.sh` | Linux | Configures database settings and permissions |
| `upload.bat` | Windows | Uploads files via SCP from Windows |
| `upload.sh` | Linux/Mac | Uploads files via SCP from Linux/Mac |
| `deploy.bat` | Windows | One-click complete deployment |
| `deploy.sh` | Linux/Mac | One-click complete deployment |

## Configuration

### Edit Configuration Files

Before running, edit these values in the scripts:

```bash
SERVER_IP="1.2.3.4"      # Your Lightsail public IP
SERVER_USER="ubuntu"      # Default user
KEY_FILE="~/.ssh/key.pem" # Path to your SSH key
```

### Database Credentials

The setup script automatically generates secure credentials. After setup, find them at:
```
/var/www/news/.deploy-credentials.txt
```

**Important**: Delete this file after setup!

## Troubleshooting

### Connection Refused

```bash
# Check security group allows SSH (port 22)
# Verify your key file permissions:
chmod 400 ~/.ssh/your-key.pem
```

### Permission Denied

```bash
# On the server, fix permissions:
sudo chown -R www-data:www-data /var/www/news
sudo chmod -R 777 /var/www/news/writable
```

### Database Connection Failed

```bash
# Check MySQL is running:
sudo systemctl status mysql

# Verify credentials in:
cat /var/www/news/.deploy-credentials.txt
```

### 500 Internal Server Error

```bash
# Check Apache error logs:
sudo tail -f /var/log/apache2/news_error.log

# Check .htaccess is enabled:
sudo a2enmod rewrite && sudo systemctl restart apache2
```

## Post-Deployment Checklist

- [ ] Access site at `http://your-ip`
- [ ] Run web installer (follow steps below)
- [ ] Delete `.deploy-credentials.txt` file
- [ ] Configure SSL with Let's Encrypt:
  ```bash
  sudo apt install certbot python3-certbot-apache -y
  sudo certbot --apache -d yourdomain.com
  ```
- [ ] Set up domain DNS (A record pointing to your IP)
- [ ] Configure firewall if needed

## Web Installer Steps

After accessing your site, you'll see the installer:

1. **License Step**
   - Enter any text as "License Code" (e.g., `bypassed`)
   - Click "Next"

2. **System Requirements**
   - Review that all requirements are met (green checkmarks)
   - Click "Next"

3. **Folder Permissions**
   - Ensure all folders show as writable
   - If not, run: `sudo chmod -R 777 /var/www/news/writable`
   - Click "Next"

4. **Database**
   - Use credentials from `/var/www/news/.deploy-credentials.txt`
   - Or enter your database details:
     - Database Host: `localhost`
     - Database Name: `news_db`
     - Database Username: `news_user`
     - Database Password: (from credentials file)
   - Click "Install"

5. **Admin Account**
   - Create your admin username, email, and password
   - Click "Finish"

6. **Done!**
   - Your site is now ready
   - Access admin at `http://your-ip/admin`

## Manual Setup (If Scripts Fail)

```bash
# 1. Install LAMP stack
sudo apt update
sudo apt install apache2 mysql-server php8.1 php8.1-mysql -y

# 2. Create database
sudo mysql
CREATE DATABASE news_db;
CREATE USER 'news_user'@'localhost' IDENTIFIED BY 'password';
GRANT ALL ON news_db.* TO 'news_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# 3. Upload files to /var/www/html
# 4. Update app/Config/Database.php
# 5. Set permissions
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 777 /var/www/html/writable
```

## Security Recommendations

1. **Change default passwords** immediately after setup
2. **Enable UFW firewall**: `sudo ufw allow 80/tcp && sudo ufw allow 443/tcp && sudo ufw enable`
3. **Disable root login**: Edit `/etc/ssh/sshd_config`
4. **Keep system updated**: `sudo apt update && sudo apt upgrade -y`
5. **Enable SSL**: Use Let's Encrypt for HTTPS

## Support

For issues with:
- **AWS Lightsail**: https://aws.amazon.com/lightsail/support/
- **CodeIgniter 4**: https://codeigniter4.com/
