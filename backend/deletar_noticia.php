<?php
require_once("conexao.php");

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Prepare a query para deletar a notícia
    $sql = "DELETE FROM tb_jornal WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$id])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->errorInfo()[2]]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
}
?>