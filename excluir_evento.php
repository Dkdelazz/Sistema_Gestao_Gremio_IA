<?php
session_start();
require 'conexao.php'; // Inclua a conexão com o banco de dados

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['loggedin']) || $_SESSION['tipo'] !== 1) {
    header("Location: acesso.php"); // Redireciona se não for admin
    exit;
}

// Obtém o ID do evento
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Verifica se o ID é válido
if ($id > 0) {
    // Busca o evento para obter a imagem
    $stmt = $conn->prepare("SELECT imagem FROM eventos WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($event) {
        // Exclui o evento do banco de dados
        $stmt = $conn->prepare("DELETE FROM eventos WHERE id = :id");
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            // Remove a imagem do servidor se existir
            if ($event['imagem'] && file_exists($event['imagem'])) {
                unlink($event['imagem']);
            }
            header("Location: eventos.php"); // Redireciona após a exclusão
            exit;
        } else {
            echo "Erro ao excluir o evento.";
        }
    } else {
        echo "Evento não encontrado.";
    }
} else {
    echo "ID inválido.";
}
?>
