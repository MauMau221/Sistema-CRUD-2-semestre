<?php 
    session_start(); //inicia a sessão


    require_once('conexao.php'); //requisição do arquivo 


    $nome = $_POST['nome']; // busca no arquivo requerido
    $senha = $_POST['senha']; // busca no arquivo requerido

    $sql = "SELECT ADM_NOME, ADM_SENHA, ADM_ATIVO
        FROM ADMINISTRADOR
        WHERE ADM_NOME = :nome 
        AND ADM_SENHA  = :senha
        AND ADM_ATIVO = 1"; 


    $query = $pdo->prepare($sql);
    $query->bindParam(':nome', $nome, PDO::PARAM_STR);
    $query->bindParam(':senha', $senha, PDO::PARAM_STR);

    $query->execute();  //PREPARAÇÃO DE SEGURANÇA


    if ($query->rowCount() > 0) {    //rowCount() = quantidade de linhas 
        $_SESSION['admin_logado'] = true;
        header('Location: pages/dashboard.php');
    }else {
        header('Location: logout.php'); //Se não retorne para a pagina de login 
    }

    //Função para deslogar 

    function logout(){
        session_start();
        session_unset();
        session_destroy();
        header("location: ../login/index.php");
    }   
?>