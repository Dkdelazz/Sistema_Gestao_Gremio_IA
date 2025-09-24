<?php
// Inclui o arquivo de conexão
include 'conexao.php';

// Captura os dados do formulário
$nome = $_POST['nome'];
$serie = $_POST['serie'];
$email = $_POST['email'];
$mensagem = $_POST['mensagem'];
$data = date('Y-m-d H:i:s'); // Data e hora atuais

try {
    // Prepara a consulta
    $sql = "INSERT INTO duvida (nome, serie, email, mensagem, data) VALUES (:nome, :serie, :email, :mensagem, :data)";
    $stmt = $conn->prepare($sql);
    
    // Bind dos parâmetros
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':serie', $serie);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mensagem', $mensagem);
    $stmt->bindParam(':data', $data);

    // Executa a consulta
    $stmt->execute();

    echo "Mensagem enviada com sucesso!";
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

// Fecha a conexão
$conn = null;
?>
