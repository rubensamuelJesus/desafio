@extends('layouts.app')

@section('title', $developer->name)

@section('conteudo')

{{-- Botão voltar --}}
<div class="animar" style="margin-bottom: 32px;">
    <a href="{{ route('developers.index') }}" class="btn-secundario">
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19 11H7.83l4.88-4.88c.39-.39.39-1.03 0-1.42-.39-.39-1.02-.39-1.41 0l-6.59 6.59c-.39.39-.39 1.02 0 1.41l6.59 6.59c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L7.83 13H19c.55 0 1-.45 1-1s-.45-1-1-1z"/>
        </svg>
        Voltar à lista
    </a>
</div>

<div style="max-width: 680px;">

    {{-- Cabeçalho do perfil --}}
    <div class="cartao animar" style="padding: 36px; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 24px; margin-bottom: 28px;">

            {{-- Avatar grande com inicial --}}
            <div style="
                width: 72px; height: 72px;
                background: rgba(108, 99, 255, 0.15);
                border: 2px solid rgba(108, 99, 255, 0.4);
                border-radius: 18px;
                display: flex; align-items: center; justify-content: center;
                font-family: 'Syne', sans-serif;
                font-weight: 800;
                font-size: 1.8rem;
                color: var(--cor-accent-soft);
                flex-shrink: 0;
            ">
                {{ strtoupper(substr($developer->name, 0, 1)) }}
            </div>

            <div>
                <h1 class="fonte-titulo" style="font-size: 1.8rem; font-weight: 800; margin: 0 0 4px 0;">
                    {{ $developer->name }}
                </h1>
                <p class="fonte-mono" style="color: var(--cor-accent-soft); font-size: 1rem; margin: 0;">
                    {{ '@' . $developer->nickname }}
                </p>
            </div>
        </div>

        <hr class="separador" style="margin-bottom: 24px;">

        {{-- Detalhes do developer --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">

            {{-- ID --}}
            <div>
                <p class="label-campo">ID</p>
                <p class="fonte-mono" style="font-size: 0.8rem; color: var(--cor-texto-mudo); margin: 0; word-break: break-all;">
                    {{ $developer->id }}
                </p>
            </div>

            {{-- Data de nascimento --}}
            <div>
                <p class="label-campo">Data de Nascimento</p>
                <p class="fonte-mono" style="font-size: 0.95rem; color: var(--cor-texto); margin: 0;">
                    {{ $developer->birth_date }}
                </p>
            </div>

        </div>

        {{-- Stack --}}
        <div>
            <p class="label-campo">Stack</p>
            @if($developer->stack && count($developer->stack) > 0)
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    @foreach($developer->stack as $tech)
                        <span class="tag-tech" style="font-size: 0.85rem; padding: 6px 14px;">
                            {{ $tech }}
                        </span>
                    @endforeach
                </div>
            @else
                <p style="color: var(--cor-texto-mudo); font-style: italic; margin: 0;">
                    Sem tecnologias definidas
                </p>
            @endif
        </div>
    </div>

    {{-- Bloco com o JSON raw do developer (útil para devs) --}}
    <div class="cartao animar-2" style="padding: 24px;">
        <p class="label-campo" style="margin-bottom: 12px;">JSON do recurso</p>
        <pre class="fonte-mono" style="
            background: var(--cor-fundo);
            border: 1px solid var(--cor-borda);
            border-radius: 10px;
            padding: 16px;
            font-size: 0.82rem;
            color: var(--cor-accent-soft);
            overflow-x: auto;
            margin: 0;
            line-height: 1.6;
        ">{{ json_encode([
            'id'         => $developer->id,
            'nickname'   => $developer->nickname,
            'name'       => $developer->name,
            'birth_date' => $developer->birth_date,
            'stack'      => $developer->stack,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </div>

</div>

@endsection
