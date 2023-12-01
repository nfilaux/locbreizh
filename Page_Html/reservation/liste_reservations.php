<?php 
session_start();
include('../parametre_connexion.php');
try {
$dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    print "Erreur !:" . $e->getMessage() . "<br/>";
    die();
}
$stmt = $dbh->prepare("SELECT photo from locbreizh._compte where id_compte = {$_SESSION['id']};");
$stmt->execute();
$photo = $stmt->fetch();
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <header>
        <a href="../Accueil/accueil_client.php">
            <img class="logot" src="../svg/logo.svg">
            <h2>Loc'Breizh</h2>
        </a>
        <div class="brecherche">
            <img src="../svg/filtre.svg">
            <input id="searchbar" type="text" name="search">
            <img src="../svg/loupe.svg">
        </div>

        <img src="../svg/booklet-fill 1.svg">
        <a href="../reservation/liste_reservations.php"><h4>Accèder à mes réservations</h4></a>

        <div class="imghead">
            <a href="../messagerie/messagerie.php" ><img src="../svg/message.svg"></a>
            <a onclick="openPopup()"><img id="pp" class="imgprofil" src="../Ressources/Images/<?php echo $photo['photo']; ?>" width="50" height="50"></a> 
        </div>
        <div id="popup" class="popup">
            <a href="">Accéder au profil</a>
            <br>
            <a href="../Compte/SeDeconnecter.php">Se déconnecter</a>
            <a onclick="closePopup()">Fermer la fenêtre</a>
        </div>
    </header>

    <main>

        <div>
            <?php
            include('../parametre_connexion.php');
                try {
                    

                    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    print "Erreur !:" . $e->getMessage() . "<br/>";
                    die();
                } 

                
                $stmt = $dbh->prepare("SELECT lien_devis, l.photo_principale, ville, code_postal, f.url_facture, l.id_logement, nom, prenom, c.photo
                from locbreizh._reservation r
                join locbreizh._logement l on l.id_logement = r.logement
                join locbreizh._proprietaire p on l.id_proprietaire = p.id_proprietaire
                join locbreizh._compte c on c.id_compte = p.id_proprietaire
                join locbreizh._adresse a on l.id_adresse = a.id_adresse
                join locbreizh._facture f on f.num_facture = r.facture
                join locbreizh._devis d on d.num_devis = f.num_devis
                join locbreizh._message_devis on d.num_devis = _message_devis.id_devis");
                $stmt->execute();
                $reservations = $stmt->fetchAll();

                foreach ($reservations as $reservation) {

                    ?>
                    <div class="cardresmain"> 
                        <img class="cardresmainimg" src="../Ressources/Images/<?php echo $reservation['photo_principale']; ?>"> 
                        <section class="rescol">      
                            <div class="logrowb">
                            <div>
                            <h3 class="titrecardres"> <?php echo $reservation['ville'] . ', ' . $reservation['code_postal'] ?> </h3>
                            <hr class="hrcard">
                            </div>
                            <div class="resrow">
                            <div>
                                <p class="restitre resplustaille">Par <?php echo $reservation['nom'] . ' ' . $reservation['prenom'];?></p>
                                <button class="btn-accueil">Contacter le proprietaire</button>
                            </div>
                            <img class="imgprofil" src=<?php echo '../Ressources/Images/' . $reservation['photo']; ?> alt="photo de profil"  width="75" height="75">
                            </div>
                            </div>
                            

                            <div class="rescrow">
                                <a href="../devis/pdf_devis/<?php echo $reservation['lien_devis'];?>" target="_blank"><button class="btn-ajoutlog">CONSULTER DEVIS</button></a>
                                <a href="../Logement/logement_detaille_client.php?logement=<?php echo $reservation['id_logement'];?>"><button class="btn-consulter">CONSULTER LOGEMENT</button></a>
                                <a><button class="btn-suppr" disabled>ANNULER</button></a>
                            </div>
                            
                            
                            <div class="logrowb">
                                <p class="resplustaille">DISCLAIMER - La suppression du compte est définitve.</p>
                            </div>
                        </secion>
                    </div>
                <?php } ?>
            
        </div>
    </main>
    <footer>
            <div class="tfooter">
                <p><a href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a></p>
                <p><a href="tel:+33623455689">(+33) 6 23 45 56 89</a></p>
                <a class="margintb" href="connexion.html"><img src="../svg/instagram.svg">  <p>@LocBreizh</p></a>
                <a  class="margintb" href="connexion.html"><img src="../svg/facebook.svg">  <p>@LocBreizh</p></a>
            </div>
            <hr>  
            <div class="bfooter">
                <p>©2023 Loc’Breizh</p>
                <p style="text-decoration: underline;"><a href="connexion.html">Conditions générales</a></p>
                <p>Développé par <a href="connexion.html" style="text-decoration: underline;">7ème sens</a></p>
            </div>
    </footer>
</body>

</html>
