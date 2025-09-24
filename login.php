<?php
include_once "conexao.php";

// Verificar se o formulário foi enviado
if (isset($_POST['bt_login'])) {
    // Verificar se 'rm' e 'senha' estão definidos
    if (isset($_POST['rm']) && isset($_POST['senha'])) {
        $rm = $_POST['rm'];
        $senha = $_POST['senha'];

        // Preparar a consulta SQL
        try {
            $consulta = $conn->prepare("SELECT * FROM usuarios WHERE rm = :rm");
            $consulta->bindValue(':rm', $rm);
            $consulta->execute();
            $row = $consulta->fetch(PDO::FETCH_ASSOC);

            // Verificar se o usuário foi encontrado
            if ($row && password_verify($senha, $row['senha'])) {
                // Iniciar a sessão
                session_start();

                // Definir variáveis de sessão
                $_SESSION['loggedin'] = true; // Corrigido
                $_SESSION['id_usuario'] = $row['id_usuario']; // Armazene o ID do usuário
                $_SESSION['rm'] = $row['rm']; // Armazene RM
                $_SESSION['nome'] = $row['nome']; // Armazene nome
                $_SESSION['tipo'] = $row['tipo']; // Armazene tipo

                // Redirecionar de acordo com o tipo de usuário
                if ($row['tipo'] == 1) {
                    header("Location: index.php");
                } elseif ($row['tipo'] == 2 || $row['tipo'] == 0) {
                    header("Location: index.php");
                }
                exit(); // Garantir que o script não continue
            } else {
                // Redirecionar com mensagem de erro
                echo "<script>
                alert('Erro no login! Verifique RM e/ou senha.');
                window.location.href = 'acesso.php';
                </script>";
            }
        } catch (PDOException $e) {
            echo "Erro na consulta: " . $e->getMessage();
        }
    } else {
        echo "<script>
        alert('Por favor, preencha todos os campos.');
        window.location.href = 'acesso.php';
        </script>";
    }
} else {
    // Redireciona se o formulário não foi enviado
    header("Location: acesso.php");
    exit();
}
