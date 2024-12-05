<?php

namespace Tests\Unit\Infrastructure\Persistence;

use PHPUnit\Framework\TestCase;
use App\Domain\Entities\Produto;
use App\Infrastructure\Persistence\MockProdutoRepository;

class MockProdutoRepositoryTest extends TestCase
{
    private MockProdutoRepository $repository;
    private Produto $produto;

    protected function setUp(): void
    {
        $this->repository = new MockProdutoRepository();
        $this->produto = new Produto(
            'PROD123',
            'Produto Teste',
            'Descrição do Produto',
            10.99,
            'imagem.jpg',
            'CATE123'
        );
    }

    public function testSaveEFindById()
    {
        $this->repository->save($this->produto);

        $produtoEncontrado = $this->repository->findById('PROD123');

        $this->assertNotNull($produtoEncontrado);
        $this->assertEquals('PROD123', $produtoEncontrado->getId());
        $this->assertEquals('Produto Teste', $produtoEncontrado->getNome());
        $this->assertEquals('Descrição do Produto', $produtoEncontrado->getDescricao());
        $this->assertEquals(10.99, $produtoEncontrado->getPreco());
        $this->assertEquals('imagem.jpg', $produtoEncontrado->getImage());
        $this->assertEquals('CATE123', $produtoEncontrado->getCategoriaId());
    }

    public function testFindByIdComProdutoInexistente()
    {
        $produto = $this->repository->findById('INEXISTENTE');
        $this->assertNull($produto);
    }

    public function testFindByNome()
    {
        $this->repository->save($this->produto);

        $produtoEncontrado = $this->repository->findByNome('Produto Teste');

        $this->assertNotNull($produtoEncontrado);
        $this->assertEquals('PROD123', $produtoEncontrado->getId());
    }

    public function testFindByNomeComProdutoInexistente()
    {
        $produto = $this->repository->findByNome('Nome Inexistente');
        $this->assertNull($produto);
    }

    public function testFindAll()
    {
        $produto2 = new Produto(
            'PROD456',
            'Outro Produto',
            'Outra Descrição',
            20.99,
            'outra-imagem.jpg',
            'CATE456'
        );

        $this->repository->save($this->produto);
        $this->repository->save($produto2);

        $produtos = $this->repository->findAll();

        $this->assertCount(2, $produtos);
        $this->assertEquals('PROD123', $produtos[0]->getId());
        $this->assertEquals('PROD456', $produtos[1]->getId());
    }

    public function testUpdate()
    {
        $this->repository->save($this->produto);

        $produtoAtualizado = new Produto(
            'PROD123',
            'Nome Atualizado',
            'Descrição Atualizada',
            15.99,
            'nova-imagem.jpg',
            'CATE789'
        );

        $this->repository->update($produtoAtualizado);

        $produtoEncontrado = $this->repository->findById('PROD123');

        $this->assertEquals('Nome Atualizado', $produtoEncontrado->getNome());
        $this->assertEquals('Descrição Atualizada', $produtoEncontrado->getDescricao());
        $this->assertEquals(15.99, $produtoEncontrado->getPreco());
        $this->assertEquals('nova-imagem.jpg', $produtoEncontrado->getImage());
        $this->assertEquals('CATE789', $produtoEncontrado->getCategoriaId());
    }

    public function testDelete()
    {
        $this->repository->save($this->produto);
        $this->assertNotNull($this->repository->findById('PROD123'));

        $this->repository->delete('PROD123');

        $this->assertNull($this->repository->findById('PROD123'));
    }

    public function testDeleteProdutoInexistente()
    {
        // Não deve lançar exceção
        $this->repository->delete('INEXISTENTE');
        $this->assertTrue(true);
    }
}
