<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Usuário</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/registerLogin.css">
    <link href="//cdn.muicss.com/mui-0.9.12/css/mui.min.css" rel="stylesheet" type="text/css" />
    <script src="//cdn.muicss.com/mui-0.9.12/js/mui.min.js"></script>
</head>
<body>
    <?php
    require_once "funcoes.php";
    if(empty($_POST)){
        include "inc/form_login.inc";
    }else{
        autenticarUsuario();
    }
    ?>

    <a href="cadastro.php">Cadastrar</a>
</body>
</html>