@echo off
################################################################################
# Windows One-Click Deployment Script
# Runs server setup and app configuration via SSH
################################################################################

setlocal enabledelayedexpansion

:: Configuration
set SERVER_IP=your-lightsail-ip
set SERVER_USER=ubuntu
set KEY_FILE=%USERPROFILE%\.ssh\lightsail-key.pem

:: Colors
for /F %%a in ('echo prompt $E ^| cmd') do set "ESC=%%a"
set GREEN=%ESC%[0;32m
set YELLOW=%ESC%[1;33m
set RED=%ESC%[0;31m
set NC=%ESC%[0m

echo.
echo %GREEN%=== News/CMS One-Click Deployment ===%NC%
echo.

:: Prompt for server IP
if "%SERVER_IP%"=="your-lightsail-ip" (
    echo %YELLOW%Enter your Lightsail server IP address:%NC%
    set /p SERVER_IP="IP: "
)

:: Check if key file exists
if not exist "%KEY_FILE%" (
    echo %YELLOW%Key file not found: %KEY_FILE%%NC%
    set /p KEY_FILE="Enter path to your SSH key file: "
)

echo.
echo %YELLOW%This will:%NC%
echo   1. Run server setup (install Apache, PHP, MySQL)
echo   2. Configure database and Apache
echo   3. Upload application files
echo   4. Configure the application
echo.
echo %YELLOW%Server: %SERVER_IP%%NC%
echo.
set /p CONFIRM="Continue? (y/n): "

if /i not "%CONFIRM%"=="y" (
    echo Deployment cancelled.
    pause
    exit /b 0
)

:: Create deploy directory on server
echo.
echo %YELLOW%[1/5] Creating deploy directory on server...%NC%
ssh -i "%KEY_FILE%" "%SERVER_USER%@%SERVER_IP%" "mkdir -p ~/deploy"

:: Upload setup scripts
echo.
echo %YELLOW%[2/5] Uploading setup scripts...%NC"
scp -i "%KEY_FILE%" "%~dp0server-setup.sh" "%SERVER_USER%@%SERVER_IP":~/deploy/
scp -i "%KEY_FILE%" "%~dp0app-deploy.sh" "%SERVER_USER%@%SERVER_IP":~/deploy/

:: Run server setup
echo.
echo %YELLOW%[3/5] Running server setup (this may take 5-10 minutes)...%NC%
ssh -i "%KEY_FILE%" "%SERVER_USER%@%SERVER_IP%" "bash ~/deploy/server-setup.sh"

if %errorlevel% neq 0 (
    echo %RED%Server setup failed!%NC%
    pause
    exit /b 1
)

:: Upload application files
echo.
echo %YELLOW%[4/5] Uploading application files...%NC%
call "%~dp0upload.bat"

if %errorlevel% neq 0 (
    echo %RED%File upload failed!%NC%
    pause
    exit /b 1
)

:: Configure application
echo.
echo %YELLOW%[5/5] Configuring application...%NC%
ssh -i "%KEY_FILE%" "%SERVER_USER%@%SERVER_IP%" "bash ~/deploy/app-deploy.sh"

if %errorlevel% neq 0 (
    echo %RED%Application configuration failed!%NC%
    pause
    exit /b 1
)

echo.
echo %GREEN%=== Deployment Complete! ===%NC%
echo.
echo %YELLOW%Your application should be accessible at:%NC%
echo   http://%SERVER_IP%
echo.
echo %YELLOW%Don't forget to:%NC%
echo   - Delete /var/www/news/.deploy-credentials.txt
echo   - Run the web installer to complete setup
echo.

pause
