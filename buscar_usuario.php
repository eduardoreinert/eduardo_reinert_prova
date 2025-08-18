<?php
    session_start();
    require_once 'conexao.php';

    //verifica se o usuario tem permissao de adm ou secretaria
    if($_SESSION['perfil'] !=1 && $_SESSION['perfil'] !=2){
        echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
        exit();
    }

    $usuario=[]; //inicializa a variavel para evitar erros

    //se o formulario for enviado, busca o usuario pelo id ou nome
    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['busca'])){
        $busca=trim($_POST['busca']);

        //verifica se a busca é um numero ou nome
        if(is_numeric($busca)){
            $sql="SELECT * FROM usuario WHERE id_usuario = :busca ORDER BY nome ASC";
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(':busca',$busca, PDO::PARAM_INT);
        } else {
            $sql="SELECT * FROM usuario WHERE nome LIKE :busca_nome ORDER BY nome ASC";
            $stmt=$pdo->prepare($sql);
            $stmt->bindValue(':busca_nome',"$busca%", PDO::PARAM_STR); //MUDAR AQUI PARA A ENTREGA (ja mudei)
        }
    } else {
        $sql="SELECT * FROM usuario ORDER BY nome ASC";
        $stmt=$pdo->prepare($sql);
    }
    $stmt->execute();
    $usuarios=$stmt->fetchAll(PDO::FETCH_ASSOC);

    
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
    <title>Buscar usuário</title>
    <link rel="stylesheet" href="styles.css">
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
    <h2 align="center">Lista de Usuários</h2>
    <form action="buscar_usuario.php" method="POST">
        <label for="busca">Digite o ID ou Nome(opcional): </label>
        <input type="text" id="busca" name="busca">
        <button class="btn btn-primary" type="submit">Pesquisar</button>
    </form>
    <?php if(!empty($usuarios)): ?>
        <div class="container">
        <table border="1" align="center" class="table table-light table-hover">
            <tr>
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
                        <a class="btn btn-warning" href="alterar_usuario.php?id=<?=htmlspecialchars($usuario['id_usuario'])?>">Alterar</a>
                        <a class="btn btn-danger" href="excluir_usuario.php?id=<?=htmlspecialchars($usuario['id_usuario'])?>" onclick="return confirm('Tem certeza da exclusão?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
            <p align="center">Nenhum usuário encontrado.</p> 
        <?php endif; ?>
        </br>
        <p align="center"><a class="btn btn-secondary" role="button" href="principal.php">Voltar</a></p>
        </div>
</body>
</html>