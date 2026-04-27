# Desafio Fullstack Dynamik

Repositório: https://github.com/rubensamuelJesus/desafio


# Stack Tecnológica

- Laravel 12.x
- Blade (templating nativo Laravel)
- Tailwind CSS 4.x
- SQLite 3.x
- PHP 8.4
- Node.js 20+


# Porquê esta stack?

Laravel foi escolhido pela sua maturidade, organização MVC clara, e pelo conjunto de ferramentas integradas que aceleram o desenvolvimento sem sacrificar qualidade. É uma tecnologia com a qual já trabalhei várias vezes e é bastante prática para trabalhar.

Inclui Form Requests para separar a lógica de validação do controller, API Resources para garantir um formato de resposta JSON consistente, Eloquent ORM com casting automático de JSON para o campo stack, UUID nativo e PHPUnit integrado para testes.

Blade por ser o motor de templates nativo do Laravel, sem overhead de configuração e sem dependências extra. Integra-se naturalmente com o backend.

Tailwind CSS permite escrever UI responsiva e coesa diretamente no HTML sem sair do ficheiro Blade.

SQLite foi escolhido por ser mais trivial de configurar, dado que o projeto tem poucos registos e não justifica um servidor de base de dados separado. Torna o setup imediato em qualquer máquina.


# Instalação e Arranque

Pré-requisitos:
- PHP >= 8.4
- Composer
- Node.js >= 20
- Laravel Herd (recomendado) ou servidor PHP equivalente

Passos:

    git clone https://github.com/rubensamuelJesus/desafio.git
    cd desafio
    composer install
    cp .env.example .env
    php artisan key:generate
    touch database/database.sqlite
    php artisan migrate
    npm install
    npm run build
    php artisan serve

Com Laravel Herd, basta colocar o projeto na pasta ~/Herd/ e aceder em http://desafio.test.

Nota: este projeto foi desenvolvido e testado com Laravel Herd. O método recomendado de instalação é o descrito acima.


# Docker (não testado)

Foi incluído um ficheiro docker-compose.yml como referência, no entanto não foi testado em ambiente Docker. O método recomendado de execução é com Laravel Herd conforme descrito acima.

    docker-compose up -d


# Endpoints da API

Base URL: /api

# POST /api/devs — Criar developer

    curl -X POST http://desafio.test/api/devs \
      -H "Content-Type: application/json" \
      -d '{
        "nickname": "judit",
        "name": "Judit Polgár",
        "birth_date": "1976-07-23",
        "stack": ["C#", "Node", "Oracle"]
      }'

Respostas:
- 201 Created — developer criado, header Location: /devs/:id
- 422 Unprocessable Entity — campos inválidos (null, duplicado)
- 400 Bad Request — tipos incorretos

# GET /api/devs — Listar developers

    curl http://desafio.test/api/devs

Retorna os primeiros 20 developers. O header X-Total-Count indica o total na base de dados.

# GET /api/devs?terms=[:termo] — Procurar developers

    curl http://desafio.test/api/devs?terms=node

Pesquisa por nickname, name e elementos de stack (case-insensitive).
Retorna 400 se terms for vazio ou não informado.

# GET /api/devs/:id — Detalhe de developer

    curl http://desafio.test/api/devs/f7379ae8-8f9b-4cd5-8221-51efe19e721b

- 200 OK — developer encontrado
- 404 Not Found — UUID não existe


# Frontend

Rota / — Lista de developers
Rota /developers/create — Formulário de criação
Rota /developers/:id — Detalhe de um developer


# Testes

    php artisan test

Os testes cobrem:
- Criação válida com e sem stack
- Validação 422 para valores nulos e duplicados
- Validação 400 para tipos incorretos
- Listagem e search por termos
- Detalhe por UUID com 200 e 404


# Estrutura do Projeto

    desafio/
        app/
            Http/
                Controllers/
                    Api/
                        DeveloperController.php
                    Web/
                        DeveloperWebController.php
                Requests/
                    StoreDeveloperRequest.php
                Resources/
                    DeveloperResource.php
            Models/
                Developer.php
        database/
            migrations/
                
        resources/
            views/
                layouts/
                    app.blade.php
                developers/
                    index.blade.php
                    show.blade.php
                    create.blade.php
        routes/
            api.php
            web.php
        tests/
            Feature/
                DeveloperApiTest.php


# Decisões Técnicas

Search com campo search_text — em vez de fazer LIKE em múltiplas colunas com OR, o campo search_text é gerado automaticamente na criação, concatenando nickname, name e stack em minúsculas. O search faz apenas um LIKE neste campo indexado.

Distinção 400 vs 422 — 422 para valores semanticamente inválidos como null ou nickname duplicado, 400 para tipos incorretos como name com número ou stack com inteiros. A separação é feita no StoreDeveloperRequest com validação de tipos em prepareForValidation() antes das regras do Laravel correrem.

Stack como JSON — o campo stack é guardado como JSON no SQLite com cast automático para array PHP.
