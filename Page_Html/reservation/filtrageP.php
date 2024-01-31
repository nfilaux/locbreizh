<?php
if ((isset($_POST['prix_min']))&&(isset($_POST['prix_max']))&&($_POST['prix_min']>$_POST['prix_max'])&&($_POST['prix_max']>0)){
    header("Location: ./liste_reservations_proprio.php?erreur=supérieur");
} else if((($_POST['prix_min']>0)&&($_POST['prix_max']>0))&&($_POST['prix_min']==$_POST['prix_max'])){ // Filtre par prix égaux
    $prix_min = urlencode($_POST['prix_min']);
    $prix_max = urlencode($_POST['prix_max']);
    header("Location: ./liste_reservations_proprio.php?prixMin={$prix_min}&prixMax={$prix_max}");

}else if((isset($_POST['prix_min']))&&(isset($_POST['prix_max']))&&($_POST['prix_min']>0)&&($_POST['prix_max']>0)){ // Filtre par les 2 bornes de prix remplies
    $prix_min = urlencode($_POST['prix_min']);
    $prix_max = urlencode($_POST['prix_max']);
    header("Location: ./liste_reservations_proprio.php?prixMin={$prix_min}&prixMax={$prix_max}");

} else if((isset($_POST['prix_min']))&&($_POST['prix_min']>0)){ // Filtre de prix minimum jusqu'à l'infini
    $prix_min = urlencode($_POST['prix_min']);
    header("Location: ./liste_reservations_proprio.php?prixMin={$prix_min}");

} else if((isset($_POST['prix_max']))&&($_POST['prix_max']>0)){ // Filtre de 0 jusqu'à prix maximum
    $prix_max = urlencode($_POST['prix_max']);
    header("Location: ./liste_reservations_proprio.php?prixMax={$prix_max}");

} else if((isset($_POST['date']))&&($_POST['date']!='')){ // Filtre par Date
    $date = urlencode($_POST['date']);
    header("Location: ./liste_reservations_proprio.php?date={$date}");

} else {
    header("Location: ./liste_reservations_proprio.php");
}
?>