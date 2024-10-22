<?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    $_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit();
}

require_once("../backend/conexao.php");

$mensagem = "";

try {
    // Verificar se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $titulo = $_POST['titulo'];
        $target_dir = "../uploads/pdf/";

        // Substituir espaços por underscores e garantir que seja um arquivo PDF
        $fileName = str_replace(' ', '_', $titulo) . ".pdf";
        $target_file = $target_dir . $fileName;

        // Verificar se o arquivo foi enviado e é um PDF
        $fileType = strtolower(pathinfo($_FILES["arquivo_pdf"]["name"], PATHINFO_EXTENSION));

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        if ($fileType === "pdf") {
            // Limitar o tamanho para 5MB
            if ($_FILES["arquivo_pdf"]["size"] <= 5000000) {
                // Mover o arquivo para o diretório de uploads com o nome dado
                if (move_uploaded_file($_FILES["arquivo_pdf"]["tmp_name"], $target_file)) {
                    // Inserir informações no banco de dados
                    $sql = "INSERT INTO tb_jornal_pdf (titulo, nome_arquivo) VALUES (:titulo, :nome_arquivo)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':titulo', $titulo);
                    $stmt->bindParam(':nome_arquivo', $target_file);
                    $stmt->execute();

                    // Exibir mensagem de sucesso e redirecionar
                    $mensagem = "PDF adicionado com sucesso!";
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    $mensagem = "Erro ao fazer o upload do PDF.";
                }
            } else {
                $mensagem = "O arquivo é muito grande. O limite é de 5MB.";
            }
        } else {
            $mensagem = "Apenas arquivos PDF são permitidos.";
        }
    }

    // Selecionar os PDFs já cadastrados
    $sql_select = "SELECT * FROM tb_jornal_pdf ORDER BY id DESC";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->execute();
    $jornal_pdfs = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Upload de Jornal</title>
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
                    <li><a href="adicionar_jornal.php" class="active">Jornal</a></li>
                    <li><a href="gerenciamento.php">Gerenciamento</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="container">
        <h2 class="tit">Upload de Jornal (PDF)</h2>

        <?php if (!empty($mensagem)): ?>
            <p class="falha"><?php echo $mensagem; ?></p>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data" class="forms">
            <label for="titulo">Título do Jornal:</label>
            <input type="text" name="titulo" id="titulo" required><br>

            <label for="arquivo_pdf">Selecione o PDF:</label>
            <input type="file" name="arquivo_pdf" id="arquivo_pdf" accept="application/pdf" required><br>

            <button type="submit">Enviar Jornal</button>
        </form>
    </section>

    <section class="container">
        <h2>Jornais Enviados</h2>
        <?php if (!empty($jornal_pdfs)): ?>
            <ul>
                <?php foreach ($jornal_pdfs as $jornal): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($jornal['titulo']); ?></strong><br>
                        <a href="<?php echo $jornal['nome_arquivo']; ?>" download>Baixar PDF</a><br><br>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nenhum jornal enviado.</p>
        <?php endif; ?>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2024 Jornal Estudantil IFSP São João da Boa Vista. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>

</html>