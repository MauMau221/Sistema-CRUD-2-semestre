<?php

    //Configurações do banco de dados

    $host = 'localhost';  //Alterar aqui o servidor do banco de dados
    $db = 'restaurante';  // Nome do banco a ser usado 
    $user = 'root';  //Usuario do banco de dados
    $pass = '';  //Senha do banco de dados 
    $charset = 'utf8mb4';  // Conjunto de caracteres

    $dsn = "mysql:host=$host;dbname=$db;$charset";

    //Criando a conexão com o banco de dados através do PDO 
try{
    $pdo = new PDO($dsn, $user, $pass);

}catch(PDOException $erro){
        echo "Erro ao tentar conectar com o banco de dados <p> .$erro";
    };

?>