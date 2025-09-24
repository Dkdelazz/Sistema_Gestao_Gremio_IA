<?php
session_start();
$loggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$isAdmin = isset($_SESSION['tipo']) && $_SESSION['tipo'] == 1;
include 'conexao.php';

// Obtendo o ano atual
$anoAtual = date("Y");

if ($isAdmin) {
    // Consultando os resultados da votação
    $votos = [];
    try {
        $sqlResultados = "SELECT gremios.nome, COUNT(votos.gremio_id) as total_votos 
                          FROM gremios 
                          LEFT JOIN votos ON gremios.id = votos.gremio_id 
                          WHERE gremios.ano = :ano 
                          GROUP BY gremios.id, gremios.nome";
        $stmtResultados = $conn->prepare($sqlResultados);
        $stmtResultados->bindParam(':ano', $anoAtual);
        $stmtResultados->execute();
        $votos = $stmtResultados->fetchAll(PDO::FETCH_ASSOC);

        // Encontrando o vencedor
        $maxVotos = 0;
        foreach ($votos as $resultado) {
            if ($resultado['total_votos'] > $maxVotos) {
                $maxVotos = $resultado['total_votos'];
                $vencedorNome = $resultado['nome']; // Armazena o nome do grêmio vencedor
            }
        }
    } catch (PDOException $e) {
        echo "Erro ao obter resultados: " . htmlspecialchars($e->getMessage());
    }
}

$conn = null; // Fecha a conexão
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Votação</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Resultados da Votação</h2>

        <?php if ($isAdmin && count($votos) > 0): ?>
            <div class="mt-4">
                <canvas id="graficoVotos"></canvas>
            </div>
            <script>
                const ctx = document.getElementById('graficoVotos').getContext('2d');
                const chart = new Chart(ctx, {
                    type: 'bar', // Define o tipo de gráfico
                    data: {
                        labels: <?= json_encode(array_column($votos, 'nome')) ?>, // Nomes dos Grêmios
                        datasets: [{
                            label: 'Número de Votos',
                            data: <?= json_encode(array_column($votos, 'total_votos')) ?>, // Quantidade de votos
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
        <?php endif; ?>

        <hr>

        <!-- Exibe o vencedor -->
        <?php if ($isAdmin && isset($vencedorNome)): ?>
            <h3 class="mt-4">Vencedor: <?= $vencedorNome ?></h3>
        <?php endif; ?>
    </div>

    <footer class="footer_section">
        <p class="text-center">&copy; 2024 Todos os direitos reservados.</p>
    </footer>
</body>

</html>
