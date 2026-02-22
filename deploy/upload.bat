@echo off
################################################################################
# Windows Upload Script for News/CMS Application
# Uploads files to AWS Lightsail instance via SCP
################################################################################

setlocal enabledelayedexpansion

:: Default configuration - EDIT THESE VALUES
set SERVER_IP=your-lightsail-ip
set SERVER_USER=ubuntu
set KEY_FILE=%USERPROFILE%\.ssh\lightsail-key.pem
set SOURCE_DIR=%~dp0..\Upload
set REMOTE_PATH=/var/www/news

:: Colors (for Windows 10+)
for /F %%a in ('echo prompt $E ^| cmd') do set "ESC=%%a"
set GREEN=%ESC%[0;32m
set YELLOW=%ESC%[1;33m
set RED=%ESC%[0;31m
set NC=%ESC%[0m

echo.
echo %GREEN%=== News/CMS Application Upload Script ===%NC%
echo.

:: Check if source directory exists
if not exist "%SOURCE_DIR%" (
    echo %RED%Error: Source directory not found: %SOURCE_DIR%%NC%
    echo Please check the path and try again.
    pause
    exit /b 1
)

:: Check if SCP is available
where scp >nul 2>&1
if %errorlevel% neq 0 (
    echo %RED%Error: scp command not found!%NC%
    echo.
    echo Please install OpenSSH Client:
    echo   Settings ^> Apps ^> Optional Features ^> Add Feature ^> OpenSSH Client
    echo.
    echo Or use Git Bash / Windows Subsystem for Linux.
    pause
    exit /b 1
)

:: Prompt for server IP if not set
if "%SERVER_IP%"=="your-lightsail-ip" (
    echo %YELLOW%Enter your Lightsail server IP address:%NC%
    set /p SERVER_IP="IP: "
)

:: Check if key file exists
if not exist "%KEY_FILE%" (
    echo %YELLOW%Key file not found: %KEY_FILE%%NC%
    echo.
    set /p USE_PASSWORD="Upload with password instead? (y/n): "
    if /i "!USE_PASSWORD!"=="y" (
        set USE_KEY=0
    ) else (
        echo.
        set /p KEY_FILE="Enter path to your SSH key file: "
        if not exist "!KEY_FILE!" (
            echo %RED%Error: Key file not found: !KEY_FILE!%NC%
            pause
            exit /b 1
        )
        set USE_KEY=1
    )
) else (
    set USE_KEY=1
)

:: Create a temporary archive
echo.
echo %YELLOW%[1/3] Creating deployment archive...%NC%
set TEMP_ARCHIVE=%TEMP%\news-upload-%RANDOM%.tar.gz

:: Use tar if available (Windows 10+), otherwise use PowerShell
where tar >nul 2>&1
if %errorlevel% equ 0 (
    pushd "%SOURCE_DIR%"
    tar -czf "%TEMP_ARCHIVE%" .
    popd
) else (
    echo %YELLOW%Using PowerShell to create archive...%NC%
    powershell -Command "& { Compress-Archive -Path '%SOURCE_DIR%\*' -DestinationPath '%TEMP_ARCHIVE%.zip' -Force }"
    set TEMP_ARCHIVE=%TEMP_ARCHIVE%.zip
)

echo %GREEN%Archive created: %TEMP_ARCHIVE%%NC%

:: Upload to server
echo.
echo %YELLOW%[2/3] Uploading files to server...%NC%
echo Server: %SERVER_USER%@%SERVER_IP%
echo Destination: %REMOTE_PATH%
echo.

if %USE_KEY%==1 (
    scp -i "%KEY_FILE%" "%TEMP_ARCHIVE%" "%SERVER_USER%@%SERVER_IP%:/tmp/"
) else (
    scp "%TEMP_ARCHIVE%" "%SERVER_USER%@%SERVER_IP%:/tmp/"
)

if %errorlevel% neq 0 (
    echo %RED%Error: Upload failed!%NC%
    pause
    exit /b 1
)

echo %GREEN%Upload complete!%NC%

:: Extract and setup on server
echo.
echo %YELLOW%[3/3] Extracting files on server...%NC%

if %USE_KEY%==1 (
    ssh -i "%KEY_FILE%" "%SERVER_USER%@%SERVER_IP%" "sudo mkdir -p %REMOTE_PATH% && cd %REMOTE_PATH% && sudo rm -rf * && sudo tar -xzf /tmp/news-upload-*.tar.gz || sudo unzip -o /tmp/news-upload-*.zip && sudo rm /tmp/news-upload-*.tar.gz /tmp/news-upload-*.zip 2>/dev/null"
) else (
    ssh "%SERVER_USER%@%SERVER_IP%" "sudo mkdir -p %REMOTE_PATH% && cd %REMOTE_PATH% && sudo rm -rf * && sudo tar -xzf /tmp/news-upload-*.tar.gz || sudo unzip -o /tmp/news-upload-*.zip && sudo rm /tmp/news-upload-*.tar.gz /tmp/news-upload-*.zip 2>/dev/null"
)

if %errorlevel% neq 0 (
    echo %RED%Error: Extraction failed!%NC%
    echo You may need to manually extract the archive on the server.
    pause
    exit /b 1
)

:: Cleanup
del "%TEMP_ARCHIVE%" 2>nul

echo.
echo %GREEN%=== Upload Complete! ===%NC%
echo.
echo %YELLOW%Next steps:%NC%
echo   1. SSH into your server: ssh %SERVER_USER%@%SERVER_IP%
echo   2. Run configuration: bash deploy/app-deploy.sh
echo   3. Access your site: http://%SERVER_IP%
echo.

pause
