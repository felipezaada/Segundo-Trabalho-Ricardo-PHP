## Problema com o Botão Voltar do Navegador

Atualmente, estamos enfrentando um problema na página do carrinho em que o botão voltar do navegador não está funcionando conforme o esperado. Para contornar essa questão, recomendamos que os usuários utilizem os botões de navegação fornecidos na página, como os botões "Voltar" ou "Continuar Comprando", para garantir uma experiência de compra sem interrupções.

## Configuração do Banco de Dados

Para que o projeto funcione corretamente, é necessário criar um banco de dados no phpMyAdmin com o nome 'hamburgueria'. Após isso, crie as tabelas:

1. **Tabela `carrinho`**:
   - `id` (auto increment, int)
   - `nome_cliente` (varchar 50)
   - `email_cliente` (varchar 50)
   - `produto_pedido` (varchar 50)
   - `quantidade` (int 11)
   - `valor_pedido` (float)

2. **Tabela `pedidos`**:
   - `id` (auto increment, int)
   - `cliente` (varchar 50)
   - `email` (varchar 50)
   - `produto` (varchar 50)
   - `quantidade` (int 11)
   - `valor` (float)

3. **Tabela `produtos`**:
   - `id` (auto increment, int)
   - `nome` (varchar 50)
   - `descricao` (varchar 100)
   - `valor` (float)

4. **Tabela `usuario`**:
   - `id` (auto increment, int)
   - `nome` (varchar 50)
   - `email` (varchar 50)
   - `senha` (varchar 100)

5. **Tabela `produtos`**:  
   Insira os seguintes dados:
   - Hambúrguer de Carne - R$ 25,00
   - Burger Bizz - R$ 20,00
   - Crackles Burger - R$ 20,00
   - Bull Burgers - R$ 20,00
   - Rocket Burgers - R$ 20,00
   - Smokin Burger - R$ 20,00
   - Delish Burger - R$ 20,00

Certifique-se de inserir os dados conforme a estrutura da tabela `produtos` e os tipos de dados especificados.

**ATENÇÃO**: Todos os nomes de tabelas e campos devem ser criados com atenção ao 'case sensitivity'.

## Como Contribuir

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-feature`)
3. Faça commit das suas mudanças (`git commit -am 'Adiciona nova feature'`)
4. Faça push para a branch (`git push origin feature/nova-feature`)
5. Crie um novo Pull Request
