<?php
session_start();

try {
    include "backend/conexao.php";



    // Selecionar as últimas 3 notícias sem vídeo
    $sql = "SELECT * FROM tb_jornal WHERE midia IS NULL ORDER BY id DESC LIMIT 3";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $menu = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Selecionar uma notícia com vídeo
    $sqlVideo = "SELECT * FROM tb_jornal WHERE midia IS NOT NULL ORDER BY id DESC LIMIT 1";
    $stmtVideo = $conn->prepare($sqlVideo);
    $stmtVideo->execute();
    $noticiaComVideo = $stmtVideo->fetch(PDO::FETCH_ASSOC);

    // Selecionar eventos
    $sqlEventos = "SELECT * FROM tb_eventos ORDER BY data_evento ASC";
    $stmtEventos = $conn->prepare($sqlEventos);
    $stmtEventos->execute();
    $eventos = $stmtEventos->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $err) {
    echo "Erro: " . $err->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jornal Estudantil IFSP SBV</title>
    <link rel="stylesheet" href="assets/css/pre-style.css">
    <script src="assets/js/modal.js" defer></script> <!-- Incluindo o arquivo JS -->
</head>

<body>
    <header id="header">
        <div class="container">
            <img src="assets/img/logojornal.png" alt="" height="80px">
            <nav>
                <ul>
                    <li><a href="index.php" class="active">Início</a></li> <!-- Class Active indica com bold a página atual -->
                    <li><a href="todas-noticias.php">Notícias</a></li>
                    <li><a href="videos.php">Vídeos</a></li>
                    <li><a href="sobre.php">Sobre</a></li>
                    <li><a href="sugestoes.php">Sugestões</a></li>
                    <li><a href="jornal.php">PDF's</a></li>
                    <?php
                    if (isset($_SESSION['adm_logado']))
                        if ($_SESSION['adm_logado'] == true) {
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

    <section id="noticias" class="container">
        <h2>Últimas Notícias</h2>
        <?php foreach ($menu as $item): ?>
            <div class="noticia">
                <a href="noticias.php?id=<?php echo $item['id']; ?>" class="">
                    <h2><?php echo htmlspecialchars($item['titulo']); ?></h2>
                </a>
                <p><?php echo htmlspecialchars($item['desc']); ?></p>
                <p class="data"><?php echo date('d/m/Y', strtotime($item['data_cad'])); ?></p>
            </div>
        <?php endforeach; ?>
        <?php if ($noticiaComVideo): ?>
            <div class="noticia" id="video">
                <div class="news-video">
                    <img src="<?php echo $noticiaComVideo['img'] ?: 'assets/img/placeholder.png'; ?>"
                        alt="Imagem do vídeo" class="thumbnail"
                        onclick="abrirModal('<?php echo $noticiaComVideo['midia']; ?>', '<?php echo htmlspecialchars($noticiaComVideo['titulo']); ?>', `<?php echo htmlspecialchars($noticiaComVideo['conteudo']); ?>`)"
                        onerror="this.src='assets/img/jorge.png';">
                    <span class="video-duration"><?php echo htmlspecialchars($noticiaComVideo['video_duration']); ?></span>
                </div>
                <div class="news-content">
                    <h2><?php echo htmlspecialchars($noticiaComVideo['titulo']); ?></h2>
                    <p><?php echo htmlspecialchars($noticiaComVideo['desc']); ?></p>
                    <p class="data"><?php echo date('d/m/Y', strtotime($noticiaComVideo['data_cad'])); ?></p>
                </div>
            </div>
        <?php else: ?>
            <p>Nenhuma notícia com vídeo disponível no momento.</p>
        <?php endif; ?>

        <!-- Modal para exibição do vídeo -->
        <div id="videoModal" class="modal">
            <div class="modal-content">
                <div class="closex" id="closeModal">x</div>
                <h2 class="news-video-title" id="modalTitulo"></h2>
                <div class="video-container"><iframe id="videoFrame" src="" frameborder="0" allowfullscreen></iframe></div>
                <p id="modalConteudo"></p> <!-- Parágrafo para o conteúdo -->
                <p class="close" id="closeModal">Sair</p>
            </div>
        </div>
    </section>

    <section id="eventos" class="container">
        <h2>Próximos Eventos</h2>
        <?php if (!empty($eventos)): ?>
            <ul>
                <?php foreach ($eventos as $evento): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($evento['evento']); ?></strong><br>
                        <?php echo date('d/m/Y', strtotime($evento['data_evento'])); ?><br>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nenhum evento cadastrado.</p>
        <?php endif; ?>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2024 Jornal Estudantil IFSP São João da Boa Vista. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="assets/js/scroll.js"></script>
</body>

</html>