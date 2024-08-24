conexao.php

<?php 



/*
$host = 'localhost'; //Especifica o nome do host onde o banco de dados MySQL está hospedado. O valor "localhost" significa que o banco de dados está no mesmo servidor onde o código PHP está sendo executado. Se o banco de dados estiver em um servidor remoto, você fornece o endereço IP ou o nome de domínio desse servidor). 
$db   = 'base1'; //nome do banco de dados que se deseja conectar
$user = 'root'; //usuário do banco de dados
$pass = ''; //senha do banco de dados (não deixe espaço)
$charset = 'utf8mb4'; //Define o conjunto de caracteres usado para comunicação com o banco de dados
*/


$host = '144.22.157.228';
$db   = 'Alpha';
$user = 'A2';
$pass = 'A2';
$charset = 'utf8mb4';


$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo 'Conexão bem sucedida!';
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>

___________________________________________________________________________________________________________________





login.php

<?php
session_start();

// Se a variável de sessão com a mensagem de erro estiver definida
if(isset($_SESSION['mensagem_erro'])) {
    echo "<p class='error-message'>" . $_SESSION['mensagem_erro'] . "</p>"; // Exibe a mensagem de erro
    unset($_SESSION['mensagem_erro']); // Descarta a variável de sessão
}
?>

<!-- login.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Login do Administrador</title>
</head>
<body>

    <h2>Login do Administrador</h2>

    

    <form action="processa_login.php" method="post">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        <p>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
        <p>

        <input type="submit" value="Entrar">

        <?php 
            if (isset($_GET['erro'])) {
                echo '<p style="color: red;">Nome de usuário ou senha incorretos!</p>';
            }
        ?>

    </form>

</body>
</html>



_____________________________________________________________________________________________________________________________

processa_login.php

<?php
session_start(); // Inicia a sessão

// Tenta conectar com o banco e fazer o login
try {
    require_once('../config/conexao.php'); // Inclui o arquivo de configuração da conexão

    $nome = $_POST['nome'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM ADMINISTRADOR WHERE ADM_NOME = :nome AND ADM_SENHA = :senha AND ADM_ATIVO = 1"; 
    $query = $pdo->prepare($sql); //$query é um objeto PDOStatement que é criado ao preparar a consulta SQL  usando $pdo->prepare($sql). Um PDOStatement é um objeto em PHP que representa uma declaração SQL preparada. Esse objeto é usado em conjunto com a extensão PDO (PHP Data Objects) para executar consultas SQL no banco de dados de forma segura e eficiente. Essa preparação é feita para evitar injeção de SQL, que é uma ameaça comum à segurança dos aplicativos web

    //podemos vincular valores a esses espaços reservados usando o método bindParam ou bindValue. Isso permite que forneçamos dados específicos para a consulta antes de executá-la
    $query->bindParam(':nome', $nome, PDO::PARAM_STR); 
    $query->bindParam(':senha', $senha, PDO::PARAM_STR);
    $query->execute(); // Esta linha efetivamente executa a consulta SQL preparada. Após preparar a consulta e vincular os parâmetros, podemos executá-la usando o método execute(). Isso enviará a consulta ao banco de dados com os valores vinculados e, em seguida, podemos recuperar os resultados, se houver, usando métodos como fetch(), fetchAll(), etc.

    //rowCount() é um método de um objeto PDOStatement que é criado ao preparar a consulta SQL usando $pdo->prepare($sql); Ele retorna o número de linhas retornadas pela consulta SELECT
    if ($query->rowCount() > 0) { // verifica se a consulta SELECT encontrou pelo menos uma linha correspondente no banco de dados
        $_SESSION['admin_logado'] = true; //Se houver pelo menos uma linha, ele define a variável de sessão $_SESSION['admin_logado'] como true e redireciona o usuário para a página painel_admin.php
        header('Location: painel_admin.php'); 
        exit; // Adicionado para encerrar o script após o redirecionamento
    } else {
        $_SESSION['mensagem_erro'] = "NOME DE USUÁRIO OU SENHA INCORRETO";
        header('Location: login.php?erro');
        exit; // Adicionado para encerrar o script após o redirecionamento
    }
} catch (Exception $e) {
    // Armazena a mensagem de erro na sessão
    $_SESSION['mensagem_erro'] = "Erro de conexão: " . $e->getMessage();
    header('Location: login.php?erro');
    exit; // Adicionado para encerrar o script após o redirecionamento
}
//

?>


___________________________________________________________________________________________________________________________________


painel_admin.php

<?php
session_start(); // Iniciar a sessão

if (!isset($_SESSION['admin_logado'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Painel do Administrador</title>
</head>
<body>
    <h2>Bem-vindo, Administrador!</h2>
    <a href="cadastrar_administrador.php">
        <button>Cadastrar Administrador</button>
    </a>
    <a href="listar_administrador.php">
        <button>Listar Administradores</button>
    </a>
    <a href="cadastrar_produto.php">
        <button>Cadastrar Produto</button>
    </a>
    <a href="listar_produtos.php">
        <button>Listar Produtos</button>
    </a>
</body>
</html>


_________________________________________________________________________________________________________________________________________

cadastrar_administrador.php

<?php
// Inicia a sessão para gerenciamento do usuário.
session_start();

// Importa a configuração de conexão com o banco de dados.
require_once('../config/conexao.php');

// Verifica se o administrador está logado.
if (!isset($_SESSION['admin_logado'])) {
    header("Location:login.php");
    exit();
}



// Bloco que será executado quando o formulário for submetido.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pegando os valores do POST.
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $ativo = isset($_POST['ativo']) ? 1 : 0;
    
    // Inserindo administrador no banco.
    try {
        $sql = "INSERT INTO ADMINISTRADOR (ADM_NOME, ADM_EMAIL, ADM_SENHA,ADM_ATIVO) VALUES (:nome, :email, :senha, :ativo);";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
        $stmt->bindParam(':ativo', $ativo, PDO::PARAM_INT); 

        $stmt->execute(); // Adicionado para executar a instrução

        // Pegando o ID do produto inserido.
        $adm_id = $pdo->lastInsertId();

        

        echo "<p style='color:green;'>Administrador cadastrado com sucesso!</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erro ao cadastrar produto: " . $e->getMessage() . "</p>";
    }
}
?>

<!-- Início do código HTML -->
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Administrador</title>

</head>
<body>
<h2>Cadastrar Administrador</h2>
<form action="" method="post" enctype="multipart/form-data">
    <!-- Campos do formulário para inserir informações do administrador -->
    <label for="nome">Nome:</label>
    <input type="text" name="nome" id="nome" required>
    <p>
    <label for="email">Email:</label>
    <textarea name="email" id="email" required></textarea>
    <p>
    <label for="senha">Senha:</label>
    <input type="number" name="senha" id="senha" required>

    <!-- o ideal seria:
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required><br>

    <label for="senha">Senha:</label>
    <input type="password" name="senha" id="senha" required><br> -->

    <label for="ativo">Ativo:</label>
    <input type="checkbox" name="ativo" id="ativo" value="1" checked>
    <p>
    </div>
    <p>
    <button type="submit">Cadastrar Administrador</button>
</form>
</body>
</html>



_______________________________________________________________________________________________________________________________________

cadastrar_produto.php

<?php
// Inicia a sessão para gerenciamento do usuário.
session_start();

// Importa a configuração de conexão com o banco de dados.
require_once('../config/conexao.php');

// Verifica se o administrador está logado.
if (!isset($_SESSION['admin_logado'])) {
    header("Location:login.php");
    exit();
}

// Bloco de consulta para buscar categorias.
try {
    $stmt_categoria = $pdo->prepare("SELECT * FROM CATEGORIA");
    $stmt_categoria->execute();
    $categorias = $stmt_categoria->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>Erro ao buscar categorias: " . $e->getMessage() . "</p>";
}

// Bloco que será executado quando o formulário for submetido.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pegando os valores do POST.
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $categoria_id = $_POST['categoria_id'];
    $ativo = isset($_POST['ativo']) ? 1 : 0;
    $desconto = $_POST['desconto'];
    $imagens = $_POST['imagem_url'];

    // Inserindo produto no banco.
    try {
        $sql = "INSERT INTO PRODUTO (PRODUTO_NOME, PRODUTO_DESC, PRODUTO_PRECO, CATEGORIA_ID, PRODUTO_ATIVO, PRODUTO_DESCONTO) VALUES (:nome, :descricao, :preco, :categoria_id, :ativo, :desconto)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
        $stmt->bindParam(':preco', $preco, PDO::PARAM_STR);
        $stmt->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
        $stmt->bindParam(':ativo', $ativo, PDO::PARAM_INT);
        $stmt->bindParam(':desconto', $desconto, PDO::PARAM_STR);
        $stmt->execute();

        // Pegando o ID do produto inserido.
        $produto_id = $pdo->lastInsertId();

        // Inserindo imagens no banco.
        foreach ($imagens as $ordem => $url_imagem) {
            $sql_imagem = "INSERT INTO PRODUTO_IMAGEM (IMAGEM_URL, PRODUTO_ID, IMAGEM_ORDEM) VALUES (:url_imagem, :produto_id, :ordem_imagem)";
            $stmt_imagem = $pdo->prepare($sql_imagem);
            $stmt_imagem->bindParam(':url_imagem', $url_imagem, PDO::PARAM_STR);
            $stmt_imagem->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
            $stmt_imagem->bindParam(':ordem_imagem', $ordem, PDO::PARAM_INT);
            $stmt_imagem->execute();
        }

        echo "<p style='color:green;'>Produto cadastrado com sucesso!</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erro ao cadastrar produto: " . $e->getMessage() . "</p>";
    }
}
?>

<!-- Início do código HTML -->
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produto</title>
    <script>
        // Adiciona um novo campo de imagem URL.
        function adicionarImagem() {
            const containerImagens = document.getElementById('containerImagens');
            const novoInput = document.createElement('input');
            novoInput.type = 'text';
            novoInput.name = 'imagem_url[]';
            containerImagens.appendChild(novoInput);
        }
    </script>
</head>
<body>
<h2>Cadastrar Produto</h2>
<form action="" method="post" enctype="multipart/form-data">
    <!-- Campos do formulário para inserir informações do produto -->
    <label for="nome">Nome:</label>
    <input type="text" name="nome" id="nome" required>
    <p>
    <label for="descricao">Descrição:</label>
    <textarea name="descricao" id="descricao" required></textarea>
    <p>
    <label for="preco">Preço:</label>
    <input type="number" name="preco" id="preco" step="0.01" required>
    <p>
    <label for="desconto">Desconto:</label>
    <input type="number" name="desconto" id="desconto" step="0.01" required>
    <p>
    <label for="categoria_id">Categoria:</label>
    <select name="categoria_id" id="categoria_id" required>
        <?php 
            // Loop para preencher o dropdown de categorias.
            foreach ($categorias as $categoria): 
        ?>
        

            <option value="<?= $categoria['CATEGORIA_ID'] ?>"><?= $categoria['CATEGORIA_NOME'] ?></option>
        <?php endforeach; ?>
    </select>
    <p>
    <label for="ativo">Ativo:</label>
    <input type="checkbox" name="ativo" id="ativo" value="1" checked>
    <p>
    <!-- Área para adicionar URLs de imagens. -->
    <label for="imagem">Imagem URL:</label>
    <div id="containerImagens">
        <input type="text" name="imagem_url[]" required>
    </div>
    <button type="button" onclick="adicionarImagem()">Adicionar mais imagens</button>
    <p>
    <button type="submit">Cadastrar Produto</button>
</form>
</body>
</html>






_______________________________________________________________________________________________________________________________________


listar_administrador.php

<?php
session_start();
require_once('../config/conexao.php');

if (!isset($_SESSION['admin_logado'])) {
    header("Location:login.php");
    exit();
}
$administradores = []; // Inicializa como array vazio

try {
    $stmt = $pdo->prepare("SELECT ADMINISTRADOR.*  FROM ADMINISTRADOR");
    $stmt->execute();
    $administradores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>Erro ao listar administradores: " . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Listar Administradores</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .action-btn {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border: none;
            text-decoration: none;
            display: inline-block;
        }
        .action-btn:hover {
            background-color: #45a049;
        }
        .delete-btn {
            background-color: #f44336;
        }
        .delete-btn:hover {
            background-color: #da190b;
        }
    </style>
</head>
<body>
<h2>Administradores Cadastrados</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Senha</th>
        <th>Ativo</th>
        <th>Ações</th>
        <!-- <th>Imagem</th> -->
    </tr>
    <?php foreach($administradores as $adm): ?>
    <tr>
        <td><?php echo $adm['ADM_ID']; ?></td>
        <td><?php echo $adm['ADM_NOME']; ?></td>
        <td><?php echo $adm['ADM_EMAIL']; ?></td>
        <td><?php echo $adm['ADM_SENHA']; ?></td>
        <td><?php echo ($adm['ADM_ATIVO'] == 1 ? 'Sim' : 'Não'); ?></td>
        
        <td>
            <a href="editar_administrador.php?id=<?php echo $adm['ADM_ID']; ?>"class="action-btn">Editar</a>
            <a href="excluir_administrador.php?id=<?php echo $adm['ADM_ID']; ?>" class="action-btn delete-btn">Excluir</a>
        </td>
</tr>

    <?php endforeach; ?>
</table>
    <p></p>
    <a href="painel_admin.php">Voltar ao Painel do Administrador</a>
</body>
</html>


________________________________________________________________________________________________________________________________

editar_administrador.php


<?php
session_start();
require_once('../config/conexao.php');

if (!isset($_SESSION['admin_logado'])) {
    header("Location:login.php");
    exit();
}

$adm_id = $_GET['id'];

// Busca as informações do administrador.
$stmt_adm = $pdo->prepare("SELECT * FROM ADMINISTRADOR WHERE ADM_ID = :adm_id");
$stmt_adm->bindParam(':adm_id', $adm_id, PDO::PARAM_INT);
$stmt_adm->execute();
$adm = $stmt_adm->fetch(PDO::FETCH_ASSOC);




if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Atualizando as informações do administrador.
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $ativo = isset($_POST['ativo']) ? 1 : 0;

    try {
        $stmt_update_adm = $pdo->prepare("UPDATE ADMINISTRADOR SET ADM_NOME = :nome, ADM_EMAIL = :email, ADM_SENHA = :senha,  ADM_ATIVO = :ativo  WHERE ADM_ID = :ADM_id");
        $stmt_update_adm->bindParam(':nome', $nome);
        $stmt_update_adm->bindParam(':email', $email);
        $stmt_update_adm->bindParam(':senha', $senha);
        $stmt_update_adm->bindParam(':ativo', $ativo);
        $stmt_update_adm->bindParam(':adm_id' ,$adm_id);
        $stmt_update_adm->execute();

        echo "<p style='color:green;'>Administrador atualizado com sucesso!</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erro ao atualizar administrador: " . $e->getMessage() . "</p>";
    }
}

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Administrador</title>
</head>
<body>
<h2>Editar Administrador</h2>
<form action="" method="post" enctype="multipart/form-data">
    <label for="nome">Nome:</label>
    <input type="text" name="nome" id="nome" value="<?= $adm['ADM_NOME'] ?>" required>
    <p>
    <label for="email">Email:</label>
    <input type="text" name="email" id="email" value=" <?= $adm['ADM_EMAIL'] ?>" required>
    <p>
    <label for="senha">Senha:</label>
    <input type="text" name="senha" id="senha" value=" <?= $adm['ADM_SENHA'] ?>" required>
    <p>
    <p>
    <label for="ativo">Ativo:</label>
    <input type="checkbox" name="ativo" id="ativo" value="1" <?= $adm['ADM_ATIVO'] ? 'checked' : '' ?>>
    <p>
    <p>
    <button type="submit">Atualizar Administrador</button>
</form>
</body>
</html>


_____________________________________________________________________________________________________________________________________


listar_produtos.php

<?php
session_start();
require_once('../config/conexao.php');

if (!isset($_SESSION['admin_logado'])) {
    header("Location:login.php");
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT PRODUTO.*, CATEGORIA.CATEGORIA_NOME, PRODUTO_IMAGEM.IMAGEM_URL
                           FROM PRODUTO 
                           JOIN CATEGORIA ON PRODUTO.CATEGORIA_ID = CATEGORIA.CATEGORIA_ID 
                           LEFT JOIN PRODUTO_IMAGEM ON PRODUTO.PRODUTO_ID = PRODUTO_IMAGEM.PRODUTO_ID");
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>Erro ao listar produtos: " . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Listar Produtos</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .action-btn {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border: none;
            text-decoration: none;
            display: inline-block;
        }
        .action-btn:hover {
            background-color: #45a049;
        }
        .delete-btn {
            background-color: #f44336;
        }
        .delete-btn:hover {
            background-color: #da190b;
        }
    </style>
</head>
<body>
<h2>Produtos Cadastrados</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Descrição</th>
        <th>Preço</th>
        <th>Categoria</th>
        <th>Ativo</th>
        <th>Desconto</th>
        <th>Imagem</th>
        <th>Ações</th>
    </tr>
    <?php foreach($produtos as $produto): ?>
    <tr>
        <td><?php echo $produto['PRODUTO_ID']; ?></td>
        <td><?php echo $produto['PRODUTO_NOME']; ?></td>
        <td><?php echo $produto['PRODUTO_DESC']; ?></td>
        <td><?php echo $produto['PRODUTO_PRECO']; ?></td>
        <td><?php echo $produto['CATEGORIA_NOME']; ?></td>
        <td><?php echo ($produto['PRODUTO_ATIVO'] == 1 ? 'Sim' : 'Não'); ?></td>
        <td><?php echo $produto['PRODUTO_DESCONTO']; ?></td>
        <td><img src="<?php echo $produto['IMAGEM_URL']; ?>" alt="<?php echo $produto['PRODUTO_NOME']; ?>" width="50"></td>
        <td>
            <a href="editar_produto.php?id=<?php echo $produto['PRODUTO_ID']; ?>" class="action-btn">Editar</a>
            <a href="excluir_produto.php?id=<?php echo $produto['PRODUTO_ID']; ?>" class="action-btn delete-btn">Excluir</a>
        </td>
</tr>

    <?php endforeach; ?>
</table>
    <p></p>
    <a href="painel_admin.php">Voltar ao Painel do Administrador</a>
</body>
</html>


_____________________________________________________________________________________________________________________________________

editar_produto.php

<?php
session_start();
require_once('../config/conexao.php');

if (!isset($_SESSION['admin_logado'])) {
    header("Location:login.php");
    exit();
}

$produto_id = $_GET['id'];

// Busca as informações do produto.
$stmt_produto = $pdo->prepare("SELECT * FROM PRODUTO WHERE PRODUTO_ID = :produto_id");
$stmt_produto->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
$stmt_produto->execute();
$produto = $stmt_produto->fetch(PDO::FETCH_ASSOC);

// Busca as categorias.
$stmt_categoria = $pdo->prepare("SELECT * FROM CATEGORIA");
$stmt_categoria->execute();
$categorias = $stmt_categoria->fetchAll(PDO::FETCH_ASSOC);

// Busca as imagens do produto.
$stmt_img = $pdo->prepare("SELECT * FROM PRODUTO_IMAGEM WHERE PRODUTO_ID = :produto_id ORDER BY IMAGEM_ORDEM");
$stmt_img->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
$stmt_img->execute();
$imagens_existentes = $stmt_img->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Atualizando as URLs das imagens.
    if (isset($_POST['editar_imagem_url'])) {
        foreach ($_POST['editar_imagem_url'] as $imagem_id => $url_editada) {
            $stmt_update = $pdo->prepare("UPDATE PRODUTO_IMAGEM SET IMAGEM_URL = :url WHERE IMAGEM_ID = :imagem_id");
            $stmt_update->bindParam(':url', $url_editada, PDO::PARAM_STR);
            $stmt_update->bindParam(':imagem_id', $imagem_id, PDO::PARAM_INT);
            $stmt_update->execute();
        }
    }

    // Atualizando as informações do produto.
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $categoria_id = $_POST['categoria_id'];
    $ativo = isset($_POST['ativo']) ? "\x01" : "\x00";
    $desconto = $_POST['desconto'];

    try {
        $stmt_update_produto = $pdo->prepare("UPDATE PRODUTO SET PRODUTO_NOME = :nome, PRODUTO_DESC = :descricao, PRODUTO_PRECO = :preco, CATEGORIA_ID = :categoria_id, PRODUTO_ATIVO = :ativo, PRODUTO_DESCONTO = :desconto WHERE PRODUTO_ID = :produto_id");
        $stmt_update_produto->bindParam(':nome', $nome);
        $stmt_update_produto->bindParam(':descricao', $descricao);
        $stmt_update_produto->bindParam(':preco', $preco);
        $stmt_update_produto->bindParam(':categoria_id', $categoria_id);
        $stmt_update_produto->bindParam(':ativo', $ativo);
        $stmt_update_produto->bindParam(':desconto', $desconto);
        $stmt_update_produto->bindParam(':produto_id', $produto_id);
        $stmt_update_produto->execute();

        echo "<p style='color:green;'>Produto atualizado com sucesso!</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erro ao atualizar produto: " . $e->getMessage() . "</p>";
    }
}

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
</head>
<body>
<h2>Editar Produto</h2>
<form action="" method="post" enctype="multipart/form-data">
    <label for="nome">Nome:</label>
    <input type="text" name="nome" id="nome" value="<?= $produto['PRODUTO_NOME'] ?>" required>
    <p>
    <label for="descricao">Descrição:</label>
    <textarea name="descricao" id="descricao" required><?= $produto['PRODUTO_DESC'] ?></textarea>
    <p>
    <label for="preco">Preço:</label>
    <input type="number" name="preco" id="preco" step="0.01" value="<?= $produto['PRODUTO_PRECO'] ?>" required>
    <p>
    <label for="desconto">Desconto:</label>
    <input type="number" name="desconto" id="desconto" step="0.01" value="<?= $produto['PRODUTO_DESCONTO'] ?>" required>
    <p>
    <label for="categoria_id">Categoria:</label>
    <select name="categoria_id" id="categoria_id" required>
        <?php 
            foreach ($categorias as $categoria): 
                $selected = $produto['CATEGORIA_ID'] == $categoria['CATEGORIA_ID'] ? 'selected' : '';
        ?>
            <option value="<?= $categoria['CATEGORIA_ID'] ?>" <?= $selected ?>><?= $categoria['CATEGORIA_NOME'] ?></option>
        <?php endforeach; ?>
    </select>
    <p>
    <label for="ativo">Ativo:</label>
    <input type="checkbox" name="ativo" id="ativo" value="1" <?= $produto['PRODUTO_ATIVO'] ? 'checked' : '' ?>>
    <p>
    <!-- Lista de imagens existentes -->
    <?php 
    foreach($imagens_existentes as $imagem) {
        echo '<div>';
        echo '<label>URL da Imagem:</label>';
        echo '<input type="text" name="editar_imagem_url[' . $imagem['IMAGEM_ID'] . ']" value="' . $imagem['IMAGEM_URL'] . '">';
        echo '</div>';
    }
    ?>
    <p>
    <button type="submit">Atualizar Produto</button>
</form>
</body>
</html>








