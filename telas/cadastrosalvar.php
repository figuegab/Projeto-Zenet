<?php

    $conexao = mysqli_connect("localhost", "root", "", "meu_sistema");
     //"$conexao" é a variável que guarda a conexão, tudo que for feito com o banco vai usar essa variável 
    //"$conexao = mysqli_connect" é o código que faz a conexão com o banco de dados
    //"localhost" seria o endereço do servido, que no caso é o meu próprio PC
    //"root" é o usuário padrão do MySQL no XAMPP (o que estou usando para estudar)
    //"" essas aspas vazias representam a senha do usuário root, que no XAMPP vem vazia
    //"meu_sistema" é o meu banco de dados, que criei no phpmyadmin

    if (!$conexao) 
    {
            echo "Erro na conexão com o banco de dados";
    }
    //esse "if (!$conexao)" significa: se NAO conectou, essa exclamação na frente da variável significa negação(NÃO)
    //se o código não conseguir se conectar ao banco, vai aparecer a mensagem de erro na proxima pagina depois do login


    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $dt_nascimento = $_POST['dt_nascimento'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    //cada variável vai guardar os dados digitados em seus respectivos campos na hora do cadastro

    if (empty($cpf))
    {
        header("Location: cadastro.php?erro=CPF obrigatório");
        exit;
    }
        
    if (empty($nome))
    {
        header("Location: cadastro.php?erro=Nome obrigatório");
        exit;
    }

    if (empty($senha))
    {
        header("Location: cadastro.php?erro=Senha obrigatória");
        exit;
    }
    //o comando "if (empty($variável)", verifica se o campo está vazio
    //o header redireciona novamente para a pagina de cadastro, com a mensagem "?erro=CPF obrigatório"
    //o exit encerra o código, sem ele, o php continuaria executando


    if ($senha != $confirmar_senha)
    {
        header("Location: cadastro.php?erro=Senhas não conferem");
        exit;
    }
    //esse "if ($senha != $confirmar_senha)" vai confirmar se as senhas digitadas são iguais, se forem diferentes, vai aparecer a mensagem "senhas não conferem"
    else
    {
            $tipo = "aluno";
            //já define o tipo como aluno automaticamente, o usuário não escolhe

            $sql = "INSERT INTO usuarios (nome, cpf, dt_nascimento, email, senha, tipo)
                    VALUES ('$nome', '$cpf', '$dt_nascimento', '$email', '$senha', '$tipo')";
            //esse é o comando que insere (INSERT) os dados no banco, o "INSERT INTO usuarios" fala que é pra inserir na tabela usuarios
            //dentro dos parenteses tem o nome das colunas da tabela, e em "VALUES", na linha de baixo, os dados que é pra colocar em cada coluna, que são os valores das variáveis, que armazenam os dados digitados pelo usuário na tela de cadastro

            $resultado = mysqli_query($conexao, $sql);
            //esse comando é o que executa o INSERT acima, insere os dados na tabela

            if ($resultado)
            {
                    echo '
                <!DOCTYPE html>
                <html lang="pt-br">
                <head>
                        <meta charset="UTF-8">
                        <title>Cadastro realizado</title>
                        <link rel="stylesheet" href="css/cadSalvar.css">
                </head>

                        <div class = "logo">
                                <img src="../img/logo1.png" alt="">
                        </div>
                        <body>

                        <h3 class="green">Cadastro realizado com sucesso!</h3>
                        <p>Agora você pode acessar o sistema</p>

                        <br>

                                <a href="logintela.php">
                                <button>Acessar o sistema</button>
                                </a>

                </body>
                </html>
                ';
            }
            //esse "if ($resultado)" verifica se deu certo, se sim, aparece uma mensagem falando que o cadastro foi feito e redireciona para a tela de login novamente
            //esse trecho ficou amarelo porque é um codigo de HTML, mas dentro do php, o "echo ' CÓDIGOS '" faz isso, ele permite usar html dentro do php
            //"<a href="logintela.php">" cria o link para a tela de login
            //o comando <button> cria um botão para o usuário clicar e voltar para a tela de login, ele esta dentro do <a> que cria o link
            //ou seja, o <a> + <button> cria o botão linkado à tela de login    

            else
            {
                    echo "Erro ao cadastrar";
            }
            //se não der certo, aparece essa mensagem de erro
    }

?>

         <?php include_once 'footer.php'; ?>