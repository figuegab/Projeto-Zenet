
<!DOCTYPE html>
<html lang="pt-br">
<!-- o comando acima serve para definir a linguagem da página -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/pgaluno.css">
    <title>ACADEMIA ZENIT</title>
</head>
<!-- os comandos acima servem pra definir as configurações do site, e o título que aparece na aba do navegador -->

<body>

<?php
session_start();
//session_start() inicia a sessão do usuário, para guardar os dados de quem está logado

//if (!isset($_SESSION['cpf'])) {
//    header("Location: logintela.php");
//    exit;
//}

$conexao = mysqli_connect("localhost", "root", "", "meu_sistema");
//"$conexao" é a variável que guarda a conexão com o banco de dados
//"localhost" é o endereço do servidor
//"root" é o usuário padrão do MySQL no XAMPP
//"" a senha do root no XAMPP vem vazia
//"meu_sistema" é o banco de dados

if (!$conexao)
{
    echo "Erro na conexão com o banco de dados";
    exit;
}
//se não conseguir conectar ao banco, aparece a mensagem e para o código

$cpf_logado = $_SESSION['cpf'];
//"$_SESSION['cpf']" pega o CPF do aluno que está logado, guardado na sessão quando fez o login

$sql_aluno = "SELECT * FROM usuarios WHERE cpf = '$cpf_logado'";
//busca os dados do aluno logado na tabela usuarios

$resultado_aluno = mysqli_query($conexao, $sql_aluno);
//executa a busca no banco

$aluno = mysqli_fetch_assoc($resultado_aluno);
//pega os dados do aluno e transforma em array
//$aluno['nome'] vai ter o nome, $aluno['cpf'] vai ter o CPF, etc.

$nome_aluno = $aluno['nome'];
//pega só o nome do aluno para usar depois
?>

<div class="img_logo">
    <img src="../img/logo1.png" alt="Imagem">
</div>
<!-- logo da academia no topo da página -->

<div class="caixa">
    <!-- caixa principal que vai conter o conteúdo da página -->

    <a class="sair" href="logintela.php">Sair</a>
    <!-- link para sair e voltar para a tela de login -->

    <h1>Olá, <?php echo $nome_aluno; ?>!</h1>
    <!-- "echo $nome_aluno" mostra o nome do aluno na tela, puxando do banco de dados -->

    <h2>Seus Treinos</h2>
    <!-- subtítulo da seção -->

    <?php
    $sql_treinos = "SELECT treinos.*, usuarios.nome AS nome_professor
                            FROM treinos
                            INNER JOIN usuarios ON treinos.cpf_professor = usuarios.cpf
                            WHERE treinos.cpf_aluno = '$cpf_logado'
                            ORDER BY treinos.id DESC";
    //esse comando SQL busca os treinos do aluno
    //SELECT treinos.* = pega todos os dados da tabela treinos
    //usuarios.nome AS nome_professor = pega o nome do professor e renomeia para usar fácil
    //INNER JOIN usuarios = junta com a tabela usuarios para pegar o nome do professor
    //WHERE treinos.cpf_aluno = filtra só os treinos desse aluno
    //ORDER BY id DESC = mostra os mais recentes primeiro

    $resultado_treinos = mysqli_query($conexao, $sql_treinos);
    //executa a busca dos treinos no banco

    if (mysqli_num_rows($resultado_treinos) > 0)
    {
        //se encontrou pelo menos 1 treino, entra aqui

        while ($treino = mysqli_fetch_assoc($resultado_treinos))
        {
            //o "while" vai repetir esse bloco para cada treino encontrado
            //"$treino" vai guardar os dados de um treino por vez

            echo "
                    <div class='card_treino'>
                        <h3>" . $treino['nome_treino'] . "</h3>
                        <p><b>Professor:</b> " . $treino['nome_professor'] . "</p>
                        <p><b>Descrição:</b> " . $treino['descricao_treino'] . "</p>
                        <p><b>Data:</b> " . date('d/m/Y', strtotime($treino['data_criacao'])) . "</p>
                    </div>
                    ";
            //"echo" mostra o HTML na tela
            //"$treino['nome_treino']" pega o nome do treino do banco
            //"$treino['nome_professor']" pega o nome do professor
            //"date('d/m/Y', strtotime(...))" formata a data para o padrão brasileiro
        }
    }
    else
    {
        echo "<p class='sem_treino'>Nenhum treino cadastrado ainda. Aguarde seu professor!</p>";
        //se não tiver treinos, mostra essa mensagem
    }
    ?>

</div>

</body>

            <?php include_once 'footer.php'; ?>

</html>