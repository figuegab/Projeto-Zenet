<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] != 'personal') {
    header("Location: logintela.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZENIT - Menu do Professor</title>
    <link rel="stylesheet" href="css/prof.css">
   
</head>
<body>
            
            <div class = "logo">
                <img src="../img/logo1.png" alt="">
            </div>

            <h1>Sistema de Gerenciamento de Treinos</h1>

            <div class = "superior">
            <h2>

             <p>Olá, <?php echo htmlspecialchars($_SESSION['nome']); ?>!</p>
            
            </h2>
            </div>

    <div class="caixa">
             



        <div class="opcao">
            <button> <!-- Botão CADASTRO -->
                <a href="Cria_prof.php" >
                    <h3>CADASTRAR TREINO</h3>
                </a>
            </button>
            
            <button> <!-- Botão EXCLUSÃO -->
                <a href="Deleta_Prof.php">
                    <h3>EXCLUIR TREINO</h3>
                </a>
            </button>

            <button> <!-- Botão EDIÇÃO -->
                <a href="Edita_Prof.php">
                    <h3>EDITAR TREINO</h3>
                </a>
            </button>

            <h2> Selecione uma das opções acima para gerenciar os treinos dos seus alunos</h2>
        </div>
            
        

    </div>

            <button class="sair"><a href="logintela.php"><h3>Sair</h3></a></button> 

</body>

        <?php include_once 'footer.php'; ?>

</html>