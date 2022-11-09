<?php
// Variaveis para conexao com o banco.
$hostname = "localhost";
$username = "aluno";
$password = "qwe123";
$database = "php_lab";
$port = "3306";

try {
    $connection = mysqli_connect(
        $hostname,
        $username,
        $password,
        $database,
        $port
    );
} catch (Exception $error ){
    echo "ERRO codigo: {$error->getCode()} <br> mensagem: {$error->getMessage()}";
    // echo "ERRO codigo: " . $error->getCode() . " <br> mensagem: " . $error->getMessage();
    // echo "ERRO: {$error}";
}
