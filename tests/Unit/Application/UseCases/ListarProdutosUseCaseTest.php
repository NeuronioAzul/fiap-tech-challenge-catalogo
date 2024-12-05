<?php

namespace Tests\Unit\Application\UseCases;

use App\Application\UseCases\ListarProdutosUseCase;
use App\Domain\Entities\Produto;
use App\Domain\Repositories\ProdutoRepositoryInterface;
use PHPUnit\Framework\TestCase;

class ListarProdutosUseCaseTest extends TestCase
{
    public function testExecute()
    {
        $produtos = [
            new Produto('PROD123', 'Nome 1', 'Descrição 1', 9.99, 'imagem1.jpg', 'CATE456'),
            new Produto('PROD456', 'Nome 2', 'Descrição 2', 19.99, 'imagem2.jpg', 'CATE789')
        ];

        $produtoRepositoryMock = $this->createMock(ProdutoRepositoryInterface::class);
        $produtoRepositoryMock->expects($this->once())
            ->method('findAll')
            ->willReturn($produtos);

        $useCase = new ListarProdutosUseCase($produtoRepositoryMock);

        $result = $useCase->execute();

        $this->assertCount(2, $result);
        $this->assertEquals('PROD123', $result[0]->id);
        $this->assertEquals('PROD456', $result[1]->id);
    }
}
