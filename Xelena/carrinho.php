<?php
require ('produto.php');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hamburgueria";
$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

if (isset($_GET['reload'])) {
    $quantidade = 1;
}

if (isset($_GET['id'])) {
    $idProduto = $_GET['id'];

    $sql = "SELECT * FROM produtos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $idProduto);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    $valor = $produto['valor'];
} else {
    $produto = ['nome' => '', 'valor' => 0]; // Exemplo de produto padrão
    $valor = $produto['valor'];
}

$sql = "SELECT produto_pedido, SUM(quantidade) as quantidade_total, SUM(valor_pedido) as valor_pedido FROM carrinho WHERE nome_cliente = :nome_cliente GROUP BY produto_pedido";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':nome_cliente', $_SESSION['nome']);
$stmt->execute();
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['quantidade']) && intval($_POST['quantidade'] > 0)) {
        $quantidade = intval($_POST['quantidade']);
    } else {
        $quantidade = 1;
    }
    $total = $quantidade * $valor;

    if (!empty($produto['nome'])) {
        if (isset($_POST['atualizar'])) {
            $nome = $_SESSION['nome'];
            $email = $_SESSION['email'];
            $valortotal = $total;

            $sql = "INSERT INTO carrinho (nome_cliente, email_cliente, produto_pedido, quantidade, valor_pedido) VALUES (:nome, :email, :produto, :quantidade, :valortotal)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':produto', $produto['nome']);
            $stmt->bindParam(':quantidade', $quantidade);
            $stmt->bindParam(':valortotal', $valortotal);
            $stmt->execute();

            header('Location: ' . $_SERVER['REQUEST_URI']);
        }
    }

    if (isset($_POST['continuar_comprando'])) {
        if (isset($_SESSION['id'])) {
            header('Location: menu.php');
        } else {
            header('Location: login.html');
        }
    } else if (isset($_POST['finalizar_compra'])) {
        if (isset($_SESSION['id'])) {
            $nome = $_SESSION['nome'];
            $email = $_SESSION['email'];

            $sql_clear = "DELETE FROM carrinho WHERE nome_cliente = :nome";
            $stmt_clear = $pdo->prepare($sql_clear);
            $stmt_clear->bindParam(':nome', $nome);
            $stmt_clear->execute();

            foreach ($resultados as $resultado) {
                $produto_pedido = $resultado['produto_pedido'];
                $quantidade_pedido = $resultado['quantidade_total'];
                $valor_pedido = $resultado['valor_pedido'];

                $sql = "INSERT INTO pedidos (cliente, email, produto, quantidade, valor) VALUES (:nome, :email, :produto, :quantidade, :valor)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':produto', $produto_pedido);
                $stmt->bindParam(':quantidade', $quantidade_pedido);
                $stmt->bindParam(':valor', $valor_pedido);
                $stmt->execute();
            }
            header('Location: pedidoEfetuado.php');
        }
    }

} else {
    $quantidade = 1;
    $total = $valor;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }

        table {
            width: 100%;
        }

        th,
        td {
            border-bottom: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            margin-top: 20px;
            text-align: right;
        }

        .checkout-btn {
            margin-top: 20px;
            text-align: center;
        }
    </style>

</head>

<body>

    <a href="menu.php">Voltar</a>

    <div class="container">
        <h2>Carrinho de Compras</h2>
        <table>
            <thead>
                <tr>
                    <th>Produtos no seu carrinho</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($resultados as $resultado) {
                    $produto_pedido = $resultado['produto_pedido'];
                    $quantidade_pedido = $resultado['quantidade_total'];
                    $valor_pedido = $resultado['valor_pedido'];
                    $valor_individual = $valor_pedido / $quantidade_pedido;

                    echo "<tr>";
                    echo "<td>$produto_pedido</td>";
                    echo "<td>R$" . number_format($valor_individual, 2, ',', '.') . "</td>";
                    echo "<td>$quantidade_pedido</td>";
                    echo "<td>R$" . number_format($valor_pedido, 2, ',', '.') . "</td>";
                    echo "<td>";
                    echo "<form method='post'>
                            <input type='hidden' name='remover' value='$produto_pedido'>
                            <input type='submit' class='btn btn-danger' value='Remover'>
                    </form>";
                    echo "</td>";
                    echo "</tr>";
                }

                if (isset($_POST['remover'])) {
                    $produto_remover = $_POST['remover'];

                    $sql_remove = "DELETE FROM carrinho WHERE produto_pedido = :produto_pedido";
                    $stmt_remove = $pdo->prepare($sql_remove);
                    $stmt_remove->bindParam(':produto_pedido', $produto_remover);
                    $stmt_remove->execute();

                    header('Location: ' . $_SERVER['REQUEST_URI']);
                }

                ?>
                <tr>
                    <th>Adicione ao seu carrinho</th>
                </tr>
                <td><?php echo $produto['nome']; ?></td>
                <td><?php echo "R$" . number_format($produto['valor'], 2, ',', '.'); ?></td>
                <td>
                    <form method="post">
                        <input type="number" name="quantidade" value="<?php echo $quantidade; ?>" min="1"
                            onchange="this.form.submit()">
                    </form>
                </td>
                <td></td>
                <td><?php echo "R$" . number_format($total, 2, ',', '.'); ?></td>
                <td></td>
                </tr>
            </tbody>
        </table>
        <div class="checkout-btn">
            <form method="post" class="d-inline-block">
                <input type="hidden" name="finalizar_compra" value="1">
                <input type="submit" class="btn btn-primary" value="Finalizar Compra">
            </form>
            <form method="post" class="d-inline-block">
                <input type="hidden" name="continuar_comprando" value="2">
                <input type="submit" class="btn btn-primary" value="Continuar Comprando">
            </form>
            <form method="post" class="d-inline-block">
                <input type="hidden" name="quantidade" value="<?php echo $quantidade; ?>">
                <input type="hidden" name="atualizar" value="3">
                <input type="submit" class="btn btn-segundary" value="Atualizar">
            </form>
        </div>
    </div>

</body>

</html>