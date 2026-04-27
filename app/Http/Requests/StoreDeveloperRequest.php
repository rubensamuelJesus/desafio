<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreDeveloperRequest extends FormRequest
{
    /**
     * Qualquer utilizador pode fazer este pedido.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     *
     * Distinção importante:
     * - 422: campos obrigatórios em falta ou inválidos (null, único)
     * - 400: tipos errados
     */
    public function rules(): array
    {
        return [
            // Nickname: obrigatório, string, único na tabela, máx 32 chars
            'nickname' => ['required', 'string', 'max:32', 'unique:developers,nickname'],

            // Name: obrigatório, string, máx 100 chars
            'name'     => ['required', 'string', 'max:100'],

            // Data de nascimento: obrigatório, string no formato YYYY-MM-DD
            'birth_date' => ['required', 'string', 'date_format:Y-m-d'],

            // Stack: opcional, array de strings, cada elemento máx 32 chars
            'stack'    => ['nullable', 'array'],
            'stack.*'  => ['string', 'max:32'],
        ];
    }

    /**
     * Mensagens de erro.
     */
    public function messages(): array
    {
        return [
            'nickname.required' => 'O nickname é obrigatório.',
            'nickname.string'   => 'O nickname deve ser uma string.',
            'nickname.max'      => 'O nickname não pode ter mais de 32 caracteres.',
            'nickname.unique'   => 'Este nickname já está em uso.',
            'name.required'     => 'O nome é obrigatório.',
            'name.string'       => 'O nome deve ser uma string.',
            'name.max'          => 'O nome não pode ter mais de 100 caracteres.',
            'birth_date.required'    => 'A data de nascimento é obrigatória.',
            'birth_date.string'      => 'A data de nascimento deve ser uma string.',
            'birth_date.date_format' => 'A data de nascimento deve estar no formato YYYY-MM-DD.',
            'stack.array'   => 'A stack deve ser um array.',
            'stack.*.string' => 'Cada elemento da stack deve ser uma string.',
            'stack.*.max'    => 'Cada elemento da stack não pode ter mais de 32 caracteres.',
        ];
    }

    /**
     * Valida os tipos ANTES das regras normais.
     * Retorna 400 se os tipos forem inválidos.
     */
    protected function prepareForValidation(): void
    {
        $data = $this->all();

        // Verifica se 'name' existe e não é string (mas não é null — null é 422)
        if (array_key_exists('name', $data) && $data['name'] !== null && !is_string($data['name'])) {
            $this->throwBadRequest('O campo name deve ser uma string.');
        }

        // Verifica se 'nickname' existe e não é string
        if (array_key_exists('nickname', $data) && $data['nickname'] !== null && !is_string($data['nickname'])) {
            $this->throwBadRequest('O campo nickname deve ser uma string.');
        }

        // Verifica se 'birth_date' existe e não é string
        if (array_key_exists('birth_date', $data) && $data['birth_date'] !== null && !is_string($data['birth_date'])) {
            $this->throwBadRequest('O campo birth_date deve ser uma string.');
        }

        // Verifica se 'stack' é array e cada elemento é string
        if (array_key_exists('stack', $data) && $data['stack'] !== null) {
            if (!is_array($data['stack'])) {
                $this->throwBadRequest('O campo stack deve ser um array.');
            }

            foreach ($data['stack'] as $item) {
                if (!is_string($item)) {
                    $this->throwBadRequest('Cada elemento da stack deve ser uma string.');
                }
            }
        }
    }

    /**
     * Lança exceção HTTP 400 - Bad Request.
     */
    private function throwBadRequest(string $message): void
    {
        throw new HttpResponseException(
            response()->json(['message' => $message], 400)
        );
    }

    /**
     * Falha de validação → retorna 422 com os erros.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Dados inválidos.',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
