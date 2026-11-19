@echo off
setlocal
title Lab Laravel 11 - preparacion del equipo

rem Si no hay permisos de administrador, se relanza con UAC.
net session >nul 2>&1
if %errorlevel% equ 0 goto ejecutar

echo.
echo  Se necesitan permisos de administrador una sola vez,
echo  antes de la clase (para el enlace de storage).
echo.
if "%~1"=="" (
  powershell -NoProfile -Command "Start-Process -FilePath '%~f0' -Verb RunAs"
) else (
  powershell -NoProfile -Command "Start-Process -FilePath '%~f0' -ArgumentList '%*' -Verb RunAs"
)
exit /b

:ejecutar
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0PREPARAR-EQUIPO.ps1" %*
echo.
pause
