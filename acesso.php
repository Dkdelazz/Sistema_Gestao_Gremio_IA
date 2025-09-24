<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A diferença Grêmios</title>
    <!-- Bootstrap CSS -->
    <link rel="shortcut icon" href="img/favicon.png">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos CSS */
        body {
            background-color: #C0C0C0;
            color: #333;
            padding-top: 1px;
        }

        .header_section {
            background-color: #C0C0C0;
            border-bottom: 1px solid #000;
            padding: 10px;
            color: #333;
        }

        .footer_section {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
            width: 100%;
        }

        .content-section {
            background-color: #FFFFFF;
            border: 1px solid #CCCCCC;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            color: #333;
        }

        .centered-box {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #C0C0C0;
            /* Cor de fundo leve para destacar a caixa */
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

    <br>

    <div class="centered-box">
        <div class="box">
            <!-- Fechar caixa com botão de fechamento -->
            <h1 class="mt-4" align="center">Acesso</h1>
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="rm">Rm:</label>
                    <input class="form-control" type="number" id="rm" placeholder="Digite o seu rm" name="rm"
                        required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input class="form-control" type="password" id="senha" placeholder="Digite sua senha" name="senha"
                        required>
                </div>
                <!-- Container para centralizar os botões -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-secondary btn-lg" name="bt_login">Login</button>
                    <button type="reset" class="btn btn-secondary btn-lg ml-2">Cancelar</button>
                </div>
            </form>
            <!-- Link para a página de cadastro -->
            <div class="mt-3 text-center">
                <p>Não tem uma conta? <a href="cadastro.php" class="btn btn-link">Cadastre-se aqui</a></p>
            </div>
        </div>
    </div>



    <br>

    <footer class="footer_section">
        <div class="container">
            <h5 class="text-center text-light">&copy; A diferença para Grêmios Estudantis Todos os Direitos Reservados
                2024.</h5>
        </div>
    </footer>

    <!-- Scripts -->
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