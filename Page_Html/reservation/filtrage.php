<?php
if ($_POST['prix_min']>$_POST['prix_max']){
    header("Location: ./liste_reservations.php?erreur=supérieur");
} else if((($_POST['prix_min']>0)&&($_POST['prix_max']>0))&&($_POST['prix_min']==$_POST['prix_max'])){ // Filtre par prix égaux
    $prix_min = urlencode($_POST['prix_min']);
    $prix_max = urlencode($_POST['prix_max']);
    header("Location: ./liste_reservations.php?prixMin={$prix_min}&prixMax={$prix_max}");

}else if(($_POST['prix_min']>0)&&($_POST['prix_max']>0)){ // Filtre par les 2 bornes de prix remplies
    $prix_min = urlencode($_POST['prix_min']);
    $prix_max = urlencode($_POST['prix_max']);
    header("Location: ./liste_reservations.php?prixMin={$prix_min}&prixMax={$prix_max}");

} else if($_POST['prix_min']>0){ // Filtre de prix minimum jusqu'à l'infini
    $prix_min = urlencode($_POST['prix_min']);
    header("Location: ./liste_reservations.php?prixMin={$prix_min}");

} else if($_POST['prix_max']>0){ // Filtre de 0 jusqu'à prix maximum
    $prix_max = urlencode($_POST['prix_max']);
    header("Location: ./liste_reservations.php?prixMax={$prix_max}");

} else if(isset($_POST['date'])){ // Filtre par Date
    //$date_EN = explode('-', $_POST['date']);
    //$date_FR = $date_EN[2] . "-" . $date_EN[1] . "-" . $date_EN[0]; 
    $date = urlencode($_POST['date']);
    header("Location: ./liste_reservations.php?date={$date}");

} else {
    header("Location: ./liste_reservations.php");
}
?>