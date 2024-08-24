<?php
session_start();

//Varificação se o usuario está logado
require_once('../valida_login.php');
//Conexão com banco de dados
require_once('../conexao.php');

// Bloco de consulta para buscar categorias.
try {
  $stmt_categoria = $pdo->prepare("SELECT * FROM CATEGORIA");
  $stmt_categoria->execute();
  $categoria = $stmt_categoria->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $erro) {
  echo "<div id='messagee'>Erro ao buscar categoria " . $erro->getMessage() . "</div>";
}


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (isset($_GET['id'])) {
    $PRODUTO_ID = $_GET['id'];

    try {
      $produto = buscarProduto($pdo, $PRODUTO_ID);

      $imagens = buscarImagens($pdo, $PRODUTO_ID);
    } catch (PDOException $erro) {
      echo "Erro: " . $erro->getMessage();
    }
  } else {
    header('Location: listar_produto.php');
    exit();
  }
}

// Se o formulário de edição foi submetido, a página é acessada via método POST, e o script tenta atualizar os detalhes do produto no banco de dados com as informações fornecidas no formulário.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $PRODUTO_NOME = $_POST['PRODUTO_NOME'];
  $PRODUTO_DESC = $_POST['PRODUTO_DESC'];
  $PRODUTO_PRECO = $_POST['PRODUTO_PRECO'];
  $PRODUTO_DESCONTO = $_POST['PRODUTO_DESCONTO'];
  $CATEGORIA_ID = $_POST['CATEGORIA_ID'];
  $PRODUTO_ATIVO = $_POST['PRODUTO_ATIVO'];
  $PRODUTO_ID = $_POST['PRODUTO_ID'];
  $PRODUTO_QTD = $_POST['PRODUTO_QTD'];
  $imagens = $_POST['imagem_url'];
  try {
    editarProduto($pdo, $PRODUTO_NOME, $PRODUTO_DESC, $PRODUTO_PRECO, $PRODUTO_DESCONTO, $CATEGORIA_ID, $PRODUTO_ATIVO, $PRODUTO_ID);

    //GAMB ESTOQUE DOS COLEGAS QUE NAO CADASTROU :/
    $gamb_estoque = buscarEstoque($pdo, $PRODUTO_ID);
    if ($gamb_estoque != NULL) {
      editarEstoque($pdo, $PRODUTO_ID, $PRODUTO_QTD);
    } else {
      adicionarEstoque($pdo, $PRODUTO_ID, $PRODUTO_QTD);
    }

    //IMAGEM - REMOVER
    $imagens_banco = buscarImagens($pdo, $PRODUTO_ID);
    $imagens_manter = array();

    foreach ($imagens as $chave => $url) {
      foreach ($imagens_banco as $indice => $imagem_banco) {
        if ($chave == $imagem_banco['IMAGEM_ID']) {
          $imagens_manter[] = $indice; // Armazena as chaves a serem removidas
        }
      }
    }

    // Remove as imagens que serao mantidas
    foreach ($imagens_manter as $indice) {
      unset($imagens_banco[$indice]);
    }

    // Imagens que nao foram separadas serao removidas
    foreach ($imagens_banco as $indice => $imagem_banco) {
      removerImagem($pdo, $imagem_banco['IMAGEM_ID']);
    }

    //IMAGEM - ADICIONAR OU EDITAR
    foreach ($imagens as $chave => $valor) {
      if (strpos($chave, 'novo_') === 0) {
        // Esta é uma nova imagem sem ID definido
        adicionarImagem($pdo, $PRODUTO_ID, $valor);
      } else {
        // Esta é uma imagem existente com ID
        editarImagem($pdo, $chave, $valor);
      }
    }

    /*Parametro para mensagem de sucesso através de GET */
    header('Location: listar_produto.php?update=success');
    exit();
  } catch (PDOException $erro) {
    echo "Erro: " . $erro->getMessage();
  }
}

function buscarProduto($pdo, $PRODUTO_ID)
{
  try {
    $stmt_produto = $pdo->prepare(
      "SELECT
      p.PRODUTO_ID,
      p.PRODUTO_NOME, 
      p.PRODUTO_DESC, 
      p.PRODUTO_PRECO,
      p.PRODUTO_DESCONTO,
      p.CATEGORIA_ID,
      p.PRODUTO_ATIVO,
      p.CATEGORIA_ID,
      c.CATEGORIA_NOME,
      pe.PRODUTO_QTD
    FROM PRODUTO AS p
    LEFT JOIN CATEGORIA AS c ON c.CATEGORIA_ID = p.CATEGORIA_ID
    LEFT JOIN PRODUTO_ESTOQUE as pe ON pe.PRODUTO_ID = p.PRODUTO_ID
    WHERE p.PRODUTO_ID = :PRODUTO_ID
    "
    );

    $stmt_produto->bindParam(':PRODUTO_ID', $PRODUTO_ID, PDO::PARAM_INT);
    $stmt_produto->execute();
    $produto = $stmt_produto->fetch(PDO::FETCH_ASSOC);

    return $produto;
  } catch (PDOException $erro) {
    echo "Erro: " . $erro->getMessage();
  }
}

function buscarImagens($pdo, $PRODUTO_ID)
{
  try {
    $stmt_imagem = $pdo->prepare("SELECT
      IMAGEM_ID,
      IMAGEM_URL,
      IMAGEM_ORDEM
      FROM PRODUTO_IMAGEM 
      WHERE PRODUTO_ID = :PRODUTO_ID AND IMAGEM_ORDEM >= 0
    ");
    $stmt_imagem->bindParam(':PRODUTO_ID', $PRODUTO_ID, PDO::PARAM_INT);
    $stmt_imagem->execute();

    $imagens = $stmt_imagem->fetchAll(PDO::FETCH_ASSOC);

    return $imagens;
  } catch (PDOException $erro) {
    echo "Erro: " . $erro->getMessage();
  }
}

function editarProduto($pdo, $PRODUTO_NOME, $PRODUTO_DESC, $PRODUTO_PRECO, $PRODUTO_DESCONTO, $CATEGORIA_ID, $PRODUTO_ATIVO, $PRODUTO_ID)
{
  try {
    $stmt_produto = $pdo->prepare("UPDATE PRODUTO
      SET PRODUTO_NOME = :PRODUTO_NOME,
          PRODUTO_DESC = :PRODUTO_DESC,
          PRODUTO_PRECO = :PRODUTO_PRECO,
          PRODUTO_DESCONTO = :PRODUTO_DESCONTO,
          CATEGORIA_ID = :CATEGORIA_ID,
          PRODUTO_ATIVO = :PRODUTO_ATIVO
      WHERE PRODUTO_ID = :PRODUTO_ID");
    $stmt_produto->bindParam(':PRODUTO_NOME', $PRODUTO_NOME);
    $stmt_produto->bindParam(':PRODUTO_DESC', $PRODUTO_DESC);
    $stmt_produto->bindParam(':PRODUTO_PRECO', $PRODUTO_PRECO);
    $stmt_produto->bindParam(':PRODUTO_DESCONTO', $PRODUTO_DESCONTO);
    $stmt_produto->bindParam(':CATEGORIA_ID', $CATEGORIA_ID);
    $stmt_produto->bindParam(':PRODUTO_ATIVO', $PRODUTO_ATIVO, PDO::PARAM_INT);
    $stmt_produto->bindParam(':PRODUTO_ID', $PRODUTO_ID, PDO::PARAM_INT);
    $stmt_produto->execute();
  } catch (PDOException $erro) {
    echo "Erro: " . $erro->getMessage();
  }
}

function editarImagem($pdo, $IMAGEM_ID, $IMAGEM_URL)
{

  try {
    $stmt_imagem = $pdo->prepare("UPDATE PRODUTO_IMAGEM 
      SET IMAGEM_URL = :IMAGEM_URL 
      WHERE IMAGEM_ID = :IMAGEM_ID
    ");
    $stmt_imagem->bindParam(':IMAGEM_ID', $IMAGEM_ID, PDO::PARAM_INT);
    $stmt_imagem->bindParam(':IMAGEM_URL', $IMAGEM_URL, PDO::PARAM_STR);
    $stmt_imagem->execute();
  } catch (PDOException $erro) {
    echo "Erro: " . $erro->getMessage();
  }
}

function adicionarImagem($pdo, $PRODUTO_ID, $IMAGEM_URL)
{

  try {
    //ULTIMA ORDEM + 1
    $stmt_ordem = $pdo->prepare("SELECT 
      COALESCE(
        (SELECT (IMAGEM_ORDEM + 1) 
        FROM PRODUTO_IMAGEM 
        WHERE PRODUTO_ID = :PRODUTO_ID AND IMAGEM_ORDEM > 0), 
        1) AS IMAGEM_ORDEM
    ");

    $stmt_ordem->bindParam(':PRODUTO_ID', $PRODUTO_ID, PDO::PARAM_INT);
    $stmt_ordem->execute();
    $IMAGEM_ORDEM_SQL = $stmt_ordem->fetch(PDO::FETCH_ASSOC);

    $IMAGEM_ORDEM = $IMAGEM_ORDEM_SQL["IMAGEM_ORDEM"];

    //ADICIONAR IMAGEM
    $stmt_imagem = $pdo->prepare("INSERT INTO PRODUTO_IMAGEM 
    (
        IMAGEM_URL,
        IMAGEM_ORDEM,
        PRODUTO_ID
    ) VALUES (
        :IMAGEM_URL,
        :IMAGEM_ORDEM,
        :PRODUTO_ID
    )");
    $stmt_imagem->bindParam(':IMAGEM_URL', $IMAGEM_URL, PDO::PARAM_STR);
    $stmt_imagem->bindParam(':IMAGEM_ORDEM', $IMAGEM_ORDEM, PDO::PARAM_INT);
    $stmt_imagem->bindParam(':PRODUTO_ID', $PRODUTO_ID, PDO::PARAM_INT);
    $stmt_imagem->execute();
  } catch (PDOException $erro) {
    echo "Erro: " . $erro->getMessage();
  }
}

function removerImagem($pdo, $IMAGEM_ID)
{

  try {
    $stmt_imagem = $pdo->prepare("UPDATE PRODUTO_IMAGEM 
      SET IMAGEM_ORDEM = -1, IMAGEM_URL = 'REMOVIDA' 
      WHERE IMAGEM_ID = :IMAGEM_ID
    ");
    $stmt_imagem->bindParam(':IMAGEM_ID', $IMAGEM_ID, PDO::PARAM_INT);
    $stmt_imagem->execute();
  } catch (PDOException $erro) {
    echo "Erro: " . $erro->getMessage();
  }
}

function buscarEstoque($pdo, $PRODUTO_ID)
{
  try {
    $stmt = $pdo->prepare("SELECT 
      COALESCE(
        (SELECT MAX(PRODUTO_QTD) FROM PRODUTO_ESTOQUE WHERE PRODUTO_ID = :PRODUTO_ID), NULL
      ) AS PRODUTO_QTD");
    $stmt->bindParam(':PRODUTO_ID', $PRODUTO_ID, PDO::PARAM_INT);
    $stmt->execute();
    $estoque = $stmt->fetch(PDO::FETCH_ASSOC);

    return $estoque["PRODUTO_QTD"];
  } catch (PDOException $erro) {
    echo "Erro: " . $erro->getMessage();
  }
}

function editarEstoque($pdo, $PRODUTO_ID, $PRODUTO_QTD)
{
  try {
    $stmt = $pdo->prepare("UPDATE PRODUTO_ESTOQUE 
      SET PRODUTO_QTD = :PRODUTO_QTD 
      WHERE PRODUTO_ID = :PRODUTO_ID
    ");
    $stmt->bindParam(':PRODUTO_ID', $PRODUTO_ID, PDO::PARAM_INT);
    $stmt->bindParam(':PRODUTO_QTD', $PRODUTO_QTD, PDO::PARAM_INT);
    $stmt->execute();
  } catch (PDOException $erro) {
    echo "Erro: " . $erro->getMessage();
  }
}

function adicionarEstoque($pdo, $PRODUTO_ID, $PRODUTO_QTD)
{
  try {
    $stmt = $pdo->prepare("INSERT INTO PRODUTO_ESTOQUE 
    (
      PRODUTO_ID,
      PRODUTO_QTD
    ) VALUES (
      :PRODUTO_ID,
      :PRODUTO_QTD
    )");
    $stmt->bindParam(':PRODUTO_ID', $PRODUTO_ID, PDO::PARAM_INT);
    $stmt->bindParam(':PRODUTO_QTD', $PRODUTO_QTD, PDO::PARAM_INT);
    $stmt->execute();
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
            <p class="mb-0">Editar Produto</p>
            <button class="btn btn-danger btn-sm ms-auto">Delete</button>
          </div>
        </div>
        <div class="card-body">
          <div>
            <form class="row" action="editar_produto.php" method="POST" enctype="multipart/form-data">
              <div class="col-md-12 d-none">
                <div class="form-group">
                  <label for="PRODUTO_ID" class="form-control-label"> Id do Produto</label>
                  <input class="form-control" type="text" name="PRODUTO_ID" id="PRODUTO_ID" value="<?= $produto['PRODUTO_ID'] ?>" required readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="PRODUTO_NOME" class="form-control-label"> Nome do Produto</label>
                  <input class="form-control" type="text" name="PRODUTO_NOME" id="PRODUTO_NOME" value="<?= $produto['PRODUTO_NOME'] ?>" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="PRODUTO_DESC" class="form-control-label">Descrição</label>
                  <textarea class="form-control" name="PRODUTO_DESC" id="PRODUTO_DESC" required><?= $produto['PRODUTO_DESC'] ?></textarea>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="PRODUTO_QTD" class="form-control-label">Quantidade</label>
                  <input class="form-control" type="number" name="PRODUTO_QTD" id="PRODUTO_QTD" value="<?= $produto['PRODUTO_QTD'] ?>" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="CATEGORIA_NOME" class="form-control-label">Categoria</label>
                  <select class="form-control" type="text" name="CATEGORIA_ID" id="CATEGORIA_NOME">
                    <?php foreach ($categoria as $categorias) {
                      // Loop para preencher o dropdown de categorias. 
                    ?>
                      <option class="form-control" value="<?= $categorias['CATEGORIA_ID'] ?>"><?= $categorias['CATEGORIA_NOME'] ?></option>
                    <?php }; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="PRODUTO_PRECO" class="form-control-label">Preço</label>
                  <input class="form-control" type="number" name="PRODUTO_PRECO" id="preco" step="0.01" value="<?= $produto['PRODUTO_PRECO'] ?>" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="PRODUTO_DESCONTO" class="form-control-label">Desconto</label>
                  <input class="form-control" type="number" name="PRODUTO_DESCONTO" id="PRODUTO_DESCONTO" step="0.01" value="<?= $produto['PRODUTO_DESCONTO'] ?>">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="PRODUTO_ATIVO">Status</label>
                  <select class="form-control" name="PRODUTO_ATIVO" id="PRODUTO_ATIVO" value="<?= $produto['PRODUTO_ATIVO'] ?>" required>
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                  </select>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for="IMAGEM_URL" class="form-control-label">URL da Imagem</label>

                  <button class="btn ms-4 bm-0" type="button" id="" onclick="adicionarImagem()">Adicionar</button>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <div id="containerImagens">
                    <?php foreach ($imagens as $imagem) { ?>
                      <div class="input-group mb-3">
                        <input class="form-control" type="text" placeholder="Maximo 500 caracteres" name="imagem_url[<?= $imagem['IMAGEM_ID'] ?>]" value="<?= $imagem['IMAGEM_URL'] ?>">
                        <button class="btn mb-0" type="button" id="remover" onclick="removerInputImagem(this)">Remover</button>
                      </div>
                    <?php } ?>
                  </div>
                </div>
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
  /* Ativar a class de ativo no menu de navegação */
  let navegaa = document.getElementById('nevega2');
  navegaa.classList.add('active');
</script>

<script>
  document.querySelector("#CATEGORIA_NOME").value = <?php echo $produto['CATEGORIA_ID']  ?>;

  document.querySelector("#PRODUTO_ATIVO").value = <?php echo $produto['PRODUTO_ATIVO']  ?>;

  function adicionarImagem() {
    const containerImagens = document.getElementById('containerImagens');

    const inputgroup = document.createElement('div');
    inputgroup.className = "input-group mb-3";

    const imagem = document.createElement('input');
    imagem.type = 'text';
    imagem.name = `imagem_url[novo_${Math.floor(Math.random() * 65536).toString(16)}]`;
    imagem.className = 'form-control';

    const botao = document.createElement('button');
    botao.className = "btn mb-0";
    botao.innerText = 'Remover';
    botao.onclick = function() {
      removerInputImagem(botao);
    };

    inputgroup.appendChild(imagem);
    inputgroup.appendChild(botao);

    containerImagens.appendChild(inputgroup);

  }

  function removerInputImagem(eleMesmo) {
    eleMesmo.parentNode.remove(); //parentNode é o pai <div> inputgroup 
  }
</script>

<?php require_once('../layouts/fim.php'); ?>