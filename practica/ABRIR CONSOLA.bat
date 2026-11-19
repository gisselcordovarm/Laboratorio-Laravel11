@echo off
title Laboratorio Laravel 11 - PHP 8.3 (portable)
set BASE_DIR=%~dp0

:: Mantener temporales y cache de Composer dentro de la carpeta
:: (no escribe nada fuera, no necesita permisos de administrador)
set TEMP=%BASE_DIR%_entorno\temp
set TMP=%BASE_DIR%_entorno\temp
set COMPOSER_HOME=%BASE_DIR%_entorno\composer\cache

:: PHP y Composer disponibles solo en esta ventana
set PATH=%BASE_DIR%_entorno\php;%BASE_DIR%_entorno\composer;%PATH%
doskey php=php -d sys_temp_dir="%BASE_DIR%_entorno\temp" $*
doskey composer=php -d sys_temp_dir="%BASE_DIR%_entorno\temp" "%BASE_DIR%_entorno\composer\composer.phar" $*

:: Entrar directamente al proyecto listo para usar
cd /d "%BASE_DIR%lab-formularios"

cls
echo ========================================================
echo   ENTORNO DE PRACTICA LISTO (PHP 8.3 + COMPOSER)
echo ========================================================
echo.
php -v
echo.
echo   Paso 1: levanta el servidor
echo       php artisan serve
echo.
echo   Paso 2: abre el navegador en
echo       http://127.0.0.1:8000/products/create
echo.
echo   Para ejecutar las pruebas: php artisan test
echo   Para detener el servidor: Ctrl + C
echo ========================================================
echo.
cmd /k
