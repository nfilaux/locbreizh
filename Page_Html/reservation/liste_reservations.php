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
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../scriptPopup.js"></script>
</head>

<body>

<?php 
        include('../header-footer/choose_header.php');
    ?><?php 
    include('../header-footer/choose_header.php');
?>
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

                
                $stmt = $dbh->prepare("SELECT l.photo_principale, ville, code_postal, f.url_facture, l.id_logement, nom, prenom, c.photo
                from locbreizh._reservation r
                join locbreizh._logement l on l.id_logement = r.logement
                join locbreizh._proprietaire p on l.id_proprietaire = p.id_proprietaire
                join locbreizh._compte c on c.id_compte = p.id_proprietaire
                join locbreizh._adresse a on l.id_adresse = a.id_adresse
                join locbreizh._facture f on f.num_facture = r.facture
                join locbreizh._devis d on d.num_devis = f.num_devis");
                $stmt->execute();
                $reservations = $stmt->fetchAll();

                foreach ($reservations as $reservation) {

                    ?>
                    <div class="card">        
                        <img src="../Ressources/Images/<?php echo $reservation['photo_principale']; ?>">
                        <h3> <?php echo $reservation['ville'] . ', ' . $reservation['code_postal'] ?> </h3>
                        <div>
                            <p>Par <?php echo $reservation['nom'] . ' ' . $reservation['prenom'];?></p>
                            <img src=<?php echo '../Ressources/Images/' . $reservation['photo']; ?> alt="photo de profil">
                            <button disabled>Contacter le proprietaire</button>
                        </div>
                        <a href="../devis/pdf_devis/"><button class="btn-accueil" disabled>CONSULTER DEVIS</button></a>
                        <a href="../Logement/logement_detaille_client.php?logement=<?php echo $reservation['id_logement'];?>"><button class="btn-accueilins">CONSULTER LOGEMENT</button></a>

                        <a><button class="btn-accueil" disabled>ANNULER</button></a>
                        <p>DISCLAIMER - L’annulation est définitve et irréversible.</p>
                    </div>
                <?php } ?>
            
        </div>
        <div class='voir_plus'>
            <hr>
            <h4>Voir plus</h4>
            <hr>
        </div>
    </main>
    <?php 
        echo file_get_contents('../header-footer/footer.html');
    ?>
</body>

</html>

