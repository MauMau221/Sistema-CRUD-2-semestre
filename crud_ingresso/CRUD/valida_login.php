<?php

//Varificação se o usuario está logado
if (!isset($_SESSION['admin_logado'])) {
    header("Location:../logout.php");
    exit();
}

?>