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
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserver</title>
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

    <main class="MainReser">
        <div class="headtablo"> 
            <h1>Paiement de la réservation</h1>
        </div>
       
        <div id="paypal-button-container"></div>
        <script src="https://www.paypal.com/sdk/js?client-id=AZ9f4oyPDCMS1YixHmqPR4wPGz2OM5CC59wpo0KTCHc2jaOMYZivqTjz-Wj52tAprCRF5PkLZD1dnn9s&currency=EUR"></script>
        <script src="paypal.js"></script>
    </main>
    <?php
        // appel du footer
        include('../header-footer/choose_footer.php'); 
    ?>
</body>

</html>
