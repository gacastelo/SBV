<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caixa de Sugestões - Jornal Estudantil IFSP SBV</title>
    <link rel="stylesheet" href="assets/css/modal.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Jornal Estudantil IFSP São João da Boa Vista</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Início</a></li>
                    <li><a href="todas-noticias.php">Notícias</a></li>
                    <li><a href="videos.php">Vídeos</a></li>
                    <li><a href="sobre.php">Sobre</a></li>
                    <li><a href="sugestoes.php">Sugestões</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <section class="container">
        <h2>Caixa de Sugestões</h2>
        <p>Quer compartilhar suas ideias ou dar sugestões para melhorar o nosso jornal? Preencha o formulário abaixo e nos envie sua sugestão!</p>
        <form action="mailto:jornalfederal.ifsp.sbv@gmail.com" method="POST" enctype="text/plain" target="_blank">
            <label for="nome">Nome:</label><br>
            <input type="text" id="nome" name="Nome" required><br><br>

            <label for="email">E-mail:</label><br>
            <input type="email" id="email" name="Email" required><br><br>

            <label for="sugestao">Sugestão:</label><br>
            <textarea id="sugestao" name="Sugestao" rows="4" required></textarea><br><br>

            <button type="submit">Enviar Sugestão</button>
        </form>
    </section>
        </form>
    </section>
    <footer>
        <div class="container">
            <p>&copy; 2024 Jornal Estudantil IFSP São João da Boa Vista. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="scripts.js"></script>
</body>
</html>
