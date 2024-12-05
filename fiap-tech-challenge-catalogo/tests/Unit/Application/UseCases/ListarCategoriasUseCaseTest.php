<?php

namespace Tests\Unit\Application\UseCases;

use App\Application\UseCases\ListarCategoriasUseCase;
use App\Domain\Entities\Categoria;
use App\Domain\Repositories\CategoriaRepositoryInterface;
use PHPUnit\Framework\TestCase;

class ListarCategoriasUseCaseTest extends TestCase
{
    public function testExecute()
    {
        $categorias = [
            new Categoria('CATE123', 'Categoria 1'),
            new Categoria('CATE456', 'Categoria 2')
        ];

        $categoriaRepositoryMock = $this->createMock(CategoriaRepositoryInterface::class);
        $categoriaRepositoryMock->expects($this->once())
            ->method('findAll')
            ->willReturn($categorias);

        $useCase = new ListarCategoriasUseCase($categoriaRepositoryMock);

        $result = $useCase->execute();

        $this->assertCount(2, $result);
        $this->assertEquals('CATE123', $result[0]->id);
        $this->assertEquals('CATE456', $result[1]->id);
    }
}
