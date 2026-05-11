<?php //indica que começou um código PHP

session_start();
//session_start() PRECISA ser chamado antes de tudo para usar sessões
//a sessão guarda os dados do usuário enquanto ele está navegando no site

$conexao = mysqli_connect("localhost", "root", "", "meu_sistema");
//"$conexao" é a variável que guarda a conexão, tudo que for feito com o banco vai usar essa variável
//"$conexao = mysqli_connect" é o código que faz a conexão com o banco de dados
//"localhost" seria o endereço do servidor, que no caso é o meu próprio PC
//"root" é o usuário padrão do MySQL no XAMPP (o que estou usando para estudar)
//"" essas aspas vazias representam a senha do usuário root, que no XAMPP vem vazia
//"meu_sistema" é o meu banco de dados, que criei no phpmyadmin

if (!$conexao)
{
    echo "Erro na conexão com o banco de dados";
}
//esse "if (!$conexao)" significa: se NAO conectou, essa exclamação na frente da variável significa negação(NÃO)
//se o código não conseguir se conectar ao banco, vai aparecer a mensagem de erro

$cpf = $_POST['cpf'];
$senha = $_POST['senha'];
//os códigos acima pegam os dados que usuário digitou no formulário
//o "$_POST" é uma variável especial do PHP que guarda os dados enviados no formulário

if (empty($cpf))
{
    header("Location: logintela.php?erro=Campo CPF obrigatório");
    exit;
}

if (empty($senha))
{
    header("Location: logintela.php?erro=Campo senha obrigatório");
    exit;
}
//esses 2 IF's, é pra aparecer uma mensagem de campo obrigatório caso não tenha sido digitado

$sql = "SELECT * FROM usuarios WHERE cpf = '$cpf' AND senha = '$senha'";
//isso é um comando SQL, significa:
// PROCURE (SELECT) - verificar na tabela usuários
// ONDE (WHERE) - se tem o cpf digitado no formulário
// AND (E) - se tem a senha digitada no formulário

$resultado = mysqli_query($conexao, $sql);
//"mysqli_query" executa o comando SQL no banco, procura os dados digitados
//"$resultado" não é o dado ainda, é uma "lista de resultados"

if (mysqli_num_rows($resultado) > 0)
{
    $usuario = mysqli_fetch_assoc($resultado);
    //pega os dados do usuário retornado do banco e transforma em array

    $_SESSION['cpf'] = $usuario['cpf'];
    $_SESSION['nome'] = $usuario['nome'];
    $_SESSION['tipo'] = $usuario['tipo'];
    //NOVO: guarda os dados do usuário na sessão
    //"$_SESSION['cpf']" vai guardar o CPF para as páginas pgaluno.php e pgprof.php usarem
    //"$_SESSION['nome']" guarda o nome do usuário
    //"$_SESSION['tipo']" guarda o tipo (aluno ou personal)

    $tipo = $usuario['tipo'];
    //esse comando pega o valor da coluna "tipo" do usuário (aluno ou personal)

    if ($tipo == "aluno")
    {
        header("Location: pgaluno.php");
    }
    //se o tipo for igual a aluno, vai para a pagina "pgaluno.php"
    else
    {
        header("Location: prof.php");
    }
    //se for tipo personal, vai para a pagina "pgprof.php"

}
//esse IF, é a resposta do sistema, caso encontrar o login e senha no banco, redireciona para a página correta
else
{
    header("Location: logintela.php?erro=Usuário não encontrado");
    exit;
}
//o ELSE é a resposta do sistema caso não encontrar o login e senha

//esse abaixo indica que está terminando o código PHP
?>