<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
$loggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

if (!$loggedIn) {
    header("Location: acesso.php"); // Redireciona para a página de login se não estiver logado
    exit;
}

// Define o nome de usuário, se estiver logado
$username = isset($_SESSION['nome']) ? $_SESSION['nome'] : '';

// Inclui o arquivo de conexão
include 'conexao.php';

// Processa a exclusão se solicitado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'excluir' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Executa a consulta de exclusão
    $sql = "DELETE FROM duvida WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        $successMessage = "Dúvida excluída com sucesso!";
    } else {
        $errorMessage = "Erro ao excluir a dúvida.";
    }
}

// Consulta para obter as mensagens da tabela 'duvida'
$sql = "SELECT id, nome, serie, email, mensagem, data FROM duvida ORDER BY data DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dúvidas - A Diferença Grêmios</title>
    <link rel="shortcut icon" href="img/favicon.png">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos CSS Combinados */

body {
    background-color: #f8f9fa; /* Usando o fundo mais claro do segundo bloco */
    color: #000;
    padding-top: 2px;
    display: flex;
    flex-direction: column;
    min-height: 100vh; /* Define altura mínima da tela */
}

.header_section {
    background-color: #fff;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
}

.carousel-inner img {
    height: 450px;
    object-fit: cover;
}

.section-title {
    font-size: 2.5rem;
    font-weight: bold;
    margin-top: 30px;
    color: #000;
}

.footer_section {
    background-color: #000;
    color: #fff;
    text-align: center;
    padding: 20px;
    width: 100%;
}

.footer_section h5 {
    margin: 0;
}

.content-section {
    background-color: #DCDCDC;
    border: 0.2px solid #C0C0C0;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 10px;
    margin-top: 20px;
    flex: 1;        
}

.modal-dialog {
    max-width: 500px;
    margin: 1.75rem auto;
}

.btn-image {
    padding: 0;
    border: none;
    background: none;
}

.btn-image img {
    max-width: 53px;
    height: auto;
}

.navbar-nav .nav-item {
    margin-right: 15px;
}

.navbar-light .navbar-nav .nav-link {
    color: #333;
}

.navbar-light .navbar-nav .nav-link:hover {
    color: #D3D3D3;
}

/* Define a cor ao passar o mouse apenas para o item de boas-vindas */
.navbar-light .navbar-nav .nav-link.welcome-message:hover {
    color: #000;
}

.btn-outline-primary {
    border-color: #fff;
    color: #fff;
}

.btn-outline-primary:hover {
    background-color: #fff;
    color: #000;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: #333;
}

.list-group-item {
    font-size: 1.1rem;
}

.list-group-item strong {
    color: #000;
}

.carousel-item img {
    max-height: 400px;
    object-fit: cover;
}

.container a {
    color: #333;
    text-decoration: none;
}

.container a:hover {
    text-decoration: underline;
}

@media (max-width: 576px) {
    .carousel-inner img {
        height: 300px;
    }
}

    </style>
</head>
<body>
    <!-- Cabeçalho -->
    <header class="header_section">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #fff;">
                <a class="navbar-brand" href="#">
                    <img src="img/LOGO1.png" width="280" height="85" alt="nossalogo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#conteudoNavbarSuportado" aria-controls="conteudoNavbarSuportado" aria-expanded="false" aria-label="Alterna navegação">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="conteudoNavbarSuportado">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Home <span class="sr-only">(página atual)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="eventos.php">Eventos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="orcamento.php">Orçamentos</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="gremios" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Grêmios</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="gremios.php">Grêmios</a>
                                <a class="dropdown-item" href="votacao.php">Votação</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contate.php">Contate-Nos</a>
                        </li>
                        <li>
                            <a class="nav-item"></a>
                        </li>
                        <li>
                            <a class="nav-item">
                                <?php if ($loggedIn): ?>
                                    <a href="perfil1.php" class="btn btn-image">
                                        <img src="img/perfil.png" width="53" height="55" alt="Perfil">
                                    </a>
                                <?php else: ?>
                                    <a href="acesso.php" class="btn btn-image">
                                        <img src="img/iconelogin" width="53" height="55" alt="Login / Cadastro">
                                    </a>
                                <?php endif; ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <!-- Mensagens Recebidas -->
    <div class="container content-section">
        <h2>Dúvidas Recebidas</h2>
        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        <?php if (!empty($result)): ?>
            <ul class="list-group">
                <?php foreach ($result as $duvida): ?>
                    <li class="list-group-item">
                        <strong><?php echo htmlspecialchars($duvida['nome']); ?> (<?php echo htmlspecialchars($duvida['serie']); ?>)</strong><br>
                        <em><?php echo htmlspecialchars($duvida['email']); ?></em><br>
                        <p><?php echo nl2br(htmlspecialchars($duvida['mensagem'])); ?></p>
                        <small><?php echo htmlspecialchars($duvida['data']); ?></small>
                        <form action="" method="post" style="display:inline;">
                            <input type="hidden" name="action" value="excluir">
                            <input type="hidden" name="id" value="<?php echo $duvida['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nenhuma mensagem recebida até o momento.</p>
        <?php endif; ?>
    </div>

    <!-- Rodapé -->
    <footer class="footer_section">
        <div class="container">
            <h5 class="text-white text-center">&copy; A Diferença para Grêmios Estudantis Todos os Direitos Reservados 2024.</h5>
        </div>
    </footer>

    <!-- jQuery, Popper.js, e Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIqA7yT9O68j2Wj7+E5x1e6o4z7IbzZ+P0ZC6R3F4t4d2X2wZhl" crossorigin="anonymous"></script>
</body>
</html>
