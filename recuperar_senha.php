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
            echo "<script>alert('Uma senha temporaria foi gerada e enviada (simulação). Verifique o arquivo emails_simulados.txt'); window.location.href='login.php';</script>";
        }
    }
?>