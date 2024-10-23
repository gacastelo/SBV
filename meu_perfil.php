<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
include 'backend/conexao.php';

// Inicializa mensagens
$mensagem_sucesso_nome = "";
$mensagem_erro_nome = "";
$mensagem_sucesso_senha = "";
$mensagem_erro_senha = "";

    $data_cadastro = $_SESSION['data_cadastro']; // Obtém a data de cadastro
    $data_formatada = date('d/m/Y', strtotime($data_cadastro)); // Formata a data para o formato desejado

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_SESSION['id_usuario']; // Obtém o ID do usuário da sessão

    // Verifica se o formulário para mudar nome foi enviado
    if (isset($_POST['acao']) && $_POST['acao'] === 'atualizar_nome') {
        $novo_nome = $_POST['nome'];

        // Atualiza o nome do usuário no banco de dados
        try {
            $sql = "UPDATE usuarios SET nome = :nome WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $novo_nome, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Atualiza o nome na sessão
                $_SESSION['nome'] = $novo_nome;
                $mensagem_sucesso_nome = "Nome atualizado com sucesso!";
            } else {
                $mensagem_erro_nome = "Erro ao atualizar o nome.";
            }
        } catch (PDOException $e) {
            $mensagem_erro_nome = "Erro: " . $e->getMessage();
        }
    }

    // Verifica se o formulário para mudar senha foi enviado
    if (isset($_POST['acao']) && $_POST['acao'] === 'atualizar_senha') {
        if (!empty($_POST['senha_antiga']) && !empty($_POST['senha_nova'])) {
            $senha_antiga = $_POST['senha_antiga'];
            $senha_nova = $_POST['senha_nova'];

            // Busca a senha atual do usuário no banco de dados
            $sql = "SELECT senha FROM usuarios WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            $senha_atual = $stmt->fetchColumn();

            // Verifica se a senha antiga está correta
            if (password_verify($senha_antiga, $senha_atual)) {
                // Atualiza a senha no banco de dados
                $senha_nova_hash = password_hash($senha_nova, PASSWORD_DEFAULT);
                $sql = "UPDATE usuarios SET senha = :senha WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':senha', $senha_nova_hash, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
                $stmt->execute();

                $mensagem_sucesso_senha = "Senha atualizada com sucesso!";
            } else {
                $mensagem_erro_senha = "Senha antiga incorreta!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Editar Perfil</title>
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
                    <li><a href="meu_perfil.php" class="active">Meu Perfil</a></li>
                    <?php
                    if (isset($_SESSION['adm_logado']) && $_SESSION['adm_logado'] == true) {
                        echo "<li><a href='adm/painel.php'>Admin</a></li>";
                    }
                    ?>
                    <?php
                    if (isset($_SESSION['logado']) && $_SESSION['logado'] == true) {
                        echo "<li><a href='./backend/logout.php'>Logout</a></li>";
                    } else {
                        echo "<li><a href='login.php'>Faça seu Login</a></li>";
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </header>

    <section class="bloco">
        <div class="info-usuario">
            <h2 class="tit-info">Informações do Usuário</h2>
            <p><strong>Usuário:</strong> <?php echo htmlspecialchars($_SESSION['usuario']); ?></p>
            <p><strong>Nome:</strong> <?php echo htmlspecialchars($_SESSION['nome']); ?></p>
            <p><strong>Data de Cadastro:</strong> <?php echo $data_formatada; ?></p>
        </div>

        <div class="mudar">
            <div class="mudar-nome">
                <h2 class="center">Mudar Nome de Exibição</h2>
                <?php if ($mensagem_sucesso_nome): ?>
                    <p class="sucesso"><?php echo $mensagem_sucesso_nome; ?></p>
                <?php elseif ($mensagem_erro_nome): ?>
                    <p class="falha"><?php echo $mensagem_erro_nome; ?></p>
                <?php endif; ?>
                <form action="meu_perfil.php" method="POST">
                    <input type="hidden" name="acao" value="atualizar_nome">
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($_SESSION['nome']); ?>" required class="input-perfil">
                    <br><br>
                    <input type="submit" value="Atualizar Nome">
                </form>
            </div>
            <br>
            <div class="mudar-senha">
                <h2 class="center">Mudar Senha</h2>
                <?php if ($mensagem_sucesso_senha): ?>
                    <p class="sucesso"><?php echo $mensagem_sucesso_senha; ?></p>
                <?php elseif ($mensagem_erro_senha): ?>
                    <p class="falha"><?php echo $mensagem_erro_senha; ?></p>
                <?php endif; ?>
                <form action="meu_perfil.php" method="POST">
                    <input type="hidden" name="acao" value="atualizar_senha">
                    <label for="senha_antiga">Senha Antiga:</label>
                    <div class="input-container">
                        <input type="password" name="senha_antiga" id="senha_antiga" placeholder="Digite sua senha antiga" class="input-perfil" required>
                        <span class="toggle-password" onclick="togglePassword('senha_antiga', this)">👁️</span>
                    </div>
                    <br><br>
                    <label for="senha_nova">Nova Senha:</label>
                    <div class="input-container">
                        <input type="password" name="senha_nova" id="senha_nova" placeholder="Digite sua nova senha" class="input-perfil" required>
                        <span class="toggle-password" onclick="togglePassword('senha_nova', this)">👁️</span>
                    </div>

                    <br><br>
                    <input type="submit" value="Atualizar Senha">
                </form>
            </div>
        </div>
    </section>

    <footer id="footer">
        <div class="container">
            <p>&copy; 2024 Jornal Estudantil IFSP São João da Boa Vista. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="assets/js/scroll.js"></script>
    <script src="assets/js/senha.js"></script>
</body>

</html>