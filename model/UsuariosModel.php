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

    // obtem todos os usuarios
    public function getUsers() {
        $sql = "SELECT * FROM usuarios WHERE (excluido = 0 OR excluido is null);";
        $users = $this->conexao->query($sql);

        $arrUsers = [];
        while($row = $users->fetch_assoc()) {
            // echo "<br/>";
            // echo $row['id'] . " - " . $row['nome_usuario'];
            // echo "<br/>";
            $arrUsers[] = $row;
        }

        // var_dump($arrUsers);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($arrUsers);
        exit;
    }

        // obtem todos os usuarios
        public function getUser($id = 0) {
            if (!$id || $id == 0 ) return [];

            $id = $id * 1; // 1

            $sql = `SELECT * FROM usuarios WHERE (excluido = 0 OR excluido is null) AND id = {$id};`;
            $users = $this->conexao->query($sql);
    
            $arrUsers = [];
            while($row = $users->fetch_assoc()) {
                // echo "<br/>";
                // echo $row['id'] . " - " . $row['nome_usuario'];
                // echo "<br/>";
                $arrUsers[] = $row;
            }
    
            // var_dump($arrUsers);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($arrUsers);
            exit;
        }



}

try {
    $objUser =  new UsuariosModel($connection); // $connection que veio do require.
    echo $objUser->getUsers();
} catch (Exception $e) {
    echo "Erro codigo: " . $e.getCode() . ". Erro Message: " . $e.getMessage();
    // "Erro codigo: 500. Erro Message: Conexao nao pode ser nula.";
}

