<?php

namespace App\Application\UseCases;

use App\Application\DTOs\ProdutoDTO;
use App\Domain\Entities\Produto;
use App\Domain\Repositories\ProdutoRepositoryInterface;

class CriarProdutoUseCase
{
    public function __construct(
        private ProdutoRepositoryInterface $produtoRepository
    ) {}

    public function execute(ProdutoDTO $dto): ProdutoDTO
    {
        $id = 'PROD' . uniqid();
        $produto = new Produto(
            $id,
            $dto->nome,
            $dto->descricao,
            $dto->preco,
            $dto->imagem,
            $dto->categoriaId
        );

        $this->produtoRepository->save($produto);

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
