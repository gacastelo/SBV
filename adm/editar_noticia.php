<?php
session_start();

// Verifica se o usuário está logado e se tem poderes administrativos
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true || !isset($_SESSION['poderes']) || $_SESSION['poderes'] != 1) {
    header("Location: ../login.php");
    exit();
}


if (isset($_SESSION['msg'])) {
    echo "<span>" . $_SESSION['msg'] . "</span>";
    unset($_SESSION['msg']);
}


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_jornal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}


if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID não especificado.");
}

$id = intval($_GET['id']);


$sql = "SELECT titulo, `desc`, img, data_cad, conteudo, midia, video_duration FROM tb_jornal WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$noticia = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novotitulo = $_POST['titulo'];
    $novadesc = $_POST['desc'];
    $novamidia = $_POST['midia'];
    $novaduration = $_POST['video_duration'];
    $conteudo = $_POST['conteudo'];
    $sql = "UPDATE tb_jornal SET titulo = ?, `desc` = ?, midia = ?, video_duration = ?, conteudo = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $novotitulo, $novadesc, $novamidia, $novaduration, $conteudo, $id);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "Notícia atualizada com sucesso!";
        header("Location: editar_noticia.php?id=" . $id . "&success=true");
    } else {
        echo "<span>Erro ao atualizar a notícia ";
    }
}

if (isset($_GET['deletar_img']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT img FROM tb_jornal WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $getnoticia = $result->fetch_assoc();

    if ($getnoticia && !empty($noticia['img'])) {
        $imgPath = '../' . $noticia['img'];

        $sql = "UPDATE tb_jornal SET img = '' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "<span>Imagem removida!</span>";
            header("Location: editar_noticia.php?id=" . $id . "&success=true");
        } else {
            echo "<span>Erro: Imagem não pôde ser removida!</span>";
        }

        if (file_exists($imgPath)) {
            unlink($imgPath);
            echo "Imagem removida do servidor.";
        } else {
            echo "Erro ao remover o link da imagem!";
        }
    }
}

if (isset($_FILES['img']) && $_FILES['img']['error'] == UPLOAD_ERR_OK) {
    $target_dir = "../uploads/";
    $imageFileType = strtolower(pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . uniqid() . "." . $imageFileType;

    $check = getimagesize($_FILES["img"]["tmp_name"]);
    if ($check === false) {
        echo "O arquivo enviado não é uma imagem.";
        exit();
    }

    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        echo "Somente arquivos JPG, JPEG, PNG e GIF são permitidos.";
        exit();
    }

    if ($_FILES['img']['error'] !== UPLOAD_ERR_OK) {
        die("Erro ao enviar o arquivo. Código do erro: " . $_FILES['img']['error']);
    }

    $sql = "SELECT img FROM tb_jornal WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $old_image_path = $row['img'];

        if (file_exists($old_image_path)) {
            unlink($old_image_path);
        }
    }
    if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
        $sql = "UPDATE tb_jornal SET img = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $target_file, $id);

        if ($stmt->execute()) {
            echo "Imagem adicionada e notícia atualizada com sucesso!";
        } else {
            echo "Erro ao atualizar a imagem no banco de dados: " . $stmt->error;
        }
    } else {
        echo "Erro ao fazer o upload da imagem.";
    }
}

if (empty($noticia['img']) && !empty($noticia['midia'])) {
    $placeholder = 'assets/img/placeholder.jpg';
    $sql = "UPDATE tb_jornal SET img = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $placeholder, $id);
    if ($stmt->execute()) {
        echo "Notícia com vídeo sem thumbnail! Thumbnail padrão acoplada.";
    } else {
        echo "Falha ao definir imagem padrão" . $stmt->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Notícias</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/icon.js"></script>
</head>

<body>
    <header>
        <div class="container">
            <h1>Área Restrita - Jornal Federal</h1>
            <nav>
                <ul>
                    <li><a href="../index.php">Site Principal</a></li>
                    <li><a href="adicionar_noticia.php">Adicionar Notícias</a></li>
                    <li><a href="admin_sugestoes.php">Ver Sugestões</a></li>
                    <li><a href="admin_eventos.php">Editar Eventos</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="container">
        <h2 class="tit">Editar Notícia</h2>
        <?php if (isset($mensagem)): ?>
            <p class="center"><?php echo $mensagem; ?></p>
        <?php endif; ?>
        <form class="forms" method="POST" action="editar_noticia.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" value="<?php echo $noticia['titulo']; ?>">
            <label for="desc">Descrição</label>
            <input type="text" name="desc" value="<?php echo $noticia['desc']; ?>">
            <?php
            if (!empty($noticia['img'])) {
                echo '<div style="display: flex; align-items: center; justify-content: center">
                        <img src="../' . $noticia['img'] . '" alt="fotoatual" height="200">
                        <a href="editar_noticia.php?id=' . $id . '&deletar_img=true"
                        onclick="return confirm(\'Tem certeza que deseja deletar esta imagem da notícia?\');">
                            <i class="fa-solid fa-trash" style="margin-left: 5px"></i>
                        </a>
                      </div>';
                echo "<br>";
            } else {
                echo '<span>A imagem não tem foto!</span><br>';
            }
            ?>
            <label for="img">Imagem:</label>
            <input type="file" name="img" accept="image/*">
            <label for="midia">Mídia (URL ou caminho):</label><br>
            <input type="text" name="midia" value="<?php echo $noticia['midia']; ?>"><br>
            <label for="video_duration">Duração do Vídeo:</label>
            <input type="text" name="video_duration" placeholder="Ex: 3 min"><br><br>
            <label for="conteudo">Conteúdo:</label>
            <textarea name="conteudo" rows="5" cols="30"></textarea><br>
            <button type="submit">Salvar Alterações</button>
        </form>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2024 Jornal Estudantil IFSP São João da Boa Vista. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>

</html>