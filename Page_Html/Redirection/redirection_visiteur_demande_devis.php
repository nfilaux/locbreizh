<?php 
session_start();
$_SESSION['lien_page'] = "../demande_devis/demande_devis.php?logement={$_GET['logement']}";
header("Location: ../Compte/connexionFront.php");
?>

