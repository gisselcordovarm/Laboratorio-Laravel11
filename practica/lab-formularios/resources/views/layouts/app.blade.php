<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Laboratorio de formularios críticos en Laravel 11: validación, CSRF y rate limiting.">
    <meta name="theme-color" content="#f7f3ec">

    <title>@yield('title', 'Registro de producto seguro') · Lab Laravel 11</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..600;1,9..144,300..600&family=Inter:wght@300..600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    <a href="#contenido" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-full focus:bg-forest focus:px-5 focus:py-3 focus:text-sm focus:text-paper">
        Saltar al contenido
    </a>

    <header class="border-b border-line/70 bg-paper/70 backdrop-blur-sm">
        <div class="shell flex h-20 items-center justify-between gap-6">
            <a href="{{ route('products.create') }}" class="group flex items-center gap-3.5">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-forest font-display text-lg text-paper shadow-lift transition group-hover:bg-forest-deep">
                    L<span class="text-[10px] align-super">11</span>
                </span>
                <span class="leading-tight">
                    <span class="block font-display text-[17px] tracking-tight text-ink">Formularios seguros</span>
                    <span class="block text-[10px] font-medium uppercase tracking-[0.22em] text-muted">Laboratorio Laravel 11</span>
                </span>
            </a>

            <nav class="hidden items-center gap-6 text-[12px] font-medium text-muted sm:flex">
                <a href="#protocolo" class="transition hover:text-forest">Protocolo de pruebas</a>
                <span class="h-4 w-px bg-line"></span>
                <span class="inline-flex items-center gap-2">
                    <span class="h-1.5 w-1.5 rounded-full bg-forest/70"></span>
                    Servidor local
                </span>
            </nav>
        </div>
    </header>

    <main id="contenido">
        @yield('content')
    </main>

    <footer class="border-t border-line/70">
        <div class="shell flex flex-col items-start justify-between gap-3 py-8 text-[11px] text-muted sm:flex-row sm:items-center">
            <p class="uppercase tracking-[0.18em]">Programación IV · IUTAJS</p>
            <p class="font-mono text-[10px] text-muted/80">
                Laravel {{ app()->version() }} · PHP {{ PHP_VERSION }} · sesión local
            </p>
        </div>
    </footer>
</body>
</html>
