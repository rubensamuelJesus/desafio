<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeveloperRequest;
use App\Http\Resources\DeveloperResource;
use App\Models\Developer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DeveloperController extends Controller
{
    /**
     * POST /devs
     *
     * Cria um novo developer.
     * Retorna 201 com o header Location apontando para o recurso criado.
     */
    public function store(StoreDeveloperRequest $request): JsonResponse
    {
        // Os dados já foram validados pelo Form Request
        $developer = Developer::create([
            'nickname'   => $request->nickname,
            'name'       => $request->name,
            'birth_date' => $request->birth_date,
            'stack'      => $request->stack,
        ]);

        return response()->json(
            new DeveloperResource($developer),
            201,
            // Header Location conforme especificado
            ['Location' => "/devs/{$developer->id}"]
        );
    }

    /**
     * GET /devs
     * GET /devs?terms=[:termo]
     *
     * Lista developers ou find por termo.
     * O header X-Total-Count indica o total de registos na base de dados.
     *
     * Se o parâmetro 'terms' estiver presente mas vazio : 400 Bad Request.
     */
    public function index(Request $request): JsonResponse
    {
        // Se 'terms' foi enviado valida
        if ($request->has('terms')) {
            $terms = $request->query('terms');

            // terms vazio : 400
            if (empty($terms)) {
                return response()->json(
                    ['message' => 'O parâmetro terms é obrigatório e não pode estar vazio.'],
                    400
                );
            }

            // Procura por termos nos campos nickname, name e stack
            $developers = Developer::search($terms)->limit(20)->get();

            return response()->json(
                DeveloperResource::collection($developers),
                200,
                ['X-Total-Count' => Developer::count()]
            );
        }

        // Listagem normal: devolve os primeiros 20 registos
        $developers = Developer::limit(20)->get();

        return response()->json(
            DeveloperResource::collection($developers),
            200,
            // Header com o total de registos na base de dados
            ['X-Total-Count' => Developer::count()]
        );
    }

    /**
     * GET /devs/:id
     *
     * Devolve os detalhes de um developer pelo UUID.
     * Retorna 404 se não existir.
     */
    public function show(string $id): JsonResponse
    {
        $developer = Developer::find($id);

        if (!$developer) {
            return response()->json(
                ['message' => 'Developer não encontrado.'],
                404
            );
        }

        return response()->json(new DeveloperResource($developer), 200);
    }
}
