<?php

namespace Tests\Unit\Application\DTOs;

use App\Application\DTOs\CategoriaDTO;
use PHPUnit\Framework\TestCase;

class CategoriaDTOTest extends TestCase
{
    public function testCriacaoDeCategoriaDTO()
    {
        $dto = new CategoriaDTO('CATE123', 'Nome da Categoria');

        $this->assertEquals('CATE123', $dto->id);
        $this->assertEquals('Nome da Categoria', $dto->nome);
    }

    public function testFromArray()
    {
        $data = [
            'id' => 'CATE123',
            'nome' => 'Nome da Categoria'
        ];

        $dto = CategoriaDTO::fromArray($data);

        $this->assertEquals('CATE123', $dto->id);
        $this->assertEquals('Nome da Categoria', $dto->nome);
    }

    public function testFromArraySemId()
    {
        $data = [
            'nome' => 'Nome da Categoria'
        ];

        $dto = CategoriaDTO::fromArray($data);

        $this->assertNull($dto->id);
        $this->assertEquals('Nome da Categoria', $dto->nome);
    }

    public function testToArray()
    {
        $dto = new CategoriaDTO('CATE123', 'Nome da Categoria');

        $array = $dto->toArray();

        $this->assertEquals([
            'id' => 'CATE123',
            'nome' => 'Nome da Categoria'
        ], $array);
    }

    public function testToArrayComIdNulo()
    {
        $dto = new CategoriaDTO(null, 'Nome da Categoria');

        $array = $dto->toArray();

        $this->assertEquals([
            'id' => null,
            'nome' => 'Nome da Categoria'
        ], $array);
    }

    public function testCriacaoDeCategoriaDTOComIdNulo()
    {
        $dto = new CategoriaDTO(null, 'Nome da Categoria');

        $this->assertNull($dto->id);
        $this->assertEquals('Nome da Categoria', $dto->nome);
    }
}
