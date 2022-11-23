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

    // upSert = insert + update (chamar por meio de um POST || PUT)
    public function upSertUser($dadosUsuario = []) {
        if (count($dadosUsuario) <= 0) {
            $this->retornoAPI();
        }

        $id = $dadosUsuario['id'] ?? 0;
        $id = $id * 1; // 1

        if ($id != 0 && !$this->isValidID($id)) $this->retornoAPI();

        $camposTabela = [
            'id' => 0,
            'email' => '',
            'senha' => '',
            'nome_usuario' => '',
            'status' => 1,
            'id_perfil_usuario' => 1,
            'excluido' => 0,
            'id_usuario_criacao' => 1,
            'id_usuario_alteracao' => 0,
            'id_usuario_exclusao' => 0,
            'data_criacao' => Date('Y-m-d H:i:s'),
            'data_alteracao' => Date('Y-m-d H:i:s'),
            'data_exclusao' => '0000-00-00 00:00:00'
        ];

        // Date('Y-m-d H:i:s') = '2022-11-14 20:56:00'
        $dadosUsuario = json_decode(json_encode($dadosUsuario), true);
        $camposTabelaUpsert = array_merge($camposTabela, $dadosUsuario);

        // $this->retornoAPI($camposTabelaUpsert);

        $cont = 0;
        $totalArray = count($camposTabelaUpsert);

        // INSERT INTO `usuarios` VALUES
        // (1,'otto.arru@gmail.com','minhasenha','otto',1,1,0,1,1,NULL,'2022-10-04 21:26:29','2022-10-04 21:26:29','0000-00-00 00:00:00'),
        // (2,'ottoarrueneto@hotmail.com','123456','venezo',1,1,NULL,1,0,NULL,'2022-10-11 19:59:38','2022-10-11 20:00:08','2022-10-11 20:00:08');

        // INSERT INTO usuarios (id, email, nome_usuario) VALUES (10, 'fulano@info', 'fulaninho')
        // ON DUPLICATE KEY
        // UPDATE usuarios SET email = 'fulano@info', nome_usuario = 'fulaninho' WHERE id =10


        $insertSQL = "INSERT INTO usuarios VALUES (";
        $updateSQL = "UPDATE usuarios SET ";

        $arrCamposString = [
            "email",
            "senha",
            "nome_usuario",
            "data_criacao",
            "data_alteracao",
            "data_exclusao",
        ];

        $fnAddAspas = function($chave, $valor) use ($arrCamposString) {
            if (in_array($chave, $arrCamposString)) {
                return "'{$valor}'";
            }

            return "{$valor}";
        };
        
        foreach($camposTabelaUpsert as $chave => $valor) {
            $cont++;

            // IF TERNARIO   (condicao) ? codigo se condicao verdadeira : codigo se nao atender a condicao
            // ($cont < $totalArray) ? $sql .= "{$chave}={'$valor'}, " : $sql .= "{$chave}={'$valor'}";

            $quandoIdForZero = ($chave === "id" && $valor <= 0);

            if ($quandoIdForZero) {
                $insertSQL .= "id,";
                continue;
            }

            if ($cont < $totalArray) {
                $insertSQL .= "{$fnAddAspas($chave, $valor)},";
                $updateSQL .= "{$chave}={$fnAddAspas($chave, $valor)},";
            } else {
                $insertSQL .= "{$fnAddAspas($chave, $valor)}";
                $updateSQL .= "{$chave}={$fnAddAspas($chave, $valor)}";
            }

            $updateSQL .= "WHERE id = {$id};";
            
        }

        $insertSQL .= ")";
        
        $sql = "{$insertSQL} ON DUPLICATE KEY {$updateSQL}";
        $this->retornoAPI($sql);

        try{

            $users = $this->conexao->query($sql);
        } catch (Exception $error ){
            $msg = "ERRO query UPSERT: {$error->getCode()} <br> mensagem: {$error->getMessage()}";
            $this->retornoAPI($msg);
        }
    }

}

try {
    
    $paramsDefault = [
        "email" => '',
        "id_usuario_criacao" => 1,
        "id_usuario_alteracao" => 1,
    ];

    $entityBody = file_get_contents('php://input');
    // $entityBody = $_REQUEST['id']; // FUNCIONA PARA REQUEST DE CHAMADAS AJAX NO NAVEGADOR.
    
    $dadosUsuario = json_decode($entityBody, true);
    // $objUser =  new UsuariosModel($connection); // $connection que veio do require.
    // echo $objUser->retornoAPI($dadosUsuario);

    // $params =  array_merge($paramsDefault, $dadosUsuario);

    // $objUser =  new UsuariosModel($connection); // $connection que veio do require.
    // echo $objUser->retornoAPI($dadosUsuario);

    // ...?idUsuario=1&nome=otto&idade=18 veio do navegador
    // $params = [
    //     "id" => 0,
    //     "nome" => 'otto',
    //     "idade" => 0,
    //     "email" => '',
    //     "idUsuario" => 1,
    // ];

    $objUser =  new UsuariosModel($connection); // $connection que veio do require.
    echo $objUser->upSertUser($dadosUsuario);

    // faca um cafe
} catch (Exception $e) {
    echo "Erro codigo: " . $e.getCode() . ". Erro Message: " . $e.getMessage();
    // "Erro codigo: 500. Erro Message: Conexao nao pode ser nula.";
}

