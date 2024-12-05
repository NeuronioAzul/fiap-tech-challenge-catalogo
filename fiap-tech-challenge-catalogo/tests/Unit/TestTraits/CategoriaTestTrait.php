<?php

namespace Tests\Unit\TestTraits;

use App\Domain\Entities\Categoria;
use App\Domain\Repositories\CategoriaRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;

trait CategoriaTestTrait
{
    protected CategoriaRepositoryInterface|MockObject $categoriaRepository;
    protected Categoria $categoriaExistente;

    protected function setupCategoriaMock(): void
    {
        $this->categoriaExistente = new Categoria('CATE123', 'Nome Antigo');
        $this->categoriaRepository = $this->createMock(CategoriaRepositoryInterface::class);
    }

    protected function mockFindById(string $id, ?Categoria $return): void
    {
        $this->categoriaRepository->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn($return);
    }

    protected function mockUpdate(callable $callback = null): void
    {
        $expectation = $this->categoriaRepository->expects($this->once())
            ->method('update');

        if ($callback) {
            $expectation->with($this->callback($callback));
        } else {
            $expectation->with($this->isInstanceOf(Categoria::class));
        }
    }
}
