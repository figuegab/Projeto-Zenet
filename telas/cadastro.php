<!DOCTYPE html>
<html lang="pt-br">
          <!-- o comando acima serve para definir a linguagem da página -->
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="css/cadastro.css">
                <title>ACADEMIA ZENIT</title>
        </head>
          <!-- os comandos acima servem pra definir as configurações do site, e o título que aparece na aba do navegador -->

    <body>


        <div class="caixa">
        <!-- Codigo para criar uma caixa no CSS-->
            <a class = voltar href="logintela.php">Voltar</a>
            <!-- Codigo para colocar um botao de voltar-->

            <img src="../img/logo1.png" alt="Imagem">
            <!-- Codigo para colocar a img da logo-->
            
            <h1 class = cadastros>CADASTRO</h1>
            <!-- subtítulo da página -->

            <?php 
                if (isset($_GET['erro']))
                {
                        echo "<p class='erro'>".$_GET['erro']."</p>";
                }
            ?>
            <!--com esse código em php acima, se der algum erro, vai aparecer na tela -->

            <form
                method="post"
                action="cadastrosalvar.php"
            >
            <!-- method="post" serve pra enviar dados, action="cadastrosalvar.php" para onde os dados vão quando clicar em cadastrar -->

                
                    <input
                        type="text"
                        name="nome"
                        placeholder = "Nome"
                    >
                <!-- campo para digitar o nome -->
                <br>

                
                    <input
                        type="text"
                        name="cpf"
                        maxlength="11"
                        placeholder = "CPF"
                    >
                <!-- campo para digitar o CPF -->
                <!-- o "maxlength" serve pra limitar os caracteres digitados no campo, para no maximo 11" -->
                <br>

                
                    <input
                        type="date"
                        name="dt_nascimento"
                        placeholder = "Data de Nascimento"
                    >
                <!-- campo para digitar a data de nascimento -->
                <br>

                
                    <input
                        type="text"
                        name="email"
                        placeholder = "E-mail"
                    >
                <!-- campo para digitar o email -->
                <br>

                
                    <input
                        type="password"
                        name="senha"
                        placeholder = "Senha"
                    >
                <!-- campo para digitar a senha -->
                <br>

                
                    <input
                        type="password"
                        name="confirmar_senha"
                        placeholder = "Confirmar Senha"
                    >
                <!-- campo para confirmar a senha -->
                <br>
                <br>

                    <button type="submit"><b>Cadastrar</b></button><br>
                    <!-- botão para enviar os dados -->

                    

            </form> <!--fecha o formulário -->
        </div>

    </body> <!-- fecha o corpo da página -->

        <?php include_once 'footer.php'; ?>

</html>