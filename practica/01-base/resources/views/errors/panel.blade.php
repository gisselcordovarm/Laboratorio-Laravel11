@extends('layouts.app')

@section('title', $title ?? 'Error')

@section('content')
    {{--  Plantilla de error vacía.
        El alumno completa los valores que recibe de la vista hija
        (code, badge, title, heading, message, hint) o sustituye
        este @include por su propio HTML dentro de @section('content').
    --}}
    <div class="shell flex min-h-[72vh] items-center justify-center py-16">
        <div class="w-full max-w-2xl text-center fade-up">
            <p class="eyebrow">Protocolo defensivo · Laravel</p>

            <div class="relative mt-6">
                <p class="font-display text-[8rem] font-light leading-none tracking-tight text-forest/[0.14] sm:text-[10rem]">
                    {{ $code ?? '---' }}
                </p>
                <div class="absolute inset-0 grid place-items-center">
                    <span class="rounded-full border border-forest/15 bg-card px-4 py-1.5 font-mono text-[10px] uppercase tracking-[0.2em] text-forest">
                        {{ $badge ?? 'Error' }}
                    </span>
                </div>
            </div>

            <h1 class="mt-2 font-display text-3xl font-light tracking-tight text-ink text-balance sm:text-4xl">
                {{ $heading ?? 'Sin título' }}
            </h1>

            <p class="mx-auto mt-4 max-w-lg text-[15px] leading-relaxed text-muted">
                {{ $message ?? '' }}
            </p>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('products.create') }}" class="btn-primary w-auto">
                    Volver al formulario
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14"/><path d="m13 6 6 6-6 6"/>
                    </svg>
                </a>
                <a href="{{ route('products.create') }}#protocolo" class="btn-ghost">Ver protocolo de pruebas</a>
            </div>

            <p class="mt-10 font-mono text-[10.5px] leading-relaxed text-muted/70">
                {{ $hint ?? '' }}
            </p>
        </div>
    </div>
@endsection
