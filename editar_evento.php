<?php
session_start();
require 'conexao.php'; // Inclua a conexão com o banco de dados

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['loggedin']) || $_SESSION['tipo'] !== 1) {
    header("Location: acesso.php"); // Redireciona se não for admin
    exit;
}

// Obtém o ID do evento
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Busca o evento no banco de dados
$stmt = $conn->prepare("SELECT * FROM eventos WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "Evento não encontrado.";
    exit;
}

// Processa a atualização do evento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $informacoes = $_POST['informacoes'];
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $local = $_POST['local'] ?? $event['local']; // Se não informado, mantém o valor atual

    // Verifica se o diretório de uploads existe, caso contrário, cria
    $uploads_dir = 'uploads';
    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir, 0755, true);
    }

    // Verifica se um arquivo foi enviado
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['imagem']['tmp_name'];
        $name = basename($_FILES['imagem']['name']);
        $filePath = "$uploads_dir/$name";

        // Move o arquivo para o diretório desejado
        if (!move_uploaded_file($tmp_name, $filePath)) {
            echo "Erro ao mover o arquivo.";
            exit;
        }
    } else {
        // Caso nenhum arquivo tenha sido enviado, mantém a imagem anterior
        $filePath = $event['imagem'];
    }

    // Atualiza o evento no banco de dados
    $stmt = $conn->prepare("UPDATE eventos SET titulo = :titulo, informacoes = :informacoes, imagem = :imagem, data_inicio = :data_inicio, data_fim = :data_fim, local = :local WHERE id = :id");
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':informacoes', $informacoes);
    $stmt->bindParam(':imagem', $filePath);
    $stmt->bindParam(':data_inicio', $data_inicio);
    $stmt->bindParam(':data_fim', $data_fim);
    $stmt->bindParam(':local', $local);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    header("Location: eventos.php"); // Redireciona após a atualização
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1 class="text-center">Editar Evento</h1>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo"
                    value="<?php echo htmlspecialchars($event['titulo'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="informacoes">Informações</label>
                <textarea class="form-control" id="informacoes" name="informacoes"
                    required><?php echo htmlspecialchars($event['informacoes'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="data_inicio">Data de Início</label>
                <input type="datetime-local" class="form-control" id="data_inicio" name="data_inicio"
                    value="<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($event['data_inicio'] ?? ''))); ?>"
                    required>
            </div>
            <div class="form-group">
                <label for="data_fim">Data de Fim</label>
                <input type="datetime-local" class="form-control" id="data_fim" name="data_fim"
                    value="<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($event['data_fim'] ?? ''))); ?>"
                    required>
            </div>
            <div class="form-group">
                <label for="local">Local</label>
                <input type="text" class="form-control" id="local" name="local" value="<?php echo htmlspecialchars($event['local'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="imagem">Imagem</label>
                <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*">
                <small class="form-text text-muted">Deixe em branco para manter a imagem atual.</small>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                <a href="excluir_evento.php?id=<?php echo htmlspecialchars($event['id']); ?>" class="btn btn-danger"
                    onclick="return confirm('Tem certeza que deseja excluir este evento?');">Excluir Evento</a>
            </div>
        </form>
    </div>
</body>

</html>
