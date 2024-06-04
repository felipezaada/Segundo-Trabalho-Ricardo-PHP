<?php

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hamburgueria";
$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

if (isset($_POST['submitRegistro'])) {
    $usuario = new Usuario();
    $usuario->cadastrar($pdo);
}

if (isset($_POST['submitLogin'])) {
    $usuario = new Usuario();
    $usuario->autenticar($pdo);
}

class Usuario
{
    public int $id;
    public string $nome;
    public string $email;
    public string $senha;

    function cadastrar(PDO $pdo)
    {

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->nome = $_POST["name"];
            $this->email = $_POST["email"];
            $this->senha = $_POST["senha"];
            $this->data_cadastro = date("y-m-d");
        }

        $sql = "INSERT INTO usuario (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nome' => $this->nome, 'email' => $this->email, 'senha' => password_hash($this->senha, PASSWORD_DEFAULT)]);

        $lastInsertId = $pdo->lastInsertId();

        $_SESSION['id'] = $lastInsertId;
        $_SESSION['nome'] = $this->nome;
        $_SESSION['email'] = $this->email;


        header("Refresh: 0; URL=index.php");

    }

    function autenticar(PDO $pdo)
    {

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->email = $_POST["email"];
            $this->senha = $_POST["senha"];
        }

        $sql = "SELECT * FROM usuario WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $this->email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($this->senha, $usuario['senha'])) {
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['email'] = $usuario['email'];
            header("Refresh: 0; URL=index.php");
        } else {
            header("Refresh: 0; URL=login.html");
        }
    }

    function getDetalhes(PDO $pdo, int $id)
    {
        $sql = "SELECT * FROM usuario WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            return $usuario['nome'] . '' . $usuario['email'];
        }
        return "Vazio";
    }

}

?>
