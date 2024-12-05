<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use App\Application\DTOs\ProdutoDTO;
use App\Application\UseCases\CriarProdutoUseCase;
use App\Infrastructure\Persistence\MockProdutoRepository;

class FeatureContext implements Context
{
    private MockProdutoRepository $produtoRepository;
    private CriarProdutoUseCase $criarProdutoUseCase;
    private ?ProdutoDTO $resultadoProduto = null;
    private ?ProdutoDTO $produtoDTO = null; // Declare a propriedade aqui

    public function __construct()
    {
        $this->produtoRepository = new MockProdutoRepository();
        $this->criarProdutoUseCase = new CriarProdutoUseCase($this->produtoRepository);
    }

    /**
     * @Given eu tenho os seguintes detalhes do produto:
     */
    public function euTenhoOsSeguintesDetalhesDoProduto(TableNode $table)
    {
        $dados = $table->getRowsHash();
        $this->produtoDTO = new ProdutoDTO(
            null,
            $dados['nome'],
            $dados['descricao'],
            (float) $dados['preco'],
            $dados['imagem'],
            $dados['categoriaId']
        );
    }

    /**
     * @When eu tento criar o produto
     */
    public function euTentoCriarOProduto()
    {
        try {
            $this->resultadoProduto = $this->criarProdutoUseCase->execute($this->produtoDTO);
        } catch (\Exception $e) {
            throw new \Exception("Erro ao criar o produto: " . $e->getMessage());
        }
    }

    /**
     * @Then o produto deve ser criado com sucesso
     */
    public function oProdutoDeveSerCriadoComSucesso()
    {
        if (!$this->resultadoProduto || !$this->resultadoProduto->id) {
            throw new Exception("O produto não foi criado com sucesso.");
        }
    }

    /**
     * @Then o produto deve ter os seguintes detalhes:
     */
    public function oProdutoDeveTerOsSeguintesDetalhes(TableNode $table)
    {
        $esperado = $table->getRowsHash();
        $atual = [
            'nome' => $this->resultadoProduto->nome,
            'descricao' => $this->resultadoProduto->descricao,
            'preco' => (string) $this->resultadoProduto->preco,
            'imagem' => $this->resultadoProduto->imagem,
            'categoriaId' => $this->resultadoProduto->categoriaId
        ];

        foreach ($esperado as $chave => $valor) {
            if ($atual[$chave] !== $valor) {
                throw new Exception("O valor de '$chave' não corresponde. Esperado: $valor, Atual: {$atual[$chave]}");
            }
        }
    }

    /**
     * @Then o produto deve estar no repositório
     */
    public function oProdutoDeveEstarNoRepositorio()
    {
        $produtoSalvo = $this->produtoRepository->findById($this->resultadoProduto->id);
        if (!$produtoSalvo) {
            throw new Exception("O produto não foi encontrado no repositório.");
        }
    }
}
