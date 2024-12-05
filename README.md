# Serviço de Catálogo

Este é um serviço de catálogo desenvolvido como parte do desafio técnico da FIAP. O serviço gerencia produtos e categorias, fornecendo operações CRUD através de uma API.

## Tecnologias Utilizadas

- PHP 8.1
- Slim Framework 4
- MySQL
- Docker
- Composer
- PHPUnit

## Estrutura do Projeto

O projeto segue os princípios da Clean Architecture:

- `src/Domain`: Contém as entidades e interfaces de repositório
- `src/Application`: Contém os DTOs e casos de uso
- `src/Infrastructure`: Contém os controladores da API e implementações de repositório

## Pré-requisitos

- Docker
- Docker Compose

## Instalação e Configuração

1. Clone o repositório:
git clone 
favicon
github.com
 cd fiap-tech-challenge-catalogo


2. Crie um arquivo `.env` na raiz do projeto e configure as variáveis de ambiente:
DB_HOST=db DB_NAME=catalogo DB_USER=root DB_PASS=sua_senha


3. Inicie os containers Docker:
docker compose up -d


4. Instale as dependências do projeto:
docker compose exec app composer install


5. Execute as migrações do banco de dados:
docker compose exec app php run_migrations.php


## Executando os Testes

Para executar os testes unitários, use o seguinte comando:

docker compose exec app composer test


## Uso da API

A API estará disponível em `http://localhost:8082`. Aqui estão alguns exemplos de endpoints:

### Produtos

- Listar todos os produtos: `GET /produtos`
- Obter um produto específico: `GET /produtos/{id}`
- Criar um novo produto: `POST /produtos`
- Atualizar um produto: `PUT /produtos/{id}`
- Excluir um produto: `DELETE /produtos/{id}`

### Categorias

- Listar todas as categorias: `GET /categorias`
- Obter uma categoria específica: `GET /categorias/{id}`
- Criar uma nova categoria: `POST /categorias`
- Atualizar uma categoria: `PUT /categorias/{id}`
- Excluir uma categoria: `DELETE /categorias/{id}`

Para mais detalhes sobre os endpoints e formatos de requisição/resposta, consulte a documentação da API.

## Desenvolvimento

Para adicionar novas funcionalidades ou fazer alterações:

1. Crie uma nova branch: `git checkout -b minha-nova-feature`
2. Faça suas alterações e adicione testes apropriados
3. Execute os testes para garantir que tudo está funcionando
4. Faça commit das suas alterações: `git commit -am 'Adiciona nova feature'`
5. Faça push para a branch: `git push origin minha-nova-feature`
6. Crie um novo Pull Request

## Documentação da API

A documentação da API está disponível através do Swagger UI. Para acessá-la:

1. Certifique-se de que a aplicação está rodando.
2. Acesse `http://seu-dominio/docs` no seu navegador.

A interface do Swagger UI fornecerá uma visão interativa de todos os endpoints da API, incluindo detalhes sobre os parâmetros de requisição e respostas esperadas.

Para desenvolvedores:
- A especificação OpenAPI está localizada no arquivo `openapi.yaml` na raiz do projeto.
- Ao adicionar ou modificar endpoints, atualize o arquivo `openapi.yaml` para manter a documentação precisa e atualizada.
