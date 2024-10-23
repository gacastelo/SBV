<?php
session_start();

// Verifica se o usuário está logado e se tem poderes administrativos
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true || !isset($_SESSION['poderes']) || $_SESSION['poderes'] != 1) {
    header("Location: ../login.php");
    exit();
}


// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_jornal";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}


// Deletar múltiplas notícias
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ids'])) {
    // Verifica se algum ID foi selecionado
    $ids = $_POST['ids']; // Recupera os IDs das notícias selecionadas

    // Converte os IDs para uma lista separada por vírgula para usar na consulta SQL
    $idList = implode(",", array_map('intval', $ids));

    // Consulta SQL para deletar as notícias selecionadas
    $sql = "DELETE FROM tb_jornal WHERE id IN ($idList)";

    if ($conn->query($sql) === TRUE) {
        $mensagem = "Notícias deletadas com sucesso!";
    } else {
        $mensagem = "Erro ao deletar notícias: " . $conn->error;
    }
}

// Recupera todas as notícias para listar
$sql = "SELECT id, titulo, `desc`, data_cad FROM tb_jornal";
$result = $conn->query($sql);

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletar Notícias</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <header id="header">
        <div class="container">
            <img src="../assets/img/logojornal.png" alt="" height="80px">
            <nav>
                <ul>
                    <li><a href="../index.php">Visualizar</a></li>
                    <li><a href="painel.php">Notícias</a></li>
                    <li><a href="admin_eventos.php">Eventos</a></li>
                    <li><a href="admin_sugestoes.php">Sugestões</a></li>
                    <li><a href="adicionar_jornal.php">Jornal</a></li>
                    <li><a href='../backend/logout.php'>Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <h2 class="tit">Lista de Notícias</h2>

    <?php if (isset($mensagem)): ?>
        <p class="center"><?php echo $mensagem; ?></p>
    <?php endif; ?>

    <form class="forms" method="POST" action="deletar_noticia.php">
        <table>
            <tr>
                <th>Selecionar</th> <!-- Nova coluna para checkbox -->
                <th>ID</th>
                <th>Título</th>
                <th>Descrição</th>
                <th>Data</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><input type="checkbox" name="ids[]" value="<?php echo $row['id']; ?>"></td> <!-- Checkbox para selecionar -->
                        <td><?php echo $row["id"]; ?></td>
                        <td><?php echo $row["titulo"]; ?></td>
                        <td><?php echo $row["desc"]; ?></td>
                        <td><?php echo $row["data_cad"]; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Nenhuma notícia encontrada.</td>
                </tr>
            <?php endif; ?>
        </table>

        <!-- Botão para deletar múltiplas notícias -->
        <button type="submit" onclick="return confirm('Tem certeza que deseja deletar as notícias selecionadas?');">Deletar Selecionadas</button>
    </form>

    <footer>
        <div class="container">
            <p>&copy; 2024 Jornal Estudantil IFSP São João da Boa Vista. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>

</html>