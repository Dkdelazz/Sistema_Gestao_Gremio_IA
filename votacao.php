<?php
session_start();
$loggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$isAdmin = isset($_SESSION['tipo']) && $_SESSION['tipo'] == 1;
include 'conexao.php';

// Obtendo o ano atual
$anoAtual = date("Y");

$mensagem = ''; // Variável para exibir mensagens de sucesso ou erro
$gremiosParaVotacao = [];
$gremiosVotados = [];
$jaVotou = false;  // Garantindo que a variável exista para uso posterior

// Consultar grêmios para todos os usuários
try {
    $sqlGremios = "SELECT id, nome FROM gremios WHERE ano = :ano";
    $stmtGremios = $conn->prepare($sqlGremios);
    $stmtGremios->bindParam(':ano', $anoAtual);
    $stmtGremios->execute();
    $gremiosParaVotacao = $stmtGremios->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensagem = "Erro ao obter os grêmios: " . $e->getMessage();
}

// Se o usuário for padrão, podemos verificar se já votou
if (!$isAdmin && $loggedIn) {
    $userId = $_SESSION['id_usuario'];
    try {
        $sqlVotacao = "SELECT gremio_id FROM votos WHERE id_usuario = :id_usuario AND ano = :ano";
        $stmtVotacao = $conn->prepare($sqlVotacao);
        $stmtVotacao->bindParam(':id_usuario', $userId);
        $stmtVotacao->bindParam(':ano', $anoAtual);
        $stmtVotacao->execute();
        $jaVotou = $stmtVotacao->rowCount() > 0;
    } catch (PDOException $e) {
        $mensagem = "Erro ao verificar se já votou: " . $e->getMessage();
    }

    // Processando o voto se o usuário não tiver votado
    if (!$jaVotou && isset($_POST['gremio_id'])) {
        $gremioId = $_POST['gremio_id'];
        try {
            // Registrando o voto
            $sqlVoto = "INSERT INTO votos (id_usuario, gremio_id, ano) VALUES (:id_usuario, :gremio_id, :ano)";
            $stmtVoto = $conn->prepare($sqlVoto);
            $stmtVoto->bindParam(':id_usuario', $userId);
            $stmtVoto->bindParam(':gremio_id', $gremioId);
            $stmtVoto->bindParam(':ano', $anoAtual);
            $stmtVoto->execute();
            $mensagem = 'Voto registrado com sucesso!';
        } catch (PDOException $e) {
            $mensagem = 'Erro ao registrar o voto: ' . $e->getMessage();
        }
    } elseif ($jaVotou) {
        $mensagem = 'Você já votou!';
    }
}

// Processamento de criação de votação para administradores
if ($isAdmin) {
    // Verifica se o formulário de criação de votação foi enviado
    if (isset($_POST['criarVotacao'])) {
        if (!empty($_POST['nome_grêmio'])) {
            foreach ($_POST['nome_grêmio'] as $nome_grêmio) {
                if (!empty($nome_grêmio)) {
                    try {
                        // Inserir grêmio na tabela gremios
                        $sql = "INSERT INTO gremios (nome, ano) VALUES (:nome, :ano)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':nome', $nome_grêmio);
                        $stmt->bindParam(':ano', $anoAtual);
                        $stmt->execute();
                    } catch (PDOException $e) {
                        $mensagem = 'Erro ao criar a votação: ' . $e->getMessage();
                    }
                }
            }
            $mensagem = 'Votação criada com sucesso!';
        } else {
            $mensagem = 'Por favor, insira pelo menos um nome de grêmio!';
        }
    }

    // Função para excluir um grêmio
    if (isset($_POST['excluirGrêmio'])) {
        $idGrêmio = $_POST['id_grêmio'];
        try {
            // Deletar grêmio da tabela gremios
            $sql = "DELETE FROM gremios WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $idGrêmio);
            $stmt->execute();
            $mensagem = 'Grêmio excluído com sucesso!';
        } catch (PDOException $e) {
            $mensagem = 'Erro ao excluir o grêmio: ' . $e->getMessage();
        }
    }

    // Consultar a quantidade de votos de cada grêmio
    try {
        $sqlVotos = "SELECT gremio_id, COUNT(*) AS votos_count FROM votos WHERE ano = :ano GROUP BY gremio_id";
        $stmtVotos = $conn->prepare($sqlVotos);
        $stmtVotos->bindParam(':ano', $anoAtual);
        $stmtVotos->execute();
        $gremiosVotados = $stmtVotos->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $mensagem = "Erro ao obter os votos: " . $e->getMessage();
    }
}

$conn = null; // Fecha a conexão
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votação de Grêmios</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .footer_section {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #f8f9fa;
            padding: 10px 0;
        }
        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
        }
        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffeeba;
            color: #856404;
        }
        .btn-custom {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .btn-custom:hover {
            background: linear-gradient(45deg, #0056b3, #003d80);
        }
        .form-control {
            border: 1px solid #007bff;
            box-shadow: none;
            border-radius: 5px;
        }
        .form-control:focus {
            border-color: #0056b3;
        }
        .list-group-item {
            border: 1px solid #007bff;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: box-shadow 0.3s;
        }
        .list-group-item:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .card-header {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Votação de Grêmios</h1>

        <!-- Botão de voltar para a tela inicial -->
        <a href="index.php" class="btn btn-secondary mb-4">Voltar para a Tela Inicial</a>

        <?php if ($mensagem): ?>
            <div class="alert alert-info"><?= $mensagem; ?></div>
        <?php endif; ?>

        <!-- Se o usuário não for admin, mostramos os grêmios para votação -->
        <?php if (!$isAdmin && !$jaVotou): ?>
            <h4>Selecione o grêmio para votar:</h4>
            <form method="POST">
                <div class="list-group">
                    <?php foreach ($gremiosParaVotacao as $gremio): ?>
                        <div class="list-group-item">
                            <input type="radio" name="gremio_id" value="<?= $gremio['id']; ?>" required>
                            <label><?= $gremio['nome']; ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="submit" class="btn btn-custom mt-3">Votar</button>
            </form>
        <?php elseif ($jaVotou): ?>
            <div class="alert alert-warning">Você já votou!</div>
        <?php endif; ?>

        <!-- Se o usuário for admin, ele pode criar e excluir grêmios -->
        <?php if ($isAdmin): ?>
            <h4 class="mt-5">Criar Nova Votação</h4>
            <form method="POST">
                <div id="grêmio-fields">
                    <div class="mb-2">
                        <input type="text" name="nome_grêmio[]" class="form-control" placeholder="Nome do Grêmio" required>
                    </div>
                </div>
                <button type="button" id="add-new-gremio" class="btn btn-info mt-3">Adicionar Novo Grêmio</button>
                <button type="submit" name="criarVotacao" class="btn btn-custom mt-3">Criar Votação</button>
            </form>

            <h4 class="mt-5">Gerenciar Grêmios e Votação</h4>
            <div class="list-group">
                <?php foreach ($gremiosParaVotacao as $gremio): ?>
                    <div class="list-group-item">
                        <h5><?= $gremio['nome']; ?></h5>
                        <p><strong>Votos: </strong>
                            <?php
                            $votos = 0;
                            foreach ($gremiosVotados as $gremioVotado) {
                                if ($gremioVotado['gremio_id'] == $gremio['id']) {
                                    $votos = $gremioVotado['votos_count'];
                                }
                            }
                            echo $votos;
                            ?>
                        </p>
                        <form method="POST">
                            <input type="hidden" name="id_grêmio" value="<?= $gremio['id']; ?>">
                            <button type="submit" name="excluirGrêmio" class="btn btn-danger">Excluir Grêmio</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <footer class="footer_section">
        <div class="text-center">
            <p>&copy; <?= date("Y"); ?> Todos os direitos reservados</p>
        </div>
    </footer>

    <script>
        document.getElementById("add-new-gremio").addEventListener("click", function () {
            var div = document.createElement("div");
            div.className = "mb-2";
            div.innerHTML = '<input type="text" name="nome_grêmio[]" class="form-control" placeholder="Nome do Grêmio">';
            document.getElementById("grêmio-fields").appendChild(div);
        });
    </script>
</body>
</html>
