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
$mostrar_formulario = false;

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
            $mensagem = " <p class='green'>Aluno encontrado! Agora preencha os dados do treino. </p>";
            $tipo_mensagem = "sucesso";
            $mostrar_formulario = true; // Mostra o formulário de cadastro
        } else {
            $mensagem = " <p class='red'>Aluno não encontrado! Verifique o CPF.</p>";
            $tipo_mensagem = "erro";
            $mostrar_formulario = false;
        }
        $stmt->close();
    } else {
        $mensagem = " <p class='red'>Digite o CPF do aluno!</p>";
        $tipo_mensagem = "erro";
        $mostrar_formulario = false;
    }
}

// Processar cadastro do treino
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['salvar_treino'])) {
    $cpf_aluno = $_POST['cpf_aluno'];
    $dia_semana = $_POST['dia_semana'];
    $nome_treino = $_POST['nome_treino'];
    $descricao_treino = $_POST['descricao_treino'];
    $cpf_professor = $_SESSION['cpf'];
    
    if (!empty($cpf_aluno) && !empty($dia_semana) && !empty($nome_treino) && !empty($descricao_treino)) {
        $sql = "INSERT INTO treinos (cpf_aluno, cpf_professor, dia_semana, nome_treino, descricao_treino) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sssss", $cpf_aluno, $cpf_professor, $dia_semana, $nome_treino, $descricao_treino); // string string string string string
        
        if ($stmt->execute()) {
            $mensagem = " <p class='green'>Treino cadastrado com sucesso! </p>";
            $tipo_mensagem = "sucesso";
            // Limpa o formulário
            $mostrar_formulario = false;
            $aluno_encontrado = null;
        } else {
            $mensagem = " Erro ao cadastrar treino: " . $conexao->error;
            $tipo_mensagem = "erro";
        }
        $stmt->close();
    } else {
        $mensagem = " Preencha todos os campos!";
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
    <title>ZENIT - Cadastrar Treino</title>
    <link rel="stylesheet" href="css/profcria.css">
</head>
<body>

<div class = "logo">
    <img src="../img/logo1.png" alt="">
</div>

        <div class= "cabeca">
            <h1> Cadastrar Novo Treino</h1>
            <p>Busque o aluno e cadastre os treinos</p>
        </div>

<div class="caixa">
        <!-- Formulário de Busca do Aluno -->
    <div class="busca">
            <h2> Buscar Aluno</h2>
            <form method="POST" action="">
                <label>CPF do Aluno:</label>

                <input type="text" name="cpf_busca" placeholder="000.000.000-00" required>

                <button type="submit" name="buscar_aluno" class= "bot"> Buscar Aluno</button>      
            </form>



            <?php if ($mensagem != ""): ?>
        <div class="mensagem"
        
            <?php echo $tipo_mensagem; ?> >
                <?php echo $mensagem; ?>
                
                
        </div>
        <?php endif; ?>
 
        

        <!-- Formulário de Cadastro do Treino (aparece só quando aluno é encontrado) -->
        <?php if ($mostrar_formulario && $aluno_encontrado): ?>
            <div class="aluno">
                <h3 class="taluno"> Dados do Aluno </h3>
                <p><strong> Nome:</strong> <?php echo htmlspecialchars($aluno_encontrado['nome']); ?></p>
                <p><strong> CPF:</strong> <?php echo htmlspecialchars($aluno_encontrado['cpf']); ?></p>
                <p><strong> E-mail:</strong> <?php echo htmlspecialchars($aluno_encontrado['email']); ?></p>
            </div>
            
            <div class="cadastro">  
                <h3 class="taluno"> Preencha os dados do Treino</h3>
                <form method="POST" action="">
                    <input type="hidden" name="cpf_aluno" value="<?php echo htmlspecialchars($aluno_encontrado['cpf']); ?>">
                    
                    <div class="espaco">
                        <label> Dia da Semana:</label>
                        <select name="dia_semana" required>
                            <option value="">-- Selecione um dia --</option>
                            <option value="Segunda-feira">Segunda-feira</option>
                            <option value="Terça-feira">Terça-feira</option>
                            <option value="Quarta-feira">Quarta-feira</option>
                            <option value="Quinta-feira">Quinta-feira</option>
                            <option value="Sexta-feira">Sexta-feira</option>
                            <option value="Sábado">Sábado</option>
                            <option value="Domingo">Domingo</option>
                        </select>
                    </div>

                   
                    <div class="espaco">
                        <label> Nome do Treino:</label>
                        <input type="text" name="nome_treino" required placeholder="Ex: Treino A - Peito e Tríceps">
                    </div>
                    
                    <div>
                        <p class="taluno">Descrição dos Exercícios:</p>
                        <textarea name="descricao_treino" rows="5" required 
                                  placeholder="Ex:&#10;Supino reto: 4x12&#10;Crucifixo: 3x15&#10;Tríceps corda: 4x10"></textarea>
                    </div>
                    
                    <div class="acao">
                        <button type="submit" name="salvar_treino" class="bot"> Salvar Treino</button>
                        <button type="reset" class="bot"> Limpar Treino</button>
                    </div>
                </form>
            </div>
            <?php endif; ?>
    </div>
        
        </div>
                      <button class="voltar"><a href="Deleta_Prof.php" classs="voltar">Excluir e Alterar Aluno</a></button>
                      <button class="voltar"><a href="prof.php" classs="voltar">Voltar ao Menu</a></button>
                      <button class="voltar"><a href="logintela.php" classs="voltar">Voltar ao Login</a></button>
    </body>

                    <?php include_once 'footer.php'; ?>

</html>