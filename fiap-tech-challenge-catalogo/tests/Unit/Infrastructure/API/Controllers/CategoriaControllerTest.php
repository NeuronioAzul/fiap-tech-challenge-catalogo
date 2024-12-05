<?php

namespace Tests\Unit\Infrastructure\API\Controllers;

use App\Application\DTOs\CategoriaDTO;
use App\Application\UseCases\CriarCategoriaUseCase;
use App\Application\UseCases\AtualizarCategoriaUseCase;
use App\Application\UseCases\DeletarCategoriaUseCase;
use App\Application\UseCases\ListarCategoriasUseCase;
use App\Application\UseCases\ObterCategoriaUseCase;
use App\Infrastructure\API\Controllers\CategoriaController;
use App\Domain\Exceptions\CategoriaNotFoundException;
use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\ResponseFactory;

class CategoriaControllerTest extends TestCase
{
    private $criarCategoriaUseCase;
    private $atualizarCategoriaUseCase;
    private $deletarCategoriaUseCase;
    private $listarCategoriasUseCase;
    private $obterCategoriaUseCase;
    private $controller;

    protected function setUp(): void
    {
        $this->criarCategoriaUseCase = $this->createMock(CriarCategoriaUseCase::class);
        $this->atualizarCategoriaUseCase = $this->createMock(AtualizarCategoriaUseCase::class);
        $this->deletarCategoriaUseCase = $this->createMock(DeletarCategoriaUseCase::class);
        $this->listarCategoriasUseCase = $this->createMock(ListarCategoriasUseCase::class);
        $this->obterCategoriaUseCase = $this->createMock(ObterCategoriaUseCase::class);

        $this->controller = new CategoriaController(
            $this->criarCategoriaUseCase,
            $this->atualizarCategoriaUseCase,
            $this->deletarCategoriaUseCase,
            $this->listarCategoriasUseCase,
            $this->obterCategoriaUseCase
        );
    }

    public function testCriar()
    {
        $request = (new ServerRequestFactory())->createServerRequest('POST', '/categorias')
            ->withParsedBody(['nome' => 'Nova Categoria']);

        $categoriaCriada = new CategoriaDTO('CATE123', 'Nova Categoria');

        $this->criarCategoriaUseCase->expects($this->once())
            ->method('execute')
            ->willReturn($categoriaCriada);

        $response = $this->controller->criar($request, (new ResponseFactory())->createResponse());

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));

        $body = json_decode((string) $response->getBody(), true);
        $this->assertEquals('CATE123', $body['id']);
        $this->assertEquals('Nova Categoria', $body['nome']);
    }

    public function testAtualizar()
    {
        $request = (new ServerRequestFactory())->createServerRequest('PUT', '/categorias')->withAttribute('id', 'CATE123')
            ->withParsedBody(['nome' => 'Categoria Atualizada']);

        $categoriaAtualizada = new CategoriaDTO('CATE123', 'Categoria Atualizada');

        $this->atualizarCategoriaUseCase->expects($this->once())
            ->method('execute')
            ->willReturn($categoriaAtualizada);

        $response = $this->controller->atualizar($request, (new ResponseFactory())->createResponse(), ['id' => 'CATE123']);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));

        $body = json_decode((string) $response->getBody(), true);
        $this->assertEquals('CATE123', $body['id']);
        $this->assertEquals('Categoria Atualizada', $body['nome']);
    }

    public function testDeletar()
    {
        $request = (new ServerRequestFactory())->createServerRequest('DELETE', '/categorias')->withAttribute('id', 'CATE123');

        $this->deletarCategoriaUseCase->expects($this->once())
            ->method('execute')
            ->with('CATE123');

        $response = $this->controller->deletar($request, (new ResponseFactory())->createResponse(), ['id' => 'CATE123']);

        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testListar()
    {
        $request = (new ServerRequestFactory())->createServerRequest('GET', '/categorias');

        $categorias = [
            new CategoriaDTO('CATE123', 'Categoria 1'),
            new CategoriaDTO('CATE456', 'Categoria 2')
        ];

        $this->listarCategoriasUseCase->expects($this->once())
            ->method('execute')
            ->willReturn($categorias);

        $response = $this->controller->listar($request, (new ResponseFactory())->createResponse());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));

        $body = json_decode((string) $response->getBody(), true);
        $this->assertCount(2, $body);
        $this->assertEquals('CATE123', $body[0]['id']);
        $this->assertEquals('CATE456', $body[1]['id']);
    }

    public function testObter()
    {
        $request = (new ServerRequestFactory())->createServerRequest('GET', '/categorias')->withAttribute('id', 'CATE123');

        $categoria = new CategoriaDTO('CATE123', 'Categoria Teste');

        $this->obterCategoriaUseCase->expects($this->once())
            ->method('execute')
            ->with('CATE123')
            ->willReturn($categoria);

        $response = $this->controller->obter($request, (new ResponseFactory())->createResponse(), ['id' => 'CATE123']);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));

        $body = json_decode((string) $response->getBody(), true);
        $this->assertEquals('CATE123', $body['id']);
        $this->assertEquals('Categoria Teste', $body['nome']);
    }

    public function testObterCategoriaNaoEncontrada()
    {
        $request = (new ServerRequestFactory())->createServerRequest('GET', '/categorias')->withAttribute('id', 'CATE999');

        $this->obterCategoriaUseCase->expects($this->once())
            ->method('execute')
            ->with('CATE999')
            ->willThrowException(new CategoriaNotFoundException("Categoria com ID CATE999 não encontrada."));

        $response = $this->controller->obter($request, (new ResponseFactory())->createResponse(), ['id' => 'CATE999']);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));

        $body = json_decode((string) $response->getBody(), true);
        $this->assertArrayHasKey('error', $body);
        $this->assertEquals('Categoria com ID CATE999 não encontrada.', $body['error']);
    }
}
