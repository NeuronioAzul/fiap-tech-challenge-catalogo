<?php

namespace App\Application\UseCases;

use App\Application\DTOs\ProdutoDTO;
use App\Domain\Repositories\ProdutoRepositoryInterface;
use App\Domain\Exceptions\ProdutoNotFoundException;

class ObterProdutoUseCase
{
    public function __construct(
        private ProdutoRepositoryInterface $produtoRepository
    ) {}

    public function execute(string $id): ProdutoDTO
    {
        $produto = $this->produtoRepository->findById($id);

        if (!$produto) {
            throw new ProdutoNotFoundException("Produto com ID $id não encontrado.");
        }

        return new ProdutoDTO(
            $produto->getId(),
            $produto->getNome(),
            $produto->getDescricao(),
            $produto->getPreco(),
            $produto->getImage(),
            $produto->getCategoriaId()
        );
    }
}
