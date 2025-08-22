<?php
    session_start();
    require_once 'conexao.php';

    //verifica se o usuario tem permissao de adm
    if($_SESSION['perfil'] !=1){
        echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
        exit();
    }

    //inicializa variaveis
    $funcionario=null;

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if(!empty($_POST['busca_funcionario'])){
            $busca=trim($_POST['busca_funcionario']);

            //verifica se a busca é um numero (id) ou um nome
            if(is_numeric($busca)){
                $sql="SELECT * FROM funcionario where id_funcionario = :busca";
                $stmt=$pdo->prepare($sql);
                $stmt->bindParam(':busca',$busca,PDO::PARAM_INT);
            } else {
                $sql="SELECT * FROM funcionario where nome_funcionario LIKE :busca_nome";
                $stmt=$pdo->prepare($sql);
                $stmt->bindValue(':busca_nome',"$busca%",PDO::PARAM_STR);
            }

            $stmt->execute();
            $funcionario=$stmt->fetch(PDO::FETCH_ASSOC);

            //se o usuario nao for encontrado, exibe um alerta
            if(!$funcionario){
                echo "<script>alert('Funcionário não encontrado!');</script>";
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
    <title>Alterar Funcionário</title>
    <link rel="stylesheet" href="styles.css">
    <script src="scripts.js"></script>
    <script src="bootstrap/jquery-3.6.0.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <script src="valida_campo.js"></script>
    <script src="validacoes.js"></script>
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
    <h2 align="center">Alterar Funcionário</h2>
    
    <form class="border border-dark-subtle" action="alterar_funcionario.php" method="POST">
        <label for="busca_funcionario">Digite o ID ou Nome do funcionário</label>
        <input type="text" id="busca_funcionario" name="busca_funcionario" required onkeyup="buscarSugestoes()">

        <!-- div para exibis sugestoes de usuarios -->
        <div id="sugestoes"></div>
        <button class="btn btn-primary" type="submit">Buscar</button>
    </form>

    <?php if($funcionario): ?>
        <!-- formulario para alterar usuario -->
        <form action="processa_alteracao_funcionario.php" method="POST">
            <input type="hidden" name="id_funcionario" value="<?=htmlspecialchars($funcionario['id_funcionario'])?>">

            <label for="nome_funcionario">Nome</label>
            <input type="text" id="nome_funcionario" placeholder="Insira o nome (min. 3)"name="nome_funcionario" value="<?=htmlspecialchars($funcionario['nome_funcionario'])?>" onkeypress="return apenasLetras(event)" required>

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" placeholder="exemplo@exemplo.com" value="<?=htmlspecialchars($funcionario['email'])?>" required>

            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco" placeholder="Rua exemplo, 0" value="<?=htmlspecialchars($funcionario['endereco'])?>" required> 
            
            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" placeholder="(00) 00000-0000" value="<?=htmlspecialchars($funcionario['telefone'])?>" onkeypress="return apenasNumeros(event)" maxlength="11" required> 

            <button class="btn btn-success" type="submit" onclick="return validarFuncionario()">Alterar</button>
            </br>
            <button class="btn btn-primary" type="reset">Limpar</button>
        </form>
    <?php endif; ?>
    <p align="center"><a class="btn btn-secondary" role="button" href="principal.php">Voltar</a></p>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
    <script>$("#telefone").mask("(00) 00000-0000")</script>
    <script>
        function validarFuncionario(){
            let telefone = document.getElementById("telefone").value.trim();
            console.log(telefone);

            if(telefone.length !== 15){
            alert('Telefone inválido!');
            event.preventDefault();
            return;
            }

            let nome = document.getElementById("nome_funcionario").value;

            if (nome.length < 3) {
                alert("O nome do funcionário deve ter pelo menos 3 caracteres.");
                return false;
            }
        }
    </script>
</body>
</html>