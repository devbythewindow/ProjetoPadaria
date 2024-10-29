<?php
    session_start();
    unset ($_SESSION['email']);
    unset ($_SESSION['senha']);
    unset ($_SESSION['nome']);
    unset ($_SESSION['id_usuario']);
    session_destroy();
    header('location:../index.html');
    exit();
?>