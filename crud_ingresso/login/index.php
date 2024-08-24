<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../crud_ingresso/assets/img/logo/letra-b.png" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <title>Ingressos</title>
</head>

<body>
  <!-- Card com E-mail e Senha -->
  <div class="card card border-warning mb-3 card text-center position-absolute top-50 start-50 translate-middle" style="width: 18rem;">
    <div class="card-body">
      <img src="Logotipo_bravo.svg" class="card-img-top" alt="...">
      <h6 class="card-subtitle p-2 text-muted">Faça seu login</h6>
        <form action="../CRUD/processa_login.php" class="validacao" novalidate method="post">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" name="nome" id="nome" placeholder="name@example.com" required>
            <label for="nome">Login</label>
            <div class="invalid-feedback">Preencha com um login válido.</div>
          </div>
          <div class="form-floating">
            <input type="password" class="form-control" name="senha" id="senha" placeholder="Senha" required>
            <label for="senha">Senha</label>
            <div class="invalid-feedback">Preencha com uma senha válida.</div>
            <button type="submit" class="btn btn-primary btn-lg p-2 g-col-6">Entrar</button>
          </div>
          <?php
          if (isset($_GET['erro'])) {
            echo '<p style = "color:red;" > Nome de usuario ou senha incorretos</p>';
          }
          ?>
        </form>
    </div>
  </div>
  </div>

  <!-- Scripts de ações do Boostrap e validação dos campos digitados -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  <script src="../assets/script/validacao.js"></script>
</body>

</html>