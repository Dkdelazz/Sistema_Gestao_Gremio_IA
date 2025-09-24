<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
$loggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$isAdmin = isset($_SESSION['tipo']) && $_SESSION['tipo'] == 1;

// Define o nome de usuário, se estiver logado
$username = $loggedIn ? (isset($_SESSION['nome']) ? $_SESSION['nome'] : '') : '';

// Variável para armazenar mensagem de sucesso
$successMessage = '';

// Inclui o arquivo de conexão
include 'conexao.php'; // Certifique-se de que este arquivo existe e está correto

// Processa o formulário se enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

        $successMessage = "Mensagem enviada com sucesso!";
    } catch (PDOException $e) {
        $successMessage = "Erro: " . $e->getMessage();
    }

    // Fecha a conexão
    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A Diferença Grêmios</title>
    <!-- Bootstrap CSS -->
    <link rel="shortcut icon" href="img/favicon.png">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos CSS Combinados */

        /* Corpo da página */
        body {
            background-color: #f8f9fa;
            /* Cor de fundo mais clara */
            color: #000;
            padding-top: 2px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            /* Define altura mínima da tela */
        }

        /* Seção de cabeçalho */
        .header_section {
            background-color: #fff;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Imagens do carrossel */
        .carousel-inner img {
            height: 450px;
            object-fit: cover;
        }

        .carousel-item img {
            max-height: 400px;
            object-fit: cover;
        }

        /* Título de seção */
        .section-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-top: 30px;
            color: #000;
        }

        /* Seção de rodapé */
        .footer_section {
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 20px;
            width: 100%;
        }

        .footer_section h5 {
            margin: 0;
        }

        /* Seção de conteúdo */
        .content-section {
            background-color: #DCDCDC;
            border: 0.2px solid #C0C0C0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 10px;
            margin-top: 20px;
            flex: 1;
        }

        /* Diálogo/modal */
        .modal-dialog {
            max-width: 500px;
            margin: 1.75rem auto;
        }

        /* Botões de imagem */
        .btn-image {
            padding: 0;
            border: none;
            background: none;
        }

        .btn-image img {
            max-width: 53px;
            /* Tamanho fixo para a imagem */
            height: auto;
        }

        /* Itens de navegação */
        .navbar-nav .nav-item {
            margin-right: 15px;
            /* Espaçamento entre os itens do menu */
        }

        .navbar-light .navbar-nav .nav-link {
            color: #333;
        }

        .navbar-light .navbar-nav .nav-link:hover {
            color: #D3D3D3;
        }

        /* Cor personalizada para o item de boas-vindas */
        .navbar-light .navbar-nav .nav-link.welcome-message:hover {
            color: #000;
        }

        /* Botões de outline primário */
        .btn-outline-primary {
            border-color: #fff;
            color: #fff;
        }

        .btn-outline-primary:hover {
            background-color: #fff;
            color: #000;
        }

        /* Ícones de navegação do carrossel */
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: #333;
        }

        /* Itens de lista */
        .list-group-item {
            font-size: 1.1rem;
        }

        .list-group-item strong {
            color: #000;
        }

        /* Links */
        .container a {
            color: #333;
            text-decoration: none;
        }

        .container a:hover {
            text-decoration: underline;
        }

        /* Responsividade */
        @media (max-width: 576px) {
            .carousel-inner img {
                height: 300px;
            }
        }
    </style>
</head>

<body>
    <!-- Cabeçalho -->
    <header class="header_section">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #fff;">
                <a class="navbar-brand" href="#">
                    <img src="img/LOGO1.png" width="280" height="85" alt="nossalogo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#conteudoNavbarSuportado" aria-controls="conteudoNavbarSuportado" aria-expanded="false" aria-label="Alterna navegação">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="conteudoNavbarSuportado">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home <span class="sr-only">(página atual)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="eventos.php">Eventos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="orcamento.php">Orçamentos</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="gremios.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Grêmios</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="gremios.php">Grêmios</a>
                                <a class="dropdown-item" href="votacao.php">Votação</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contate.php">Contate-Nos</a>
                        </li>
                        <?php if ($isAdmin) : // Verifica se o usuário é admin 
                        ?>
                            <li class="nav-item">
                                <a class="nav-link" href="duvida.php">Dúvidas</a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a class="nav-item"></a>
                        </li>
                        <li>
                            <a class="nav-item">
                                <?php if ($loggedIn) : ?>
                                    <a href="perfil1.php" class="btn btn-image">
                                        <img src="img/perfil.png" width="53" height="55" alt="Perfil">
                                    </a>
                                <?php else : ?>
                                    <a href="acesso.php" class="btn btn-image">
                                        <img src="img/iconelogin" width="53" height="55" alt="Login / Cadastro">
                                    </a>
                                <?php endif; ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <p></p>
    <br>


    <!-- Contatos -->
    <div class="container content-section">
        <h2>Contate-Nos</h2>
        <p>Agradecemos o seu interesse neste trabalho. Estamos sempre abertos a sugestões, críticas construtivas e quaisquer dúvidas que você possa ter sobre a pesquisa apresentada. Seu feedback é muito importante para nós!</p>
        <p>Estamos comprometidos em promover um diálogo aberto e construtivo sobre o tema abordado neste trabalho. Não hesite em nos contatar para discutir qualquer aspecto da pesquisa ou para compartilhar suas ideias. Sua participação é fundamental para enriquecer este campo de estudo!</p>

        <h5>Formulário de Contato</h5>

        <?php if ($successMessage) : ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <!-- Alterado para enviar para a mesma página -->
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="serie">Série:</label>
                <input type="text" class="form-control" id="serie" name="serie" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="mensagem">Mensagem:</label>
                <textarea class="form-control" id="mensagem" name="mensagem" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-secondary">Enviar</button>
        </form>
    </div>

    <p></p>
    <br>

    <!-- Rodapé -->
    <footer class="footer_section">
        <div class="container">
            <h5 class="text-white text-center">&copy; A Diferença para Grêmios Estudantis Todos os Direitos Reservados 2024.</h5>
        </div>
    </footer>

    <!-- jQuery, Popper.js, e Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIqA7yT9O68j2Wj7+E5x1e6o4z7IbzZ+P0ZC6R3F4t4d2X2wZhl" crossorigin="anonymous"></script>
</body>

</html>