<?php

namespace Tests\Unit\Infrastructure\API\Controllers;

use App\Application\DTOs\ProdutoDTO;
use App\Application\UseCases\CriarProdutoUseCase;
use App\Application\UseCases\AtualizarProdutoUseCase;
use App\Application\UseCases\DeletarProdutoUseCase;
use App\Application\UseCases\ListarProdutosUseCase;
use App\Application\UseCases\ObterProdutoUseCase;
use App\Infrastructure\API\Controllers\ProdutoController;
use App\Domain\Exceptions\ProdutoNotFoundException;
use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\ResponseFactory;

class ProdutoControllerTest extends TestCase
{
    private $criarProdutoUseCase;
    private $atualizarProdutoUseCase;
    private $deletarProdutoUseCase;
    private $listarProdutosUseCase;
    private $obterProdutoUseCase;
    private $controller;

    protected function setUp(): void
    {
        $this->criarProdutoUseCase = $this->createMock(CriarProdutoUseCase::class);
        $this->atualizarProdutoUseCase = $this->createMock(AtualizarProdutoUseCase::class);
        $this->deletarProdutoUseCase = $this->createMock(DeletarProdutoUseCase::class);
        $this->listarProdutosUseCase = $this->createMock(ListarProdutosUseCase::class);
        $this->obterProdutoUseCase = $this->createMock(ObterProdutoUseCase::class);

        $this->controller = new ProdutoController(
            $this->criarProdutoUseCase,
            $this->atualizarProdutoUseCase,
            $this->deletarProdutoUseCase,
            $this->listarProdutosUseCase,
            $this->obterProdutoUseCase
        );
    }

    public function testCriar()
    {
        $request = (new ServerRequestFactory())->createServerRequest('POST', '/produtos')
            ->withParsedBody([
                'nome' => 'Produto Teste',
                'descricao' => 'Descrição do Produto Teste',
                'preco' => 10.99,
                'imagem' => 'imagem.jpg',
                'categoria_id' => 'CATE123'
            ]);

        $produtoCriado = new ProdutoDTO('PROD123', 'Produto Teste', 'Descrição do Produto Teste', 10.99, 'imagem.jpg', 'CATE123');

        $this->criarProdutoUseCase->expects($this->once())
            ->method('execute')
            ->willReturn($produtoCriado);

        $response = $this->controller->criar($request, (new ResponseFactory())->createResponse());

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));

        $body = json_decode((string) $response->getBody(), true);
        $this->assertEquals('PROD123', $body['id']);
        $this->assertEquals('Produto Teste', $body['nome']);
    }

    public function testAtualizar()
    {
        $request = (new ServerRequestFactory())->createServerRequest('PUT', '/produtos')->withAttribute('id', 'PROD123')
            ->withParsedBody([
                'nome' => 'Produto Atualizado',
                'descricao' => 'Descrição Atualizada',
                'preco' => 15.99,
                'imagem' => 'nova_imagem.jpg',
                'categoria_id' => 'CATE456'
            ]);

        $produtoAtualizado = new ProdutoDTO('PROD123', 'Produto Atualizado', 'Descrição Atualizada', 15.99, 'nova_imagem.jpg', 'CATE456');

        $this->atualizarProdutoUseCase->expects($this->once())
            ->method('execute')
            ->willReturn($produtoAtualizado);

        $response = $this->controller->atualizar($request, (new ResponseFactory())->createResponse(), ['id' => 'PROD123']);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));

        $body = json_decode((string) $response->getBody(), true);
        $this->assertEquals('PROD123', $body['id']);
        $this->assertEquals('Produto Atualizado', $body['nome']);
    }

    public function testDeletar()
    {
        $request = (new ServerRequestFactory())->createServerRequest('DELETE', '/produtos')->withAttribute('id', 'PROD123');

        $this->deletarProdutoUseCase->expects($this->once())
            ->method('execute')
            ->with('PROD123');

        $response = $this->controller->deletar($request, (new ResponseFactory())->createResponse(), ['id' => 'PROD123']);

        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testListar()
    {
        $request = (new ServerRequestFactory())->createServerRequest('GET', '/produtos');

        $produtos = [
            new ProdutoDTO('PROD123', 'Produto 1', 'Descrição 1', 10.99, 'imagem1.jpg', 'CATE123'),
            new ProdutoDTO('PROD456', 'Produto 2', 'Descrição 2', 20.99, 'imagem2.jpg', 'CATE456')
        ];

        $this->listarProdutosUseCase->expects($this->once())
            ->method('execute')
            ->willReturn($produtos);

        $response = $this->controller->listar($request, (new ResponseFactory())->createResponse());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));

        $body = json_decode((string) $response->getBody(), true);
        $this->assertCount(2, $body);
        $this->assertEquals('PROD123', $body[0]['id']);
        $this->assertEquals('PROD456', $body[1]['id']);
    }

    public function testObter()
    {
        $request = (new ServerRequestFactory())->createServerRequest('GET', '/produtos')->withAttribute('id', 'PROD123');

        $produto = new ProdutoDTO('PROD123', 'Produto Teste', 'Descrição do Produto Teste', 10.99, 'imagem.jpg', 'CATE123');

        $this->obterProdutoUseCase->expects($this->once())
            ->method('execute')
            ->with('PROD123')
            ->willReturn($produto);

        $response = $this->controller->obter($request, (new ResponseFactory())->createResponse(), ['id' => 'PROD123']);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));

        $body = json_decode((string) $response->getBody(), true);
        $this->assertEquals('PROD123', $body['id']);
        $this->assertEquals('Produto Teste', $body['nome']);
    }

    public function testObterProdutoNaoEncontrado()
    {
        $request = (new ServerRequestFactory())->createServerRequest('GET', '/produtos')->withAttribute('id', 'PROD999');

        $this->obterProdutoUseCase->expects($this->once())
            ->method('execute')
            ->with('PROD999')
            ->willThrowException(new ProdutoNotFoundException("Produto com ID PROD999 não encontrado."));

        $response = $this->controller->obter($request, (new ResponseFactory())->createResponse(), ['id' => 'PROD999']);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));

        $body = json_decode((string) $response->getBody(), true);
        $this->assertArrayHasKey('error', $body);
        $this->assertEquals('Produto com ID PROD999 não encontrado.', $body['error']);
    }
}
