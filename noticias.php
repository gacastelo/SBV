<?php
session_start();

try {
    include "backend/conexao.php";

    $id = $_GET['id'];

    // comando que sera executado no banco de dados
    $sql = "SELECT * FROM tb_jornal WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $menu = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Buscando os primeiros 5 comentários relacionados à notícia
    $sql_comentarios = "SELECT c.comentario, c.data_comentario, u.nome 
                        FROM comentarios c 
                        JOIN usuarios u ON c.id_usuario = u.id 
                        WHERE c.id_noticia = :id_noticia 
                        ORDER BY c.data_comentario DESC 
                        LIMIT 5";
    $stmt_comentarios = $conn->prepare($sql_comentarios);
    $stmt_comentarios->bindParam(':id_noticia', $id);
    $stmt_comentarios->execute();
    $comentarios = $stmt_comentarios->fetchAll(PDO::FETCH_ASSOC);

    // Conta o total de comentários
    $sql_total = "SELECT COUNT(*) FROM comentarios WHERE id_noticia = :id_noticia";
    $stmt_total = $conn->prepare($sql_total);
    $stmt_total->bindParam(':id_noticia', $id);
    $stmt_total->execute();
    $total_comentarios = $stmt_total->fetchColumn();
} catch (PDOException $err) {
    echo "Erro" . $err->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jornal Estudantil IFSP</title>
    <link rel="stylesheet" href="assets/css/pre-style.css">
</head>

<body>
    <header id="header">
        <div class="container">
            <img src="assets/img/logojornal.png" alt="" height="80px">
            <nav>
                <ul>
                    <li><a href="index.php">Início</a></li>
                    <li><a href="todas-noticias.php">Notícias</a></li>
                    <li><a href="videos.php">Vídeos</a></li>
                    <li><a href="sobre.php">Sobre</a></li>
                    <li><a href="sugestoes.php">Sugestões</a></li>
                    <li><a href="jornal.php">PDF's</a></li>
                    <?php
                    if (isset($_SESSION['adm_logado']) && $_SESSION['adm_logado'] == true) {
                        echo "<li><a href=adm/painel.php>Admin</a></li>";
                    }
                    ?>
                    <?php
                    if (isset($_SESSION['logado'])) {
                        if ($_SESSION['logado'] == true) {
                            echo "<li><a href='meu_perfil.php'>Meu Perfil</a></li>";
                            echo "<li><a href='logout.php'>Logout</a></li>";
                        } else {
                            echo "<li><a href='login.php'>Faça seu Login</a></li>";
                        }
                    } else {
                        echo "<li><a href='login.php'>Faça seu Login</a></li>";
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="holder">
            <div class="noticia-container">
                <?php
                foreach ($menu as $item) {
                ?>
                    <div class="noticia-info">
                        <h2 class="news-tit"><?php echo $item['titulo']; ?></h2>
                        <img class="img" src="<?php echo $item['img']; ?>" alt="">
                        <p><?php echo $item['conteudo']; ?></p>
                        <br><br>
                    </div>
                <?php
                };
                ?>
            </div>

            <!-- Formulário de Comentário -->
            <div class="comentario-form">
                <h3>Deixe seu comentário:</h3>
                <?php if (isset($_SESSION['logado']) && $_SESSION['logado'] == true) { ?>
                    <form id="comentario-form" method="POST">
                        <input type="hidden" name="id_noticia" value="<?php echo $id; ?>">
                        <textarea name="comentario" placeholder="Escreva seu comentário..." required></textarea>
                        <input class="button" type="submit" value="Enviar">
                    </form>
                <?php } else { ?>
                    <p>Você precisa <a class="text-login" href="login.php">fazer login</a> para comentar.</p>
                <?php } ?>
            </div>

            <!-- Exibir Comentários -->
            <div class="comentarios-section">
                <h3>Comentários:</h3>
                <div id="comentarios-list">
                    <?php
                    if (count($comentarios) > 0) {
                        foreach ($comentarios as $comentario) {
                            // Formata a data para o formato desejado
                            $data_formatada = date('d/m/Y H:i', strtotime($comentario['data_comentario']));
                            echo "<div class='comentario'>";
                            echo "<strong class='username'>" . htmlspecialchars($comentario['nome']) . " </strong><small>" . $data_formatada . "</small>";
                            echo "<p class='comentario'>" . htmlspecialchars($comentario['comentario']) . "</p>";
                            echo "<hr>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>Seja o primeiro a comentar!</p>";
                    }
                    ?>
                </div>
                <div id="ver-mais-container">
                    <p class="ver-mais" id="ver-mais" data-offset="3" data-id-noticia="<?php echo $id; ?>">Ver mais</p>
                </div>
            </div>
        </div>
        </section>
    </div>

    <footer>
        <p>&copy; 2024 Jornal Estudantil IFSP São João da Boa Vista. Todos os direitos reservados.</p>
    </footer>
    <script src="assets/js/scroll.js"></script>
    <script src="assets/js/comentarios.js"></script>
</body>

</html>