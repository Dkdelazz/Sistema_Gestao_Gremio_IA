<?php
session_start();
$loggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
include 'conexao.php';

$anoSelecionado = isset($_GET['ano']) ? (int) $_GET['ano'] : 2024;
$membros = [];
$propostas = [];

// Obtendo informações do grêmio
try {
    $sqlMembros = "SELECT * FROM membros WHERE ano = :ano";
    $stmtMembros = $conn->prepare($sqlMembros);
    $stmtMembros->bindParam(':ano', $anoSelecionado);
    $stmtMembros->execute();
    $membros = $stmtMembros->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao obter membros: " . $e->getMessage();
}

try {
    $sqlPropostas = "SELECT * FROM propostas WHERE ano = :ano";
    $stmtPropostas = $conn->prepare($sqlPropostas);
    $stmtPropostas->bindParam(':ano', $anoSelecionado);
    $stmtPropostas->execute();
    $propostas = $stmtPropostas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao obter propostas: " . $e->getMessage();
}

// Obtendo cargos disponíveis
$cargos = [];
try {
    $sqlCargos = "SELECT id, nome FROM cargos";
    $stmtCargos = $conn->prepare($sqlCargos);
    $stmtCargos->execute();
    $cargos = $stmtCargos->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao obter cargos: " . $e->getMessage();
}


// Obtendo apenas os 7 primeiros cargos disponíveis
$cargos = [];
try {
    $sqlCargos = "SELECT id, nome FROM cargos LIMIT 7";
    $stmtCargos = $conn->prepare($sqlCargos);
    $stmtCargos->execute();
    $cargos = $stmtCargos->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao obter cargos: " . $e->getMessage();
}



// Processando a atualização do grêmio
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atualizando membros
    foreach ($_POST['membros'] as $membroId => $membroData) {
        $membroNome = $membroData['nome'];
        $membroCargoId = $membroData['cargo_id'];
        try {
            $sqlUpdate = "UPDATE membros SET nome = :nome, cargo_id = :cargo_id WHERE id = :id AND ano = :ano";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':nome', $membroNome);
            $stmtUpdate->bindParam(':cargo_id', $membroCargoId);
            $stmtUpdate->bindParam(':id', $membroId);
            $stmtUpdate->bindParam(':ano', $anoSelecionado);
            $stmtUpdate->execute();
        } catch (PDOException $e) {
            echo "Erro ao atualizar membro: " . $e->getMessage();
        }
    }


    // Atualizando propostas
    foreach ($_POST['propostas'] as $propostaId => $propostaData) {
        $propostaTitulo = $propostaData['titulo'];
        $propostaDescricao = $propostaData['descricao'];
        try {
            $sqlUpdateProposta = "UPDATE propostas SET titulo = :titulo, descricao = :descricao WHERE id = :id AND ano = :ano";
            $stmtUpdateProposta = $conn->prepare($sqlUpdateProposta);
            $stmtUpdateProposta->bindParam(':titulo', $propostaTitulo);
            $stmtUpdateProposta->bindParam(':descricao', $propostaDescricao);
            $stmtUpdateProposta->bindParam(':id', $propostaId);
            $stmtUpdateProposta->bindParam(':ano', $anoSelecionado);
            $stmtUpdateProposta->execute();
        } catch (PDOException $e) {
            echo "Erro ao atualizar proposta: " . $e->getMessage();
        }
    }

    // Redirecionar após a atualização
    header("Location: gremios.php?ano=$anoSelecionado");
    exit;
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
            background-color: #fff;
            color: #000;
            padding-top: 2px;
        }

        .footer_section {
            background-color: #000;
            color: #FFFFFF;
            text-align: center;
            padding: 10px;
            width: 100%;
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
                                <a href="perfil1.php" class="btn btn-image">
                                    <img src="img/perfil.png" width="53" height="55" alt="Perfil">
                                </a>
                            <?php else: ?>
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

    <br>

    <div class="container">
        <h2 class="text-center">Editar Grêmio - Ano <?php echo htmlspecialchars($anoSelecionado); ?></h2>
        <form method="POST">
            <div class="content-section">
                <h3>Membros</h3>
                <?php foreach ($membros as $membro): ?>
                    <div class="form-group row">
                        <div class="col-md-2">
                            <img src="<?php echo htmlspecialchars($membro['foto']); ?>" class="img-fluid"
                                alt="Foto do Membro">
                        </div>
                        <div class="col-md-10">
                            <label><?php echo htmlspecialchars($membro['nome']); ?></label>
                            <input type="hidden" name="membros[<?php echo $membro['id']; ?>][foto]"
                                value="<?php echo htmlspecialchars($membro['foto']); ?>">
                            <input type="text" name="membros[<?php echo $membro['id']; ?>][nome]" class="form-control"
                                value="<?php echo htmlspecialchars($membro['nome']); ?>">

                            <label>Cargo</label>
                            <select name="membros[<?php echo $membro['id']; ?>][cargo_id]" class="form-control">
                                <?php foreach ($cargos as $cargo): ?>
                                    <option value="<?php echo htmlspecialchars($cargo['id']); ?>" <?php echo ($cargo['id'] === $membro['cargo_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cargo['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>


            <div class="content-section mt-4">
                <h3>Propostas</h3>
                <?php foreach ($propostas as $proposta): ?>
                    <div class="form-group">
                        <label>Título da Proposta</label>
                        <input type="text" name="propostas[<?php echo $proposta['id']; ?>][titulo]" class="form-control"
                            value="<?php echo htmlspecialchars($proposta['titulo']); ?>">
                        <label>Descrição</label>
                        <textarea name="propostas[<?php echo $proposta['id']; ?>][descricao]" class="form-control"
                            rows="3"><?php echo htmlspecialchars($proposta['descricao']); ?></textarea>
                    </div>
                <?php endforeach; ?>
            </div>

            <br>

            <button type="submit" class="btn btn-success">Salvar Alterações</button>
        </form>
    </div>

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