<?php

namespace App\Application\UseCases;

use App\Application\DTOs\CategoriaDTO;
use App\Domain\Repositories\CategoriaRepositoryInterface;
use App\Domain\Exceptions\CategoriaNotFoundException;

class ObterCategoriaUseCase
{
    public function __construct(
        private CategoriaRepositoryInterface $categoriaRepository
    ) {}

    public function execute(string $id): CategoriaDTO
    {
        $categoria = $this->categoriaRepository->findById($id);

        if (!$categoria) {
            throw new CategoriaNotFoundException("Categoria com ID $id não encontrada.");
        }

        return new CategoriaDTO(
            $categoria->getId(),
            $categoria->getNome()
        );
    }
}
