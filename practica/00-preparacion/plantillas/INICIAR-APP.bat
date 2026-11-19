@echo off
title Lab Laravel 11 - iniciar
call "%~dp0lab-config.bat"
cd /d "%~dp0"

rem Si el servidor ya esta corriendo, solo abre el navegador.
netstat -ano | findstr /c:":%PUERTO% " | findstr /i "LISTENING" >nul 2>&1
if not errorlevel 1 goto abrir

start "Lab Laravel 11 - servidor" /min "%PHP%" artisan serve --host=127.0.0.1 --port=%PUERTO%

rem Espera hasta 20 segundos a que el servidor responda.
for /l %%i in (1,1,20) do (
  curl -s --max-time 3 -o NUL "http://127.0.0.1:%PUERTO%/products/create" && goto abrir
  ping -n 2 127.0.0.1 >nul
)

echo.
echo El servidor no respondio.
echo Ejecuta VERIFICAR-ENTORNO.bat y avisa al facilitador.
echo.
pause
exit /b 1

:abrir
start "" "http://127.0.0.1:%PUERTO%/products/create"
exit /b 0
