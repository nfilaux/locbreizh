<?php 
session_start();
$_SESSION['lien_page'] = "../messagerie/messagerie.php";
header("Location: ../Compte/connexionFront.php");
?>