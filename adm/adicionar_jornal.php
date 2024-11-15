<?php
session_start();

// Função para registrar logs
function logDebug($mensagem) {
    $logFile = __DIR__ . "/upload_debug.log";
    $data = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$data] $mensagem\n", FILE_APPEND);
}

// Verifica se o usuário está logado e se tem poderes administrativos
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true || !isset($_SESSION['poderes']) || $_SESSION['poderes'] != 1) {
    header("Location: ../login.php");
    exit();
}

require_once("../backend/conexao.php");

$mensagem = "";

// Código para deletar PDF
if (isset($_GET['deletar'])) {
    $id = intval($_GET['deletar']);
    
    // Primeiro, pegue o nome do arquivo antes de deletar o registro
    $sql_arquivo = "SELECT nome_arquivo FROM tb_jornal_pdf WHERE id = :id";
    $stmt_arquivo = $conn->prepare($sql_arquivo);
    $stmt_arquivo->bindParam(':id', $id);
    $stmt_arquivo->execute();
    $arquivo = $stmt_arquivo->fetch(PDO::FETCH_ASSOC);
    
    if ($arquivo) {
        // Delete o arquivo físico
        if (file_exists($arquivo['nome_arquivo'])) {
            unlink($arquivo['nome_arquivo']);
            logDebug("Arquivo físico deletado: " . $arquivo['nome_arquivo']);
        } else {
            logDebug("Arquivo físico não encontrado: " . $arquivo['nome_arquivo']);
        }
        
        // Delete o registro do banco de dados
        $sql_delete = "DELETE FROM tb_jornal_pdf WHERE id = :id";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bindParam(':id', $id);
        
        if ($stmt_delete->execute()) {
            $mensagem = "PDF deletado com sucesso!";
            logDebug("Registro deletado do banco de dados, ID: " . $id);
        } else {
            $mensagem = "Erro ao deletar o PDF do banco de dados.";
            logDebug("Erro ao deletar registro do banco de dados, ID: " . $id);
        }
    } else {
        $mensagem = "PDF não encontrado.";
        logDebug("Registro não encontrado no banco de dados, ID: " . $id);
    }
    
    // Redireciona para atualizar a página
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

try {
    // Verificar se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        logDebug("Formulário enviado via POST");
        
        // Verificar se houve erros no upload
        if ($_FILES["arquivo_pdf"]["error"] > 0) {
            switch ($_FILES["arquivo_pdf"]["error"]) {
                case UPLOAD_ERR_INI_SIZE:
                    logDebug("O arquivo excede o limite definido no php.ini");
                    $mensagem = "O arquivo é muito grande. Máximo permitido: " . ini_get("upload_max_filesize");
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    logDebug("O arquivo excede o limite definido no formulário HTML");
                    $mensagem = "O arquivo é muito grande";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    logDebug("O upload foi feito parcialmente");
                    $mensagem = "Erro no upload do arquivo - tente novamente";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    logDebug("Nenhum arquivo foi enviado");
                    $mensagem = "Nenhum arquivo foi selecionado";
                    break;
                default:
                    logDebug("Erro desconhecido: " . $_FILES["arquivo_pdf"]["error"]);
                    $mensagem = "Erro no upload do arquivo";
                    break;
            }
        } else {
            $titulo = $_POST['titulo'];
            $target_dir = "../uploads/pdf/";
            
            // Verificar se o diretório existe e criar se necessário
            if (!file_exists($target_dir)) {
                logDebug("Criando diretório: " . $target_dir);
                if (!mkdir($target_dir, 0777, true)) {
                    logDebug("Erro ao criar diretório");
                    $mensagem = "Erro ao criar diretório de upload";
                    exit();
                }
            }
            
            // Verificar permissões do diretório
            if (!is_writable($target_dir)) {
                logDebug("Diretório não tem permissão de escrita: " . $target_dir);
                chmod($target_dir, 0777);
                logDebug("Tentando definir permissões 0777");
            }

            $fileName = str_replace(' ', '_', $titulo) . ".pdf";
            $target_file = $target_dir . $fileName;
            
            logDebug("Informações do arquivo:");
            logDebug("Nome original: " . $_FILES["arquivo_pdf"]["name"]);
            logDebug("Tipo: " . $_FILES["arquivo_pdf"]["type"]);
            logDebug("Tamanho: " . $_FILES["arquivo_pdf"]["size"] . " bytes");
            logDebug("Nome final: " . $target_file);
            
            // Verificar se o arquivo foi enviado e é um PDF
            $fileType = strtolower(pathinfo($_FILES["arquivo_pdf"]["name"], PATHINFO_EXTENSION));
            error_log("Tipo do arquivo: " . $fileType);
            error_log("Tamanho do arquivo: " . $_FILES["arquivo_pdf"]["size"] . " bytes");
            error_log("Erro no upload: " . $_FILES["arquivo_pdf"]["error"]);

            if (!is_dir($target_dir)) {
                error_log("Criando diretório: " . $target_dir);
                mkdir($target_dir, 0755, true);
            }

            if ($fileType === "pdf") {
                error_log("Arquivo é PDF - verificando tamanho");
                // Limitar o tamanho para 5MB
                if ($_FILES["arquivo_pdf"]["size"] <= 5000000) {
                    error_log("Tamanho do arquivo está dentro do limite");
                    // Mover o arquivo para o diretório de uploads com o nome dado
                    if (move_uploaded_file($_FILES["arquivo_pdf"]["tmp_name"], $target_file)) {
                        error_log("Arquivo movido com sucesso para: " . $target_file);
                        // Inserir informações no banco de dados
                        $sql = "INSERT INTO tb_jornal_pdf (titulo, nome_arquivo) VALUES (:titulo, :nome_arquivo)";
                        error_log("SQL: " . $sql);
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':titulo', $titulo);
                        $stmt->bindParam(':nome_arquivo', $target_file);
                        $stmt->execute();

                        error_log("Registro inserido no banco de dados");
                        // Exibir mensagem de sucesso e redirecionar
                        $mensagem = "PDF adicionado com sucesso!";
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    } else {
                        error_log("Erro ao mover o arquivo. Permissões do diretório: " . substr(sprintf('%o', fileperms($target_dir)), -4));
                        $mensagem = "Erro ao fazer o upload do PDF.";
                    }
                } else {
                    error_log("Arquivo muito grande: " . $_FILES["arquivo_pdf"]["size"] . " bytes");
                    $mensagem = "O arquivo é muito grande. O limite é de 5MB.";
                }
            } else {
                error_log("Tipo de arquivo inválido: " . $fileType);
                $mensagem = "Apenas arquivos PDF são permitidos.";
            }
        }
    }

    // Selecionar os PDFs já cadastrados
    $sql_select = "SELECT * FROM tb_jornal_pdf ORDER BY id DESC";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->execute();
    $jornal_pdfs = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Upload de Jornal</title>
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
                    <li><a href="admin_eventos.php">Eventos</a></li>
                    <li><a href="admin_sugestoes.php">Sugestões</a></li>
                    <li><a href="adicionar_jornal.php" class="active">Jornal</a></li>
                    <li><a href="gerenciamento.php">Gerenciamento</a></li>
                    <li><a href='../backend/logout.php'>Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="container">
        <h2 class="tit">Upload de Jornal (PDF)</h2>

        <?php if (!empty($mensagem)): ?>
            <p class="falha"><?php echo $mensagem; ?></p>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data" class="forms">
            <label for="titulo">Título do Jornal:</label>
            <input type="text" name="titulo" id="titulo" required><br>

            <label for="arquivo_pdf">Selecione o PDF (máx. 5MB):</label>
            <input type="file" name="arquivo_pdf" id="arquivo_pdf" accept="application/pdf" required 
                   max="5000000"><br>

            <button type="submit">Enviar Jornal</button>
        </form>
    </section>

    <section class="container">
        <h2>Jornais Enviados</h2>
        <?php if (!empty($jornal_pdfs)): ?>
            <ul>
                <?php foreach ($jornal_pdfs as $jornal): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($jornal['titulo']); ?></strong><br>
                        <a href="<?php echo $jornal['nome_arquivo']; ?>" download>Baixar PDF</a>
                        <a href="?deletar=<?php echo $jornal['id']; ?>" 
                           onclick="return confirm('Tem certeza que deseja deletar este PDF?');"
                           style="color: red; margin-left: 10px;">Deletar</a><br><br>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nenhum jornal enviado.</p>
        <?php endif; ?>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2024 Jornal Estudantil IFSP São João da Boa Vista. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>

</html>