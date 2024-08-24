<?php
session_start();

// Importa a configuração de conexão com o banco de dados.
require_once('../conexao.php');

// Valida login
require_once('../valida_login.php');

// Bloco de consulta para buscar categorias.
try {
    $stmt_categoria = $pdo->prepare("SELECT * FROM CATEGORIA");
    $stmt_categoria->execute();
    $categoria = $stmt_categoria->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $erro) {
    echo "<div id='messagee'>Erro ao buscar categoria " . $erro->getMessage() . "</div>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];
    $desconto = $_POST['desconto'];
    $categoria_id = $_POST['categoria'];
    $status = $_POST['status'];
    $imagens = $_POST['imagem_url'];

    try {
        $sql_produto = "INSERT INTO PRODUTO
            (
                PRODUTO_NOME, 
                PRODUTO_DESC, 
                PRODUTO_PRECO,
                PRODUTO_DESCONTO,
                CATEGORIA_ID,
                PRODUTO_ATIVO
            ) VALUES (
                :PRODUTO_NOME,
                :PRODUTO_DESC, 
                :PRODUTO_PRECO, 
                :PRODUTO_DESCONTO, 
                :CATEGORIA_ID, 
                :PRODUTO_ATIVO
            )";

        //Preparação para não conter injeção de sql.
        $stmt = $pdo->prepare($sql_produto);
        $stmt->bindParam(':PRODUTO_NOME', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':PRODUTO_DESC', $descricao, PDO::PARAM_STR);
        $stmt->bindParam(':PRODUTO_PRECO', $preco, PDO::PARAM_STR);
        $stmt->bindParam(':PRODUTO_DESCONTO', $desconto, PDO::PARAM_STR);
        $stmt->bindParam(':CATEGORIA_ID', $categoria_id, PDO::PARAM_STR);
        $stmt->bindParam(':PRODUTO_ATIVO', $status, PDO::PARAM_INT);

        //Execulta os comando á cima
        $stmt->execute();
        //Pegando o ID do produto inserido.
        $produto_id = $pdo->lastInsertId();

        //Inserindo imagens no banco.
        foreach ($imagens as $imagem => $imagem_url) {
            $sql_imagem = "INSERT INTO PRODUTO_IMAGEM 
            (
                IMAGEM_URL,
                IMAGEM_ORDEM,
                PRODUTO_ID
            ) VALUES (
                :IMAGEM_URL,
                :IMAGEM_ORDEM,
                :PRODUTO_ID
            )";

            $stmt_imagem = $pdo->prepare($sql_imagem);
            $stmt_imagem->bindParam(':IMAGEM_URL', $imagem_url, PDO::PARAM_STR);
            $stmt_imagem->bindParam(':IMAGEM_ORDEM', $imagem_url, PDO::PARAM_INT);
            $stmt_imagem->bindParam(':PRODUTO_ID', $produto_id, PDO::PARAM_INT);
            $stmt_imagem->execute();
        }

        //Inserindo estoque
        $sql_estoque = "INSERT INTO PRODUTO_ESTOQUE 
        (
            PRODUTO_ID,
            PRODUTO_QTD
        ) VALUES (
            :PRODUTO_ID,
            :PRODUTO_QTD
        )";

        $stmt_estoque = $pdo->prepare($sql_estoque);
        $stmt_estoque->bindParam(':PRODUTO_ID', $produto_id, PDO::PARAM_INT);
        $stmt_estoque->bindParam(':PRODUTO_QTD', $quantidade, PDO::PARAM_INT);
        $stmt_estoque->execute();

        echo "<div id='messagee'>Cadastrado com sucesso</div>";
    } catch (PDOException $erro) {
        echo "<div id='messagee'>Erro ao realizar o cadastro</div>" . $erro->getMessage() . "</p>";
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <link rel="stylesheet" href="../assets/css/mensagem.css">
    <script src="../js/javinha.js"></script>
    <title>
        Cadastrar Produto
    </title>
    <script>
        // Adiciona um novo campo de imagem URL.
        function adicionarImagem() {
            const containerImagens = document.getElementById('containerImagens');
            const novoInput = document.createElement('input');
            novoInput.type = 'text';
            novoInput.name = 'imagem_url[]';
            novoInput.className = 'form-control';
            containerImagens.appendChild(novoInput);
        }
    </script>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.0.4" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-primary position-absolute w-100"></div>
    <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html " target="_blank">
                <img src="../assets/img/logobravo.png" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-1 font-weight-bold">Bravo Ticket</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link " href="../pages/dashboard.php">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../pages/listar_produto.php">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Produtos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../pages/listar_admin.php">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-copy-04 text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Administradores</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../pages/listar_categoria.php">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-copy-04 text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Categoria</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
    <main class="main-content position-relative border-radius-lg ">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Editar produto</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Editar Produto</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <div class="input-group">
                            <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" placeholder="Buscar Produto...">
                        </div>
                    </div>
                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <a href="../logout.php" class="nav-link text-white font-weight-bold px-0">
                                <i class="fa fa-user me-sm-1"></i>
                                <span class="d-sm-inline d-none">Logout</span>
                            </a>
                        </li>
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line bg-white"></i>
                                    <i class="sidenav-toggler-line bg-white"></i>
                                    <i class="sidenav-toggler-line bg-white"></i>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Cadastro de produto</p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div>
                                <form class="row" action="" method="post" enctype="multipart/form-data">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nome" class="form-control-label"> Nome</label>
                                            <input class="form-control" type="text" name="nome" id="nome" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="descricao" class="form-control-label">Descrição</label>
                                            <textarea class="form-control" name="descricao" id="descricao" required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="quantidade" class="form-control-label">Quantidade</label>
                                            <input class="form-control" type="number" name="quantidade" id="quantidade">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="categoria" class="form-control-label">Categoria</label>
                                            <select class="form-control" type="text" name="categoria" id="categoria">
                                                <?php foreach ($categoria as $categorias) { // Loop para preencher o dropdown de categorias. 
                                                ?>
                                                    <option class="form-control" value="<?= $categorias['CATEGORIA_ID'] ?>"><?= $categorias['CATEGORIA_NOME'] ?></option>
                                                <?php }; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="preco" class="form-control-label">Preço</label>
                                            <input class="form-control" type="number" name="preco" id="preco" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="desconto" class="form-control-label">Desconto</label>
                                            <input class="form-control" type="number" name="desconto" id="desconto" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <!-- UPDATE DE IMAGEM E SELEÇÃO POR LINK -->
                                            <label for="imagem_url" class="form-control-label">URL da Imagem</label>
                                                <div id="containerImagens">
                                                    <input class="form-control" type="text" name="imagem_url[]" placeholder="Maximo 500 caracteres" required id="imagem_url">
                                                    <p id="mensagem" style="display: none; color: red;">Preencha com um valor máximo de 500 caracteres.</p>
                                                </div>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="adicionarImagem()">Adicionar mais imagens</button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" name="status" id="status">
                                                <option value="1">Ativo</option>
                                                <option value="0">Inativo</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input class="btn btn-danger btn-sm ms-auto" type="submit" value="Cadastrar">
                                </form>
                            </div>
                            <hr class="horizontal dark">
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php require_once('../layouts/fim.php'); ?>
