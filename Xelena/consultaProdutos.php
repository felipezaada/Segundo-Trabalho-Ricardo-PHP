<?php

require 'produto.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hamburgueria";
$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

$produto = new Produto();

echo "VALOR DO PRODUTO id 20: </br>" . $produto->getValor($pdo, "20") . "</br></br>";
echo "DETALHES PRODUTO id 21: </br>" . $produto->getDetalhes($pdo, "21");

?>