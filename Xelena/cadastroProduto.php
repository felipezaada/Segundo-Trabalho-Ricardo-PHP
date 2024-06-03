<?php

require ('produto.php');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hamburgueria";
$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

$produto = new Produto();

$produto->cadastrarProduto($pdo, 'Hambúrguer de Carne', 25);
$produto->cadastrarProduto($pdo, 'Burger Bizz', 20.00);
$produto->cadastrarProduto($pdo, 'Crackles Burger', 20.00);
$produto->cadastrarProduto($pdo, 'Crackles Burger', 20.00);
$produto->cadastrarProduto($pdo, 'Bull Burgers', 20.00);
$produto->cadastrarProduto($pdo, 'Rocket Burgers', 20.00);
$produto->cadastrarProduto($pdo, 'Smokin Burger', 20.00);
$produto->cadastrarProduto($pdo, 'Delish Burger', 20.00);

?>