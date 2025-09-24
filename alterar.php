<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: acesso.php');
    exit;
}

// Obtém os dados do usuário logado
$id_usuario = $_SESSION['id_usuario'] ?? null;

// Verifica se o ID do usuário está na sessão
if ($id_usuario === null) {
    echo 'ID do Usuário não encontrado na sessão.';
    exit;
}

// Inicializa a variável userData
$userData = [];

// Carregar dados do usuário
try {
    $sql = "SELECT id_usuario, nome, rm, tipo FROM usuarios WHERE id_usuario = :id_usuario";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "<div class='alert alert-warning'>Nenhum usuário encontrado com este ID: $id_usuario</div>";
        exit;
    }
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Erro ao carregar dados do usuário: " . htmlspecialchars($e->getMessage()) . "</div>";
    exit;
}

// Atualiza os dados do usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $rm = intval($_POST['rm']);

    // Validação básica
    if (empty($nome) || empty($rm)) {
        echo "<div class='alert alert-danger'>Por favor, preencha todos os campos.</div>";
    } else {
        try {
            $updateSql = "UPDATE usuarios SET nome = :nome, rm = :rm WHERE id_usuario = :id_usuario";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bindParam(':nome', $nome);
            $updateStmt->bindParam(':rm', $rm);
            $updateStmt->bindParam(':id_usuario', $id_usuario);
            $updateStmt->execute();

            echo "<div class='alert alert-success'>Dados atualizados com sucesso!</div>";
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Erro ao atualizar dados: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Dados Pessoais</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Alterar Dados Pessoais</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" class="form-control"
                    value="<?php echo htmlspecialchars($userData['nome']); ?>" required>
            </div>
            <div class="form-group">
                <label for="rm">RM</label>
                <input type="number" id="rm" name="rm" class="form-control"
                    value="<?php echo htmlspecialchars($userData['rm']); ?>" required>
            </div>
            <div class="form-group">
                <label for="senha">Nova Senha (deixe em branco para manter a atual)</label>
                <input type="password" id="senha" name="senha" class="form-control">
            </div>

            <button type="submit" class="btn btn-success">Salvar Alterações</button>
            <a href="perfil1.php" class="btn btn-dark">Voltar</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>