<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
$loggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

// Define o nome de usuário, se estiver logado
$username = $loggedIn ? (isset($_SESSION['nome']) ? $_SESSION['nome'] : '') : '';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A Diferença Grêmios - Home</title>
    <!-- Bootstrap CSS -->
    <link rel="shortcut icon" href="img/favicon.png">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #000;
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
            /* Ou qualquer cor que você preferir para o hover */
        }

        .footer_section h5 {
            margin: 0;
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
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="#">
                    <img src="img/LOGO1.png" width="250" height="75" alt="Logo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#conteudoNavbarSuportado">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="conteudoNavbarSuportado">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link welcome-message"><span>Bem-vindo,
                                    <?php echo htmlspecialchars($username); ?>!</span></a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="eventos.php">Eventos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="orcamento.php">Orçamentos</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="gremios" id="navbarDropdown"
                                data-toggle="dropdown">Grêmios</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="gremios.php">Grêmios</a>
                                <a class="dropdown-item" href="votacao.php">Votação</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contate.php">Contate-Nos</a>
                        </li>
                        <li class="nav-item">
                            <?php if ($loggedIn): ?>
                                <a href="perfil1.php" class="btn btn-image">
                                    <img src="img/perfil.png" width="45" alt="Perfil">
                                </a>
                            <?php else: ?>
                                <a href="acesso.php" class="btn btn-image">
                                    <img src="img/iconelogin" width="45" alt="Login / Cadastro">
                                </a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <!-- Carrossel -->
    <div class="container mt-4">
        <div id="carrosel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <a href="eventos.php">
                        <img class="d-block w-100" src="img/interclasse.png" alt="Primeiro Slide">
                    </a>
                </div>
                <div class="carousel-item">
                    <a href="eventos.php">
                        <img class="d-block w-100" src="img/festajunina.png" alt="Segundo Slide">
                    </a>
                </div>
                <div class="carousel-item">
                    <a href="eventos.php">
                        <img class="d-block w-100" src="img/halloween.png" alt="Terceiro Slide">
                    </a>
                </div>
            </div>
            <a class="carousel-control-prev" href="#carrosel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </a>
            <a class="carousel-control-next" href="#carrosel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </a>
        </div>
    </div>

    <!-- Depoimentos -->
    <div class="container mt-5 text-center">
        <h2 class="section-title">O Que Dizem Sobre Nós</h2>
        <div id="testimonials" class="carousel slide mt-4" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <blockquote class="blockquote">
                        <p class="mb-0">"Os grêmios fizeram a diferença na escola. Hoje temos mais atividades para
                            todos!"</p>
                        <footer class="blockquote-footer">Estudante do 3º ano, São Paulo</footer>
                    </blockquote>
                </div>
                <div class="carousel-item">
                    <blockquote class="blockquote">
                        <p class="mb-0">"Participar do grêmio me ajudou a desenvolver liderança e organização."</p>
                        <footer class="blockquote-footer">Membro de grêmio, Rio de Janeiro</footer>
                    </blockquote>
                </div>
            </div>
            <a class="carousel-control-prev" href="#testimonials" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Anterior</span>
            </a>
            <a class="carousel-control-next" href="#testimonials" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Próximo</span>
            </a>
        </div>
    </div>

    <!-- Eventos Realizados -->
    <div class="container mt-5">
        <h2 class="section-title">Eventos Realizados</h2>
        <div class="list-group">
            <?php
            // Conexão com o banco de dados
            require_once 'conexao.php'; // Certifique-se de que o caminho está correto
            
            // Consulta para buscar eventos passados
            $stmt = $pdo->prepare("SELECT * FROM eventos WHERE data_fim < NOW() ORDER BY data_fim DESC");
            $stmt->execute();
            $pastEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Verifica se há eventos realizados
            if (!empty($pastEvents)):
                foreach ($pastEvents as $event): ?>
                    <li class="list-group-item">
                        <strong><?php echo htmlspecialchars(date('d/m/Y', strtotime($event['data_fim']))); ?>:</strong>
                        <?php echo htmlspecialchars($event['titulo']); ?>
                    </li>
                <?php endforeach;
            else: ?>
                <li class="list-group-item text-muted">
                    Não há eventos realizados no momento. Fique atento aos próximos!
                </li>
            <?php endif; ?>
        </div>
    </div>



    <!-- Eventos Realizados -->
    <div class="container mt-5">
        <h2 class="section-title">Proximos Eventos</h2>
        <div class="list-group">
            <?php
            // Conexão com o banco de dados
            require_once 'conexao.php'; // Certifique-se de que o caminho está correto
            
            // Consulta para buscar eventos futuros
            $stmt = $pdo->prepare("SELECT * FROM eventos WHERE data_inicio >= NOW() ORDER BY data_inicio ASC");
            $stmt->execute();
            $upcomingEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Verifica se há eventos
            if (!empty($upcomingEvents)):
                foreach ($upcomingEvents as $event): ?>
                    <li class="list-group-item">
                        <strong><?php echo htmlspecialchars(date('d/m/Y', strtotime($event['data_inicio']))); ?>:</strong>
                        <?php echo htmlspecialchars($event['titulo']); ?>
                    </li>
                <?php endforeach;
            else: ?>
                <li class="list-group-item text-muted">
                    O Grêmio está preparando algo incrível e inesquecível para a gente.
                </li>
            <?php endif; ?>
        </div>
    </div>



    <!-- Rodapé -->
    <footer class="footer_section mt-5">
        <div class="container">
            <h5>&copy; 2024 A Diferença para Grêmios Estudantis. Todos os Direitos Reservados.</h5>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>