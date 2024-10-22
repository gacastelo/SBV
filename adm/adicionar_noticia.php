<?php
session_start();

// Verifica se o usuário está logado e se tem poderes administrativos
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true || !isset($_SESSION['poderes']) || $_SESSION['poderes'] != 1) {
    header("Location: ../login.php");
    exit();
}


// Conexão com o banco de dados reutilizando conexao.php
require_once("../backend/conexao.php");

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'] ?? NULL;
    $desc = $_POST['desc'] ?? NULL;
    $midia = !empty($_POST['midia']) ? $_POST['midia'] : NULL;
    $video_duration = !empty($_POST['video_duration']) ? $_POST['video_duration'] : NULL;
    $conteudo = !empty($_POST['conteudo']) ? $_POST['conteudo'] : NULL;
    $data_cad = $_POST['data_cad'] ?? NULL;

    // Diretório de upload
    $target_dir = "./uploads/img/";
    $imageFileType = strtolower(pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . uniqid() . "." . $imageFileType;

    // Verifica se o diretório existe
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Verifica o upload da imagem
    if (isset($_FILES['img']) && $_FILES['img']['error'] == UPLOAD_ERR_OK) {
        $check = getimagesize($_FILES["img"]["tmp_name"]);
        if ($check !== false) {
            if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
                    // Inserir os dados da notícia no banco de dados
                    $sql = "INSERT INTO tb_jornal (titulo, `desc`, img, data_cad, midia, video_duration, conteudo) 
                            VALUES (:titulo, :desc, :img, :data_cad, :midia, :video_duration, :conteudo)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':titulo', $titulo);
                    $stmt->bindParam(':desc', $desc);
                    $stmt->bindParam(':img', $target_file); // Caminho da imagem no servidor
                    $stmt->bindParam(':data_cad', $data_cad);
                    $stmt->bindParam(':midia', $midia);
                    $stmt->bindParam(':video_duration', $video_duration);
                    $stmt->bindParam(':conteudo', $conteudo);

                    if ($stmt->execute()) {
                        $mensagem = "Notícia adicionada com sucesso!";
                    } else {
                        $mensagem = "Erro ao adicionar a notícia no banco de dados.";
                    }
                } else {
                    $mensagem = "Erro ao fazer o upload da imagem.";
                }
            } else {
                $mensagem = "Somente arquivos JPG, JPEG, PNG e GIF são permitidos.";
            }
        } else {
            $mensagem = "O arquivo enviado não é uma imagem.";
        }
    } else {
        // Se não há imagem, inserir os dados sem a imagem
        $sql = "INSERT INTO tb_jornal (titulo, `desc`, data_cad, midia, video_duration, conteudo) 
                VALUES (:titulo, :desc, :data_cad, :midia, :video_duration, :conteudo)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':desc', $desc);
        $stmt->bindParam(':data_cad', $data_cad);
        $stmt->bindParam(':midia', $midia);
        $stmt->bindParam(':video_duration', $video_duration);
        $stmt->bindParam(':conteudo', $conteudo);

        if ($stmt->execute()) {
            $mensagem = "Notícia adicionada com sucesso!";
        } else {
            $mensagem = "Erro ao adicionar a notícia no banco de dados.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Notícia</title>
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
                    <li><a href="gerenciamento.php">Gerenciamento</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2 class="tit">Adicionar Notícia</h2>

        <?php if (!empty($mensagem)): ?>
            <h2 style="text-align: center;"><?php echo $mensagem; ?></h2>
        <?php endif; ?>

        <form class="forms" method="POST" action="adicionar_noticia.php" enctype="multipart/form-data">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" required><br>

            <label for="desc">Descrição:</label>
            <input type="text" name="desc" required><br>

            <label for="data_cad">Data:</label>
            <input type="date" name="data_cad" required><br>

            <label for="img">Imagem (upload):</label>
            <input type="file" name="img" accept="image/*"><br>

            <label for="midia">Mídia (URL ou caminho):</label>
            <input type="text" name="midia"><br>

            <label for="video_duration">Duração do Vídeo:</label>
            <input type="text" name="video_duration" placeholder="Ex: 3 min"><br>

            <label for="conteudo">Conteúdo:</label>
            <textarea name="conteudo" rows="5" cols="30" required></textarea><br>

            <button type="submit">Adicionar Notícia</button>
        </form>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2024 Jornal Estudantil IFSP São João da Boa Vista. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>

</html>