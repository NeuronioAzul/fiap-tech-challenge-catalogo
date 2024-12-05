<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\Produto;
use App\Domain\Repositories\ProdutoRepositoryInterface;

class MockProdutoRepository implements ProdutoRepositoryInterface
{
    private array $produtos = [];

    public function save(Produto $produto): void
    {
        $this->produtos[$produto->getId()] = $produto;
    }

    public function findById(string $id): ?Produto
    {
        return $this->produtos[$id] ?? null;
    }

    public function findByNome(string $nome): ?Produto
    {
        foreach ($this->produtos as $produto) {
            if ($produto->getNome() === $nome) {
                return $produto;
            }
        }
        return null;
    }

    public function findAll(): array
    {
        return array_values($this->produtos);
    }

    public function update(Produto $produto): void
    {
        $this->produtos[$produto->getId()] = $produto;
    }

    public function delete(string $id): void
    {
        unset($this->produtos[$id]);
    }
}
