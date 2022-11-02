<?php

require_once '../database/conexao.php';

// var_dump($connection);

class UsuariosModel {
    public $conexao = null;
    // private $params = $_POST;
    //['id => 1', 'nome' => 'ariel']

    public function __construct($conexao = null) {
        if (!$conexao) {
            throw new Exception('Conexao nao pode ser nula.', 500);
        }

        $this->conexao = $conexao;
    }

    public function retornoAPI($dados = []) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($dados);
        exit;
    }

    // obtem todos os usuarios
    public function getUsers() {
        $sql = "SELECT * FROM usuarios WHERE (excluido = 0 OR excluido is null);";
        $users = $this->conexao->query($sql);

        $arrUsers = [];
        while($row = $users->fetch_assoc()) {
            $arrUsers[] = $row;
        }

        $this->retornoAPI($arrUsers);
    }

    // obtem todos os usuarios
    public function getUser($id = 0) {
        if (!$id || $id == 0 ) $this->retornoAPI();

        $id = $id * 1; // 1

        // $sql = "SELECT * FROM usuarios WHERE (excluido = 0 OR excluido is null) AND id = " . $id . ";"; // versao php 5 ate < 7
        $sql = "SELECT * FROM usuarios WHERE (excluido = 0 OR excluido is null) AND id = {$id};"; // versao php 7+
        $users = $this->conexao->query($sql);

        if ($users->num_rows <= 0) $this->retornoAPI(); // se nao encontrou registros no banco.

        $arrUsers = [];
        while($row = $users->fetch_assoc()) {
            $arrUsers[] = $row;
        }

        $this->retornoAPI($arrUsers);
    }

    public function deleteUser($id = 0) {
        if (!$id || $id == 0 ) $this->retornoAPI();

        $id = $id * 1; // 1

        $sql = "UPDATE usuarios SET excluido = 1 WHERE id = {$id};";+
        $users = $this->conexao->query($sql);

        if ($users) $this->retornoAPI(); // true ou false se foi deletado (soft delete)
    }

}

try {
    
    $paramsDefault = [
        "id" => 0,
        "nome" => '',
        "idade" => 0,
        "email" => '',
    ];
    
    $params =  array_merge($paramsDefault, $_REQUEST);

    // ...?idUsuario=1&nome=otto&idade=18 veio do navegador
    // $params = [
    //     "id" => 0,
    //     "nome" => 'otto',
    //     "idade" => 0,
    //     "email" => '',
    //     "idUsuario" => 1,
    // ];

    $objUser =  new UsuariosModel($connection); // $connection que veio do require.
    echo $objUser->getUser($params['idUsuario']);

    // faca um cafe
} catch (Exception $e) {
    echo "Erro codigo: " . $e.getCode() . ". Erro Message: " . $e.getMessage();
    // "Erro codigo: 500. Erro Message: Conexao nao pode ser nula.";
}

