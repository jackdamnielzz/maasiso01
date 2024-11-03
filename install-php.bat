@echo off
echo Installing PHP...

REM Create PHP directory
mkdir "C:\Program Files\PHP" 2>nul
if errorlevel 1 (
    echo Please run this script as Administrator
    pause
    exit /b 1
)

REM Extract PHP files
powershell -command "Expand-Archive -Path 'D:\Downloads\php-8.3.13-nts-Win32-vs16-x64.zip' -DestinationPath 'C:\Program Files\PHP' -Force"

REM Create php.ini
copy "C:\Program Files\PHP\php.ini-development" "C:\Program Files\PHP\php.ini"

REM Enable required extensions
powershell -command "(Get-Content 'C:\Program Files\PHP\php.ini') -replace ';extension=openssl', 'extension=openssl' | Set-Content 'C:\Program Files\PHP\php.ini'"
powershell -command "(Get-Content 'C:\Program Files\PHP\php.ini') -replace ';extension=mbstring', 'extension=mbstring' | Set-Content 'C:\Program Files\PHP\php.ini'"
powershell -command "(Get-Content 'C:\Program Files\PHP\php.ini') -replace ';extension=pdo_mysql', 'extension=pdo_mysql' | Set-Content 'C:\Program Files\PHP\php.ini'"

REM Add PHP to system PATH
setx /M PATH "%PATH%;C:\Program Files\PHP"

echo PHP installation complete!
echo Installing Composer...

REM Download and install Composer
powershell -command "Invoke-WebRequest -Uri 'https://getcomposer.org/Composer-Setup.exe' -OutFile 'D:\Downloads\Composer-Setup.exe'"
D:\Downloads\Composer-Setup.exe /SILENT

echo Installation complete! Please restart your terminal to use PHP and Composer.
pause
