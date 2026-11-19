@extends('layouts.app')

@section('title', 'Registro de producto')

{{--
|--------------------------------------------------------------------------
| PUNTO DE PARTIDA · vista sin defensas
|--------------------------------------------------------------------------
| Este formulario funciona, pero le faltan las cuatro piezas que veremos
| en clase:
|
|   1. @csrf            → sin él, el POST responde 419 (Page Expired).
|   2. old('campo')     → sin él, se pierde lo escrito tras un error.
|   3. @error('campo')  → sin él, no se muestra ningún mensaje.
|   4. aria-invalid    → sin él, el error no se anuncia a lectores de pantalla.
|
| No las añadas todavía: primero comprueba cómo se comporta así.
|--------------------------------------------------------------------------
--}}

@section('content')
    <div class="shell pb-24 pt-10 sm:pt-14">
        <div class="grid items-start gap-12 lg:grid-cols-[1fr_1fr] lg:gap-16">

            {{-- Columna editorial --}}
            <section class="fade-up lg:sticky lg:top-10">
                <p class="eyebrow">Laboratorio · Formularios críticos</p>

                <h1 class="mt-4 font-display text-[2.6rem] font-light leading-[1.02] tracking-tight text-ink text-balance sm:text-6xl">
                    Registro de<br>
                    <em class="font-normal italic text-forest">producto</em>
                </h1>

                <p class="mt-6 max-w-md text-[15px] leading-relaxed text-muted">
                    Versión inicial del módulo: el formulario existe y guarda datos,
                    pero todavía no valida nada y no tiene ninguna defensa activa.
                </p>

                <div class="mt-7 flex flex-wrap gap-2">
                    <span class="chip"><span class="h-1.5 w-1.5 rounded-full bg-brick"></span> Sin CSRF</span>
                    <span class="chip"><span class="h-1.5 w-1.5 rounded-full bg-brick"></span> Sin validación</span>
                    <span class="chip"><span class="h-1.5 w-1.5 rounded-full bg-brick"></span> Sin throttle</span>
                </div>

                <div class="mt-10 hidden items-center gap-3 text-[11px] text-muted/80 lg:flex">
                    <span class="h-px w-8 bg-line"></span>
                    <span class="font-mono">php artisan serve</span>
                    <span>·</span>
                    <span>http://127.0.0.1:8000</span>
                </div>
            </section>

            {{-- Columna funcional --}}
            <section class="fade-up-delay">

                @if (session('success'))
                    <div class="mb-6 rounded-3xl border border-brick/20 bg-brick/[0.045] p-5">
                        <p class="eyebrow text-brick/70">Sin validación</p>
                        <h2 class="mt-1 font-display text-[20px] leading-tight text-brick">
                            {{ session('success') }}
                        </h2>
                        <p class="mt-2 text-[12.5px] leading-relaxed text-muted">
                            El servidor aceptó lo que llegó. Nada comprobó el tipo,
                            el formato ni el tamaño de los datos.
                        </p>
                    </div>
                @endif

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
                      class="card p-6 sm:p-8" data-product-form novalidate>

                    <div class="flex items-start justify-between gap-4 border-b border-line/80 pb-6">
                        <div>
                            <p class="eyebrow">Versión inicial</p>
                            <h2 class="mt-2 font-display text-[26px] leading-none text-ink">Nuevo producto</h2>
                        </div>
                        <span class="chip shrink-0 font-mono text-[10px]">
                            <span class="h-1.5 w-1.5 rounded-full bg-brick"></span>
                            sin defensas
                        </span>
                    </div>

                    <div class="mt-6 grid gap-5 sm:grid-cols-2">

                        {{-- Código --}}
                        <div>
                            <div class="field-label">
                                <label for="code">Código</label>
                                <span class="font-mono text-[10px] font-normal tracking-normal text-muted/70" data-counter-for="code">0/10</span>
                            </div>
                            <input id="code" name="code" type="text" maxlength="10"
                                   placeholder="PRD001"
                                   autocomplete="off" autocapitalize="characters" spellcheck="false"
                                   class="input">
                            <p class="hint">Letras y números · máx. 10</p>
                        </div>

                        {{-- Precio --}}
                        <div>
                            <div class="field-label">
                                <label for="price">Precio</label>
                                <span class="font-mono text-[10px] font-normal tracking-normal text-muted/70">USD</span>
                            </div>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 font-mono text-[13px] text-muted/70" aria-hidden="true">$</span>
                                <input id="price" name="price" type="text" inputmode="decimal"
                                       placeholder="0.00"
                                       autocomplete="off"
                                       class="input pl-8">
                            </div>
                            <p class="hint">Valor numérico mayor a 0.01</p>
                        </div>

                        {{-- Nombre --}}
                        <div class="sm:col-span-2">
                            <div class="field-label">
                                <label for="name">Nombre del producto</label>
                                <span class="font-mono text-[10px] font-normal tracking-normal text-muted/70" data-counter-for="name">0/100</span>
                            </div>
                            <input id="name" name="name" type="text" maxlength="100"
                                   placeholder="Ej. Audífonos de estudio"
                                   autocomplete="off"
                                   class="input">
                            <p class="hint">Entre 3 y 100 caracteres</p>
                        </div>

                        {{-- Imagen comprobante --}}
                        <div class="sm:col-span-2">
                            <div class="field-label">
                                <span>Imagen comprobante</span>
                                <span class="font-mono text-[10px] font-normal tracking-normal text-muted/70">máx. 5 MB</span>
                            </div>

                            <div class="file-drop" data-dropzone>
                                <div class="flex items-center gap-4">
                                    <label for="image_proof" class="flex min-w-0 flex-1 cursor-pointer items-center gap-4">
                                        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl border border-line bg-white text-muted">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                                <circle cx="9" cy="9" r="1.6"/>
                                                <path d="m21 15-4.2-4.2a2 2 0 0 0-2.8 0L4 21"/>
                                            </svg>
                                        </span>
                                        <span class="min-w-0">
                                            <span class="block text-[13.5px] font-medium text-ink">Arrastra tu comprobante aquí</span>
                                            <span class="mt-0.5 block text-[12px] text-muted">o haz clic para seleccionar</span>
                                            <span class="mt-1 block truncate font-mono text-[10.5px] text-forest" data-file-meta></span>
                                        </span>
                                    </label>

                                    <img data-file-thumb alt="Vista previa del comprobante"
                                         class="hidden h-14 w-14 shrink-0 rounded-lg border border-forest/15 object-cover">

                                    <button type="button" data-file-clear
                                            class="hidden h-8 w-8 shrink-0 place-items-center rounded-full border border-line bg-white text-muted transition hover:border-brick/30 hover:text-brick"
                                            aria-label="Quitar imagen seleccionada">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                                            <path d="M18 6 6 18M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <input id="image_proof" name="image_proof" type="file"
                                       class="sr-only" data-file-input>
                            </div>

                            <p class="hint">De momento se acepta cualquier archivo.</p>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary group mt-7">
                        <span>Registrar producto</span>
                        <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14"/><path d="m13 6 6 6-6 6"/>
                        </svg>
                    </button>

                    <div class="mt-4 flex flex-wrap items-center justify-between gap-2 text-[10.5px] text-muted/80">
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                            </svg>
                            Sin token CSRF
                        </span>
                        <span class="font-mono text-[9.5px] uppercase tracking-wider">sin validated()</span>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
