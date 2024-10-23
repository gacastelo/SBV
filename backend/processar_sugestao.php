<?php
session_start();

// Verificar token CSRF no envio
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Falha na verificação do token CSRF");
    }

    // Conectar ao banco de dados
    try {
        include "conexao.php";
    } catch (PDOException $err) {
        die("Erro: " . $err->getMessage());
    }

    // Obter os dados do formulário
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : 'Anônimo'; // Usar 'Anônimo' se o campo nome estiver vazio
    $sugestao = trim($_POST['sugestao']);

    // Preparar e executar a inserção
    try {
        $stmt = $pdo->prepare("INSERT INTO tb_sugestoes (nome, sugestao, data_envio) VALUES (:nome, :sugestao, NOW())");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':sugestao', $sugestao);
        $stmt->execute();

        // Redirecionar ou exibir mensagem de sucesso
        header("Location: ../sugestoes.php?status=success");
        exit;
    } catch (PDOException $err) {
        die("Erro ao inserir sugestão: " . $err->getMessage());
    }
} else {
    die("Método não permitido.");
}
?>
