<?php
session_start();

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_jornal";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Consulta SQL para verificar o usuário
    $sql = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o usuário existe
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verifica se a senha está correta
        if (password_verify($senha, $row['senha'])) {
            // Define a sessão logado como true
            $_SESSION['logado'] = true;

            //Define o ID do usuário
            $_SESSION['id_usuario'] = $row['id'];

            //Define o Nome do usuário
            $_SESSION['nome'] = $row['nome'];
            
            $_SESSION['poderes'] = $row['poderes'];

            $_SESSION['usuario'] = $row['usuario'];

            $_SESSION['data_cadastro'] = $row['data_cadastro'];

            // Verifica o valor da coluna 'poderes'
            if ($row['poderes'] == 0) {
                // Usuário comum, redireciona para index.php
                header('Location: index.php');
                exit();
            } else {
                // Define a sessão adm_logado como true se for administrador
                $_SESSION['adm_logado'] = true;
            }

            // Redireciona para a página de origem se existir
            if (isset($_SESSION['redirect'])) {
                $redirect_url = $_SESSION['redirect'];
                unset($_SESSION['redirect']); // Remove a página de origem da sessão
                header("Location: $redirect_url");  // Redireciona para a página salva
            } else {
                // Redireciona para adicionar notícias por padrão
                header('Location: adm/painel.php');
            }
            exit();
        } else {
            $erro = "Senha incorreta!";
        }
    } else {
        $erro = "Usuário não encontrado!";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Login</title>
    <link rel="stylesheet" href="assets/css/pre-style.css">
</head>

<body>
    <header id="header">
        <div class="container">
            <img src="assets/img/logojornal.png" alt="" height="80px">
        </div>
    </header>

    <section class="container">
        <div>
            <h3 style="color: #00510f; text-align: center; margin: 20px 0; font-size: 40px;">Login</h3>
            <?php if (isset($erro)): ?>
                <p class="falha"><?php echo $erro; ?></p>
            <?php endif; ?>
            <form class="forms" method="POST" action="login.php">
                <label for="usuario">Usuário:</label>
                <input class="login-input" type="text" name="usuario" required><br><br>
                <label for="senha">Senha:</label>
                <input type="password" name="senha" required><br><br>
                <button type="submit">Entrar</button>
            </form>
            <p style="text-align: center;"><a style="color: #00510f;" href="cadastrar.php">Criar uma Conta</a></p>
            <p style="text-align: center;"><a style="color: #333;" href="index.php">Entrar como Anônimo</a></p>
        </div>
    </section>
    <footer id="footer">
        <div class="container">
            <p>&copy; 2024 Jornal Estudantil IFSP São João da Boa Vista. Todos os direitos reservados.</p>
        </div>
    </footer>
    <script src="assets/js/scroll.js"></script>
</body>

</html>