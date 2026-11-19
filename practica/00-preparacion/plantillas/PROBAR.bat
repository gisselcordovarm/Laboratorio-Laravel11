@echo off
call "%~dp0lab-config.bat"
cd /d "%~dp0"
echo.
echo  Ejecutando las pruebas del laboratorio...
echo.
"%PHP%" artisan test --compact
echo.
echo  Recuerda: las 5 pruebas del Reto 8 fallan hasta que las escribas.
echo.
pause
