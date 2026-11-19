# Guía del docente · Preparación previa (una sola vez)

Esta guía es solo para quien prepara los equipos del laboratorio. **Los alumnos no la leen.**

## Qué se instala en cada equipo (requiere administrador, una vez)

1. **Visual C++ Redistributable 2015-2022 (x64)** — PHP 8.3 lo necesita para arrancar.
2. Copiar la carpeta completa `practica\` en cada equipo (Escritorio, USB o unidad de red).

## Qué ya viene listo en la carpeta

| Elemento | Estado |
|----------|--------|
| `_entorno\php` | PHP 8.3.36 portable, listo |
| `_entorno\composer\composer.phar` | Composer listo |
| `lab-formularios\` | Proyecto Laravel 11 completo: vendor, assets compilados, `public/storage`, `.env` |
| `ABRIR CONSOLA.bat` | Abre consola con PHP y Composer listos |
| `03-practica\` | Esqueletos con los `TODO` de los 8 retos |
| `99-solucion\` | Versión resuelta (solo del facilitador) |

## En clase (sin administrador, sin terminal extra)

El alumno solo hace doble clic en **`ABRIR CONSOLA.bat`**. Desde ahí ejecuta `php artisan serve` y abre el navegador. No se pide contraseña ni se sale de la carpeta.

## Si se usa PREPARAR-EQUIPO (opcional, con admin)

El script `00-preparacion/PREPARAR-EQUIPO.bat` instala herramientas y deja el proyecto en `Documentos\LabLaravel11\lab-formularios`. Solo úsalo si necesitas regenerar el proyecto; no es necesario si ya copiaste `lab-formularios` listo.

> No compartas `99-solucion\` al inicio de la clase.
