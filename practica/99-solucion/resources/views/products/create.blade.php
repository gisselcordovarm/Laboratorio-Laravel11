@extends('layouts.app')

@section('title', 'Registro de producto seguro')

@section('content')
    <div class="shell pb-24 pt-10 sm:pt-14">
        <div class="grid items-start gap-12 lg:grid-cols-[1.02fr_1fr] lg:gap-16">

            {{-- Columna editorial: contexto de la práctica --}}
            <section class="fade-up lg:sticky lg:top-10">
                <p class="eyebrow">Laboratorio · Formularios críticos</p>

                <h1 class="mt-4 font-display text-[2.6rem] font-light leading-[1.02] tracking-tight text-ink text-balance sm:text-6xl">
                    Registro de<br>
                    <em class="font-normal italic text-forest">producto seguro</em>
                </h1>

                <p class="mt-6 max-w-md text-[15px] leading-relaxed text-muted">
                    Un formulario que demuestra, en vivo, las cuatro defensas del módulo:
                    validación dedicada, asignación controlada, token CSRF y límite de frecuencia.
                    Todo el peso de la seguridad vive en el servidor.
                </p>

                <div class="mt-7 flex flex-wrap gap-2">
                    <span class="chip"><span class="h-1.5 w-1.5 rounded-full bg-forest"></span> CSRF · 419</span>
                    <span class="chip"><span class="h-1.5 w-1.5 rounded-full bg-forest"></span> Validación · 422</span>
                    <span class="chip"><span class="h-1.5 w-1.5 rounded-full bg-forest"></span> Mass assignment</span>
                    <span class="chip"><span class="h-1.5 w-1.5 rounded-full bg-gold"></span> Throttle · 429</span>
                </div>

                <div class="mt-10 hidden lg:block">
                    <div class="flex items-baseline justify-between">
                        <p class="eyebrow">Ruta de la práctica</p>
                        <span class="font-mono text-[10px] text-muted/70">paso 5 de 7</span>
                    </div>

                    <ol class="mt-4 space-y-0.5 border-l border-line/80 pl-3">
                        <li class="step"><span class="step-dot">0</span> Verificación del entorno</li>
                        <li class="step"><span class="step-dot">1</span> Proyecto Laravel 11</li>
                        <li class="step"><span class="step-dot">2</span> Form Request dedicado</li>
                        <li class="step"><span class="step-dot">3</span> Controlador delgado</li>
                        <li class="step"><span class="step-dot">4</span> Rutas y throttle</li>
                        <li class="step step-active">
                            <span class="step-dot">5</span>
                            <span class="flex-1">Vista Blade y UX</span>
                            <span class="rounded-full bg-forest px-2 py-0.5 font-mono text-[9px] uppercase tracking-wider text-paper">ahora</span>
                        </li>
                        <li class="step"><span class="step-dot">6</span> Pruebas defensivas</li>
                    </ol>
                </div>

                <div class="mt-8 hidden items-center gap-3 text-[11px] text-muted/80 lg:flex">
                    <span class="h-px w-8 bg-line"></span>
                    <span class="font-mono">php artisan serve</span>
                    <span>·</span>
                    <span>http://127.0.0.1:8000</span>
                </div>

                <div class="mt-10 hidden lg:block">
                    <div class="flex items-baseline justify-between">
                        <p class="eyebrow">Matriz de alineación</p>
                        <span class="font-mono text-[10px] text-muted/70">teoría → código</span>
                    </div>

                    <div class="mt-3 divide-y divide-line/70 border-y border-line/70">
                        <div class="grid grid-cols-[1fr_1.15fr_auto] items-center gap-3 py-2.5 text-[11.5px]">
                            <span class="text-ink/80">Anti-CSRF</span>
                            <span class="truncate font-mono text-[10px] text-muted">&#64;csrf</span>
                            <span class="code-badge">419</span>
                        </div>
                        <div class="grid grid-cols-[1fr_1.15fr_auto] items-center gap-3 py-2.5 text-[11.5px]">
                            <span class="text-ink/80">Validación estricta</span>
                            <span class="truncate font-mono text-[10px] text-muted">Form Request</span>
                            <span class="code-badge">422</span>
                        </div>
                        <div class="grid grid-cols-[1fr_1.15fr_auto] items-center gap-3 py-2.5 text-[11.5px]">
                            <span class="text-ink/80">Mass assignment</span>
                            <span class="truncate font-mono text-[10px] text-muted">validated()</span>
                            <span class="code-badge">filtra</span>
                        </div>
                        <div class="grid grid-cols-[1fr_1.15fr_auto] items-center gap-3 py-2.5 text-[11.5px]">
                            <span class="text-ink/80">Rate limiting</span>
                            <span class="truncate font-mono text-[10px] text-muted">throttle:6,1</span>
                            <span class="code-badge">429</span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Columna funcional: confirmación, errores y formulario --}}
            <section class="fade-up-delay">

                @if (session('success'))
                    @php($product = session('product'))
                    <div id="registro" class="mb-6 rounded-3xl border border-forest/20 bg-forest/[0.055] p-6">
                        <div class="flex items-start gap-4">
                            <span class="mt-0.5 grid h-9 w-9 shrink-0 place-items-center rounded-full bg-forest text-paper">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 6 9 17l-5-5"/>
                                </svg>
                            </span>

                            <div class="min-w-0 flex-1">
                                <p class="eyebrow text-forest/70">Registro completado</p>
                                <h2 class="mt-1 font-display text-[22px] leading-tight text-forest-deep">
                                    {{ session('success') }}
                                </h2>

                                @if ($product)
                                    <dl class="mt-4 grid grid-cols-1 gap-x-6 gap-y-3 text-[13px] sm:grid-cols-3">
                                        <div>
                                            <dt class="text-[10px] uppercase tracking-[0.16em] text-muted">Código</dt>
                                            <dd class="mt-0.5 font-mono text-ink">{{ $product['code'] }}</dd>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <dt class="text-[10px] uppercase tracking-[0.16em] text-muted">Producto</dt>
                                            <dd class="mt-0.5 text-ink">{{ $product['name'] }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-[10px] uppercase tracking-[0.16em] text-muted">Precio</dt>
                                            <dd class="mt-0.5 font-mono text-ink">$ {{ number_format((float) $product['price'], 2, ',', '.') }}</dd>
                                        </div>
                                        @if ($product['image_path'])
                                            <div class="sm:col-span-2">
                                                <dt class="text-[10px] uppercase tracking-[0.16em] text-muted">Comprobante</dt>
                                                <dd class="mt-2 flex items-center gap-3">
                                                    <img src="{{ asset('storage/'.$product['image_path']) }}"
                                                         alt="Comprobante de {{ $product['name'] }}"
                                                         class="h-12 w-12 rounded-lg border border-forest/15 object-cover">
                                                    <span class="truncate font-mono text-[10.5px] text-muted">{{ $product['image_path'] }}</span>
                                                </dd>
                                            </div>
                                        @endif
                                    </dl>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-3xl border border-brick/20 bg-brick/[0.045] p-5" role="alert">
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 text-brick">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
                                    <path d="M12 9v4"/><path d="M12 17h.01"/>
                                </svg>
                            </span>
                            <div>
                                <p class="text-[13px] font-semibold text-brick">
                                    Revisa {{ $errors->count() }} {{ $errors->count() === 1 ? 'campo' : 'campos' }} del formulario
                                </p>
                                <ul class="mt-2 space-y-1 text-[12.5px] leading-relaxed text-brick/85">
                                    @foreach ($errors->all() as $error)
                                        <li class="flex gap-2">
                                            <span aria-hidden="true">—</span>
                                            <span>{{ $error }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
                      class="card p-6 sm:p-8" data-product-form novalidate>
                    @csrf

                    <div class="flex items-start justify-between gap-4 border-b border-line/80 pb-6">
                        <div>
                            <p class="eyebrow">Paso 5 · Vista Blade</p>
                            <h2 class="mt-2 font-display text-[26px] leading-none text-ink">Nuevo producto</h2>
                        </div>
                        <span class="chip shrink-0 font-mono text-[10px]">
                            <span class="h-1.5 w-1.5 rounded-full bg-gold"></span>
                            throttle:6,1
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
                                   value="{{ old('code') }}"
                                   placeholder="PRD001"
                                   autocomplete="off" autocapitalize="characters" spellcheck="false"
                                   aria-required="true"
                                   aria-invalid="{{ $errors->has('code') ? 'true' : 'false' }}"
                                   @if ($errors->has('code')) aria-describedby="code-error" @endif
                                   @class(['input', 'input-invalid' => $errors->has('code')])>
                            @error('code')
                                <p id="code-error" class="error-text">
                                    <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                                    {{ $message }}
                                </p>
                            @else
                                <p class="hint">Letras y números · máx. 10</p>
                            @enderror
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
                                       value="{{ old('price') }}"
                                       placeholder="0.00"
                                       autocomplete="off"
                                       aria-required="true"
                                       aria-invalid="{{ $errors->has('price') ? 'true' : 'false' }}"
                                       @if ($errors->has('price')) aria-describedby="price-error" @endif
                                       @class(['input pl-8', 'input-invalid' => $errors->has('price')])>
                            </div>
                            @error('price')
                                <p id="price-error" class="error-text">
                                    <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                                    {{ $message }}
                                </p>
                            @else
                                <p class="hint">Valor numérico mayor a 0.01</p>
                            @enderror
                        </div>

                        {{-- Nombre --}}
                        <div class="sm:col-span-2">
                            <div class="field-label">
                                <label for="name">Nombre del producto</label>
                                <span class="font-mono text-[10px] font-normal tracking-normal text-muted/70" data-counter-for="name">0/100</span>
                            </div>
                            <input id="name" name="name" type="text" maxlength="100"
                                   value="{{ old('name') }}"
                                   placeholder="Ej. Audífonos de estudio"
                                   autocomplete="off"
                                   aria-required="true"
                                   aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                                   @if ($errors->has('name')) aria-describedby="name-error" @endif
                                   @class(['input', 'input-invalid' => $errors->has('name')])>
                            @error('name')
                                <p id="name-error" class="error-text">
                                    <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                                    {{ $message }}
                                </p>
                            @else
                                <p class="hint">Entre 3 y 100 caracteres</p>
                            @enderror
                        </div>

                        {{-- Imagen comprobante --}}
                        <div class="sm:col-span-2">
                            <div class="field-label">
                                <span>Imagen comprobante</span>
                                <span class="font-mono text-[10px] font-normal tracking-normal text-muted/70">máx. 5 MB</span>
                            </div>

                            <div @class(['file-drop', 'is-invalid' => $errors->has('image_proof')]) data-dropzone>
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
                                            <span class="mt-0.5 block text-[12px] text-muted">o haz clic para seleccionar · JPG, PNG o WebP</span>
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
                                       accept="image/jpeg,image/png,image/webp"
                                       class="sr-only" data-file-input
                                       aria-required="true"
                                       aria-invalid="{{ $errors->has('image_proof') ? 'true' : 'false' }}"
                                       @if ($errors->has('image_proof')) aria-describedby="image-error" @endif>
                            </div>

                            @error('image_proof')
                                <p id="image-error" class="error-text">
                                    <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                                    {{ $message }}
                                </p>
                            @else
                                <p class="hint">Solo JPEG, PNG o WebP. El tipo real se valida en el servidor.</p>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn-primary group mt-7">
                        <span>Registrar producto seguro</span>
                        <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14"/><path d="m13 6 6 6-6 6"/>
                        </svg>
                    </button>

                    <div class="mt-4 flex flex-wrap items-center justify-between gap-2 text-[10.5px] text-muted/80">
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                            </svg>
                            Token CSRF activo
                        </span>
                        <span class="font-mono text-[9.5px] uppercase tracking-wider">validated() · sin mass assignment</span>
                    </div>
                </form>
            </section>
        </div>

        {{-- Protocolo de pruebas --}}
        <section id="protocolo" class="mt-20 scroll-mt-10">
            <div class="flex flex-col gap-4 border-t border-line pt-10 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="eyebrow">Paso 6 · Verificación en vivo</p>
                    <h2 class="mt-3 font-display text-3xl font-light tracking-tight text-ink sm:text-4xl">
                        Protocolo de pruebas defensivas
                    </h2>
                </div>
                <p class="max-w-sm text-[13px] leading-relaxed text-muted">
                    Cuatro intentos deliberados de romper el formulario. Cada defensa
                    responde con un estado HTTP distinto y verificable.
                </p>
            </div>

            <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <article class="protocol-card">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-[10px] text-muted/60">01</span>
                        <span class="code-badge">422</span>
                    </div>
                    <h3 class="mt-4 text-[14px] font-semibold text-ink">Formulario vacío</h3>
                    <p class="mt-2 text-[12.5px] leading-relaxed text-muted">Envía sin completar campos. La recarga conserva los valores y marca cada borde en rojo.</p>
                    <p class="mt-3 font-mono text-[10px] uppercase tracking-wider text-forest">Errores en español</p>
                </article>

                <article class="protocol-card">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-[10px] text-muted/60">02</span>
                        <span class="code-badge">422</span>
                    </div>
                    <h3 class="mt-4 text-[14px] font-semibold text-ink">Archivo no permitido</h3>
                    <p class="mt-2 text-[12.5px] leading-relaxed text-muted">Adjunta un <code class="font-mono text-[11px]">.php</code> o <code class="font-mono text-[11px]">.exe</code> como comprobante.</p>
                    <p class="mt-3 font-mono text-[10px] uppercase tracking-wider text-forest">mimes:jpeg,png,webp</p>
                </article>

                <article class="protocol-card">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-[10px] text-muted/60">03</span>
                        <span class="code-badge">419</span>
                    </div>
                    <h3 class="mt-4 text-[14px] font-semibold text-ink">Token CSRF omitido</h3>
                    <p class="mt-2 text-[12.5px] leading-relaxed text-muted">Con F12 elimina el input <code class="font-mono text-[11px]">_token</code> y vuelve a enviar.</p>
                    <p class="mt-3 font-mono text-[10px] uppercase tracking-wider text-forest">Page Expired</p>
                </article>

                <article class="protocol-card">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-[10px] text-muted/60">04</span>
                        <span class="code-badge">429</span>
                    </div>
                    <h3 class="mt-4 text-[14px] font-semibold text-ink">Saturación por envío</h3>
                    <p class="mt-2 text-[12.5px] leading-relaxed text-muted">Envía 7 veces en menos de un minuto: el séptimo intento queda bloqueado.</p>
                    <p class="mt-3 font-mono text-[10px] uppercase tracking-wider text-forest">Too Many Requests</p>
                </article>
            </div>

            <div class="mt-5 flex flex-col gap-2 rounded-2xl border border-line bg-white/40 px-5 py-4 text-[12px] text-muted sm:flex-row sm:items-center sm:justify-between">
                <p>
                    <span class="font-semibold text-ink">Nota de laboratorio:</span>
                    el throttle cuenta todos los POST, incluidos los rechazados.
                </p>
                <p class="font-mono text-[10.5px] text-forest">php artisan cache:clear <span class="text-muted/70">entre pruebas</span></p>
            </div>
        </section>
    </div>
@endsection
