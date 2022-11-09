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

    private function isValidID($id) {
        if (!$id || !is_numeric($id) || $id == 0 ) {
            return false;
        }

        return true;
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
        
        if (!$this->isValidID($id)) $this->retornoAPI();

        $id = $id * 1; // 1 || "1" || '1' is_numeric

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
        if (!$this->isValidID($id)) $this->retornoAPI();

        $id = $id * 1; // 1

        $sql = "UPDATE usuarios SET excluido = 1 WHERE id = {$id};";+
        $users = $this->conexao->query($sql);

        if ($users) $this->retornoAPI(); // true ou false se foi deletado (soft delete)
    }

    public function upSertUser($id = 0, $dadosUsuario = []) {

        $id = $id * 1; // 1

        if ($id != 0 && !$this->isValidID($id)) $this->retornoAPI();

        if (count($dadosUsuario) <= 0) {
            $this->retornoAPI();
        }


        $camposTabela = [
            'id' => 0,
            'email' => '',
            'senha' => '',
            'nome_usuario' => '',
            'status' => '',
            'id_perfil_usuario' => 1,
            'excluido' => '',
            'id_usuario_criacao' => '',
            'id_usuario_alteracao' => '',
            'id_usuario_exclusao' => '',
            'data_criacao' => '',
            'data_alteracao' => '',
            'data_exclusao' => '',
        ];


        $camposTabelaUpsert = array_merge($camposTabela, $dadosUsuario);


        $sql = "UPDATE usuarios SET excluido = 1 WHERE id = {$id};";+
        $users = $this->conexao->query($sql);


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

