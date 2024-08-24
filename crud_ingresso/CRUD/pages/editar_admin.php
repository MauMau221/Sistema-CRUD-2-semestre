<?php
session_start();
require_once('../conexao.php');

//Varificação se o usuario está logado
require_once('../valida_login.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['ADM_ID'])) {
        $ADM_ID = $_GET['ADM_ID'];
        try {
            $stmt = $pdo->prepare(
                "SELECT *
            FROM ADMINISTRADOR
            WHERE ADM_ID = :ADM_ID"
            );
            $stmt->bindParam(':ADM_ID', $ADM_ID, PDO::PARAM_INT);
            $stmt->execute();
            $adms = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $erro) {
            echo "Erro: " . $erro->getMessage();
        }
    } else {
        header('Location: listar_admin.php');
        exit();
    }
}

// Se o formulário de edição foi submetido, a página é acessada via método POST, e o script tenta atualizar os detalhes do adms no banco de dados com as informações fornecidas no formulário.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ADM_ID = $_POST['ADM_ID'];
    $ADM_NOME = $_POST['ADM_NOME'];
    $ADM_EMAIL = $_POST['ADM_EMAIL'];
    $ADM_SENHA = $_POST['ADM_SENHA'];
    $ADM_ATIVO = $_POST['ADM_ATIVO'];

    try {
        $stmt = $pdo->prepare(
            "UPDATE ADMINISTRADOR
            SET ADM_NOME = :ADM_NOME,
                ADM_EMAIL = :ADM_EMAIL, 
                ADM_SENHA = :ADM_SENHA, 
                ADM_ATIVO = :ADM_ATIVO 
          WHERE ADM_ID = :ADM_ID"
        );
        $stmt->bindParam(':ADM_ID', $ADM_ID, PDO::PARAM_INT);
        $stmt->bindParam(':ADM_NOME', $ADM_NOME, PDO::PARAM_STR);
        $stmt->bindParam(':ADM_EMAIL', $ADM_EMAIL, PDO::PARAM_STR);
        $stmt->bindParam(':ADM_SENHA', $ADM_SENHA, PDO::PARAM_STR);
        $stmt->bindParam(':ADM_ATIVO', $ADM_ATIVO, PDO::PARAM_STR);
        $stmt->execute();

        /*Parametro para mensagem de sucesso através de GET */
        header('Location: listar_admin.php?update=success');
        exit();
    } catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
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
                            <p class="mb-0">Editar Administrador</p>
                            <a href="deletar_admin.php?ADM_ID=<?php echo $adms['ADM_ID']; ?>" class="btn btn-danger btn-sm ms-auto">Delete</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <form action="editar_admin.php" method="post">
                                <input type="hidden" name="ADM_ID" value="<?php echo $adms['ADM_ID']; ?>">
                                <!-- Essa linha cria um campo de entrada (input) oculto no formulário. Um campo de entrada oculto é usado quando você quer incluir um dado no formulário que não precisa ser visível ou editável pelo usuário, mas que precisa ser enviado junto com os outros dados quando o formulário é submetido. -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ADM_NOME" class="form-control-label">Usuario</label>
                                        <input class="form-control" type="text" name="ADM_NOME" id="ADM_NOME" value="<?php echo $adms['ADM_NOME']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ADM_EMAIL" class="form-control-label">Usuario</label>
                                        <input class="form-control" type="text" name="ADM_EMAIL" id="ADM_EMAIL" value="<?php echo $adms['ADM_EMAIL']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ADM_SENHA" class="form-control-label">Senha</label>
                                        <input class="form-control" type="password" name="ADM_SENHA" id="ADM_SENHA" placeholder="Nova senha">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="ADM_ATIVO">Status</label>
                                    <select class="form-control" name="ADM_ATIVO" id="ADM_ATIVO" value="<?php $adms['ADM_ATIVO'] ?>">
                                        <option value="1">Ativo</option>
                                        <option value="0">Inativo</option>
                                    </select>
                                </div>
                                <input class="btn btn-danger btn-sm ms-auto" type="submit" value="Atualizar">
                            </form>
                        </div>
                            <hr class="horizontal dark">
                    </div>
                </div>
            </div>
        </div>
    </div>
        <script>
            document.querySelector("#ADM_ATIVO").value = <?php echo $adms['ADM_ATIVO']  ?>;
        </script>
        <script>
            /* Ativar a class de ativo no menu de navegação */
            let navegaa = document.getElementById('nevega3');
            navegaa.classList.add('active');
        </script>
        <?php require_once('../layouts/fim.php'); ?>
