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
                $stmt->bindValue(':busca_nome',"$busca%",PDO::PARAM_STR);
            }

            $stmt->execute();
            $usuario=$stmt->fetch(PDO::FETCH_ASSOC);

            //se o usuario nao for encontrado, exibe um alerta
            if(!$usuario){
                echo "<script>alert('Usuário não encontrado!');</script>";
            }
        }
    }
    
    //obtendo o nome do perfil do usuario logado
    $id_perfil=$_SESSION['perfil'];
    $sqlPerfil="SELECT nome_perfil FROM perfil WHERE id_perfil =:id_perfil";
    $stmtPerfil=$pdo->prepare($sqlPerfil);
    $stmtPerfil->bindParam(':id_perfil',$id_perfil);
    $stmtPerfil->execute();
    $perfil=$stmtPerfil->fetch(PDO::FETCH_ASSOC);
    $nome_perfil=$perfil['nome_perfil'];

    //definição das terminações por perfil

    $permissoes=[
        1 => [
            "Cadastrar"=>["cadastro_usuario.php","cadastro_perfil.php","cadastro_cliente.php","cadastro_fornecedor.php","cadastro_produto.php","cadastro_funcionario.php"],
            "Buscar"=>["buscar_usuario.php","buscar_perfil.php","buscar_cliente.php","buscar_fornecedor.php","buscar_produto.php","buscar_funcionario.php"],
            "Alterar"=>["alterar_usuario.php","alterar_perfil.php","alterar_cliente.php","alterar_fornecedor.php","alterar_produto.php","alterar_funcionario.php"],
            "Excluir"=>["excluir_usuario.php","excluir_perfil.php","excluir_cliente.php","excluir_fornecedor.php","excluir_produto.php","excluir_funcionario.php"]
        ],

        2 => [
            "Cadastrar"=>["cadastro_cliente.php"],
            "Buscar"=>["buscar_cliente.php","buscar_fornecedor.php","buscar_produto.php"],
            "Alterar"=>["alterar_fornecedor.php","alterar_produto.php"],
            "Excluir"=>["excluir_produto.php"]
        ],

        3 => [
            "Cadastrar"=>["cadastro_fornecedor.php","cadastro_produto.php"],
            "Buscar"=>["buscar_cliente.php","buscar_fornecedor.php","buscar_produto.php"],
            "Alterar"=>["alterar_fornecedor.php","alterar_produto.php"],
            "Excluir"=>["excluir_produto.php"]
        ],

        4 => [
            "Cadastrar"=>["cadastro_cliente.php"],
            "Buscar"=>["buscar_produto.php"],
            "Alterar"=>["alterar_cliente.php"],
        ],
    ];

    //obtendo as opções disponiveis para o perfil logado
    $opcoes_menu=$permissoes[$id_perfil];
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
    <script src="bootstrap/jquery-3.6.0.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
</head>
<body>
    <nav>
        <ul class="menu">
            <?php foreach($opcoes_menu as $categoria => $arquivos): ?>
                <li class="dropdown">
                    <a href="#"><?=$categoria?></a>
                    <ul class="dropdown-menu">
                        <?php foreach($arquivos as $arquivo): ?>
                            <li>
                                <a href="<?=$arquivo ?>"><?=ucfirst(str_replace("_"," ",basename($arquivo,".php")))?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <h2 align="center">Alterar usuário</h2>
    
    <form class="border border-dark-subtle" action="alterar_usuario.php" method="POST">
        <label for="busca_usuario">Digite o ID ou Nome do usuário</label>
        <input type="text" id="busca_usuario" name="busca_usuario" required onkeyup="buscarSugestoes()">

        <!-- div para exibis sugestoes de usuarios -->
        <div id="sugestoes"></div>
        <button class="btn btn-primary" type="submit">Buscar</button>
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
                <option value="2" <?=$usuario['id_perfil'] == 2 ?'select':''?>>Secretaria</option>
                <option value="3" <?=$usuario['id_perfil'] == 3 ?'select':''?>>Almoxarife</option>
                <option value="4" <?=$usuario['id_perfil'] == 4 ?'select':''?>>Cliente</option>
            </select>

            <!-- se o usuario logado for adm, exibir opção de alterar senha -->
            <?php if($_SESSION['perfil']==1): ?>
                <label for="nova_senha">Nova senha</label>
                <input type="password" id="nova_senha" name="nova_senha">
            <?php endif; ?>

            <button class="btn btn-primary" type="submit">Alterar</button>
            </br>
            <button class="btn btn-primary" type="reset">Limpar</button>
        </form>
    <?php endif; ?>
    <p align="center"><a class="btn btn-secondary" role="button" href="principal.php">Voltar</a></p>
</body>
</html>