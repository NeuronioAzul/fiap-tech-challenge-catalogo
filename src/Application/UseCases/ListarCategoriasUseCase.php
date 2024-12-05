<?php

namespace App\Application\UseCases;

use App\Application\DTOs\CategoriaDTO;
use App\Domain\Repositories\CategoriaRepositoryInterface;

class ListarCategoriasUseCase
{
    public function __construct(
        private CategoriaRepositoryInterface $categoriaRepository
    ) {}

    public function execute(): array
    {
        $categorias = $this->categoriaRepository->findAll();

        return array_map(function ($categoria) {
            return new CategoriaDTO(
                $categoria->getId(),
                $categoria->getNome()
            );
        }, $categorias);
    }
}
