<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_jornal";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Inserir usuário e senha criptografada
$usuario = 'test'; // Nome do usuário
$senha = password_hash('test123', PASSWORD_DEFAULT); // Criptografa a senha
$poderes = '0';

$sql = "INSERT INTO usuarios (usuario, senha) VALUES ('$usuario', '$senha')";

if ($conn->query($sql) === TRUE) {
    echo "Usuário inserido com sucesso!";
} else {
    echo "Erro ao inserir usuário: " . $conn->error;
}

$conn->close();
?>