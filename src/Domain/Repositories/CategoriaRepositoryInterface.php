<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Categoria;

interface CategoriaRepositoryInterface
{
    public function findById(string $id): ?Categoria;
    public function findAll(): array;
    public function save(Categoria $categoria): void;
    public function update(Categoria $categoria): void;
    public function delete(string $id): void;
}
