@echo off
call "%~dp0lab-config.bat"
cd /d "%~dp0"
"%PHP%" artisan cache:clear
echo.
echo Contador del throttle reiniciado. Ya puedes volver a enviar el formulario.
ping -n 4 127.0.0.1 >nul
