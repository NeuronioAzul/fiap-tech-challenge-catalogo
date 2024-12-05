<?php

namespace App\Application\UseCases;

use App\Application\DTOs\CategoriaDTO;
use App\Domain\Repositories\CategoriaRepositoryInterface;
use App\Domain\Exceptions\CategoriaNotFoundException;

class AtualizarCategoriaUseCase
{
    public function __construct(
        private CategoriaRepositoryInterface $categoriaRepository
    ) {}

    public function execute(string $id, CategoriaDTO $dto): CategoriaDTO
    {
        $categoria = $this->categoriaRepository->findById($id);

        if (!$categoria) {
            throw new CategoriaNotFoundException("Categoria com ID $id não encontrada.");
        }

        $categoria->setNome($dto->nome);

        $this->categoriaRepository->update($categoria);

        return new CategoriaDTO(
            $categoria->getId(),
            $categoria->getNome()
        );
    }
}
