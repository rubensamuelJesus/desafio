@extends('layouts.app')

@section('title', 'Novo Developer')

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

<div style="max-width: 560px;">

    {{-- Título --}}
    <div class="animar" style="margin-bottom: 32px;">
        <h1 class="fonte-titulo" style="font-size: 2rem; font-weight: 800; margin: 0 0 8px 0;">
            Novo Developer
        </h1>
        <p style="color: var(--cor-texto-mudo); margin: 0;">
            Preenche os dados para registar um novo developer.
        </p>
    </div>

    {{-- Formulário --}}
    <div class="cartao animar-2" style="padding: 32px;">

        {{-- Mensagem de sucesso --}}
        <div id="msg-sucesso" style="
            display: none;
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 24px;
            color: #22c55e;
            font-family: 'Syne', sans-serif;
            font-weight: 600;
        ">
            ✅ Developer registado com sucesso!
        </div>

        {{-- Mensagem de erro --}}
        <div id="msg-erro" style="
            display: none;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 24px;
            color: #ef4444;
            font-family: 'Syne', sans-serif;
            font-weight: 600;
        ">
            <span id="msg-erro-texto"></span>
        </div>

        {{-- Campo: Nickname --}}
        <div style="margin-bottom: 20px;">
            <label class="label-campo" for="nickname">Nickname *</label>
            <input
                type="text"
                id="nickname"
                class="campo-input"
                placeholder="ex: judit"
                maxlength="32"
            >
            <p id="err-nickname" style="color: var(--cor-erro); font-size: 0.82rem; margin: 4px 0 0 0; display: none;"></p>
        </div>

        {{-- Campo: Nome --}}
        <div style="margin-bottom: 20px;">
            <label class="label-campo" for="name">Nome Completo *</label>
            <input
                type="text"
                id="name"
                class="campo-input"
                placeholder="ex: Judit Polgár"
                maxlength="100"
            >
            <p id="err-name" style="color: var(--cor-erro); font-size: 0.82rem; margin: 4px 0 0 0; display: none;"></p>
        </div>

        {{-- Campo: Data de nascimento --}}
        <div style="margin-bottom: 20px;">
            <label class="label-campo" for="birth_date">Data de Nascimento *</label>
            <input
                type="date"
                id="birth_date"
                class="campo-input"
            >
            <p id="err-birth_date" style="color: var(--cor-erro); font-size: 0.82rem; margin: 4px 0 0 0; display: none;"></p>
        </div>

        {{-- Campo: Stack --}}
        <div style="margin-bottom: 28px;">
            <label class="label-campo" for="stack-input">Stack <span style="font-weight: 400; text-transform: none; letter-spacing: 0;">(opcional)</span></label>

            {{-- Tags de stack adicionadas --}}
            <div id="stack-tags" style="display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 10px; min-height: 0;"></div>

            {{-- Input para adicionar tecnologias --}}
            <div style="display: flex; gap: 8px;">
                <input
                    type="text"
                    id="stack-input"
                    class="campo-input"
                    placeholder="ex: Laravel, Vue, Python..."
                    maxlength="32"
                    style="flex: 1;"
                >
                <button type="button" onclick="adicionarTech()" class="btn-secundario" style="flex-shrink: 0;">
                    Adicionar
                </button>
            </div>
            <p style="color: var(--cor-texto-mudo); font-size: 0.82rem; margin: 6px 0 0 0;">
                Escreve uma tecnologia e clica em Adicionar. Clica na tag para remover.
            </p>
        </div>

        <hr class="separador" style="margin-bottom: 24px;">

        {{-- Botão de submeter --}}
        <button
            type="button"
            id="btn-criar"
            onclick="criarDeveloper()"
            class="btn-principal"
            style="width: 100%; justify-content: center; padding: 14px; font-size: 1rem;"
        >
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
            </svg>
            Registar Developer
        </button>

    </div>
</div>

@push('scripts')
<script>
    // Array que guarda as tecnologias adicionadas
    let stackTechs = [];

    /**
     * Adiciona uma tecnologia à stack.
     * Validações: não vazia, não duplicada, máx 32 chars.
     */
    function adicionarTech() {
        const input = document.getElementById('stack-input');
        const valor = input.value.trim();

        if (!valor) return;
        if (valor.length > 32) {
            alert('Cada tecnologia pode ter no máximo 32 caracteres.');
            return;
        }
        if (stackTechs.map(t => t.toLowerCase()).includes(valor.toLowerCase())) {
            alert('Esta tecnologia já foi adicionada.');
            return;
        }

        // Adiciona ao array e atualiza a UI
        stackTechs.push(valor);
        renderizarTags();
        input.value = '';
        input.focus();
    }

    /**
     * Remove uma tecnologia da stack pelo índice.
     */
    function removerTech(index) {
        stackTechs.splice(index, 1);
        renderizarTags();
    }

    /**
     * Renderiza as tags de tecnologia visualmente.
     */
    function renderizarTags() {
        const container = document.getElementById('stack-tags');
        container.innerHTML = '';

        stackTechs.forEach((tech, i) => {
            const tag = document.createElement('span');
            tag.style.cssText = `
                background: rgba(108, 99, 255, 0.15);
                border: 1px solid rgba(108, 99, 255, 0.4);
                color: var(--cor-accent-soft);
                font-family: 'DM Mono', monospace;
                font-size: 0.8rem;
                padding: 4px 10px;
                border-radius: 6px;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                transition: background 0.15s;
            `;
            tag.title = 'Clica para remover';
            tag.innerHTML = `${tech} <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>`;
            tag.onclick = () => removerTech(i);
            container.appendChild(tag);
        });
    }

    /**
     * Permite adicionar tecnologia ao clicar Enter no input de stack.
     */
    document.getElementById('stack-input').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            adicionarTech();
        }
    });

    /**
     * Mostra um erro de campo específico.
     */
    function mostrarErro(campo, mensagem) {
        const el = document.getElementById('err-' + campo);
        if (el) {
            el.textContent = mensagem;
            el.style.display = 'block';
        }
    }

    /**
     * Limpa todos os erros do campo.
     */
    function limparErros() {
        ['nickname', 'name', 'birth_date'].forEach(campo => {
            const el = document.getElementById('err-' + campo);
            if (el) el.style.display = 'none';
        });
        document.getElementById('msg-erro').style.display = 'none';
        document.getElementById('msg-sucesso').style.display = 'none';
    }

    /**
     * Submete o formulário para a API POST /api/devs.
     * Trata as respostas 201, 422 e 400.
     */
    async function criarDeveloper() {
        limparErros();

        const nickname   = document.getElementById('nickname').value.trim();
        const name       = document.getElementById('name').value.trim();
        const birth_date = document.getElementById('birth_date').value;

        // Payload para a API
        const payload = {
            nickname:   nickname   || null,
            name:       name       || null,
            birth_date: birth_date || null,
            stack:      stackTechs.length > 0 ? stackTechs : null,
        };

        // Desabilita o botão durante o pedido
        const btn = document.getElementById('btn-criar');
        btn.disabled = true;
        btn.textContent = 'A registar...';

        try {
            const resposta = await fetch('/api/devs', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(payload),
            });

            const dados = await resposta.json();

            if (resposta.status === 201) {
                // Sucesso: mostra mensagem e redireciona após 1.5s
                document.getElementById('msg-sucesso').style.display = 'block';

                // Vai para a página de detalhe do novo developer
                const location = resposta.headers.get('Location');
                if (location) {
                    // Converte /devs/:id para rota web /developers/:id
                    const id = location.split('/').pop();
                    setTimeout(() => {
                        window.location.href = `/developers/${id}`;
                    }, 1500);
                }

            } else if (resposta.status === 422) {
                // Erros de validação
                if (dados.errors) {
                    Object.keys(dados.errors).forEach(campo => {
                        mostrarErro(campo, dados.errors[campo][0]);
                    });
                } else {
                    document.getElementById('msg-erro-texto').textContent = dados.message || 'Dados inválidos.';
                    document.getElementById('msg-erro').style.display = 'block';
                }

            } else if (resposta.status === 400) {
                // Erro de sintaxe
                document.getElementById('msg-erro-texto').textContent = dados.message || 'Pedido inválido.';
                document.getElementById('msg-erro').style.display = 'block';

            } else {
                document.getElementById('msg-erro-texto').textContent = 'Erro inesperado. Tenta novamente.';
                document.getElementById('msg-erro').style.display = 'block';
            }

        } catch (erro) {
            document.getElementById('msg-erro-texto').textContent = 'Erro de ligação. Verifica a tua rede.';
            document.getElementById('msg-erro').style.display = 'block';
            console.error('Erro:', erro);

        } finally {
            // Reativa o botão
            btn.disabled = false;
            btn.innerHTML = `
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                </svg>
                Registar Developer
            `;
        }
    }
</script>
@endpush

@endsection
