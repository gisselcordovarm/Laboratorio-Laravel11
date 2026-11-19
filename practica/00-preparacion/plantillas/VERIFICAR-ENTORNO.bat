@echo off
title Lab Laravel 11 - verificacion
call "%~dp0lab-config.bat"
cd /d "%~dp0"
echo.
echo  Comprobando el laboratorio...
echo.

if exist "%PHP%" (echo  [OK]    PHP encontrado) else (echo  [X]     PHP no encontrado)
if exist "artisan" (echo  [OK]    Proyecto Laravel) else (echo  [X]     Falta artisan)
if exist "public\build\manifest.json" (echo  [OK]    Assets compilados) else (echo  [X]     Falta public\build)
if exist "public\storage" (echo  [OK]    Enlace de storage) else (echo  [X]     Falta el enlace public\storage)

netstat -ano | findstr /c:":%PUERTO% " | findstr /i "LISTENING" >nul 2>&1
if not errorlevel 1 (
  echo  [OK]    Servidor en marcha en el puerto %PUERTO%
) else (
  echo  [i]     Servidor detenido: usa INICIAR-APP.bat
)

echo.
echo  Si todo esta en OK, cierra esta ventana y abre INICIAR-APP.bat.
echo.
pause
