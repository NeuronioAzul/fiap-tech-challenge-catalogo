<?php

namespace App\Domain\Entities;

class Categoria
{
    public function __construct(
        private string $id,
        private string $nome
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }
}
