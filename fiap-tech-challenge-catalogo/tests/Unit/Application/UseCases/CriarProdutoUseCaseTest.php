<?php

namespace Tests\Unit\Application\UseCases;

use App\Application\DTOs\ProdutoDTO;
use App\Application\UseCases\CriarProdutoUseCase;
use App\Domain\Entities\Produto;
use App\Domain\Repositories\ProdutoRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CriarProdutoUseCaseTest extends TestCase
{
    public function testExecute()
    {
        $produtoRepositoryMock = $this->createMock(ProdutoRepositoryInterface::class);
        $produtoRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Produto::class));

        $useCase = new CriarProdutoUseCase($produtoRepositoryMock);

        $dto = new ProdutoDTO(null, 'Nome', 'Descrição', 10.99, 'imagem.jpg', 'CATE456');

        $result = $useCase->execute($dto);

        $this->assertInstanceOf(ProdutoDTO::class, $result);
        $this->assertNotNull($result->id);
        $this->assertStringStartsWith('PROD', $result->id);
        $this->assertEquals('Nome', $result->nome);
        $this->assertEquals('Descrição', $result->descricao);
        $this->assertEquals(10.99, $result->preco);
        $this->assertEquals('imagem.jpg', $result->imagem);
        $this->assertEquals('CATE456', $result->categoriaId);
    }
}
