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
                <input type="date" id="dateArrivee" name="dateArrivee" />
            </div>
            <div>

            </div>
            <input type="button" id="envoie" alt="envoie"/>
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
</body>
</html>