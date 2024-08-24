<?php
session_start();
require_once('../conexao.php');

//Varificação se o usuario está logado
require_once('../valida_login.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {  //Conexão com o banco de dados

    $CATEGORIA_NOME = $_POST['CATEGORIA_NOME'];
    $CATEGORIA_DESC = $_POST['CATEGORIA_DESC'];
    $CATEGORIA_ATIVO = $_POST['CATEGORIA_ATIVO'];

    try {
        $sql = "INSERT INTO CATEGORIA (CATEGORIA_NOME, CATEGORIA_DESC, CATEGORIA_ATIVO) VALUES (:CATEGORIA_NOME, :CATEGORIA_DESC, :CATEGORIA_ATIVO)";
        $stmt = $pdo->prepare($sql); // Preparação para não conter injeção de sql
        $stmt->bindParam(':CATEGORIA_NOME', $CATEGORIA_NOME, PDO::PARAM_STR);
        $stmt->bindParam(':CATEGORIA_DESC', $CATEGORIA_DESC, PDO::PARAM_STR);
        $stmt->bindParam(':CATEGORIA_ATIVO', $CATEGORIA_ATIVO, PDO::PARAM_INT);
        $stmt->execute(); //execulta os comando á cima

        echo "<div id='messagee'>Cadastrado com sucesso</div>";
    } catch (PDOException $erro) {
        echo "<div id='messagee'>Erro ao realizar o cadastro</div>" . $erro->getMessage() . "</p>";
    }
}

?>
<?php require_once('../layouts/inicio.php'); ?>

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
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="CATEGORIA_NOME" class="form-control-label">Nome da categoria </label>
                                    <input class="form-control" type="text" name="CATEGORIA_NOME" id="CATEGORIA_NOME" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="CATEGORIA_DESC" class="form-control-label">Descriçãoda categoria</label>
                                    <input class="form-control" type="text" name="CATEGORIA_DESC" id="CATEGORIA_DESC" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="CATEGORIA_ATIVO">Status</label>
                                    <select class="form-control" name="CATEGORIA_ATIVO" id="CATEGORIA_ATIVO">
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