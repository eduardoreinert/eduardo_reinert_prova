<?php
    session_start();
    require_once 'conexao.php';
    require_once 'funcoes_email.php'; //arquivo com as funções que geram as senhas e simulam o envio

    if($_SERVER['REQUEST_METHOD']=="POST"){
        $email=$_POST['email'];

        //verifica se o email existe no banco de dados
        $sql="SELECT * FROM usuario WHERE email=:email";
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':email',$email);
        $stmt->execute();
        $usuario=$stmt->fetch(PDO::FETCH_ASSOC);

        if($usuario){
            //gera uma senha temporaria e aleatoria
            $senha_temporaria=gerarSenhaTemporaria();
            $senha_hash=password_hash($senha_temporaria,PASSWORD_DEFAULT);

            //atualiza a senha do usuario no banco 
            $sql="UPDATE usuario SET senha=:senha,senha_temporaria=TRUE WHERE email=:email";
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(':senha',$senha_hash);
            $stmt->bindParam(':email',$email);
            $stmt->execute();

            //simula o envio do email (grava em txt)
            simularEnvioEmail($email,$senha_temporaria);
            echo "<script>alert('Uma senha temporaria foi gerada e enviada (simulação). Verifique o arquivo emails_simulados.txt'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('E-mail não encontrado!');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar senha</title>
    <link rel="stylesheet" href="styles.css">
    <script src="bootstrap/jquery-3.6.0.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
</head>
<body>
    <h2 align="center">Recuperar senha</h2>
    <form action="recuperar_senha.php" method="POST">
        <label for="email">Digite o seu E-mail cadastrado</label>
        <input type="email" id="email" name="email" required>

        <button type="submit">Enviar a senha temporária</button>
    </form>
    <p align="center">EDUARDO BORSATO REINERT | DESN20242v1</p>

</body>
</html>