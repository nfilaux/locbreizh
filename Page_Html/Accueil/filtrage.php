<?php

if(($_POST['prix_min']>0)&&($_POST['prix_max']>0)){ // Filtre par les 2 bornes de prix remplies
    $prix_min = urlencode($_POST['prix_min']);
    $prix_max = urlencode($_POST['prix_max']);
    header("Location: ./accueil_visiteur.php?prixMin={$prix_min}&prixMax={$prix_max}");

} else if($_POST['prix_min']>0){ // Filtre de prix minimum jusqu'à l'infini
    $prix_min = urlencode($_POST['prix_min']);
    header("Location: ./accueil_visiteur.php?prixMin={$prix_min}");

} else if($_POST['prix_max']>0){ // Filtre de 0 jusqu'à prix maximum
    $prix_max = urlencode($_POST['prix_max']);
    header("Location: ./accueil_visiteur.php?prixMax={$prix_max}");

} else if($_POST['lieu']!=''){ // Filtre par lieu
    $lieu = urlencode($_POST['lieu']);
    header("Location: ./accueil_visiteur.php?lieu={$lieu}");

} else if($_POST['proprietaire']!=''){ // Fitlre par nom de propriétaire
    $proprietaire = urlencode($_POST['proprietaire']);
    header("Location: ./accueil_visiteur.php?proprio={$proprietaire}");

} else if($_POST['personne']>0){ // Filtre par nombre de voyageur
    $voyageurs = urlencode($_POST['personne']);
    header("Location: ./accueil_visiteur.php?voyageurs={$voyageurs}");
} else {
    header("Location: ./accueil_visiteur.php");
}
?>