<?php
    session_start();
    require_once 'conexao.php';

    //verifica se o usuario tem permissao de adm
    if($_SESSION['perfil'] !=1){
        echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
        exit();
    }

    //inicializa variaveis
    $usuario=null;

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if(!empty($_POST['busca_usuario'])){
            $busca=trim($_POST['busca_usuario']);

            //verifica se a busca é um numero (id) ou um nome
            if(is_numeric($busca)){
                $sql="SELECT * FROM usuario where id_usuario = :busca";
                $stmt=$pdo->prepare($sql);
                $stmt->bindParam(':busca',$busca,PDO::PARAM_INT);
            } else {
                $sql="SELECT * FROM usuario where nome LIKE :busca_nome";
                $stmt=$pdo->prepare($sql);
                $stmt->bindParam(':busca_nome',"$busca%",PDO::PARAM_STR);
            }

            $stmt->execute();
            $usuario=$stmt->fetch(PDO::FETCH_ASSOC);

            //se o usuario nao for encontrado, exibe um alerta
            if(!$usuario){
                echo "<script>alert('Usuário não encontrado!');</script>";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar usuario</title>
    <link rel="stylesheet" href="styles.css">
    <!-- certifique-se de que o JavaScript esta sendo carregado corretamente -->
    <script src="scripts.js"></script>
</head>
<body>
    <h2>Alterar usuário</h2>
    
    <form action="alterar_usuario.php" method="POST">
        <label for="busca_usuario">Digite o ID ou Nome do usuário</label>
        <input type="text" id="busca_usuario" name="busca_usuario" required onkeyup="buscarSugestoes()">

        <!-- div para exibis sugestoes de usuarios -->
        <div id="sugestoes"></div>
        <button type="submit">Buscar</button>
    </form>

    <?php if($usuario): ?>
        <!-- formulario para alterar usuario -->
        <form action="processa_alteracao_usuario.php" method="POST">
            <input type="hidden" name="id_usuario" value="<?=htmlspecialchars($usuario['id_usuario'])?>">

            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" value="<?=htmlspecialchars($usuario['nome'])?>" required>

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" value="<?=htmlspecialchars($usuario['email'])?>" required>

            <label for="id_perfil">Perfil</label>
            <select id="id_perfil" name="id_perfil">
                <option value="1" <?=$usuario['id_perfil'] == 1 ?'select':''?>>Administrador</option>
                <option value="2" <?=$usuario['id_perfil'] == 1 ?'select':''?>>Secretaria</option>
                <option value="3" <?=$usuario['id_perfil'] == 1 ?'select':''?>>Almoxarife</option>
                <option value="4" <?=$usuario['id_perfil'] == 1 ?'select':''?>>Cliente</option>
            </select>
        </form>
</body>
</html>