@echo off
call "%~dp0lab-config.bat"
for /f "tokens=5" %%p in ('netstat -ano ^| findstr /c:":%PUERTO% " ^| findstr /i "LISTENING"') do taskkill /f /pid %%p >nul 2>&1
echo Servidor detenido (puerto %PUERTO%).
ping -n 3 127.0.0.1 >nul
