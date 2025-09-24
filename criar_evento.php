<?php
session_start();

// Verifica se o usuário está logado e se é admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['tipo'] !== 1) {
    header("Location: acesso.php");
    exit;
}

// Inclui a conexão com o banco de dados
require 'conexao.php';

// Inicializa variáveis
$titulo = $informacoes = $data_inicio = $data_fim = $local = $categoria = $capacidade = $criador = '';
$erro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário
    $titulo = trim($_POST['titulo']);
    $informacoes = trim($_POST['informacoes']);
    $data_inicio = trim($_POST['data_inicio']);
    $data_fim = trim($_POST['data_fim']);
    $local = trim($_POST['local']);

    // Verifica se um arquivo foi enviado
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $uploads_dir = 'uploads';
        if (!is_dir($uploads_dir)) {
            mkdir($uploads_dir, 0755, true);
        }
        $tmp_name = $_FILES['imagem']['tmp_name'];
        $name = basename($_FILES['imagem']['name']);
        $filePath = "$uploads_dir/$name";

        // Move o arquivo para o diretório desejado
        if (!move_uploaded_file($tmp_name, $filePath)) {
            $erro = "Erro ao mover o arquivo.";
        }
    } else {
        $erro = "A imagem é obrigatória.";
    }

    // Validação simples
    $currentDate = new DateTime();
    $endDate = new DateTime($data_fim);

    if (empty($titulo) || empty($informacoes) || empty($data_inicio) || empty($data_fim) || empty($local) || empty($categoria) || empty($criador)) {
        $erro = "Todos os campos são obrigatórios.";
    } elseif ($endDate < $currentDate) {
        $erro = "A data de fim deve ser uma data futura.";
    } elseif (empty($erro)) {
        // Insere o evento no banco de dados
        $stmt = $conn->prepare("INSERT INTO eventos (titulo, informacoes, imagem, data_inicio, data_fim, local, categoria, capacidade, criador) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$titulo, $informacoes, $filePath, $data_inicio, $data_fim, $local, $categoria, $capacidade, $criador])) {
            header("Location: eventos.php");
            exit;
        } else {
            $erro = "Erro ao criar o evento.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Evento</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1 class="text-center">Criar Evento</h1>

        <?php if ($erro): ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>" required>
            </div>
            <div class="form-group">
                <label for="informacoes">Informações</label>
                <textarea class="form-control" id="informacoes" name="informacoes" required><?php echo htmlspecialchars($informacoes); ?></textarea>
            </div>
            <div class="form-group">
                <label for="data_inicio">Data de Início</label>
                <input type="datetime-local" class="form-control" id="data_inicio" name="data_inicio" value="<?php echo htmlspecialchars($data_inicio); ?>" required>
            </div>
            <div class="form-group">
                <label for="data_fim">Data de Fim</label>
                <input type="datetime-local" class="form-control" id="data_fim" name="data_fim" value="<?php echo htmlspecialchars($data_fim); ?>" required>
            </div>
            <div class="form-group">
                <label for="imagem">Imagem</label>
                <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Criar Evento</button>
            <a href="eventos.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>

</html>
