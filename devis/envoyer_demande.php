<?php 
    print_r($_POST);
    //test date
    if($_POST['dateArrivee'] > $_POST['dateDepart']){
        header("Location: demande_devis.php?animaux={$_POST['animaux']}&menage={$_POST['menage']}&nb_pers={$_POST['nb_pers']}&nb_supp={$_POST['nb_pers_supp']}");
    }

?>