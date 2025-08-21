<?php
    session_start();
    require 'conexao.php';

    if($_SESSION['perfil']!=1){
        echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
        exit();
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $id_funcionario = $_POST['id_funcionario'];
        $nome = $_POST['nome_funcionario'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $endereco = $_POST['endereco'];

        //atualiza os dados do usuario
        $sql="UPDATE funcionario SET nome_funcionario=:nome_funcionario,email=:email,telefone=:telefone,endereco=:endereco WHERE id_funcionario = :id_funcionario";
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':email',$email);
        $stmt->bindParam(':nome_funcionario',$nome);
        $stmt->bindParam(':telefone',$telefone);
        $stmt->bindParam(':endereco',$endereco);
        $stmt->bindParam(':id_funcionario',$id_funcionario);

        if($stmt->execute()){
            echo "<script>alert('Funcionário atualizado com sucesso!');window.location.href='buscar_funcionarios.php';</script>";
        } else {
            echo "<script>alert('Erro ao atualizar o funcionário!');window.location.href='alterar_funcionario.php?id=$id_funcionario';</script>";
        }
    }
?>