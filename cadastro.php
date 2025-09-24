<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <!-- Bootstrap CSS -->
    <link rel="shortcut icon" href="img/favicon.png">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #C0C0C0;
            color: #333;
            padding-top: 1px;
        }

        .centered-box {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #C0C0C0;
        }

        .box {
            background: #FFFFFF;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.7);
            max-width: 500px;
            width: 100%;
            position: relative;
        }

        .box h1 {
            margin-top: 0;
        }
    </style>
</head>

<body>
    <div class="centered-box">
        <div class="box">
            <h1 class="mt-4 text-center">Cadastre-se</h1>

            <?php
            include "conexao.php";

            if (isset($_POST["bt_cadastrar"])) {
                $nome = $_POST["nome"];
                $rm = $_POST["rm"];
                $senha = $_POST["senha"];

                // Cria o hash da senha
                $hash = password_hash($senha, PASSWORD_DEFAULT);

                try {
                    $sql = $conn->prepare("INSERT INTO usuarios (nome, rm, senha) VALUES (:nome, :rm, :hash)");

                    $sql->bindValue(':nome', $nome);
                    $sql->bindValue(':rm', $rm);
                    $sql->bindValue(':hash', $hash);

                    $sql->execute();

                    echo "<div class='alert alert-success'>Cadastro realizado com sucesso!</div>";
                } catch (PDOException $erro) {
                    echo "<div class='alert alert-danger'>Erro: " . htmlspecialchars($erro->getMessage()) . "</div>";
                }
            }
            ?>

            <form id="registrationForm" method="POST" action="">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input class="form-control" type="text" id="nome" placeholder="Digite o seu nome" name="nome"
                        required>
                </div>
                <div class="form-group">
                    <label for="rm">RM:</label>
                    <input class="form-control" type="number" id="rm" placeholder="Digite o seu RM" name="rm" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input class="form-control" type="password" id="senha" placeholder="Digite a sua senha" name="senha"
                        required>
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-secondary btn-lg" name="bt_cadastrar">Cadastrar</button>
                    <button type="reset" class="btn btn-secondary btn-lg ml-2">Limpar</button>
                </div>
            </form>

            <div class="mt-3 text-center">
                <p>Já tem uma conta? <a href="acesso.php" class="btn btn-link">Faça login aqui</a></p>
            </div>
        </div>
    </div>

    <!-- JavaScript (Opcional) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>
</body>

</html>