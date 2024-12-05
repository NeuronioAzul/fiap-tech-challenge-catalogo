<?php

namespace App\Application\DTOs;

class ProdutoDTO
{
    public function __construct(
        public readonly ?string $id,
        public readonly string $nome,
        public readonly string $descricao,
        public readonly float $preco,
        public readonly string $imagem,
        public readonly string $categoriaId
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['nome'],
            $data['descricao'],
            $data['preco'],
            $data['imagem'],
            $data['categoria_id']
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'preco' => $this->preco,
            'imagem' => $this->imagem,
            'categoria_id' => $this->categoriaId
        ];
    }
}
