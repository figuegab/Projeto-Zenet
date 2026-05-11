<?php
session_start();

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] != 'personal') {
    header("Location: login_professor.php");
    exit;
}

$conexao = mysqli_connect("localhost", "root", "", "meu_sistema");

if (!$conexao) {
    echo "Erro na conexão com o banco de dados";
    exit;
}

$mensagem = "";
$tipo_mensagem = "";
$aluno_encontrado = null;
$treinos_aluno = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buscar_aluno'])) {
    $cpf_busca = trim($_POST['cpf_busca']);
    
    if (!empty($cpf_busca)) {
        $sql = "SELECT id, nome, email, cpf FROM usuarios WHERE cpf = ? AND tipo = 'aluno'";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $cpf_busca);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            $aluno_encontrado = $resultado->fetch_assoc();
            $mensagem = "<p class='green'> Aluno encontrado!</p>";
            $tipo_mensagem = "sucesso";
            
            // Busca os treinos do aluno
            $sql_treinos = "SELECT * FROM treinos WHERE cpf_aluno = ? ORDER BY data_criacao DESC";
            $stmt_treinos = $conexao->prepare($sql_treinos);
            $stmt_treinos->bind_param("s", $aluno_encontrado['cpf']);
            $stmt_treinos->execute();
            $resultado_treinos = $stmt_treinos->get_result();
            
            while ($treino = $resultado_treinos->fetch_assoc()) {
                $treinos_aluno[] = $treino;
            }
            $stmt_treinos->close();
        } else {
            $mensagem = "<p class='red'>Aluno não encontrado! Verifique o CPF.</p>";
            $tipo_mensagem = "erro";
        }
        $stmt->close();
    } else {
        $mensagem = "<p class='red'>Digite o CPF do aluno!</p>";
        $tipo_mensagem = "erro";
    }
}

// Processa exclusão
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['excluir_treino'])) {
    $id_treino = $_POST['id_treino'];
    $cpf_aluno = $_POST['cpf_aluno'];
    
    if (!empty($id_treino)) {
        $sql_delete = "DELETE FROM treinos WHERE id = ?";
        $stmt_delete = $conexao->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id_treino);
        
        if ($stmt_delete->execute()) {
            $mensagem = "<p class='green'>Treino excluído com sucesso!</p>";
            $tipo_mensagem = "sucesso";
            
            // Recarrega a lista
            $sql_treinos = "SELECT * FROM treinos WHERE cpf_aluno = ? ORDER BY data_criacao DESC";
            $stmt_treinos = $conexao->prepare($sql_treinos);
            $stmt_treinos->bind_param("s", $cpf_aluno);
            $stmt_treinos->execute();
            $resultado_treinos = $stmt_treinos->get_result();
            
            $treinos_aluno = [];
            while ($treino = $resultado_treinos->fetch_assoc()) {
                $treinos_aluno[] = $treino;
            }
            $stmt_treinos->close();
            
            // Atualiza aluno
            $sql_aluno = "SELECT id, nome, email, cpf FROM usuarios WHERE cpf = ?";
            $stmt_aluno = $conexao->prepare($sql_aluno);
            $stmt_aluno->bind_param("s", $cpf_aluno);
            $stmt_aluno->execute();
            $aluno_encontrado = $stmt_aluno->get_result()->fetch_assoc();
            $stmt_aluno->close();
        } else {
            $mensagem = "<p class='red'>Erro ao excluir treino!</p>";
            $tipo_mensagem = "erro";
        }
        $stmt_delete->close();
    }
}

mysqli_close($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZENIT - Buscar Aluno para Exclusão</title>
    <link rel="stylesheet" href="css/profdeleta.css">
</head>
<body>
        <div class = "logo">
                <img src="../img/logo1.png" alt="">
        </div>

        <div class="cabeca">
            <h1> Excluir Treino</h1>
            <p>Busque o aluno para gerenciar e excluir treinos</p>
        </div>


    <div class="caixa">
  
        <div class="excluir">
            <h2> Buscar Aluno</h2>
            <form method="POST" action="">
                <label>CPF do Aluno:</label>
                <input type="text" name="cpf_busca" placeholder="000.000.000-00" 
                       value="<?php echo isset($_POST['cpf_busca']) ? htmlspecialchars($_POST['cpf_busca']) : ''; ?>">
                <button type="submit" name="buscar_aluno" class="bot">Buscar Aluno</button>
            </form>
        
        
            <?php if ($mensagem != ""): ?>
                <div class="mensagem <?php echo $tipo_mensagem; ?>">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>
        
            <?php if ($aluno_encontrado): ?>
            <div class="treino">
                    <h3 class="taluno"> Dados do Aluno</h3>
                    <p><strong>Nome:</strong> <?php echo htmlspecialchars($aluno_encontrado['nome']); ?></p>
                    <p><strong>CPF:</strong> <?php echo htmlspecialchars($aluno_encontrado['cpf']); ?></p>
                    <p><strong>E-mail:</strong> <?php echo htmlspecialchars($aluno_encontrado['email']); ?></p>
                    <p>Total de treinos: <strong><?php echo count($treinos_aluno); ?></strong></p>
            
            
                    <?php if (count($treinos_aluno) > 0): ?>
                    <?php foreach ($treinos_aluno as $treino): ?>
                    
                            <h3 class="taluno"> Informações do Treino</h3>

                            <p><?php echo htmlspecialchars($treino['dia_semana']); ?></p>

                            <h3><?php echo htmlspecialchars($treino['nome_treino']); ?></h3>
                        
                        
                            <?php echo nl2br(htmlspecialchars($treino['descricao_treino'])); ?>
                        
                                <br>
                                <br>

                             Criado em: <?php echo date('d/m/Y', strtotime($treino['data_criacao'])); ?>
                        
                                

                            <form method="POST" action="" onsubmit="return confirm(' Tem certeza que deseja excluir este treino?');">
                                <input type="hidden" name="id_treino" value="<?php echo $treino['id']; ?>">
                                <input type="hidden" name="cpf_aluno" value="<?php echo $aluno_encontrado['cpf']; ?>">
                                <button type="submit" name="excluir_treino" class="bot"> Excluir</button>
                            </form>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <div class="red">
                            Nenhum treino cadastrado para este aluno.
                        </div>
                    <?php endif; ?>
            

            <?php endif; ?>
        </div>

            </div>          
    </div>

                    <button class="voltar"><a href="Cria_Prof.php" classs="voltar">Cadastrar Aluno</a></button>       
                    <button class="voltar"><a href="prof.php" classs="voltar">Voltar ao Menu</a></button>
                    <button class="voltar"><a href="logintela.php" classs="voltar">Voltar ao Login</a></button>
</body>

                <?php include_once 'footer.php'; ?>

</html>