<!DOCTYPE html>
<html lang="pt" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DevRegistry') — Dynamik</title>

    {{-- Fonte: Syne para títulos, DM Mono para código/IDs --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Mono:wght@400;500&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    {{-- Vite: Tailwind CSS compilado --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Variáveis de design do sistema */
        :root {
            --cor-fundo:       #0a0a0f;
            --cor-superficie:  #111118;
            --cor-borda:       #1e1e2e;
            --cor-accent:      #6c63ff;
            --cor-accent-soft: #9d97ff;
            --cor-texto:       #e8e8f0;
            --cor-texto-mudo:  #6b6b85;
            --cor-sucesso:     #22c55e;
            --cor-erro:        #ef4444;
            --cor-tag:         #1a1a2e;
        }

        * { box-sizing: border-box; }

        body {
            background-color: var(--cor-fundo);
            color: var(--cor-texto);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        /* Grelha decorativa de fundo */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(108, 99, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(108, 99, 255, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        /* Brilho de fundo no topo */
        body::after {
            content: '';
            position: fixed;
            top: -200px;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            height: 400px;
            background: radial-gradient(ellipse, rgba(108, 99, 255, 0.12) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* Conteúdo sempre acima do fundo decorativo */
        .conteudo { position: relative; z-index: 1; }

        /* Tipografia de títulos */
        .fonte-titulo { font-family: 'Syne', sans-serif; }
        .fonte-mono   { font-family: 'DM Mono', monospace; }

        /* Cartão base */
        .cartao {
            background: var(--cor-superficie);
            border: 1px solid var(--cor-borda);
            border-radius: 16px;
            transition: border-color 0.2s ease, transform 0.2s ease;
        }
        .cartao:hover {
            border-color: rgba(108, 99, 255, 0.4);
        }

        /* Cartão clicável */
        .cartao-link:hover {
            transform: translateY(-2px);
        }

        /* Botão principal */
        .btn-principal {
            background: var(--cor-accent);
            color: white;
            font-family: 'Syne', sans-serif;
            font-weight: 600;
            border-radius: 10px;
            padding: 10px 20px;
            transition: background 0.2s, transform 0.15s;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-principal:hover {
            background: #5b53e8;
            transform: translateY(-1px);
        }

        /* Botão secundário */
        .btn-secundario {
            background: transparent;
            color: var(--cor-texto-mudo);
            font-family: 'Syne', sans-serif;
            font-weight: 600;
            border-radius: 10px;
            padding: 10px 20px;
            transition: color 0.2s, border-color 0.2s;
            border: 1px solid var(--cor-borda);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-secundario:hover {
            color: var(--cor-texto);
            border-color: var(--cor-accent);
        }

        /* Tag de tecnologia */
        .tag-tech {
            background: var(--cor-tag);
            border: 1px solid var(--cor-borda);
            color: var(--cor-accent-soft);
            font-family: 'DM Mono', monospace;
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 6px;
            white-space: nowrap;
        }

        /* Campo de input */
        .campo-input {
            background: var(--cor-superficie);
            border: 1px solid var(--cor-borda);
            color: var(--cor-texto);
            border-radius: 10px;
            padding: 10px 16px;
            width: 100%;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s;
        }
        .campo-input:focus {
            border-color: var(--cor-accent);
        }
        .campo-input::placeholder {
            color: var(--cor-texto-mudo);
        }

        /* Label dos formulários */
        .label-campo {
            display: block;
            font-family: 'Syne', sans-serif;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--cor-texto-mudo);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Separador decorativo */
        .separador {
            border: none;
            border-top: 1px solid var(--cor-borda);
        }

        /* Animação de entrada */
        @keyframes surgir {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animar { animation: surgir 0.4s ease both; }
        .animar-2 { animation: surgir 0.4s ease 0.1s both; }
        .animar-3 { animation: surgir 0.4s ease 0.2s both; }

        /* Barra de navegação */
        .navbar {
            border-bottom: 1px solid var(--cor-borda);
            backdrop-filter: blur(12px);
            background: rgba(10, 10, 15, 0.8);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        /* Badge de contagem */
        .badge {
            background: rgba(108, 99, 255, 0.15);
            color: var(--cor-accent-soft);
            border: 1px solid rgba(108, 99, 255, 0.3);
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 0.8rem;
            font-family: 'DM Mono', monospace;
        }

        /* Mensagem de estado vazio */
        .estado-vazio {
            text-align: center;
            padding: 60px 20px;
            color: var(--cor-texto-mudo);
        }
    </style>
</head>
<body class="h-full">

    {{-- Barra de navegação principal --}}
    <nav class="navbar conteudo">
        <div style="max-width: 1100px; margin: 0 auto; padding: 0 24px;">
            <div style="display: flex; align-items: center; justify-content: space-between; height: 64px;">

                {{-- Logo / Nome da app --}}
                <a href="{{ route('developers.index') }}" style="text-decoration: none; display: flex; align-items: center; gap: 10px;">
                    <div style="width: 32px; height: 32px; background: var(--cor-accent); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <svg width="18" height="18" fill="white" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                        </svg>
                    </div>
                    <span class="fonte-titulo" style="font-size: 1.1rem; font-weight: 700; color: var(--cor-texto);">
                        Dev<span style="color: var(--cor-accent);">Registry</span>
                    </span>
                </a>

                {{-- Ações da navbar --}}
                <div style="display: flex; align-items: center; gap: 12px;">
                    <a href="{{ route('developers.index') }}" class="btn-secundario" style="font-size: 0.9rem;">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"/></svg>
                        Lista
                    </a>
                    <a href="{{ route('developers.create') }}" class="btn-principal" style="font-size: 0.9rem;">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        Novo Dev
                    </a>
                </div>

            </div>
        </div>
    </nav>

    {{-- Conteúdo principal da página --}}
    <main class="conteudo" style="max-width: 1100px; margin: 0 auto; padding: 40px 24px;">
        @yield('conteudo')
    </main>

    {{-- Rodapé --}}
    <footer class="conteudo" style="border-top: 1px solid var(--cor-borda); margin-top: 80px; padding: 24px; text-align: center;">
        <span class="fonte-mono" style="color: var(--cor-texto-mudo); font-size: 0.8rem;">
            DevRegistry · Desafio Dynamik · Laravel + Blade + Tailwind + SQLite
        </span>
    </footer>

    {{-- Script para o formulário de criação via API --}}
    @stack('scripts')
</body>
</html>
