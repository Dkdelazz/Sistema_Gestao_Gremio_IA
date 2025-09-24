<?php
session_start();
include 'conexao.php'; // Inclua seu arquivo de conexão

// Verifica se o usuário está logado e se é admin
$loggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$isAdmin = isset($_SESSION['tipo']) && $_SESSION['tipo'] === 1;

// Inicializa receitas e despesas com valores padrão
$receitas = [
    'contribuicoes_alunos' => 0.00,
    'patrocinio' => 0.00
];
$despesas = [
    'eventos_culturais' => 0.00,
    'material_escolar' => 0.00,
    'manutencao_equipamentos' => 0.00,
    'outras_despesas' => 0.00
];

// Carrega o orçamento do banco de dados
$sql = "SELECT * FROM orcamento WHERE id = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    // Atualiza as variáveis se o orçamento for encontrado no banco
    $receitas = [
        'contribuicoes_alunos' => floatval($row['contribuicoes_alunos']),
        'patrocinio' => floatval($row['patrocinio'])
    ];
    $despesas = [
        'eventos_culturais' => floatval($row['eventos_culturais']),
        'material_escolar' => floatval($row['material_escolar']),
        'manutencao_equipamentos' => floatval($row['manutencao_equipamentos']),
        'outras_despesas' => floatval($row['outras_despesas'])
    ];
}

// Atualiza o orçamento se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isAdmin) {
    try {
        if (isset($_POST['reset'])) {
            // Reseta o orçamento
            $resetSql = "UPDATE orcamento SET 
                contribuicoes_alunos = 0, 
                patrocinio = 0, 
                eventos_culturais = 0, 
                material_escolar = 0, 
                manutencao_equipamentos = 0, 
                outras_despesas = 0 
                WHERE id = 1";
            $conn->exec($resetSql);
            echo "<div class='alert alert-success'>Orçamento resetado com sucesso!</div>";
        } else {
            // Atualiza receitas (verificando se existem no POST)
            if (isset($_POST['receitas']) && is_array($_POST['receitas'])) {
                foreach ($_POST['receitas'] as $key => $value) {
                    if (isset($receitas[$key])) {
                        $receitas[$key] = floatval($value);
                    }
                }
            }

            // Atualiza despesas (verificando se existem no POST)
            if (isset($_POST['despesas']) && is_array($_POST['despesas'])) {
                foreach ($_POST['despesas'] as $key => $value) {
                    if (isset($despesas[$key])) {
                        $despesas[$key] = floatval($value);
                    }
                }
            }

            // Prepara a atualização no banco de dados
            $updateSql = "UPDATE orcamento SET 
                contribuicoes_alunos = :contribuicoes_alunos, 
                patrocinio = :patrocinio, 
                eventos_culturais = :eventos_culturais, 
                material_escolar = :material_escolar, 
                manutencao_equipamentos = :manutencao_equipamentos, 
                outras_despesas = :outras_despesas 
                WHERE id = 1";

            $stmtUpdate = $conn->prepare($updateSql);
            $stmtUpdate->execute([
                ':contribuicoes_alunos' => $receitas['contribuicoes_alunos'],
                ':patrocinio' => $receitas['patrocinio'],
                ':eventos_culturais' => $despesas['eventos_culturais'],
                ':material_escolar' => $despesas['material_escolar'],
                ':manutencao_equipamentos' => $despesas['manutencao_equipamentos'],
                ':outras_despesas' => $despesas['outras_despesas']
            ]);
            echo "<div class='alert alert-success'>Orçamento atualizado com sucesso!</div>";
        }
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Erro ao atualizar o orçamento: " . $e->getMessage() . "</div>";
    }
}

$totalReceitas = array_sum($receitas);
$totalDespesas = array_sum($despesas);
$totalDisponivel = $totalReceitas - $totalDespesas;
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

    <header class="header_section">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #fff;">
                <a class="navbar-brand" href="#"><img src="img/LOGO1.png" width="280" height="85" alt="nossalogo"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#conteudoNavbarSuportado" aria-controls="conteudoNavbarSuportado" aria-expanded="false"
                    aria-label="Alterna navegação">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="conteudoNavbarSuportado">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="eventos.php">Eventos</a></li>
                        <li class="nav-item"><a class="nav-link" href="orcamento.php">Orçamentos</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="gremios" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Grêmios</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="gremios.php">Grêmios</a>
                                <a class="dropdown-item" href="votacao.php">Votação</a>
                            </div>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="contate.php">Contate-Nos</a></li>
                        <li class="nav-item">
                            <?php if ($loggedIn): ?>
                                <a href="perfil1.php" class="btn btn-image"><img src="img/perfil.png" width="53" height="55"
                                        alt="Perfil"></a>
                            <?php else: ?>
                                <a href="acesso.php" class="btn btn-image"><img src="img/iconelogin.png" width="53"
                                        height="55" alt="Login / Cadastro"></a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <div class="container mt-5">
        <h2 class="section-title">Orçamento do Grêmio</h2>

        <!-- Receitas -->
        <div class="budget-details mb-4">
            <h5 class="text-center">Receitas</h5>
            <div class="list-group">
                <?php foreach ($receitas as $key => $value): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo str_replace('_', ' ', ucfirst($key)); ?>
                        <span class="badge badge-success badge-pill">R$
                            <?php echo number_format($value, 2, ',', '.'); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Despesas -->
        <div class="budget-details mb-4">
            <h5 class="text-center">Despesas Previstas</h5>
            <div class="list-group">
                <?php foreach ($despesas as $key => $value): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo str_replace('_', ' ', ucfirst($key)); ?>
                        <span class="badge badge-danger badge-pill">R$
                            <?php echo number_format($value, 2, ',', '.'); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Barra de Progresso de Total -->
        <div class="mt-4">
            <h3 class="text-center">Total Disponível:</h3>
            <div class="progress">
                <!-- Barra de receitas (verde) -->
                <div class="progress-bar bg-success" role="progressbar"
                    style="width: <?php echo ($totalReceitas + $totalDespesas > 0) ? (($totalReceitas / ($totalReceitas + $totalDespesas)) * 100) : 0; ?>%;"
                    aria-valuenow="<?php echo $totalReceitas; ?>" aria-valuemin="0"
                    aria-valuemax="<?php echo $totalReceitas + $totalDespesas; ?>">
                    <span class="sr-only">Receitas</span>
                </div>
                <!-- Barra de despesas (vermelha) -->
                <div class="progress-bar bg-danger" role="progressbar"
                    style="width: <?php echo ($totalReceitas + $totalDespesas > 0) ? (($totalDespesas / ($totalReceitas + $totalDespesas)) * 100) : 0; ?>%;"
                    aria-valuenow="<?php echo $totalDespesas; ?>" aria-valuemin="0"
                    aria-valuemax="<?php echo $totalReceitas + $totalDespesas; ?>">
                    <span class="sr-only">Despesas</span>
                </div>
            </div>
            <!-- Exibindo valores de receitas e despesas -->
            <div class="row">
                <div class="col-6 text-left">
                    <span>Receitas: R$ <?php echo number_format($totalReceitas, 2, ',', '.'); ?></span>
                </div>
                <div class="col-6 text-right">
                    <span>Despesas: R$ <?php echo number_format($totalDespesas, 2, ',', '.'); ?></span>
                </div>
            </div>
            <!-- Exibindo o saldo disponível -->
            <div class="text-center mt-2">
                <strong>Saldo Disponível: R$
                    <?php echo number_format($totalReceitas - $totalDespesas, 2, ',', '.'); ?></strong>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-8">
                <?php if ($isAdmin): ?>
                    <div class="content-section mt-4">
                        <h4>Alterar Orçamento</h4>
                        <form method="POST">
                            <h5>Receitas</h5>
                            <?php foreach ($receitas as $key => $value): ?>
                                <div class="form-group">
                                    <label
                                        for="receitas_<?php echo htmlspecialchars($key); ?>"><?php echo str_replace('_', ' ', ucfirst($key)); ?></label>
                                    <input type="number" id="receitas_<?php echo htmlspecialchars($key); ?>"
                                        name="receitas[<?php echo htmlspecialchars($key); ?>]" class="form-control"
                                        value="<?php echo number_format($value, 2, '.', ''); ?>" step="0.01" required>
                                </div>
                            <?php endforeach; ?>

                            <h5>Despesas Previstas</h5>
                            <?php foreach ($despesas as $key => $value): ?>
                                <div class="form-group">
                                    <label
                                        for="despesas_<?php echo htmlspecialchars($key); ?>"><?php echo str_replace('_', ' ', ucfirst($key)); ?></label>
                                    <input type="number" id="despesas_<?php echo htmlspecialchars($key); ?>"
                                        name="despesas[<?php echo htmlspecialchars($key); ?>]" class="form-control"
                                        value="<?php echo number_format($value, 2, '.', ''); ?>" step="0.01" required>
                                </div>
                            <?php endforeach; ?>

                            <button type="submit" class="btn btn-outline-secondary">Salvar Alterações</button>
                            <button type="submit" name="reset" class="btn btn-danger">Resetar Orçamento</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <br>

    <footer class="footer_section" style="background-color: black;">
        <div class="container">
            <h5 align="center">&copy; A Diferença para Grêmios Estudantis Todos os Direitos Reservados 2024.</h5>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>