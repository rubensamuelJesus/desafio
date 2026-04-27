<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Developer extends Model
{
    /**
     * Nome da tabela na base de dados.
     */
    protected $table = 'developers';

    /**
     * Chave primária é um UUID, não auto-increment.
     */
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Campos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'id',
        'nickname',
        'name',
        'birth_date',
        'stack',
        'search_text',
    ];

    /**
     * Casting automático dos campos.
     * Campo stack é guardado como JSON e convertido automaticamente para array.
     */
    protected $casts = [
        'stack' => 'array',
    ];

    /**
     * Campos que não devem aparecer nas respostas JSON.
     */
    protected $hidden = [
        'search_text',
        'created_at',
        'updated_at',
    ];

    /**
     * Gera automaticamente um UUID antes de criar o registo.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Developer $developer) {
            // Gera UUID v4 se não tiver sido definido
            if (empty($developer->id)) {
                $developer->id = (string) Str::uuid();
            }

            // Gera o texto de busca
            $developer->search_text = self::buildSearchText(
                $developer->nickname,
                $developer->name,
                $developer->stack
            );
        });
    }

    /**
     * Constrói a string de busca concatenando nickname, name e stack.
     * 
     *
     * @param string $nickname
     * @param string $name
     * @param array|null $stack
     * @return string
     */
    public static function buildSearchText(string $nickname, string $name, ?array $stack): string
    {
        $parts = [$nickname, $name];

        if (!empty($stack)) {
            $parts = array_merge($parts, $stack);
        }

        // Converte para minúsculas 
        return strtolower(implode(' ', $parts));
    }

    /**
     * Busca por termo.
     * Pesquisa no campo search_text que já tem tudo concatenado.
     */
    public function scopeSearch($query, string $term)
    {
        return $query->where('search_text', 'LIKE', '%' . strtolower($term) . '%');
    }
}
