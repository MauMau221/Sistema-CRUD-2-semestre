<?php
session_start();
require_once('../conexao.php');

//Varificação se o usuario está logado
require_once('../valida_login.php');

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['ADM_ID'])) {
    $ADM_ID = $_GET['ADM_ID'];
    try {
        $stmt = $pdo->prepare("UPDATE 
        ADMINISTRADOR
        SET ADM_ATIVO = 0 
        WHERE ADM_ID = :ADM_ID");
        $stmt->bindParam(':ADM_ID', $ADM_ID, PDO::PARAM_INT);
        $stmt->execute();
        header('Location: listar_admin.php?update=successdelete');
    } catch (PDOException $erro) {
        $mensagem = "Erro: " . $erro->getMessage();
    }
}
?>