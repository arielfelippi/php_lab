$(document).ready(function() {

    // DOM = Document Object Model = Pagina do site.

    // addEventListener == on
    
    // JS Puro
    // var btnEnviarDados = document.getElementByClassName('btnEnviarDados')
    // var btnEnviarDados = document.getElementById('btnEnviarDados')

    // LIb AJAX/JQuery
    // $('.btnEnviarDados')
    // $('#btnEnviarDados')

    $('.btnEnviarDados').on('click', function() {

        var idUsuarioForm = $('#id').val();
        var email = $('#email').val();
        var nomeUsuarioForm = $('#nomeUsuario').val(); // ariel.infoserv
        var senha = $('#senha').val(); // ariel.infoserv
        var confirmaSenha = $('#confirmaSenha').val(); // ariel.infoserv

        if (senha !== confirmaSenha) {
            // pedir para o usuario verificar a senha.
        }

        console.log("O nome do usuario é: ", nomeUsuarioForm);

        // envia dados para o back end (PHP).
        // $.get() // ajax XHR ?id=101&idade=34434343434&cidade=osorio 

        var dadosUsuario = { 
            idPHP: idUsuarioForm, 
            nomeUsuarioPHP: nomeUsuarioForm,
            cpfPHP: email,
        }

        // $.post("/usuarioModel.php",  dadosUsuario) // ajax XHR 

        // fetch() // nativo navegadores XHR

    });


});
