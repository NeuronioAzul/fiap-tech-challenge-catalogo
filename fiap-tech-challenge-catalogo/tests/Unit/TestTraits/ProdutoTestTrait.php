<?php

namespace Tests\Unit\TestTraits;

use App\Domain\Entities\Produto;
use App\Domain\Repositories\ProdutoRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;

trait ProdutoTestTrait
{
    protected ProdutoRepositoryInterface|MockObject $produtoRepository;
    protected Produto $produtoExistente;

    protected function setupProdutoMock(): void
    {
        $this->produtoExistente = new Produto(
            'PROD123',
            'Produto Teste',
            'Descrição do Produto',
            10.99,
            'imagem.jpg',
            'CATE123'
        );
        $this->produtoRepository = $this->createMock(ProdutoRepositoryInterface::class);
    }

    protected function mockFindById(string $id, ?Produto $return): void
    {
        $this->produtoRepository->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn($return);
    }

    protected function mockUpdate(callable $callback = null): void
    {
        $expectation = $this->produtoRepository->expects($this->once())
            ->method('update');

        if ($callback) {
            $expectation->with($this->callback($callback));
        } else {
            $expectation->with($this->isInstanceOf(Produto::class));
        }
    }
}
