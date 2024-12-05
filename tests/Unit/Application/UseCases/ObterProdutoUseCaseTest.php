<?php

namespace Tests\Unit\Application\UseCases;

use App\Application\UseCases\ObterProdutoUseCase;
use App\Domain\Entities\Produto;
use App\Domain\Repositories\ProdutoRepositoryInterface;
use App\Domain\Exceptions\ProdutoNotFoundException;
use PHPUnit\Framework\TestCase;

class ObterProdutoUseCaseTest extends TestCase
{
    public function testExecuteComSucesso()
    {
        $produtoExistente = new Produto('PROD123', 'Nome', 'Descrição', 9.99, 'imagem.jpg', 'CATE456');

        $produtoRepositoryMock = $this->createMock(ProdutoRepositoryInterface::class);
        $produtoRepositoryMock->expects($this->once())
            ->method('findById')
            ->with('PROD123')
            ->willReturn($produtoExistente);

        $useCase = new ObterProdutoUseCase($produtoRepositoryMock);

        $result = $useCase->execute('PROD123');

        $this->assertEquals('PROD123', $result->id);
        $this->assertEquals('Nome', $result->nome);
        $this->assertEquals('Descrição', $result->descricao);
        $this->assertEquals(9.99, $result->preco);
        $this->assertEquals('imagem.jpg', $result->imagem);
        $this->assertEquals('CATE456', $result->categoriaId);
    }

    public function testExecuteComProdutoNaoEncontrado()
    {
        $produtoRepositoryMock = $this->createMock(ProdutoRepositoryInterface::class);
        $produtoRepositoryMock->expects($this->once())
            ->method('findById')
            ->with('PROD123')
            ->willReturn(null);

        $useCase = new ObterProdutoUseCase($produtoRepositoryMock);

        $this->expectException(ProdutoNotFoundException::class);
        $useCase->execute('PROD123');
    }
}
