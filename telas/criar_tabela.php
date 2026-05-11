<?php
// Script para criar as tabelas automaticamente
// Execute este arquivo uma vez no navegador para criar as tabelas

$conexao = mysqli_connect("localhost", "root", "", "meu_sistema");

if (!$conexao) {
    die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
}

// Criar tabela usuarios
$sql_usuarios = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(11) NOT NULL UNIQUE,
    dt_nascimento DATE NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo VARCHAR(20) NOT NULL DEFAULT 'aluno'
)";

if (mysqli_query($conexao, $sql_usuarios)) {
    echo "<h1 style='color: green;'>Tabela 'usuarios' criada com sucesso!</h1>";
} else {
    echo "<h1 style='color: red;'>Erro ao criar tabela 'usuarios':</h1>";
    echo "<p>" . mysqli_error($conexao) . "</p>";
}

// Criar tabela treinos
$sql_treinos = "CREATE TABLE IF NOT EXISTS treinos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpf_aluno VARCHAR(11) NOT NULL,
    cpf_professor VARCHAR(11) NOT NULL,
    dia_semana VARCHAR(20) NOT NULL,
    nome_treino VARCHAR(100) NOT NULL,
    descricao_treino TEXT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cpf_aluno) REFERENCES usuarios(cpf) ON DELETE CASCADE,
    FOREIGN KEY (cpf_professor) REFERENCES usuarios(cpf) ON DELETE CASCADE
)";

if (mysqli_query($conexao, $sql_treinos)) {
    echo "<h1 style='color: green;'>Tabela 'treinos' criada com sucesso!</h1>";
    echo "<p>Agora você pode usar o sistema completo.</p>";
    echo "<a href='logintela.php'>Ir para o Login</a>";
} else {
    echo "<h1 style='color: red;'>Erro ao criar tabela 'treinos':</h1>";
    echo "<p>" . mysqli_error($conexao) . "</p>";
}

mysqli_close($conexao);
?>