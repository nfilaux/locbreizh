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
$stmt = $dbh->prepare("SELECT photo from locbreizh._compte where id_compte = :id;");
$stmt->bindParam(':id', $_SESSION['id']);
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
    <div>
        <img src="../svg//logo.svg">
        <h2>Loc'Breizh</h2>
    </div></a>

    <div>
        <img src="../svg//filtre.svg">
        <input id="searchbar" type="text" name="search">
        <img src="../svg//loupe.svg">
    </div>
        <div>
            <img src="../svg//booklet-fill 1.svg">
            <a href="../reservation/liste_reservations.php">
                <h4>Accèder à mes reservations</h4>
            </a>
        </div>
        

        <div class="imghead">
            <a href="../messagerie/messagerie.php"><img src="../svg/message.svg"></a>
            <a onclick="openPopup()"><img id="pp" class="imgprofil" src="../Ressources/Images/<?php echo $photo['photo']; ?>" width="50" height="50"></a>
        </div>
        <div id="overlay" onclick="closePopup()"></div>
        <div id="popup" class="popup">
            <table id="tableProfil">
                <tr>
                    <td>
                        <a id="monprofil" href="">Accéder au profil</a>
                    </td>
                </tr>
                <tr>
                    <td> 
                        <a id="deconnexion" href="../Compte/SeDeconnecter.php">Se déconnecter</a>
                    </td>  
                </tr>
            </table>
        </div>
</header>

    <main>
        <h1>Paiement de la reservation</h1>
        <div id="paypal-button-container"></div>
        <script src="https://www.paypal.com/sdk/js?client-id=AZ9f4oyPDCMS1YixHmqPR4wPGz2OM5CC59wpo0KTCHc2jaOMYZivqTjz-Wj52tAprCRF5PkLZD1dnn9s&currency=EUR"></script>
        <script src="paypal.js"></script>
    </main>
    <?php 
        echo file_get_contents('../header-footer/footer.html');
    ?>
</body>

</html>

<script src="../scriptPopup.js"></script>