<!DOCTYPE html>
<html lang="pt-br">
          <!-- o comando acima serve para definir a linguagem da página -->
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="css/login.css">
                <title>ACADEMIA ZENIT</title>
        </head>
          <!-- os comandos acima servem pra definir as configurações do site, e o título que aparece na aba do navegador -->
    <body>
        <div class = img_logo>
          <img src="../img/logo1.png" alt="Imagem">
        </div>
          <!-- os comandos acima servem pra definir os títulos e subtítulos que terão no site, e o style é pra centralizar tudo -->
       
        <div class=caixa_login>
        <!-- div é uma caixa que colocamos os conteudos dentro para modificar no CSS -->


          <form
              method="post"
              action="loginvalidacaoCOMSQL.php"
            >

            <!-- os comandos acima são pra criar formulários (campos para preenchimento do usuário), e o action é pra onde os dados vão -->
             

              <h1>Login</h1>
              <!--H1 nome que fica dentro da div-->
              <input 
                  type="text"
                  id="cpf"
                  name="cpf"
                  placeholder = "Digite seu CPF"
                  maxlength="11"
              >
            <!--placeholder e o nome que vai aparecer dentro do input-->
            <!-- os comandos acima são pra criar um campo para o usuario digitar o login -->    
              <br> 
              <input
                  type="password"
                  id="senha"
                  name="senha"
                  placeholder = "Digite sua senha"   
              >
            <!--placeholder e o nome que vai aparecer dentro do input-->
            <!-- os comandos acima são pra criar um campo para o usuario digitar a senha -->
              <br><br>

              <button type="submit"><b>Logar</b></button><br>

              <?php
          if (isset($_GET['erro']))
          {
                  echo "<p class='erro'>".$_GET['erro']."</p>";
          }
        ?>
            <!-- os comandos acima são pra criar botão para o usuário enviar os dados para outro lugar, nesse caso, para o arquivo "loginvalidacaoCOMSQL.php", que é o action do form -->            
              <br><br>
              <p class = "cadastro">Não tem uma conta?  <a href="cadastro.php">Cadastre-se</a></p>

            <!-- os comando acima são para criar um link clicável,
            "<a>" é o link
            "href" é pra onde vai
            "cadastro.php" é a tela que vai abrir quando clicar 
            "Não tem conta? Cadastre-se" é o que o usuário vai ver -->

                
          </form>
        </div>
    </body>

          <?php include_once 'footer.php'; ?>

</html>