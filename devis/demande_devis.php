<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>
<header>
        <nav>
            <div id="logo">
                <img src="image/logo.svg">
                <p>Loc’Breizh</p>
            </div>
            <img src="image/filtre.svg">
            <form name="formulaire" method="post" action="recherche.php" enctype="multipart/form-data">
                <input type="search" id="recherche" name="recherche" placeholder="Rechercher"><br>
                <input type="image" id="loupe" alt="loupe" src="image/loupe.svg" />
            </form>
            <div>
                <img src="image/reserv.svg">
                <a href="liste_reservations.html">Accéder à mes réservations</a>
            </div>
            <div id="parametre">
                <a href="messagerie.php"><img src="image/messagerie.svg"></a>
                <a href="compte.php"><img src=<?php echo $photo_profil['url_photo']; ?>></a>
                <div>
        </nav>
    </header>
    <main>
        <h1>Faire ma demande de devis</h1>
        <form name="envoie_demande_devis" method="post" action="envoyer_demande.php" enctype="multipart/form-data">
            <div>
                <label for="dateArrivee">Date d’arrivée :</label>
                <input type="date" id="dateArrivee" name="dateArrivee" required/>

                <label for="dateDepart">Date de depart :</label>
                <input type="date" id="dateDepart" name="dateDepart" required/>

                <label for="nb_pers">Nombre de persones :</label>
                <input type="number" id="nb_pers" name="nb_pers" min="1" max="50" value=<?php if($_GET['nb_pers'] > 1){echo $_GET['nb_pers'];} else{echo 1;} ?> required/>
            </div>
            <h2>Supplements</h2>
            <div>
                <label for="nb_pers_supp">Nombre de personnes supplementaires :</label>
                <input type="number" id="nb_pers_supp" name="nb_pers_supp" min="0" max="50"  value=<?php if($_GET['nb_supp'] > 0){echo $_GET['nb_supp'];} else{echo 0;} ?> required/>

                <label for="animaux">Animaux :</label>
                <input type="checkbox" id="animaux" name="animaux" <?php if($_GET['animaux'] === 'on'){echo 'checked';}; ?>/>

                <label for="menage">Menage</label>
                <input type="checkbox" id="menage" name="menage" <?php if($_GET['menage'] === 'on'){echo 'checked';}; ?>/>
            </div>
            <input type="submit" value="Soumettre ma demande" />
        </form>
    </main>
    <footer class="mt-4 container-fluid">
        <div class="mt-4 column">
            <div class="col-12 text-center">
                <a class="col-2" href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a>
                <a class="offset-md-1 col-2" href="tel:+33623455689">(+33) 6 23 45 56 89</a>
                <a class="offset-md-1 col-1" href="connexion.html"><img src="svg/instagram.svg">  @LocBreizh</a>
                <a class="offset-md-1 col-1" href="connexion.html"><img src="svg/facebook.svg">  @LocBreizh</a>
            </div>
            <hr>
            <div class="offset-md-1 col-10 mt-4 text-center row">
                <p class="offset-md-1 col-2">©2023 Loc’Breizh</p>
                <p class="offset-md-1 col-3" style="text-decoration: underline;"><a href="connexion.html">Conditions générales</a></p>
                <p class="offset-md-1 col-4" >Développé par <a href="connexion.html" style="text-decoration: underline;">7ème sens</a></p>
            </div>
        </div>
    </footer>
    <?php echo '<script>alert("Date incorrecte !")</script>';?>
</body>
</html>