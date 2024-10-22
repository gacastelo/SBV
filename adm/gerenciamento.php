<?php
session_start();

// Inclui o arquivo de conexão com o banco de dados
include '../backend/conexao.php';


// Verifica se o usuário está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    // Se não estiver logado, redireciona para o login
    header("Location: ../login.php");
    exit();
}

// Verifica se o usuário tem poderes administrativos (poderes = 1)
if (!isset($_SESSION['poderes']) || $_SESSION['poderes'] != 1) {
    // Se não for administrador, redireciona para a página principal ou de acesso negado
    header("Location: ../index.php");
    exit();
}

try {
    // Consulta para buscar todos os usuários com poderes administrativos (poderes = 1)
    $sql = "SELECT id, usuario, nome FROM usuarios WHERE poderes = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Obtém todos os resultados
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar usuários: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários Administrativos</title>
    <link rel="stylesheet" href="../assets/css/pre-style.css">
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
                    <li><a href="gerenciamento.php" class="active">Gerenciamento</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="container">
        <h2>Lista de Usuários com Poderes Administrativos</h2>

        <table cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($usuarios) > 0): ?>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['usuario']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                            <td>
                                <!-- Aqui podem ser adicionadas ações como editar ou excluir o usuário -->
                                <a href="excluir_usuario.php?id=<?php echo $usuario['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Nenhum usuário administrativo encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php
            // Exibe a mensagem de erro, se houver
            if (isset($_SESSION['erro_cadastro'])) {
                echo '<p style="color: red; text-align: center;">' . $_SESSION['erro_cadastro'] . '</p>';
                unset($_SESSION['erro_cadastro']); // Remove a mensagem de erro da sessão
            }
            ?> <br>
            <h2 class="tit">Adicionar Usuário Administrador</h2>
            <form class="forms" action="../backend/processa_cadastro_adm.php" method="POST">
                <label for="usuario">Nome de Usuário:</label>
                <input class="login-input" type="text" name="usuario" id="usuario" required>
                <br><br>
                <label for="senha">Senha:</label>
                <input class="login-input" type="password" name="senha" id="senha" required>
                <br><br>
                <button type="submit">Cadastrar</button>
            </form>
    </section>

    <footer id="footer">
        <div class="container">
            <p>&copy; 2024 Jornal Estudantil IFSP São João da Boa Vista. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>

</html>
