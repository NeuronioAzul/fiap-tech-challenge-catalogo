<?php

namespace Tests\Unit\Application\UseCases;

use App\Application\UseCases\DeletarCategoriaUseCase;
use App\Domain\Entities\Categoria;
use App\Domain\Repositories\CategoriaRepositoryInterface;
use App\Domain\Exceptions\CategoriaNotFoundException;
use PHPUnit\Framework\TestCase;

class DeletarCategoriaUseCaseTest extends TestCase
{
    public function testExecuteComSucesso()
    {
        $categoriaExistente = new Categoria('CATE123', 'Nome');

        $categoriaRepositoryMock = $this->createMock(CategoriaRepositoryInterface::class);
        $categoriaRepositoryMock->expects($this->once())
            ->method('findById')
            ->with('CATE123')
            ->willReturn($categoriaExistente);
        $categoriaRepositoryMock->expects($this->once())
            ->method('delete')
            ->with('CATE123');

        $useCase = new DeletarCategoriaUseCase($categoriaRepositoryMock);

        $useCase->execute('CATE123');
    }

    public function testExecuteComCategoriaNaoEncontrada()
    {
        $categoriaRepositoryMock = $this->createMock(CategoriaRepositoryInterface::class);
        $categoriaRepositoryMock->expects($this->once())
            ->method('findById')
            ->with('CATE123')
            ->willReturn(null);

        $useCase = new DeletarCategoriaUseCase($categoriaRepositoryMock);

        $this->expectException(CategoriaNotFoundException::class);
        $useCase->execute('CATE123');
    }
}
