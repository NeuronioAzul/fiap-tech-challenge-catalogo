<?php

namespace Tests\Unit\Application\UseCases;

use App\Application\DTOs\ProdutoDTO;
use App\Application\UseCases\AtualizarProdutoUseCase;
use App\Domain\Entities\Produto;
use App\Domain\Repositories\ProdutoRepositoryInterface;
use App\Domain\Exceptions\ProdutoNotFoundException;
use PHPUnit\Framework\TestCase;

class AtualizarProdutoUseCaseTest extends TestCase
{
    private ProdutoRepositoryInterface $produtoRepository;
    private AtualizarProdutoUseCase $useCase;
    private Produto $produtoExistente;

    protected function setUp(): void
    {
        $this->produtoExistente = new Produto('PROD123', 'Nome Antigo', 'Descrição Antiga', 9.99, 'imagem_antiga.jpg', 'CATE456');
        $this->produtoRepository = $this->createMock(ProdutoRepositoryInterface::class);
        $this->useCase = new AtualizarProdutoUseCase($this->produtoRepository);
    }

    public function testExecuteComSucesso()
    {
        $this->produtoRepository->expects($this->once())
            ->method('findById')
            ->with('PROD123')
            ->willReturn($this->produtoExistente);

        $this->produtoRepository->expects($this->once())
            ->method('update')
            ->with($this->isInstanceOf(Produto::class));

        $dto = new ProdutoDTO('PROD123', 'Nome Novo', 'Descrição Nova', 10.99, 'imagem_nova.jpg', 'CATE789');

        $result = $this->useCase->execute('PROD123', $dto);

        $this->assertInstanceOf(ProdutoDTO::class, $result);
        $this->assertEquals('PROD123', $result->id);
        $this->assertEquals('Nome Novo', $result->nome);
        $this->assertEquals('Descrição Nova', $result->descricao);
        $this->assertEquals(10.99, $result->preco);
        $this->assertEquals('imagem_nova.jpg', $result->imagem);
        $this->assertEquals('CATE789', $result->categoriaId);
    }

    public function testExecuteComProdutoNaoEncontrado()
    {
        $this->produtoRepository->expects($this->once())
            ->method('findById')
            ->with('PROD123')
            ->willReturn(null);

        $dto = new ProdutoDTO('PROD123', 'Nome Novo', 'Descrição Nova', 10.99, 'imagem_nova.jpg', 'CATE789');

        $this->expectException(ProdutoNotFoundException::class);
        $this->useCase->execute('PROD123', $dto);
    }

    public function testExecuteAtualizacaoParcial()
    {
        $this->produtoRepository->expects($this->once())
            ->method('findById')
            ->with('PROD123')
            ->willReturn($this->produtoExistente);

        $this->produtoRepository->expects($this->once())
            ->method('update')
            ->with($this->callback(function ($produto) {
                return $produto->getId() === 'PROD123' &&
                    $produto->getNome() === 'Nome Novo' &&
                    $produto->getDescricao() === 'Descrição Antiga' &&
                    $produto->getPreco() === 9.99 &&
                    $produto->getImage() === 'imagem_antiga.jpg' &&
                    $produto->getCategoriaId() === 'CATE456';
            }));

        $dto = new ProdutoDTO('PROD123', 'Nome Novo', 'Descrição Antiga', 9.99, 'imagem_antiga.jpg', 'CATE456');

        $result = $this->useCase->execute('PROD123', $dto);

        $this->assertEquals('Nome Novo', $result->nome);
        $this->assertEquals('Descrição Antiga', $result->descricao);
        $this->assertEquals(9.99, $result->preco);
    }

    public function testExecuteComIdsDiferentes()
    {
        $this->produtoRepository->expects($this->once())
            ->method('findById')
            ->with('PROD123')
            ->willReturn($this->produtoExistente);

        $this->produtoRepository->expects($this->once())
            ->method('update');

        $dto = new ProdutoDTO('PROD456', 'Nome Novo', 'Descrição Nova', 10.99, 'imagem_nova.jpg', 'CATE789');

        $result = $this->useCase->execute('PROD123', $dto);

        $this->assertEquals('PROD123', $result->id);
    }

    public function testExecuteComPrecoZero()
    {
        $this->produtoRepository->expects($this->once())
            ->method('findById')
            ->with('PROD123')
            ->willReturn($this->produtoExistente);

        $this->produtoRepository->expects($this->once())
            ->method('update');

        $dto = new ProdutoDTO('PROD123', 'Nome Novo', 'Descrição Nova', 0.00, 'imagem_nova.jpg', 'CATE789');

        $result = $this->useCase->execute('PROD123', $dto);

        $this->assertEquals(0.00, $result->preco);
    }
}
