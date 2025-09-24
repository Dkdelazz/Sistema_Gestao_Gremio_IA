<?php
session_start();
require_once("conexao.php");

// Verifica se o usuário está logado e se é um administrador
$loggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$isAdmin = isset($_SESSION['tipo']) && $_SESSION['tipo'] === 1;

if (!$loggedIn || !$isAdmin) {
    header('Location: acesso.php'); // Redireciona se não estiver logado ou não for admin
    exit;
}

// Obtém o ID do usuário a ser editado
$id_usuario = $_GET['id'] ?? null;

if ($id_usuario === null) {
    echo "<div class='alert alert-danger'>ID do usuário não especificado.</div>";
    exit;
}

// Inicializa a variável para armazenar os dados do usuário
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
}

// Atualiza os dados do usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $rm = intval($_POST['rm']);
    $tipo = intval($_POST['tipo']);
    $senha = trim($_POST['senha']); // Captura a nova senha, se fornecida

    // Validação básica
    if (empty($nome) || empty($rm)) {
        echo "<div class='alert alert-danger'>Por favor, preencha todos os campos obrigatórios.</div>";
    } else {
        try {
            $updateSql = "UPDATE usuarios SET nome = :nome, rm = :rm, tipo = :tipo";
            
            // Verifica se a senha foi preenchida
            if (!empty($senha)) {
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT); // Criptografa a nova senha
                $updateSql .= ", senha = :senha"; // Adiciona a coluna da senha
            }

            $updateSql .= " WHERE id_usuario = :id_usuario";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bindParam(':nome', $nome);
            $updateStmt->bindParam(':rm', $rm);
            $updateStmt->bindParam(':tipo', $tipo);
            $updateStmt->bindParam(':id_usuario', $id_usuario);

            // Se a senha foi preenchida, adiciona o parâmetro
            if (!empty($senha)) {
                $updateStmt->bindParam(':senha', $senhaHash);
            }

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
    <title>Editar Usuário</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Usuário</h2>
        <form method="POST">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?php echo htmlspecialchars($userData['nome']); ?>" required>
            </div>
            <div class="form-group">
                <label for="rm">RM</label>
                <input type="number" id="rm" name="rm" class="form-control" value="<?php echo htmlspecialchars($userData['rm']); ?>" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo</label>
                <select id="tipo" name="tipo" class="form-control" required>
                    <option value="1" <?php echo ($userData['tipo'] == 1) ? 'selected' : ''; ?>>Admin</option>
                    <option value="0" <?php echo ($userData['tipo'] == 0) ? 'selected' : ''; ?>>Usuário Padrão</option>
                </select>
            </div>
            <div class="form-group">
                <label for="senha">Nova Senha (deixe em branco para manter a atual)</label>
                <input type="password" id="senha" name="senha" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Salvar Alterações</button>
            <a href="alterar_usuarios.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
