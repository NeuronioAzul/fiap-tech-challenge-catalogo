<?php

namespace App\Application\UseCases;

use App\Application\DTOs\ProdutoDTO;
use App\Domain\Repositories\ProdutoRepositoryInterface;
use App\Domain\Exceptions\ProdutoNotFoundException;

class AtualizarProdutoUseCase
{
    public function __construct(
        private ProdutoRepositoryInterface $produtoRepository
    ) {}

    public function execute(string $id, ProdutoDTO $dto): ProdutoDTO
    {
        $produto = $this->produtoRepository->findById($id);

        if (!$produto) {
            throw new ProdutoNotFoundException("Produto com ID $id não encontrado.");
        }

        $produto->setNome($dto->nome);
        $produto->setDescricao($dto->descricao);
        $produto->setPreco($dto->preco);
        $produto->setImage($dto->imagem);
        $produto->setCategoriaId($dto->categoriaId);

        $this->produtoRepository->update($produto);

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
