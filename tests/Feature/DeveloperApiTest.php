<?php

namespace Tests\Feature;

use App\Models\Developer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Testes de Feature para a API de Developers.
 *
 * Cobre os 4 endpoints e os principais casos de validação.
 */
class DeveloperApiTest extends TestCase
{
    // Limpa a BD antes de cada teste
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // POST /api/devs
    // -------------------------------------------------------------------------

    public function test_cria_developer_com_dados_validos(): void
    {
        $resposta = $this->postJson('/api/devs', [
            'nickname'   => 'judit',
            'name'       => 'Judit Polgár',
            'birth_date' => '1976-07-23',
            'stack'      => ['C#', 'Node', 'Oracle'],
        ]);

        $resposta->assertStatus(201);
        $resposta->assertJsonFragment(['nickname' => 'judit']);
        $this->assertTrue($resposta->headers->has('Location'));
    }

    public function test_cria_developer_sem_stack(): void
    {
        $resposta = $this->postJson('/api/devs', [
            'nickname'   => 'leo',
            'name'       => 'Leonardo Barreto',
            'birth_date' => '1986-09-05',
            'stack'      => null,
        ]);

        $resposta->assertStatus(201);
        $resposta->assertJsonFragment(['stack' => null]);
    }

    public function test_retorna_422_para_nickname_duplicado(): void
    {
        // Cria o primeiro developer
        Developer::create([
            'nickname'   => 'judit',
            'name'       => 'Judit Polgár',
            'birth_date' => '1976-07-23',
            'stack'      => null,
        ]);

        // Tenta criar outro com o mesmo nickname
        $resposta = $this->postJson('/api/devs', [
            'nickname'   => 'judit',
            'name'       => 'Outro Nome',
            'birth_date' => '1990-01-01',
            'stack'      => null,
        ]);

        $resposta->assertStatus(422);
    }

    public function test_retorna_422_para_name_nulo(): void
    {
        $resposta = $this->postJson('/api/devs', [
            'nickname'   => 'leo',
            'name'       => null,
            'birth_date' => '1986-09-05',
            'stack'      => null,
        ]);

        $resposta->assertStatus(422);
    }

    public function test_retorna_422_para_nickname_nulo(): void
    {
        $resposta = $this->postJson('/api/devs', [
            'nickname'   => null,
            'name'       => 'Judit Polgár',
            'birth_date' => '1976-07-23',
            'stack'      => null,
        ]);

        $resposta->assertStatus(422);
    }

    public function test_retorna_400_para_name_numerico(): void
    {
        $resposta = $this->postJson('/api/devs', [
            'nickname'   => 'nickname',
            'name'       => 1, // tipo errado
            'birth_date' => '1985-01-01',
            'stack'      => null,
        ]);

        $resposta->assertStatus(400);
    }

    public function test_retorna_400_para_stack_com_numero(): void
    {
        $resposta = $this->postJson('/api/devs', [
            'nickname'   => 'nickname',
            'name'       => 'Nome',
            'birth_date' => '1985-01-01',
            'stack'      => [1, 'PHP'], // tipo errado no array
        ]);

        $resposta->assertStatus(400);
    }

    // -------------------------------------------------------------------------
    // GET /api/devs
    // -------------------------------------------------------------------------

    public function test_lista_developers_retorna_200(): void
    {
        $resposta = $this->getJson('/api/devs');

        $resposta->assertStatus(200);
        $this->assertTrue($resposta->headers->has('X-Total-Count'));
    }

    public function test_lista_vazia_retorna_array_vazio(): void
    {
        $resposta = $this->getJson('/api/devs');

        $resposta->assertStatus(200);
        $resposta->assertJson([]);
    }

    // -------------------------------------------------------------------------
    // GET /api/devs?terms=[:termo]
    // -------------------------------------------------------------------------

    public function test_search_por_termo_retorna_resultados_corretos(): void
    {
        // Cria dois developers
        Developer::create([
            'nickname' => 'judit', 'name' => 'Judit Polgár',
            'birth_date' => '1976-07-23', 'stack' => ['C#', 'Node', 'Oracle'],
        ]);
        Developer::create([
            'nickname' => 'leo', 'name' => 'Leonardo Barreto',
            'birth_date' => '1986-09-05', 'stack' => null,
        ]);

        // Find por "node" — deve retornar só o judit
        $resposta = $this->getJson('/api/devs?terms=node');

        $resposta->assertStatus(200);
        $resposta->assertJsonCount(1);
        $resposta->assertJsonFragment(['nickname' => 'judit']);
    }

    public function test_search_sem_resultados_retorna_array_vazio(): void
    {
        $resposta = $this->getJson('/api/devs?terms=Python');

        $resposta->assertStatus(200);
        $resposta->assertJson([]);
    }

    public function test_search_sem_terms_retorna_400(): void
    {
        $resposta = $this->getJson('/api/devs?terms=');

        $resposta->assertStatus(400);
    }

    // -------------------------------------------------------------------------
    // GET /api/devs/:id
    // -------------------------------------------------------------------------

    public function test_detalhe_de_developer_existente_retorna_200(): void
    {
        $dev = Developer::create([
            'nickname' => 'judit', 'name' => 'Judit Polgár',
            'birth_date' => '1976-07-23', 'stack' => ['C#'],
        ]);

        $resposta = $this->getJson("/api/devs/{$dev->id}");

        $resposta->assertStatus(200);
        $resposta->assertJsonFragment(['nickname' => 'judit']);
    }

    public function test_detalhe_de_developer_inexistente_retorna_404(): void
    {
        $resposta = $this->getJson('/api/devs/uuid-que-nao-existe');

        $resposta->assertStatus(404);
    }
}
