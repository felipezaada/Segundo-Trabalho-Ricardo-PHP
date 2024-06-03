<?php

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hamburgueria";
$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

class Produto
{
    public int $id;
    public string $nome;
    public string $descricao;
    public float $valor;

    function cadastrarProduto(PDO $pdo, $nome, $valor, $descricao = "")
    {
        $this->nome = $nome;
        $this->valor = $valor;
        $this->descricao = $descricao;

        $sql = "INSERT INTO produtos (nome, descricao, valor) VALUES (:nome, :descricao, :valor)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nome', $this->nome);
        $stmt->bindValue(':valor', $this->valor);
        $stmt->bindValue(':descricao', $this->descricao);
        $stmt->execute();
    }


    function getValor(PDO $pdo, $id)
    {
        $sql = "SELECT valor FROM produtos WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $valorProduto = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($valorProduto) {
            return "R$" . $valorProduto['valor'];
        } else {
            return null;
        }
    }

    public function getDetalhes(PDO $pdo, $id)
    {
        $sql = "SELECT * FROM produtos WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($produto) {
            return "ID PRODUTO: " . $produto['id'] . " NOME: " . $produto['nome'] . ' DESCRIÇÃO: ' . $produto['descricao'] . ' VALOR: R$' . $produto['valor'];
        } else {
            return null;
        }
    }
}

?>