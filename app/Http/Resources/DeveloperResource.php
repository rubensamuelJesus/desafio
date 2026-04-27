<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeveloperResource extends JsonResource
{
    /**
     * Transforma o modelo Developer numa resposta JSON.
     *
     * Garante o formato correto conforme o desafio:
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'nickname'   => $this->nickname,
            'name'       => $this->name,
            'birth_date' => $this->birth_date,
            // null em vez de [] quando não há stack
            'stack'      => $this->stack ?: null,
        ];
    }
}
