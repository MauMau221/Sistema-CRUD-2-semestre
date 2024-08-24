<?php
session_start();

//Conexão com banco de dados
require_once('../conexao.php');
//Varificação se o usuario está logado
require_once('../valida_login.php');

//Notificação de atualização do adm
if (isset($_GET['update']) && $_GET['update'] === 'success') {
  echo "<div id='messagee'>Administrador atualizado com sucesso!</div>";
}
//Notificação de inativação do adm
if (isset($_GET['update']) && $_GET['update'] === 'successdelete') {
  echo "<div id='messagee'>Administrador inativado com sucesso!</div>";
}

try {
  $stmt = $pdo->prepare("SELECT 
    ADM_ID,
    ADM_NOME,
    ADM_EMAIL,
    ADM_ATIVO 
    FROM ADMINISTRADOR
    ");
  $stmt->execute();
  $administrador = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $erro) {
  echo "Erro " . $erro->getMessage();
}


//Trazer apenas ATIVOS 

if (isset($_GET['ativo'])){
  try {
    $administrador = $_GET['ativo'];
    $stmt = $pdo->prepare("SELECT 
      ADM_ID,
      ADM_NOME,
      ADM_EMAIL,
      ADM_ATIVO 
      FROM ADMINISTRADOR
      WHERE ADM_ATIVO = 1
      ORDER BY ADM_ID DESC
      ");
    $stmt->execute();
    $administrador_ativo = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $erro) {
    echo "Erro " . $erro->getMessage();
  }
}

//Trazer apenas INATIVOS 

if (isset($_GET['inativo'])){
  try {
    $administrador = $_GET['inativo'];
    $stmt = $pdo->prepare("SELECT 
      ADM_ID,
      ADM_NOME,
      ADM_EMAIL,
      ADM_ATIVO 
      FROM ADMINISTRADOR
      WHERE ADM_ATIVO = 0
      ORDER BY ADM_ID DESC
      ");
    $stmt->execute();
    $administrador_inativo = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $erro) {
    echo "Erro " . $erro->getMessage();
  }
}

//Trazer buscas em administrador feitas pelo usuario
if (isset($_GET['busca'])){
  try {
    $pesquisa = $_GET['busca'];
    $stmt = $pdo->prepare("SELECT
      ADM_ID,
      ADM_NOME,
      ADM_EMAIL,
      ADM_ATIVO 
      FROM ADMINISTRADOR
      WHERE ADM_NOME LIKE '%$pesquisa%'
    ");
    $stmt->execute();
    $resultado_busca = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $erro) {
    echo "Erro " . $erro->getMessage();
  }
}

?>
<?php require_once('../layouts/inicio.php'); ?>

<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white">Pages</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Administrador</li>
      </ol>
      <h6 class="font-weight-bolder text-white mb-0">Administradores</h6>
    </nav>
    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
      <div class="ms-md-auto pe-md-3 d-flex align-items-center">
      <form action="">
          <div class="input-group">
            <input name="busca" value="<?php if(isset($_GET['busca'])) echo $_GET['busca'] ;?>" class="form-control" placeholder="Buscar administrador..." type="text"> 
            <button type="submit" class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></button>
          </div>
        </form>
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
    </div>
</nav>
<!-- End Navbar -->
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-body d-flex justify-content-between">
          <div>
            <h6 class="card-link">Administradores</h6>
          </div>
          <form action="" method="GET">
            <div class="col-md-6">
              <div class="btn-group" role="group">
                  <button type="submit" name="ativo" class="btn btn-primary btn-sm" value="ativo">Ativos</button>
                  <button type="submit" name="inativo" class="btn btn-danger btn-sm" value="inativo">Inativos</button>
              </div>
            </div>
          </form>
          <div>
            <a href="cadastrar_admin.php" class="card-link btn btn-danger btn-sm ms-auto">Cadastrar Admin</a>
          </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Nome</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Email</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Status</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Editar</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle">Inativar</th>
                </tr>
              </thead>

              <tbody>
                  <?php if(isset($_GET['busca'])){
                    $administrador = $resultado_busca;
                  } ?>
                    <?php if(isset($_GET['ativo'])){
                      $administrador = $administrador_ativo;
                    } ?>
                  <?php if(isset($_GET['inativo'])){
                    $administrador = $administrador_inativo;
                  } ?>
                <?php foreach ($administrador as $adms) { ?>
                  <tr>
                    <td class="d-none">
                      <?php echo $adms['ADM_ID']; ?>
                    </td>
                    <td class="align-middle text-center">
                      <?php echo $adms['ADM_NOME']; ?>
                    </td>
                    <td class="align-middle text-center">
                      <?php echo $adms['ADM_EMAIL']; ?>
                    </td>
                    <td class="align-middle text-center">
                      <?php
                      if ($adms['ADM_ATIVO'] == 0) {
                        echo '<span class="statusUser badge badge-sm bg-gradient-secondary">Inativo</span>';
                      } else {
                        echo '<span class="statusUser badge badge-sm bg-gradient-success">Ativo</span>';
                      };
                      ?>
                    </td>
                    <td class="align-middle text-center">
                      <a href="editar_admin.php?ADM_ID=<?php echo $adms['ADM_ID']; ?>" class="btn badge badge-sm bg-gradient-primary" data-toggle="tooltip" data-original-title="Edit user">
                        Edit
                      </a>
                    </td>
                    <td class="align-middle text-center">
                      <a href="deletar_admin.php?ADM_ID=<?php echo $adms['ADM_ID']; ?>" class="btn badge badge-sm bg-gradient-danger" data-toggle="tooltip" data-original-title="Edit user">
                        Inativar
                      </a>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</main>
<script>
  /* Ativar a class de ativo no menu de navegação */ 
  let navegaa = document.getElementById('nevega3');
  navegaa.classList.add('active');
</script>
    <?php require_once('../layouts/fim.php'); ?>