<?php
$servername = "localhost";
$username = "root";
$password = ""; // Coloque a senha se houver
$dbname = "gremio"; // Nome do banco de dados

try {
    // Criação da conexão PDO
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Definindo o modo de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Caso ocorra erro na conexão
    echo 'Erro de conexão: ' . $e->getMessage();
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Configurações adicionais
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>