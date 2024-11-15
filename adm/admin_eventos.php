<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    $_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit();
}

require_once("../backend/conexao.php");

$mensagem = "";

// Processar a adição de um novo evento
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_evento'])) {
    // Validação dos campos
    $evento = $_POST['evento'] ?? NULL;
    $data_evento = $_POST['data_evento'] ?? NULL;
    $horario = $_POST['horario'] ?? NULL;

    // Verificar se os campos obrigatórios estão preenchidos
    if (!empty($evento) && !empty($data_evento)) {
        // Se o horário estiver vazio, usar NULL
        $horario = !empty($horario) ? $horario : null;

        // Inserir no banco de dados
        $stmt = $conn->prepare("INSERT INTO tb_eventos (evento, data_evento, horario) VALUES (:evento, :data_evento, :horario)");
        $stmt->bindParam(':evento', $evento);
        $stmt->bindParam(':data_evento', $data_evento);
        // Usar PDO::PARAM_NULL para NULL
        if ($horario === null) {
            $stmt->bindValue(':horario', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':horario', $horario);
        }

        if ($stmt->execute()) {
            $mensagem = "Evento adicionado com sucesso!";
        } else {
            $mensagem = "Erro ao adicionar o evento.";
        }
    } else {
        // Mensagem de erro caso algum campo esteja vazio
        $mensagem = "Preencha todos os campos obrigatórios.";
    }
}

// Processar a exclusão de um evento
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_evento'])) {
    $id_evento = $_POST['id_evento'] ?? NULL;

    // Deletar evento pelo ID
    if (!empty($id_evento)) {
        $stmt = $conn->prepare("DELETE FROM tb_eventos WHERE id = :id");
        $stmt->bindParam(':id', $id_evento, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $mensagem = "Evento deletado com sucesso!";
        } else {
            $mensagem = "Erro ao deletar o evento.";
        }
    }
}

// Selecionar todos os eventos cadastrados
try {
    $sql = "SELECT * FROM tb_eventos ORDER BY data_evento ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro na consulta: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Eventos</title>
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
                    <li><a href="admin_eventos.php" class="active">Eventos</a></li>
                    <li><a href="admin_sugestoes.php">Sugestões</a></li>
                    <li><a href="adicionar_jornal.php">PDF's</a></li>
                    <li><a href="gerenciamento.php">Gerenciamento</a></li>
                    <li><a href='../backend/logout.php'>Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="container">
        <h2 class="tit">Adicionar Novo Evento</h2>
        <form method="POST" action="" class="forms">
            <label for="evento">Nome do Evento:</label>
            <input type="text" name="evento" id="evento" required>
            <label for="data_evento">Data do Evento:</label>
            <input type="date" name="data_evento" id="data_evento" required>
            <label for="horario">Horário do Evento:</label>
            <input type="time" name="horario" id="horario">
            <button type="submit" name="add_evento">Adicionar Evento</button>
        </form>
        <?php if ($mensagem): ?>
            <p><?php echo htmlspecialchars($mensagem); ?></p>
        <?php endif; ?>
    </section>

    <section class="container">
        <h2>Eventos Cadastrados</h2>
        <?php if (!empty($eventos)): ?>
            <ul>
                <?php foreach ($eventos as $evento): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($evento['evento'] ?? 'Evento sem nome'); ?></strong><br>
                        <?php echo date('d/m/Y', strtotime($evento['data_evento'] ?? '')); ?>
                        <?php if (!empty($evento['horario'])): ?>
                            , <?php echo date('H:i', strtotime($evento['horario'])); ?>
                        <?php endif; ?>
                        <br>

                        <!-- Formulário para deletar o evento -->
                        <form method="POST" action="" style="display:inline;">
                            <input type="hidden" name="id_evento" value="<?php echo $evento['id']; ?>">
                            <button type="submit" name="delete_evento" onclick="return confirm('Tem certeza que deseja deletar este evento?');">Deletar</button>
                        </form>
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
</body>

</html>