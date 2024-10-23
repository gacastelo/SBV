<?php
session_start();

// Verifica se o usuário está logado e se tem poderes administrativos
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true || !isset($_SESSION['poderes']) || $_SESSION['poderes'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
include 'conexao.php';

// Verifica se o ID do usuário foi passado via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepara a query para excluir o usuário
    try {
        $sql = "DELETE FROM usuarios WHERE id = :id AND poderes = 1"; // Somente exclui usuários administrativos
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Executa a query
        if ($stmt->execute()) {
            header("Location: ../adm/gerenciamento.php"); // Redireciona de volta para a lista de usuários
            exit();
        } else {
            echo "Erro ao tentar excluir o usuário.";
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
} else {
    echo "ID de usuário inválido.";
}
?>
