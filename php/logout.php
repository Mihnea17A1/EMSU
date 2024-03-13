<?php

@include 'config.php'; //conectarea la baza de date

session_start();
session_unset();
session_destroy();
//incheierea sesiunii si mutarea inapoi in pagina de login
header('location:login.php');

?>