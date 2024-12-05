Feature: Criação de Produto no Catálogo

  Scenario: Criar um novo produto com sucesso
    Given eu tenho os seguintes detalhes do produto:
      | nome        | Hambúrguer Clássico   |
      | descricao   | Delicioso hambúrguer  |
      | preco       | 15.99                 |
      | imagem      | hamburger.jpg         |
      | categoriaId | CAT001                |
    When eu tento criar o produto
    Then o produto deve ser criado com sucesso
    And o produto deve ter os seguintes detalhes:
      | nome        | Hambúrguer Clássico   |
      | descricao   | Delicioso hambúrguer  |
      | preco       | 15.99                 |
      | imagem      | hamburger.jpg         |
      | categoriaId | CAT001                |
    And o produto deve estar no repositório