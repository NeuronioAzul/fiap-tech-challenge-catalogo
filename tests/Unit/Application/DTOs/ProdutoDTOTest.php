<?php

namespace Tests\Unit\Application\DTOs;

use App\Application\DTOs\ProdutoDTO;
use PHPUnit\Framework\TestCase;

class ProdutoDTOTest extends TestCase
{
    public function testCriacaoDeProdutoDTO()
    {
        $dto = new ProdutoDTO('PROD123', 'Nome', 'Descrição', 10.99, 'imagem.jpg', 'CATE456');

        $this->assertEquals('PROD123', $dto->id);
        $this->assertEquals('Nome', $dto->nome);
        $this->assertEquals('Descrição', $dto->descricao);
        $this->assertEquals(10.99, $dto->preco);
        $this->assertEquals('imagem.jpg', $dto->imagem);
        $this->assertEquals('CATE456', $dto->categoriaId);
    }

    public function testFromArray()
    {
        $data = [
            'id' => 'PROD123',
            'nome' => 'Nome',
            'descricao' => 'Descrição',
            'preco' => 10.99,
            'imagem' => 'imagem.jpg',
            'categoria_id' => 'CATE456'
        ];

        $dto = ProdutoDTO::fromArray($data);

        $this->assertEquals('PROD123', $dto->id);
        $this->assertEquals('Nome', $dto->nome);
        $this->assertEquals('Descrição', $dto->descricao);
        $this->assertEquals(10.99, $dto->preco);
        $this->assertEquals('imagem.jpg', $dto->imagem);
        $this->assertEquals('CATE456', $dto->categoriaId);
    }

    public function testFromArraySemId()
    {
        $data = [
            'nome' => 'Nome',
            'descricao' => 'Descrição',
            'preco' => 10.99,
            'imagem' => 'imagem.jpg',
            'categoria_id' => 'CATE456'
        ];

        $dto = ProdutoDTO::fromArray($data);

        $this->assertNull($dto->id);
        $this->assertEquals('Nome', $dto->nome);
        $this->assertEquals('Descrição', $dto->descricao);
        $this->assertEquals(10.99, $dto->preco);
        $this->assertEquals('imagem.jpg', $dto->imagem);
        $this->assertEquals('CATE456', $dto->categoriaId);
    }

    public function testToArray()
    {
        $dto = new ProdutoDTO('PROD123', 'Nome', 'Descrição', 10.99, 'imagem.jpg', 'CATE456');

        $array = $dto->toArray();

        $this->assertEquals([
            'id' => 'PROD123',
            'nome' => 'Nome',
            'descricao' => 'Descrição',
            'preco' => 10.99,
            'imagem' => 'imagem.jpg',
            'categoria_id' => 'CATE456'
        ], $array);
    }

    public function testToArrayComIdNulo()
    {
        $dto = new ProdutoDTO(null, 'Nome', 'Descrição', 10.99, 'imagem.jpg', 'CATE456');

        $array = $dto->toArray();

        $this->assertEquals([
            'id' => null,
            'nome' => 'Nome',
            'descricao' => 'Descrição',
            'preco' => 10.99,
            'imagem' => 'imagem.jpg',
            'categoria_id' => 'CATE456'
        ], $array);
    }
}
