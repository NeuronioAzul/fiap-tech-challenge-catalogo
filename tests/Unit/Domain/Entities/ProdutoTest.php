<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Produto;
use PHPUnit\Framework\TestCase;

class ProdutoTest extends TestCase
{
    private Produto $produto;

    protected function setUp(): void
    {
        $this->produto = new Produto('PROD123', 'Nome', 'Descrição', 10.99, 'imagem.jpg', 'CATE456');
    }

    public function testCriacaoDeProduto()
    {
        $this->assertEquals('PROD123', $this->produto->getId());
        $this->assertEquals('Nome', $this->produto->getNome());
        $this->assertEquals('Descrição', $this->produto->getDescricao());
        $this->assertEquals(10.99, $this->produto->getPreco());
        $this->assertEquals('imagem.jpg', $this->produto->getImage());
        $this->assertEquals('CATE456', $this->produto->getCategoriaId());
    }

    public function testAtualizacaoDeProduto()
    {
        $this->produto->setNome('Novo Nome');
        $this->produto->setDescricao('Nova Descrição');
        $this->produto->setPreco(15.99);
        $this->produto->setImage('nova_imagem.jpg');
        $this->produto->setCategoriaId('CATE789');

        $this->assertEquals('Novo Nome', $this->produto->getNome());
        $this->assertEquals('Nova Descrição', $this->produto->getDescricao());
        $this->assertEquals(15.99, $this->produto->getPreco());
        $this->assertEquals('nova_imagem.jpg', $this->produto->getImage());
        $this->assertEquals('CATE789', $this->produto->getCategoriaId());
    }

    public function testAtualizacaoIndividualDePropriedades()
    {
        $this->produto->setNome('Novo Nome');
        $this->assertEquals('Novo Nome', $this->produto->getNome());
        $this->assertEquals('Descrição', $this->produto->getDescricao());

        $this->produto->setDescricao('Nova Descrição');
        $this->assertEquals('Nova Descrição', $this->produto->getDescricao());
        $this->assertEquals(10.99, $this->produto->getPreco());

        $this->produto->setPreco(15.99);
        $this->assertEquals(15.99, $this->produto->getPreco());
        $this->assertEquals('imagem.jpg', $this->produto->getImage());

        $this->produto->setImage('nova_imagem.jpg');
        $this->assertEquals('nova_imagem.jpg', $this->produto->getImage());
        $this->assertEquals('CATE456', $this->produto->getCategoriaId());

        $this->produto->setCategoriaId('CATE789');
        $this->assertEquals('CATE789', $this->produto->getCategoriaId());
    }

    public function testManutencaoDeOutrasPropriedadesAoAtualizar()
    {
        $idOriginal = $this->produto->getId();
        $nomeOriginal = $this->produto->getNome();
        $descricaoOriginal = $this->produto->getDescricao();
        $precoOriginal = $this->produto->getPreco();
        $imagemOriginal = $this->produto->getImage();
        $categoriaIdOriginal = $this->produto->getCategoriaId();

        // Atualiza apenas o nome
        $this->produto->setNome('Novo Nome');

        // Verifica se apenas o nome foi alterado
        $this->assertEquals('Novo Nome', $this->produto->getNome());
        $this->assertEquals($idOriginal, $this->produto->getId());
        $this->assertEquals($descricaoOriginal, $this->produto->getDescricao());
        $this->assertEquals($precoOriginal, $this->produto->getPreco());
        $this->assertEquals($imagemOriginal, $this->produto->getImage());
        $this->assertEquals($categoriaIdOriginal, $this->produto->getCategoriaId());
    }
}
