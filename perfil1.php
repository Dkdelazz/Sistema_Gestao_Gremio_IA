<?php
session_start();
require_once("conexao.php");

// Verifica se o usuário está logado
$loggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$isAdmin = isset($_SESSION['tipo']) && $_SESSION['tipo'] === 1;
$username = $loggedIn ? $_SESSION['nome'] : '';

if (!$loggedIn) {
    header('Location: acesso.php'); // Redireciona para a página de acesso se não estiver logado
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Perfil</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Bem-vindo, <?php echo htmlspecialchars($username); ?></h1>

        <h3>Opções</h3>
        <ul class="list-group">
            <?php if ($isAdmin): ?>
                <li class="list-group-item"><a href="alterar_usuarios.php">Alterar Usuários</a></li>
            <?php endif; ?>
            <li class="list-group-item"><a href="dados_pessoais.php">Dados Pessoais</a></li>
            <li class="list-group-item"><a href="index.php">Tela Inicial</a></li>
            <li class="list-group-item"><a href="logout.php">Sair</a></li>
        </ul>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
