<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\CategoriaRepositoryInterface;
use App\Domain\Exceptions\CategoriaNotFoundException;

class DeletarCategoriaUseCase
{
    public function __construct(
        private CategoriaRepositoryInterface $categoriaRepository
    ) {}

    public function execute(string $id): void
    {
        $categoria = $this->categoriaRepository->findById($id);

        if (!$categoria) {
            throw new CategoriaNotFoundException("Categoria com ID $id não encontrada.");
        }

        $this->categoriaRepository->delete($id);
    }
}
