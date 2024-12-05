<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\Produto;
use App\Domain\Repositories\ProdutoRepositoryInterface;
use PDO;

class ProdutoRepository implements ProdutoRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById(string $id): ?Produto
    {
        $stmt = $this->pdo->prepare('SELECT * FROM produtos WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new Produto(
            $data['id'],
            $data['nome'],
            $data['descricao'],
            $data['preco'],
            $data['imagem'],
            $data['categoria_id']
        );
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM produtos');
        $produtos = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $produtos[] = new Produto(
                $data['id'],
                $data['nome'],
                $data['descricao'],
                $data['preco'],
                $data['imagem'],
                $data['categoria_id']
            );
        }

        return $produtos;
    }

    public function save(Produto $produto): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO produtos (id, nome, descricao, preco, imagem, categoria_id) VALUES (:id, :nome, :descricao, :preco, :imagem, :categoria_id)');
        $stmt->execute([
            'id' => $produto->getId(),
            'nome' => $produto->getNome(),
            'descricao' => $produto->getDescricao(),
            'preco' => $produto->getPreco(),
            'imagem' => $produto->getImage(),
            'categoria_id' => $produto->getCategoriaId()
        ]);
    }

    public function update(Produto $produto): void
    {
        $stmt = $this->pdo->prepare('UPDATE produtos SET nome = :nome, descricao = :descricao, preco = :preco, imagem = :imagem, categoria_id = :categoria_id WHERE id = :id');
        $stmt->execute([
            'id' => $produto->getId(),
            'nome' => $produto->getNome(),
            'descricao' => $produto->getDescricao(),
            'preco' => $produto->getPreco(),
            'imagem' => $produto->getImage(),
            'categoria_id' => $produto->getCategoriaId()
        ]);
    }

    public function delete(string $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM produtos WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
