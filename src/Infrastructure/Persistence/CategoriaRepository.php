<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\Categoria;
use App\Domain\Repositories\CategoriaRepositoryInterface;
use PDO;

class CategoriaRepository implements CategoriaRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById(string $id): ?Categoria
    {
        $stmt = $this->pdo->prepare('SELECT * FROM categorias WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new Categoria($data['id'], $data['nome']);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM categorias');
        $categorias = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categorias[] = new Categoria($data['id'], $data['nome']);
        }

        return $categorias;
    }

    public function save(Categoria $categoria): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO categorias (id, nome) VALUES (:id, :nome)');
        $stmt->execute([
            'id' => $categoria->getId(),
            'nome' => $categoria->getNome()
        ]);
    }

    public function update(Categoria $categoria): void
    {
        $stmt = $this->pdo->prepare('UPDATE categorias SET nome = :nome WHERE id = :id');
        $stmt->execute([
            'id' => $categoria->getId(),
            'nome' => $categoria->getNome()
        ]);
    }

    public function delete(string $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM categorias WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
