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

// Buscar aluno
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
            $mensagem = "<p class='green'>Aluno encontrado! Selecione um treino para editar.</p>";
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

// Processar edição do treino
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_treino'])) {
    $id_treino = $_POST['id_treino'];
    $dia_semana = $_POST['dia_semana'];
    $nome_treino = $_POST['nome_treino'];
    $descricao_treino = $_POST['descricao_treino'];

    if (!empty($dia_semana) && !empty($nome_treino) && !empty($descricao_treino)) {
        $sql_update = "UPDATE treinos SET dia_semana = ?, nome_treino = ?, descricao_treino = ? WHERE id = ?";
        $stmt_update = $conexao->prepare($sql_update);
        $stmt_update->bind_param("sssi", $dia_semana, $nome_treino, $descricao_treino, $id_treino);

        if ($stmt_update->execute()) {
            $mensagem = "<p class='green'>Treino atualizado com sucesso!</p>";
            $tipo_mensagem = "sucesso";

            // Recarrega os treinos
            if ($aluno_encontrado) {
                $sql_treinos = "SELECT * FROM treinos WHERE cpf_aluno = ? ORDER BY data_criacao DESC";
                $stmt_treinos = $conexao->prepare($sql_treinos);
                $stmt_treinos->bind_param("s", $aluno_encontrado['cpf']);
                $stmt_treinos->execute();
                $resultado_treinos = $stmt_treinos->get_result();

                $treinos_aluno = [];
                while ($treino = $resultado_treinos->fetch_assoc()) {
                    $treinos_aluno[] = $treino;
                }
                $stmt_treinos->close();
            }
        } else {
            $mensagem = "<p class='red'>Erro ao atualizar treino!</p>";
            $tipo_mensagem = "erro";
        }
        $stmt_update->close();
    } else {
        $mensagem = "<p class='red'>Preencha todos os campos!</p>";
        $tipo_mensagem = "erro";
    }
}

mysqli_close($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZENIT - Editar Treino</title>
    <link rel="stylesheet" href="css/profcria.css">
</head>

<body>

    <div class="logo">
        <img src="../img/logo1.png" alt="">
    </div>

    <div class="cabeca">
        <h1> Editar Treino</h1>
        <p>Busque o aluno e edite os treinos</p>
    </div>

    <div class="caixa">

        <div class="busca">
            <h2> Buscar Aluno</h2>
            <form method="POST" action="">
                <label>CPF do Aluno:</label>
                <input type="text" name="cpf_busca" placeholder="000.000.000-00" required>
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

                            <h3 class="taluno"> Editar Treino</h3>

                            <form method="POST" action="">
                                <input type="hidden" name="id_treino" value="<?php echo $treino['id']; ?>">

                                <label>Dia da Semana:</label>
                                <select name="dia_semana" required>
                                    <option value="Segunda-feira" <?php echo ($treino['dia_semana'] == 'Segunda-feira') ? 'selected' : ''; ?>>Segunda-feira</option>
                                    <option value="Terça-feira" <?php echo ($treino['dia_semana'] == 'Terça-feira') ? 'selected' : ''; ?>>Terça-feira</option>
                                    <option value="Quarta-feira" <?php echo ($treino['dia_semana'] == 'Quarta-feira') ? 'selected' : ''; ?>>Quarta-feira</option>
                                    <option value="Quinta-feira" <?php echo ($treino['dia_semana'] == 'Quinta-feira') ? 'selected' : ''; ?>>Quinta-feira</option>
                                    <option value="Sexta-feira" <?php echo ($treino['dia_semana'] == 'Sexta-feira') ? 'selected' : ''; ?>>Sexta-feira</option>
                                    <option value="Sábado" <?php echo ($treino['dia_semana'] == 'Sábado') ? 'selected' : ''; ?>>Sábado</option>
                                    <option value="Domingo" <?php echo ($treino['dia_semana'] == 'Domingo') ? 'selected' : ''; ?>>Domingo</option>
                                </select>

                                <label>Nome do Treino:</label>
                                <input type="text" name="nome_treino" value="<?php echo htmlspecialchars($treino['nome_treino']); ?>" required>

                                <label>Descrição do Treino:</label>
                                <textarea name="descricao_treino" rows="4" required><?php echo htmlspecialchars($treino['descricao_treino']); ?></textarea>

                                <button type="submit" name="editar_treino" class="bot">Salvar Alterações</button>
                            </form>

                            <p><small>Criado em: <?php echo date('d/m/Y H:i', strtotime($treino['data_criacao'])); ?></small></p>

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
    <button class="voltar"><a href="Cria_prof.php" class="voltar">Cadastrar Treino</a></button>
    <button class="voltar"><a href="Deleta_Prof.php" class="voltar">Excluir Treino</a></button>
    <button class="voltar"><a href="prof.php" class="voltar">Voltar ao Menu</a></button>
    <button class="voltar"><a href="logintela.php" class="voltar">Voltar ao Login</a></button>
</body>

<?php include_once 'footer.php'; ?>

</html>