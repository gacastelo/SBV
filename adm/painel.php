<!-- Painel de Administrador -->

<?php
session_start();

// Verifica se o usuário está logado e se tem poderes administrativos
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true || !isset($_SESSION['poderes']) || $_SESSION['poderes'] != 1) {
    header("Location: ../login.php");
    exit();
}

require_once("../backend/conexao.php");

// Definir o número de notícias por página
$noticiasPorPagina = 10;

// Pega a página atual da URL, se não existir, define como 1
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaAtual - 1) * $noticiasPorPagina;

// Notícia sem mídia (vídeo)
$sql = "SELECT * FROM tb_jornal WHERE midia IS NULL ORDER BY id DESC LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':limit', $noticiasPorPagina, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$noticiasSemVideos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Notícia com mídia (vídeo)
$sql = "SELECT * FROM tb_jornal WHERE midia IS NOT NULL ORDER BY id DESC LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':limit', $noticiasPorPagina, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$noticiasComVideos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica o total de notícias para calcular o número de páginas
$sqlCount = "SELECT COUNT(*) FROM tb_jornal WHERE midia IS NULL";
$totalNoticiasSemVideos = $conn->query($sqlCount)->fetchColumn();
$totalPaginasSemVideos = ceil($totalNoticiasSemVideos / $noticiasPorPagina);

$sqlCount = "SELECT COUNT(*) FROM tb_jornal WHERE midia IS NOT NULL";
$totalNoticiasComVideos = $conn->query($sqlCount)->fetchColumn();
$totalPaginasComVideos = ceil($totalNoticiasComVideos / $noticiasPorPagina);

$mensagem = "";

if (isset($_GET['id'])) { // Deletar notícia individual
    $id = intval($_GET['id']); // Garante que o ID seja um número inteiro
    $sql = "DELETE FROM tb_jornal WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$id])) {
        $mensagem = "Notícia deletada com sucesso!";
    } else {
        $mensagem = "Erro ao deletar notícia: " . $conn->errorInfo()[2];
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jornal Estudantil IFSP SBV</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/icon.js"></script>
    <script src="../assets/js/modal.js" defer></script> <!-- Incluindo o arquivo JS -->
</head>

<body>
    <header id="header">
        <div class="container">
            <img src="../assets/img/logojornal.png" alt="" height="80px">
            <nav>
                <ul>
                    <li><a href="../index.php">Visualizar</a></li>
                    <li><a href="painel.php" class="active">Notícias</a></li>
                    <li><a href="admin_eventos.php">Eventos</a></li>
                    <li><a href="admin_sugestoes.php">Sugestões</a></li>
                    <li><a href="adicionar_jornal.php">Jornal</a></li>
                    <li><a href="gerenciamento.php">Gerenciamento</a></li>
                    <li><a href='../backend/logout.php'>Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section id="noticias" class="container">
        <?php if ($mensagem): ?>
            <p><?php echo $mensagem; ?></p>
        <?php endif; ?>

        <div style="display: flex; align-items: center; justify-content: space-between">
            <h2 class="tit">Todas as Notícias</h2>
            <a href="adicionar_noticia.php"><button style="height: max-content; padding: 10px">Adicionar Notícia</button></a>
            <a href="deletar_noticia.php"><button style="height: max-content; padding: 10px">Deletar em massa</button></a>
        </div>

        <!-- Exibição de notícias sem vídeos -->
        <?php foreach ($noticiasSemVideos as $item): ?>
            <div class="noticia">
                <div class="noticia_adm">
                    <a href="../noticias.php?id=<?php echo $item['id']; ?>" class="">
                        <h2><?php echo htmlspecialchars($item['titulo']); ?></h2>
                    </a>
                    <a href="editar_noticia.php?id=<?php echo $item['id']; ?>"><i class="fa-solid fa-pen-to-square"></i></a> <!-- Editar -->
                    <a href="painel.php?id=<?php echo $item['id']; ?>" onclick="return confirm('Tem certeza que deseja deletar esta notícia?');">
                        <i class="fa-solid fa-trash"></i>
                    </a> <!-- Excluir -->
                </div>
                <p><?php echo htmlspecialchars($item['desc']); ?></p>
                <p class="data"><?php echo date('d/m/Y', strtotime($item['data_cad'])); ?></p>
            </div>
        <?php endforeach; ?>

        <!-- Navegação de páginas para notícias sem vídeos -->
        <div class="navegacao">
            <?php if ($paginaAtual > 1): ?>
                <a href="?pagina=<?php echo $paginaAtual - 1; ?>">Anterior</a>
            <?php endif; ?>

            <?php if ($paginaAtual < $totalPaginasSemVideos): ?>
                <a href="?pagina=<?php echo $paginaAtual + 1; ?>">Próxima</a>
            <?php endif; ?>
        </div>

        <h2 class="tit">Notícias com Vídeos</h2>
        <!-- Exibição de notícias com vídeos -->
        <?php foreach ($noticiasComVideos as $item): ?>
            <div class="noticia" id="video">
                <div class="news-video">
                    <!-- Miniatura do vídeo -->
                    <img src="../<?php echo $item['img'] ?: 'assets/img/placeholder.jpg'; ?>" alt="Imagem do vídeo" class="thumbnail"
                        onclick="abrirModal('<?php echo $item['midia']; ?>',
                                                '<?php echo htmlspecialchars($item['titulo']); ?>',
                                                `<?php echo htmlspecialchars($item['conteudo']); ?>`)">
                </div>
                <div class="news-content">
                    <a href="../noticias.php?id=<?php echo $item['id']; ?>" class="">
                        <h2><?php echo htmlspecialchars($item['titulo']); ?></h2>
                    </a>
                    <p><?php echo htmlspecialchars($item['desc']); ?></p>
                    <p class="data"><?php echo date('d/m/Y', strtotime($item['data_cad'])); ?></p>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Navegação de páginas para notícias com vídeos -->
        <div class="navegacao">
            <?php if ($paginaAtual > 1): ?>
                <a href="?pagina=<?php echo $paginaAtual - 1; ?>">Anterior</a>
            <?php endif; ?>

            <?php if ($paginaAtual < $totalPaginasComVideos): ?>
                <a href="?pagina=<?php echo $paginaAtual + 1; ?>">Próxima</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Overlay para escurecer a página de fundo -->
    <div id="overlay" class="overlay"></div>

    <!-- Modal para exibição do vídeo -->
    <div id="videoModal" class="modal">
        <div class="modal-content">
            <h2 class="news-video-title" id="modalTitulo"></h2>
            <div class="video-container"><iframe id="videoFrame" src="" frameborder="0" allowfullscreen></iframe></div>
            <p id="modalConteudo"></p> <!-- Parágrafo para o conteúdo -->
            <p class="close" id="closeModal">Sair</p>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Jornal Estudantil IFSP São João da Boa Vista. Todos os direitos reservados.</p>
    </footer>

    <script src="../assets/js/scroll.js"></script>
    <script src="../assets/js/delete_news.js"></script>
</body>

</html>