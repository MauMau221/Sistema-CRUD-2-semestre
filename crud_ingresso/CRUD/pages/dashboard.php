<?php
session_start();

//Varificação se o usuario está logado
require_once('../valida_login.php');

require_once('../conexao.php');


// Trazer apenas a quantidade de produtos que estão com categoria e produto estoque  
try {
  $stmt_contagem_pd = $pdo->prepare("SELECT COUNT(1) AS total
    FROM PRODUTO AS p
    INNER JOIN CATEGORIA AS c ON c.CATEGORIA_ID = p.CATEGORIA_ID
    INNER JOIN PRODUTO_ESTOQUE as pe ON pe.PRODUTO_ID = p.PRODUTO_ID
  ");
  $stmt_contagem_pd->execute();
  $resultado_pd = $stmt_contagem_pd->fetch(PDO::FETCH_ASSOC);
  $contagem_produtos = $resultado_pd['total'];
} catch (PDOException $erro) {
  echo "Erro " . $erro->getMessage();
}

//Trazer adms
try {
  $stmt_contagem_adm = $pdo->prepare("SELECT COUNT(*) total
    FROM ADMINISTRADOR
  ");
  $stmt_contagem_adm->execute();
  $resultado_adm = $stmt_contagem_adm->fetch(PDO::FETCH_ASSOC);
  $contagem_adms = $resultado_adm['total'];
} catch (PDOException $erro) {
  echo "Erro " . $erro->getMessage();
}

//Trazer categorias
try {
  $stmt_contagem_ctg = $pdo->prepare("SELECT COUNT(*) total
    FROM CATEGORIA
  ");
  $stmt_contagem_ctg->execute();
  $resultado_ctg = $stmt_contagem_ctg->fetch(PDO::FETCH_ASSOC);
  $contagem_ctgs = $resultado_ctg['total'];
} catch (PDOException $erro) {
  echo "Erro " . $erro->getMessage();
}

?>
<?php require_once('../layouts/inicio.php'); ?>

<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white">Pages</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Dashboard</li>
      </ol>
      <h6 class="font-weight-bolder text-white mb-0">Dashboard</h6>
    </nav>
    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 justify-content-end" id="navbar">
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
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Produtos</p>
                <h5 class="font-weight-bolder">
                  <?php echo $contagem_produtos;?>
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Administradores</p>
                <h5 class="font-weight-bolder">
                  <?php echo $contagem_adms; ?>
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Categorias</p>
                <h5 class="font-weight-bolder">
                  <?php echo $contagem_ctgs; ?>
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  /* Ativar a class de ativo no menu de navegação */ 
  let navegaa = document.getElementById('nevega1');
  navegaa.classList.add('active');
</script>
<?php require_once('../layouts/fim.php'); ?>