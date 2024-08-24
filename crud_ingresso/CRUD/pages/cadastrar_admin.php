<?php
session_start();
require_once('../conexao.php');

//Varificação se o usuario está logado
require_once('../valida_login.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {  //Conexão com o banco de dados

    $ADM_NOME = $_POST['ADM_NOME'];
    $ADM_EMAIL = $_POST['ADM_EMAIL'];
    $ADM_SENHA = $_POST['ADM_SENHA'];
    $ADM_ATIVO = $_POST['ADM_ATIVO'];

    try {
        $sql = "INSERT INTO ADMINISTRADOR (ADM_NOME, ADM_EMAIL, ADM_SENHA, ADM_ATIVO) VALUES (:ADM_NOME, :ADM_EMAIL, :ADM_SENHA, :ADM_ATIVO)";
        $stmt = $pdo->prepare($sql); // Preparação para não conter injeção de sql
        $stmt->bindParam(':ADM_NOME', $ADM_NOME, PDO::PARAM_STR);
        $stmt->bindParam(':ADM_EMAIL', $ADM_EMAIL, PDO::PARAM_STR);
        $stmt->bindParam(':ADM_SENHA', $ADM_SENHA, PDO::PARAM_STR);
        $stmt->bindParam(':ADM_ATIVO', $ADM_ATIVO, PDO::PARAM_STR);
        $stmt->execute(); //execulta os comando á cima

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
    <link href="../assets/css/mensagem.css" rel="stylesheet">
    <script src="../js/javinha.js"></script>
    <title>
        Cadastrar Administradores
    </title>
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
            <a class="navbar-brand m-0" href="dashboard.php" target="_blank">
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
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Editar administrador</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Editar Administradores</h6>
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
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Cadastro de admin</p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ADM_NOME" class="form-control-label"> Usuario adiministrador</label>
                                            <input class="form-control" type="text" name="ADM_NOME" id="ADM_NOME" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ADM_EMAIL" class="form-control-label"> Email</label>
                                            <input class="form-control" type="text" name="ADM_EMAIL" id="ADM_EMAIL" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ADM_SENHA" class="form-control-label">Senha</label>
                                            <input class="form-control" type="password" name="ADM_SENHA" id="ADM_SENHA" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ADM_ATIVO">Status</label>
                                            <select class="form-control" name="ADM_ATIVO" id="ADM_ATIVO">
                                                <option value="1">Ativo</option>
                                                <option value="0">Inativo</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input class="btn btn-danger btn-sm ms-auto" type="submit" value="Cadastrar">
                                </form>
                                <hr class="horizontal dark">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php require_once('../layouts/fim.php'); ?>
