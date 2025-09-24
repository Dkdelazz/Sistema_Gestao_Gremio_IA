<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: acesso.php');
    exit;
}

// Obtém os dados do usuário logado
$id_usuario = $_SESSION['id_usuario'] ?? null;

// Verifica se o ID do usuário está na sessão
if ($id_usuario === null) {
    echo 'ID do Usuário não encontrado na sessão.';
    exit;
}

// Inicializa a variável userData
$userData = [];

// Carregar dados do usuário
try {
    $sql = "SELECT id_usuario, nome, rm, tipo FROM usuarios WHERE id_usuario = :id_usuario";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "<div class='alert alert-warning'>Nenhum usuário encontrado com este ID: $id_usuario</div>";
        exit;
    }
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Erro ao carregar dados do usuário: " . htmlspecialchars($e->getMessage()) . "</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dados Pessoais</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #fff;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Dados Pessoais</h2>
        <table class="table table-bordered">
            <tr>
                <th scope="row">Nome</th>
                <td><?php echo htmlspecialchars($userData['nome']); ?></td>
            </tr>
            <tr>
                <th scope="row">RM</th>
                <td><?php echo htmlspecialchars($userData['rm']); ?></td>
            </tr>
            <tr>
                <th scope="row">Senha</th>
                <td>*** Não disponível ***</td>
            </tr>
            <tr>
                <th scope="row">Tipo</th>
                <td><?php echo ($userData['tipo'] == 1) ? 'Admin' : 'Usuário Padrão'; ?></td>
            </tr>
        </table>

        <div class="mt-4">
            <div class="row">
                <div class="col text-left">
                    <a href="alterar.php" class="btn btn-primary">Alterar Dados</a>
                </div>
                <div class="col text-right">
                    <a href="perfil1.php" class="btn btn-primary">Voltar</a>
                </div>
            </div>
        </div>



        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>