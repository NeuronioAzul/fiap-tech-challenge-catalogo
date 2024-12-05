<?php

namespace Tests\Unit\Application\UseCases;

use App\Application\UseCases\ObterCategoriaUseCase;
use App\Domain\Entities\Categoria;
use App\Domain\Repositories\CategoriaRepositoryInterface;
use App\Domain\Exceptions\CategoriaNotFoundException;
use PHPUnit\Framework\TestCase;

class ObterCategoriaUseCaseTest extends TestCase
{
    public function testExecuteComSucesso()
    {
        $categoriaExistente = new Categoria('CATE123', 'Nome da Categoria');

        $categoriaRepositoryMock = $this->createMock(CategoriaRepositoryInterface::class);
        $categoriaRepositoryMock->expects($this->once())
            ->method('findById')
            ->with('CATE123')
            ->willReturn($categoriaExistente);

        $useCase = new ObterCategoriaUseCase($categoriaRepositoryMock);

        $result = $useCase->execute('CATE123');

        $this->assertEquals('CATE123', $result->id);
        $this->assertEquals('Nome da Categoria', $result->nome);
    }

    public function testExecuteComCategoriaNaoEncontrada()
    {
        $categoriaRepositoryMock = $this->createMock(CategoriaRepositoryInterface::class);
        $categoriaRepositoryMock->expects($this->once())
            ->method('findById')
            ->with('CATE123')
            ->willReturn(null);

        $useCase = new ObterCategoriaUseCase($categoriaRepositoryMock);

        $this->expectException(CategoriaNotFoundException::class);
        $useCase->execute('CATE123');
    }
}
