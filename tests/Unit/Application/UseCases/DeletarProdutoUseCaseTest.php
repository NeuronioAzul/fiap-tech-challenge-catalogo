<?php

namespace Tests\Unit\Application\UseCases;

use App\Application\UseCases\DeletarProdutoUseCase;
use App\Domain\Entities\Produto;
use App\Domain\Repositories\ProdutoRepositoryInterface;
use App\Domain\Exceptions\ProdutoNotFoundException;
use PHPUnit\Framework\TestCase;

class DeletarProdutoUseCaseTest extends TestCase
{
    public function testExecuteComSucesso()
    {
        $produtoExistente = new Produto('PROD123', 'Nome', 'Descrição', 9.99, 'imagem.jpg', 'CATE456');

        $produtoRepositoryMock = $this->createMock(ProdutoRepositoryInterface::class);
        $produtoRepositoryMock->expects($this->once())
            ->method('findById')
            ->with('PROD123')
            ->willReturn($produtoExistente);
        $produtoRepositoryMock->expects($this->once())
            ->method('delete')
            ->with('PROD123');

        $useCase = new DeletarProdutoUseCase($produtoRepositoryMock);

        $useCase->execute('PROD123');
    }

    public function testExecuteComProdutoNaoEncontrado()
    {
        $produtoRepositoryMock = $this->createMock(ProdutoRepositoryInterface::class);
        $produtoRepositoryMock->expects($this->once())
            ->method('findById')
            ->with('PROD123')
            ->willReturn(null);

        $useCase = new DeletarProdutoUseCase($produtoRepositoryMock);

        $this->expectException(ProdutoNotFoundException::class);
        $useCase->execute('PROD123');
    }
}
