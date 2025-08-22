<?php
    session_start();
    require_once 'conexao.php';

    //verifica se o usuario tem permissao 
    //supondo que o perfil 1 seja o administrador

    if($_SESSION['perfil']!=1){
        echo "Acesso Negado!";
    }

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $nome_funcionario=$_POST['nome_funcionario'];
        $email=$_POST['email'];
        $endereco=$_POST['endereco'];
        $telefone=$_POST['telefone'];
        
        $sql="INSERT INTO funcionario(nome_funcionario,endereco,telefone,email) VALUES (:nome_funcionario,:endereco,:telefone,:email)";
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':nome_funcionario',$nome_funcionario);
        $stmt->bindParam(':email',$email);
        $stmt->bindParam(':endereco',$endereco);
        $stmt->bindParam(':telefone',$telefone);

        if($stmt->execute()){
            echo "<script>alert('Funcionário cadastrado com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar funcionário');</script>";
        }
    }

    
    //obtendo o nome do perfil do usuario logado
    $id_perfil=$_SESSION['perfil'];
    $sqlPerfil="SELECT nome_perfil FROM perfil WHERE id_perfil =:id_perfil";
    $stmtPerfil=$pdo->prepare($sqlPerfil);
    $stmtPerfil->bindParam(':id_perfil',$id_perfil);
    $stmtPerfil->execute();
    $perfil=$stmtPerfil->fetch(PDO::FETCH_ASSOC);
    $nome_funcionario_perfil=$perfil['nome_perfil'];

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
    <title>Cadastrar funcionário</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script src="valida_campo.js"></script>
    <script src="bootstrap/jquery-3.6.0.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="validacoes.js"></script>
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
    <h2 align="center">Cadastrar Funcionário</h2>
    <form class="border border-dark-subtle" action="cadastro_funcionario.php" method="POST">
        <label for="nome_funcionario">Nome Funcionário:</label>
        <input type="text" id="nome_funcionario" name="nome_funcionario" placeholder="Insira o nome (min. 3)" onkeypress="return apenasLetras(event)" required>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" placeholder="exemplo@exemplo.com" required> 

        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" placeholder="Rua exemplo, 0" required> 
        
        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone" placeholder="(00) 00000-0000" onkeypress="return apenasNumeros(event)" minlength="10" maxlength="11" required> 
        </br>
        <button class="btn btn-success" id="enviar_formulario" type="submit" onclick="return validarFuncionario()">Salvar</button>
        </br>
        <button class="btn btn-primary" type="reset">Cancelar</button>
    </form>

    <p align="center"><a class="btn btn-secondary" role="button" href="principal.php">Voltar</a></p>

    <p align="center">EDUARDO BORSATO REINERT | DESN20242v1</p>

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