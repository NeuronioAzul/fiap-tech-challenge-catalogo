<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Produto;

interface ProdutoRepositoryInterface
{
    public function findById(string $id): ?Produto;
    public function findAll(): array;
    public function save(Produto $produto): void;
    public function update(Produto $produto): void;
    public function delete(string $id): void;
}
