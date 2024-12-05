<?php

namespace Tests\Unit\Application\UseCases;

use App\Application\DTOs\CategoriaDTO;
use App\Application\UseCases\CriarCategoriaUseCase;
use App\Domain\Entities\Categoria;
use App\Domain\Repositories\CategoriaRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CriarCategoriaUseCaseTest extends TestCase
{
    public function testExecute()
    {
        $categoriaRepositoryMock = $this->createMock(CategoriaRepositoryInterface::class);
        $categoriaRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Categoria::class));

        $useCase = new CriarCategoriaUseCase($categoriaRepositoryMock);

        $dto = new CategoriaDTO(null, 'Nome da Categoria');

        $result = $useCase->execute($dto);

        $this->assertInstanceOf(CategoriaDTO::class, $result);
        $this->assertNotNull($result->id);
        $this->assertStringStartsWith('CATE', $result->id);
        $this->assertEquals('Nome da Categoria', $result->nome);
    }
}
