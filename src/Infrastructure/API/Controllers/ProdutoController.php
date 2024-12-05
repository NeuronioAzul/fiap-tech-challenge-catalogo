<?php

namespace App\Infrastructure\API\Controllers;

use App\Application\DTOs\ProdutoDTO;
use App\Application\UseCases\CriarProdutoUseCase;
use App\Application\UseCases\AtualizarProdutoUseCase;
use App\Application\UseCases\DeletarProdutoUseCase;
use App\Application\UseCases\ListarProdutosUseCase;
use App\Application\UseCases\ObterProdutoUseCase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProdutoController
{
    public function __construct(
        private CriarProdutoUseCase $criarProdutoUseCase,
        private AtualizarProdutoUseCase $atualizarProdutoUseCase,
        private DeletarProdutoUseCase $deletarProdutoUseCase,
        private ListarProdutosUseCase $listarProdutosUseCase,
        private ObterProdutoUseCase $obterProdutoUseCase
    ) {}

    public function criar(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $dto = ProdutoDTO::fromArray($data);

        $produtoCriado = $this->criarProdutoUseCase->execute($dto);

        $response->getBody()->write(json_encode($produtoCriado->toArray()));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }

    public function atualizar(Request $request, Response $response): Response
    {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();
        $dto = ProdutoDTO::fromArray($data);

        try {
            $produtoAtualizado = $this->atualizarProdutoUseCase->execute($id, $dto);
            $response->getBody()->write(json_encode($produtoAtualizado->toArray()));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
    }

    public function deletar(Request $request, Response $response): Response
    {
        $id = $request->getAttribute('id');

        try {
            $this->deletarProdutoUseCase->execute($id);
            return $response->withStatus(204);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
    }

    public function listar(Request $request, Response $response): Response
    {
        $produtos = $this->listarProdutosUseCase->execute();

        $response->getBody()->write(json_encode(array_map(fn($dto) => $dto->toArray(), $produtos)));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function obter(Request $request, Response $response): Response
    {
        $id = $request->getAttribute('id');

        try {
            $produto = $this->obterProdutoUseCase->execute($id);
            $response->getBody()->write(json_encode($produto->toArray()));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
    }
}
