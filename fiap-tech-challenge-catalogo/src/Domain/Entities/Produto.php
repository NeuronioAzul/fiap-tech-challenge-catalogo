<?php

namespace App\Domain\Entities;

class Produto
{
    public function __construct(
        private string $id,
        private string $nome,
        private string $descricao,
        private float $preco,
        private string $imagem,
        private string $categoriaId
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

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): void
    {
        $this->descricao = $descricao;
    }

    public function getPreco(): float
    {
        return $this->preco;
    }

    public function setPreco(float $preco): void
    {
        $this->preco = $preco;
    }

    public function getImage(): string
    {
        return $this->imagem;
    }

    public function setImage(string $imagem): void
    {
        $this->imagem = $imagem;
    }

    public function getCategoriaId(): string
    {
        return $this->categoriaId;
    }

    public function setCategoriaId(string $categoriaId): void
    {
        $this->categoriaId = $categoriaId;
    }
}
