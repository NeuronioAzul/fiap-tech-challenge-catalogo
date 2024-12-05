<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Categoria;
use PHPUnit\Framework\TestCase;

class CategoriaTest extends TestCase
{
    private Categoria $categoria;

    protected function setUp(): void
    {
        $this->categoria = new Categoria('CATE123', 'Nome Original');
    }

    public function testCriacaoDeCategoria()
    {
        $this->assertEquals('CATE123', $this->categoria->getId());
        $this->assertEquals('Nome Original', $this->categoria->getNome());
    }

    public function testAtualizacaoDeCategoria()
    {
        $this->categoria->setNome('Nome Atualizado');
        $this->assertEquals('Nome Atualizado', $this->categoria->getNome());
    }

    public function testManutencaoDeIdAoAtualizarNome()
    {
        $idOriginal = $this->categoria->getId();
        $this->categoria->setNome('Nome Atualizado');

        $this->assertEquals($idOriginal, $this->categoria->getId());
        $this->assertEquals('Nome Atualizado', $this->categoria->getNome());
    }

    public function testMultiplasAtualizacoesDeNome()
    {
        $this->categoria->setNome('Primeiro Nome');
        $this->assertEquals('Primeiro Nome', $this->categoria->getNome());

        $this->categoria->setNome('Segundo Nome');
        $this->assertEquals('Segundo Nome', $this->categoria->getNome());

        $this->categoria->setNome('Terceiro Nome');
        $this->assertEquals('Terceiro Nome', $this->categoria->getNome());
    }

    public function testAtualizacaoComMesmoNome()
    {
        $nomeOriginal = $this->categoria->getNome();
        $this->categoria->setNome($nomeOriginal);

        $this->assertEquals($nomeOriginal, $this->categoria->getNome());
    }
}
