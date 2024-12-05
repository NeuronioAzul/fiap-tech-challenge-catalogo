<?php

namespace App\Application\UseCases;

use App\Application\DTOs\ProdutoDTO;
use App\Domain\Repositories\ProdutoRepositoryInterface;

class ListarProdutosUseCase
{
    public function __construct(
        private ProdutoRepositoryInterface $produtoRepository
    ) {}

    public function execute(): array
    {
        $produtos = $this->produtoRepository->findAll();

        return array_map(function ($produto) {
            return new ProdutoDTO(
                $produto->getId(),
                $produto->getNome(),
                $produto->getDescricao(),
                $produto->getPreco(),
                $produto->getImage(),
                $produto->getCategoriaId()
            );
        }, $produtos);
    }
}
