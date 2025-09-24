<?php
session_start();

// Verifica se o usuário está logado
$loggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] = true;
$username = $loggedIn ? (isset($_SESSION['nome']) ? $_SESSION['nome'] : '') : '';

// Verifica se o usuário é admin
$isAdmin = isset($_SESSION['tipo']) && $_SESSION['tipo'] = 1;

// Inclui a conexão com o banco de dados
require 'conexao.php'; // Certifique-se de que o caminho está correto

try {
    // Função para carregar os eventos de acordo com a condição
    function carregarEventos($conn, $condicao) {
        $stmt = $conn->prepare("SELECT * FROM eventos WHERE $condicao ORDER BY data_inicio ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Carregar eventos com as respectivas condições
    $upcomingEvents = carregarEventos($conn, "data_inicio >= NOW()");
    $ongoingEvents = carregarEventos($conn, "data_inicio <= NOW() AND data_fim >= NOW()");
    $completedEvents = carregarEventos($conn, "data_fim < NOW()");

} catch (PDOException $e) {
    echo "Erro ao carregar eventos: " . htmlspecialchars($e->getMessage());
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A Diferença Grêmios</title>
    <link rel="shortcut icon" href="img/favicon.png">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #000;
            padding-top: 2px;
        }

        .header_section {
            background-color: #fff;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .navbar-nav .nav-item {
            margin-right: 15px;
        }

        .navbar-brand img {
            width: 280px;
            height: 85px;
        }

        .content-section {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .content-section img {
            max-width: 100%;
            border-radius: 8px;
        }

        .footer_section {
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 20px;
        }
        
        .navbar-light .navbar-nav .nav-link {
            color: #333;
        }

        .navbar-light .navbar-nav .nav-link:hover {
            color: #D3D3D3;
        }

        .btn-image {
            padding: 0;
            border: none;
            background: none;
        }

        .btn-outline-primary {
            color: #007bff;
            border-color: #007bff;
        }

        .btn-outline-primary:hover {
            background-color: #007bff;
            color: #fff;
        }

        h1, h2 {
            text-align: center;
            color: #000;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <header class="header_section">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="#">
                    <img src="img/LOGO1.png" alt="nossalogo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#conteudoNavbarSuportado" aria-controls="conteudoNavbarSuportado" aria-expanded="false" aria-label="Alterna navegação">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="conteudoNavbarSuportado">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="eventos.php">Eventos</a></li>
                        <li class="nav-item"><a class="nav-link" href="orcamento.php">Orçamentos</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Grêmios</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="gremios.php">Grêmios</a>
                                <a class="dropdown-item" href="votacao.php">Votação</a>
                            </div>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="contate.php">Contate-Nos</a></li>
                        <li class="nav-item">
                            <?php if ($loggedIn): ?>
                                <a href="perfil1.php" class="btn btn-image">
                                    <img src="img/perfil.png" width="53" height="55" alt="Perfil">
                                </a>
                            <?php else: ?>
                                <a href="acesso.php" class="btn btn-image">
                                    <img src="img/iconelogin" width="53" height="55" alt="Login / Cadastro">
                                </a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <div class="container my-4">
        <h1>Eventos</h1>

        <h2 class="mt-4 d-flex justify-content-between align-items-center">
            Eventos em Andamento
            <?php if ($isAdmin): ?>
                <a href="criar_evento.php" class="btn btn-outline-secondary">Criar Evento</a>
            <?php endif; ?>
        </h2>

        <?php foreach ($ongoingEvents as $event): ?>
            <div class="content-section mb-4">
                <h5 class="text-center"><?php echo htmlspecialchars($event['titulo']); ?></h5>
                <img class="img-fluid mb-3" src="<?php echo htmlspecialchars($event['imagem']); ?>" alt="Imagem do Evento">
                <p><strong>Informações:</strong> <?php echo htmlspecialchars($event['informacoes']); ?></p>
                <p><strong>Data de Início:</strong> <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($event['data_inicio']))); ?></p>
                <p><strong>Data de Fim:</strong> <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($event['data_fim']))); ?></p>
                <p><strong>Local:</strong> <?php echo htmlspecialchars($event['local']); ?></p>
                <?php if ($isAdmin): ?>
                    <div class="text-right">
                        <a href="editar_evento.php?id=<?php echo $event['id']; ?>" class="btn btn-outline-secondary">Editar Evento</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <h2 class="mt-4">Eventos Proximos</h2>
        <?php foreach ($upcomingEvents as $event): ?>
            <div class="content-section mb-4">
                <h5 class="text-center"><?php echo htmlspecialchars($event['titulo']); ?></h5>
                <img class="img-fluid mb-3" src="<?php echo htmlspecialchars($event['imagem']); ?>" alt="Imagem do Evento">
                <p><strong>Informações:</strong> <?php echo htmlspecialchars($event['informacoes']); ?></p>
                <p><strong>Data de Início:</strong> <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($event['data_inicio']))); ?></p>
                <p><strong>Data de Fim:</strong> <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($event['data_fim']))); ?></p>
                <p><strong>Local:</strong> <?php echo htmlspecialchars($event['local']); ?></p>
                <?php if ($isAdmin): ?>
                    <div class="text-right">
                        <a href="editar_evento.php?id=<?php echo $event['id']; ?>" class="btn btn-outline-secondaryr">Editar Evento</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <h2 class="mt-4">Eventos Concluídos</h2>
        <?php foreach ($completedEvents as $event): ?>
            <div class="content-section mb-4">
                <h5 class="text-center"><?php echo htmlspecialchars($event['titulo']); ?></h5>
                <img class="img-fluid mb-3" src="<?php echo htmlspecialchars($event['imagem']); ?>" alt="Imagem do Evento">
                <p><strong>Informações:</strong> <?php echo htmlspecialchars($event['informacoes']); ?></p>
                <p><strong>Data de Início:</strong> <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($event['data_inicio']))); ?></p>
                <p><strong>Data de Fim:</strong> <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($event['data_fim']))); ?></p>
                <p><strong>Local:</strong> <?php echo htmlspecialchars($event['local']); ?></p>
                <?php if ($isAdmin): ?>
                    <div class="text-right">
                        <a href="editar_evento.php?id=<?php echo $event['id']; ?>" class="btn btn-outline-secondary">Editar Evento</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Rodapé -->
    <footer class="footer_section mt-5">
        <div class="container">
            <h5>&copy; 2024 A Diferença para Grêmios Estudantis. Todos os Direitos Reservados.</h5>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
