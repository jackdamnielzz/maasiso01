@echo off
echo Adding PHP to system PATH...

REM Add PHP to system PATH
setx /M PATH "%PATH%;D:\Downloads\php-8.3.13-nts-Win32-vs16-x64"

echo PHP has been added to PATH. Please restart your terminal to use PHP.
pause
