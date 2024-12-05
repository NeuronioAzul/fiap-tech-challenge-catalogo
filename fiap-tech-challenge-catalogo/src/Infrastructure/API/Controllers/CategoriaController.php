<?php

namespace App\Infrastructure\API\Controllers;

use App\Application\DTOs\CategoriaDTO;
use App\Application\UseCases\CriarCategoriaUseCase;
use App\Application\UseCases\AtualizarCategoriaUseCase;
use App\Application\UseCases\DeletarCategoriaUseCase;
use App\Application\UseCases\ListarCategoriasUseCase;
use App\Application\UseCases\ObterCategoriaUseCase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CategoriaController
{
    public function __construct(
        private CriarCategoriaUseCase $criarCategoriaUseCase,
        private AtualizarCategoriaUseCase $atualizarCategoriaUseCase,
        private DeletarCategoriaUseCase $deletarCategoriaUseCase,
        private ListarCategoriasUseCase $listarCategoriasUseCase,
        private ObterCategoriaUseCase $obterCategoriaUseCase
    ) {}

    public function criar(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $dto = CategoriaDTO::fromArray($data);

        $categoriaCriada = $this->criarCategoriaUseCase->execute($dto);

        $response->getBody()->write(json_encode($categoriaCriada->toArray()));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }

    public function atualizar(Request $request, Response $response): Response
    {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();
        $dto = CategoriaDTO::fromArray($data);

        try {
            $categoriaAtualizada = $this->atualizarCategoriaUseCase->execute($id, $dto);
            $response->getBody()->write(json_encode($categoriaAtualizada->toArray()));
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
            $this->deletarCategoriaUseCase->execute($id);
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
        $categorias = $this->listarCategoriasUseCase->execute();

        $response->getBody()->write(json_encode(array_map(fn($dto) => $dto->toArray(), $categorias)));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function obter(Request $request, Response $response): Response
    {
        $id = $request->getAttribute('id');

        try {
            $categoria = $this->obterCategoriaUseCase->execute($id);
            $response->getBody()->write(json_encode($categoria->toArray()));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
    }
}
