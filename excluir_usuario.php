<?php
    session_start();
    require 'conexao.php';

    //verifica se o usuario tem permissao de adm
    if($_SESSION['perfil'] !=1){
        echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
        exit();
    }

    //inicializa vriavel para amarzenar usuarios
    $usuarios=[];

    //busca todos os usuarios cadastrados em ordem alfabetica
    $sql="SELECT * FROM usuario ORDER BY nome ASC";
    $stmt=$pdo->prepare($sql);
    $stmt->execute();
    $usuarios=$stmt->fetchAll(PDO::FETCH_ASSOC);

    //se um id for passado via get exclui o usuario
    if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $id_usuario=$_GET['id'];

        //exclui o usuario do banco de dados
        $sql="DELETE FROM usuario WHERE id_usuario =:id";
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':id',$id_usuario,PDO::PARAM_INT);

        if($stmt->execute()){
            echo "<script>alert('Usuário excluído com sucesso!');window.location.href='excluir_usuario.php';</script>";
        } else {
            echo "<script>alert('Erro ao excluir o usuário!');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir usuário</title>
    <link rel="stylesheet" href="styles.css">
    <script src="bootstrap/jquery-3.6.0.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
</head>
<body>
    <h2 align="center">Excluir usuário</h2>
    <?php if(!empty($usuarios)): ?>
        <div class="container">
        <table border="1" align="center" class="table table-light table-hover">
            <tr class="table-secondary">
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Perfil</th>
                <th>Ações</th>
            </tr>
            <?php foreach($usuarios as $usuario): ?>
            <tr>
                <td><?=htmlspecialchars($usuario['id_usuario'])?></td>
                <td><?=htmlspecialchars($usuario['nome'])?></td>
                <td><?=htmlspecialchars($usuario['email'])?></td>
                <td><?=htmlspecialchars($usuario['id_perfil'])?></td>
                <td>
                    <a href="excluir_usuario.php?id=<?=htmlspecialchars($usuario['id_usuario'])?>" onclick="return confirm('Tem certeza de que deseja excluir este usuário?')">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Nenhum usuário encontrado!</p>
    <?php endif; ?>
    </br>
    <p align="center"><a class="btn btn-secondary" role="button" href="principal.php">Voltar</a></p>
    </div>
</body>
</html>