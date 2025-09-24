<?php
session_start();
$loggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$isAdmin = isset($_SESSION['tipo']) && $_SESSION['tipo'] === 1;
include 'conexao.php';

// Anos e valores correspondentes
$anos = [
    2024 => 6,
    2023 => 1,
    2022 => 2,
    2021 => 3,
    2020 => 4,
    2019 => 5,
];

$anoSelecionado = isset($_POST['ano']) && array_key_exists($_POST['ano'], $anos) ? (int) $_POST['ano'] : 2024;

$membros = [];
$propostas = [];

try {
    // Consulta para obter os membros do grupo do ano selecionado
    $sqlMembros = "
        SELECT m.nome, m.foto, c.nome AS cargo
        FROM membros m
        JOIN cargos c ON m.cargo_id = c.id
        WHERE m.ano = :ano";
    $stmtMembros = $conn->prepare($sqlMembros);
    $stmtMembros->bindParam(':ano', $anoSelecionado);
    $stmtMembros->execute();
    $membros = $stmtMembros->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao obter membros: " . $e->getMessage();
}

try {
    // Consulta para obter as propostas do grupo filtradas por ano
    $sqlPropostas = "SELECT titulo, descricao FROM propostas WHERE ano = :ano";
    $stmtPropostas = $conn->prepare($sqlPropostas);
    $stmtPropostas->bindParam(':ano', $anoSelecionado);
    $stmtPropostas->execute();
    $propostas = $stmtPropostas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao obter propostas: " . $e->getMessage();
}

$conn = null; // Fecha a conexão
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

        list-group-item {
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

        .content-section {
            background-color: #DCDCDC;
            border: 0.2px solid #C0C0C0;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .card {
            background-color: #1C1C1C;
        }

        .card-title,
        .card-text {
            color: white;
        }
    </style>
</head>

<body>
    <header class="header_section">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="#">
                    <img src="img/LOGO1.png" width="280" height="85" alt="nossalogo">
                </a>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="eventos.php">Eventos</a></li>
                        <li class="nav-item"><a class="nav-link" href="orcamento.php">Orçamentos</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="gremios" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Grêmios</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="gremios.php">Grêmios</a>
                                <a class="dropdown-item" href="votacao.php">Votação</a>
                            </div>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="contate.php">Contate-Nos</a></li>
                        <li class="nav-item">
                            <?php if ($loggedIn) : ?>
                                <a href="perfil1.php" class="btn btn-image">
                                    <img src="img/perfil.png" width="53" height="55" alt="Perfil">
                                </a>
                            <?php else : ?>
                                <a href="acesso.php" class="btn btn-image">
                                    <img src="img/iconelogin" width="53" height="50" alt="">
                                </a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <p></p>

    <div class="container">
        <h2 class="text-center"> Grêmios <?php echo htmlspecialchars($anoSelecionado); ?></h2>
        <br>
        <form method="POST" class="text-center mb-4">
            <select name="ano" onchange="this.form.submit()">
                <?php foreach ($anos as $ano => $valor) : ?>
                    <option value="<?php echo $ano; ?>" <?php if ($anoSelecionado == $ano)
                                                            echo 'selected'; ?>>
                        <?php echo $ano; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>


        <br>


        <div class="content-section">
            <h2 class="text-center mb-4">Conheça os Membros</h2>
            <div class="row">
                <?php foreach ($membros as $membro) : ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($membro['foto']); ?>" class="card-img-top img-fluid" alt="Foto do Membro">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($membro['nome']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($membro['cargo']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <h2 class="text-center mb-4 mt-5">Propostas do Grupo</h2>
            <div class="row">
                <?php foreach ($propostas as $proposta) : ?>
                    <div class="col-md-6 mb-4">
                        <div class="card" style="border: 2px solid #fff;">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($proposta['titulo']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($proposta['descricao']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($isAdmin) : ?>
                <div class="row">
                    <div class="col-md-6">
                        <a href="editar_gremio.php?ano=<?php echo $anoSelecionado; ?>" class="btn btn-outline-secondary">Editar o
                            Grêmio</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<p></p>
    <br>

    <footer class="footer_section">
        <div class="container-fluid">
            <h5>&copy; A diferença para Grêmios Estudantis Todos os Direitos Reservados 2024.</h5>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>

</html>