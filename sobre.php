<?php
session_start();

try {
    include "backend/conexao.php";
} catch (PDOException $err) {
    echo "Erro: " . $err->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre - Jornal Estudantil IFSP SBV</title>
    <link rel="stylesheet" href="assets/css/style.css">
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
                    <li><a href="sobre.php" class="active">Sobre</a></li>
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
                            echo "<li><a href='./backend/logout.php'>Logout</a></li>";
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
    <section id="sobre" class="container">
        <h2>Sobre o Jornal Estudantil IFSP SBV</h2>
        <p>O <strong>Jornal Estudantil do IFSP São João da Boa Vista</strong> é uma iniciativa dos estudantes com o objetivo de promover e divulgar notícias, eventos e acontecimentos relevantes no campus. O jornal é gerido por alunos voluntários, que se dedicam a cobrir os principais fatos e a manter a comunidade acadêmica informada.</p>

        <h3 class="sobretitle">Missão</h3>
        <p>Nosso objetivo é fornecer informações atualizadas e relevantes para a comunidade acadêmica, além de promover o engajamento dos estudantes em projetos de comunicação e jornalismo dentro do ambiente escolar.</p>

        <h3 class="sobretitle">Visão</h3>
        <p>Acreditamos que a informação é uma ferramenta essencial para a formação de cidadãos críticos e engajados. Nosso jornal visa ser uma referência em comunicação estudantil, incentivando o protagonismo dos alunos e a troca de ideias.</p>

        <h3 class="sobretitle">Equipe</h3>
        <ul>
            <li><strong>Chefe de Redação:</strong> Thais</li>
            <li><strong>Repórteres:</strong> Luca</li>
            <li><strong>Editores:</strong> Victor</li>
            <li><strong>Diretor de imagens:</strong> Rafa</li>
            <li><strong>Colunistas:</strong> Sarah</li>
            <li><strong>Webmaster:</strong> Gabriel C.</li>
        </ul>

        <h3 class="sobretitle">Contato</h3>
        <p>Para colaborações ou dúvidas, entre em contato conosco pelo e-mail: <a id="email" href="mailto:jornalfederal.ifsp.sbv@gmail.com">jornalfederal.ifsp.sbv@gmail.com</a></p><br><br>
        <div class="center"><a href="index.html" class="button">Voltar para Início</a></div>
    </section>
    <footer>
        <div class="container">
            <p>&copy; 2024 Jornal Estudantil IFSP São João da Boa Vista. Todos os direitos reservados.</p>
        </div>
    </footer>
    <script src="assets/js/scroll.js"></script>
</body>

</html>