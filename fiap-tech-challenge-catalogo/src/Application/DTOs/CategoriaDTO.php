<?php

namespace App\Application\DTOs;

class CategoriaDTO
{
    public function __construct(
        public readonly ?string $id,
        public readonly string $nome
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['nome']
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome
        ];
    }
}
