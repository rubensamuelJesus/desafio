@extends('layouts.app')

@section('title', $terms ? 'Procura: ' . $terms : 'Developers')

@section('conteudo')

{{-- Cabeçalho da página --}}
<div class="animar" style="margin-bottom: 32px;">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 class="fonte-titulo" style="font-size: 2rem; font-weight: 800; margin: 0 0 6px 0;">
                @if($terms)
                    Resultados para <span style="color: var(--cor-accent);">"{{ $terms }}"</span>
                @else
                    Developers
                @endif
            </h1>
            <p style="color: var(--cor-texto-mudo); margin: 0; font-size: 0.95rem;">
                @if($terms)
                    {{ $developers->count() }} resultado(s) encontrado(s)
                @else
                    <span class="badge">{{ $total }}</span> developer(s) registado(s)
                @endif
            </p>
        </div>

        {{-- Botão de limpar procura --}}
        @if($terms)
            <a href="{{ route('developers.index') }}" class="btn-secundario">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 11H7.83l4.88-4.88c.39-.39.39-1.03 0-1.42-.39-.39-1.02-.39-1.41 0l-6.59 6.59c-.39.39-.39 1.02 0 1.41l6.59 6.59c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L7.83 13H19c.55 0 1-.45 1-1s-.45-1-1-1z"/></svg>
                Ver todos
            </a>
        @endif
    </div>
</div>

{{-- Barra de search --}}
<div class="animar-2" style="margin-bottom: 40px;">
    <form method="GET" action="{{ route('developers.index') }}" style="display: flex; gap: 12px;">
        <div style="flex: 1; position: relative;">
            <svg width="18" height="18" fill="var(--cor-texto-mudo)" viewBox="0 0 24 24"
                 style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); pointer-events: none;">
                <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
            </svg>
            <input
                type="text"
                name="terms"
                value="{{ $terms ?? '' }}"
                placeholder="Procurar por nome, nickname ou tecnologia..."
                class="campo-input"
                style="padding-left: 44px;"
            >
        </div>
        <button type="submit" class="btn-principal">
            Procurar
        </button>
    </form>
</div>

{{-- Lista de developers --}}
@if($developers->isEmpty())
    <div class="estado-vazio animar-3">
        <svg width="48" height="48" fill="var(--cor-texto-mudo)" viewBox="0 0 24 24" style="margin-bottom: 16px;">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
        </svg>
        <p class="fonte-titulo" style="font-size: 1.1rem; margin: 0 0 8px 0;">
            @if($terms)
                Nenhum resultado para "{{ $terms }}"
            @else
                Ainda não há developers registados
            @endif
        </p>
        <p style="font-size: 0.9rem; margin: 0 0 24px 0;">
            @if($terms)
                Usa outro termo.
            @endif
        </p>
        <a href="{{ route('developers.create') }}" class="btn-principal">
            Registar Developer
        </a>
    </div>
@else
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;" class="animar-3">
        @foreach($developers as $dev)
            <a href="{{ route('developers.show', $dev->id) }}" style="text-decoration: none;">
                <div class="cartao cartao-link" style="padding: 24px;">

                    {{-- Cabeçalho do cartão --}}
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 16px;">
                        <div>
                            {{-- Avatar gerado com as iniciais --}}
                            <div style="
                                width: 44px; height: 44px;
                                background: rgba(108, 99, 255, 0.15);
                                border: 1px solid rgba(108, 99, 255, 0.3);
                                border-radius: 12px;
                                display: flex; align-items: center; justify-content: center;
                                font-family: 'Syne', sans-serif;
                                font-weight: 700;
                                font-size: 1rem;
                                color: var(--cor-accent-soft);
                                margin-bottom: 12px;
                            ">
                                {{ strtoupper(substr($dev->name, 0, 1)) }}
                            </div>
                            <p class="fonte-titulo" style="font-size: 1.05rem; font-weight: 700; margin: 0 0 2px 0; color: var(--cor-texto);">
                                {{ $dev->name }}
                            </p>
                            <p class="fonte-mono" style="font-size: 0.85rem; color: var(--cor-accent-soft); margin: 0;">
                                @{{ $dev->nickname }}
                            </p>
                        </div>

                        {{-- Seta de navegação --}}
                        <svg width="18" height="18" fill="var(--cor-texto-mudo)" viewBox="0 0 24 24">
                            <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
                        </svg>
                    </div>

                    <hr class="separador" style="margin-bottom: 16px;">

                    {{-- Data de nascimento --}}
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px; color: var(--cor-texto-mudo); font-size: 0.88rem;">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 3h-1V1h-2v2H7V1H5v2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 18H4V8h16v13z"/>
                        </svg>
                        <span class="fonte-mono">{{ $dev->birth_date }}</span>
                    </div>

                    {{-- Stack de tecnologias --}}
                    @if($dev->stack)
                        <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                            @foreach($dev->stack as $tech)
                                <span class="tag-tech">{{ $tech }}</span>
                            @endforeach
                        </div>
                    @else
                        <p style="color: var(--cor-texto-mudo); font-size: 0.85rem; margin: 0; font-style: italic;">
                            Sem stack definida
                        </p>
                    @endif

                </div>
            </a>
        @endforeach
    </div>

    {{-- Nota de paginação --}}
    @if(!$terms && $total > 20)
        <p style="text-align: center; color: var(--cor-texto-mudo); font-size: 0.85rem; margin-top: 32px;">
            A mostrar os primeiros 20 de {{ $total }} developers.
            Usa o search para filtrar resultados.
        </p>
    @endif
@endif

@endsection
