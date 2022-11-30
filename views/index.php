<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./meucss.css"></link>
    <link href="./bootstrap.css"></link>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script>
    <script src="./bootstrat.js" ></script>
    <title>Usuarios</title>
</head>
<body>

    <form class="usuarios">
        <input hidden type="text" class="" name="id" id="id" placeholder="id do usuário" > <!--campo escondido\oculto-->
        <input type="text" class="" name="email" id="email" placeholder="nome do usuário">
        <input type="text" class="" name="nomeUsuario" id="nomeUsuario" placeholder="nickname">
        <input type="text" class="" name="senha" id="senha" placeholder="digite sua senha">
        <input type="text" class="" name="confirmaSenha" id="confirmaSenha" placeholder="repita a senha">
        <select name="status" id="stauts">
            <option value="1" selected >Ativo</option>
            <option value="0" >Inativo</option>
        </select>
        <button type="button" class="btnEnviarDados">Salvar</button>
    </form>

    <p class="paragrafo-teste" > apos o js </p> <!-- medio e na cor roxa -->
    <p class="paragrafo-voltar" > apos o js </p> <!-- pequeno e na cor verde -->

</body>
</html>
<script src="./usuarios.js" ></script>
