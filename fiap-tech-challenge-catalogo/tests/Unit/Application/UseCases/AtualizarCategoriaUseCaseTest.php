<?php

namespace Tests\Unit\Application\UseCases;

use App\Application\DTOs\CategoriaDTO;
use App\Application\UseCases\AtualizarCategoriaUseCase;
use App\Domain\Exceptions\CategoriaNotFoundException;
use PHPUnit\Framework\TestCase;
use Tests\Unit\TestTraits\CategoriaTestTrait;

class AtualizarCategoriaUseCaseTest extends TestCase
{
    use CategoriaTestTrait;

    private AtualizarCategoriaUseCase $useCase;

    protected function setUp(): void
    {
        $this->setupCategoriaMock();
        $this->useCase = new AtualizarCategoriaUseCase($this->categoriaRepository);
    }

    public function testExecuteComSucesso()
    {
        $this->mockFindById('CATE123', $this->categoriaExistente);
        $this->mockUpdate();

        $dto = new CategoriaDTO('CATE123', 'Nome Novo');
        $result = $this->useCase->execute('CATE123', $dto);

        $this->assertInstanceOf(CategoriaDTO::class, $result);
        $this->assertEquals('CATE123', $result->id);
        $this->assertEquals('Nome Novo', $result->nome);
    }

    public function testExecuteComCategoriaNaoEncontrada()
    {
        $this->mockFindById('CATE123', null);

        $dto = new CategoriaDTO('CATE123', 'Nome Novo');

        $this->expectException(CategoriaNotFoundException::class);
        $this->useCase->execute('CATE123', $dto);
    }

    public function testExecuteComIdsDiferentes()
    {
        $this->mockFindById('CATE123', $this->categoriaExistente);
        $this->mockUpdate(function ($categoria) {
            return $categoria->getId() === 'CATE123' &&
                $categoria->getNome() === 'Nome Novo';
        });

        $dto = new CategoriaDTO('CATE456', 'Nome Novo');
        $result = $this->useCase->execute('CATE123', $dto);

        $this->assertEquals('CATE123', $result->id);
        $this->assertEquals('Nome Novo', $result->nome);
    }

    public function testExecuteComMesmoNome()
    {
        $this->mockFindById('CATE123', $this->categoriaExistente);
        $this->mockUpdate(function ($categoria) {
            return $categoria->getId() === 'CATE123' &&
                $categoria->getNome() === 'Nome Antigo';
        });

        $dto = new CategoriaDTO('CATE123', 'Nome Antigo');
        $result = $this->useCase->execute('CATE123', $dto);

        $this->assertEquals('CATE123', $result->id);
        $this->assertEquals('Nome Antigo', $result->nome);
    }

    public function testExecuteComNomeVazio()
    {
        $this->mockFindById('CATE123', $this->categoriaExistente);
        $this->mockUpdate(function ($categoria) {
            return $categoria->getId() === 'CATE123' &&
                $categoria->getNome() === '';
        });

        $dto = new CategoriaDTO('CATE123', '');
        $result = $this->useCase->execute('CATE123', $dto);

        $this->assertEquals('CATE123', $result->id);
        $this->assertEquals('', $result->nome);
    }
}
