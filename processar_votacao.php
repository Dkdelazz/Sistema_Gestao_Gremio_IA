<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die('Você precisa estar logado para votar.');
}

// Verifique se um grêmio foi selecionado
if (isset($_POST['gremio_id'])) {
    $gremio_id = $_POST['gremio_id'];
    $id_usuario = $_SESSION['id_usuario']; // Agora usa id_usuario

    try {
        // Verifique se o usuário já votou
        $sqlVerificaVoto = "SELECT * FROM votos WHERE id_usuario = :id_usuario AND gremio_id = :gremio_id";
        $stmtVerificaVoto = $conn->prepare($sqlVerificaVoto);
        $stmtVerificaVoto->bindParam(':id_usuario', $id_usuario);
        $stmtVerificaVoto->bindParam(':gremio_id', $gremio_id);
        $stmtVerificaVoto->execute();

        if ($stmtVerificaVoto->rowCount() > 0) {
            echo "Você já votou nesse grêmio!";
        } else {
            // Registrar o voto
            $sqlVoto = "INSERT INTO votos (id_usuario, gremio_id) VALUES (:id_usuario, :gremio_id)";
            $stmtVoto = $conn->prepare($sqlVoto);
            $stmtVoto->bindParam(':id_usuario', $id_usuario);
            $stmtVoto->bindParam(':gremio_id', $gremio_id);
            $stmtVoto->execute();

            echo "Voto registrado com sucesso!";
        }
    } catch (PDOException $e) {
        echo "Erro ao registrar o voto: " . htmlspecialchars($e->getMessage());
    }
} else {
    echo "Por favor, selecione um grêmio para votar.";
}

$conn = null; // Fecha a conexão
?>
