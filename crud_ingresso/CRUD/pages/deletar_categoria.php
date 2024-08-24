<?php
session_start();
require_once('../conexao.php');


//Varificação se o usuario está logado
require_once('../valida_login.php');

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['CATEGORIA_ID'])) {
    $id = $_GET['CATEGORIA_ID'];
    try { 
        $stmt = $pdo->prepare("UPDATE
         CATEGORIA
         SET CATEGORIA_ATIVO = 0
         WHERE CATEGORIA_ID = :CATEGORIA_ID"); 
        $stmt->bindParam(':CATEGORIA_ID', $id, PDO::PARAM_INT);
        $stmt->execute();
        header('Location: listar_categoria.php?update=successdelete');
    } catch (PDOException $erro) {
        $mensagem = "Erro: " . $erro->getMessage();
    }
}
?>