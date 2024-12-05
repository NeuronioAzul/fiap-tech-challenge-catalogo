<?php

namespace App\Application\UseCases;

use App\Application\DTOs\CategoriaDTO;
use App\Domain\Entities\Categoria;
use App\Domain\Repositories\CategoriaRepositoryInterface;

class CriarCategoriaUseCase
{
    public function __construct(
        private CategoriaRepositoryInterface $categoriaRepository
    ) {}

    public function execute(CategoriaDTO $dto): CategoriaDTO
    {
        $id = 'CATE' . uniqid();
        $categoria = new Categoria($id, $dto->nome);

        $this->categoriaRepository->save($categoria);

        return new CategoriaDTO(
            $categoria->getId(),
            $categoria->getNome()
        );
    }
}
