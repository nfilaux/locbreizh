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
    <?php 
    include('../header-footer/choose_header.php');
    ?>

    <main class="MainTablo">
        <div class="headtabloP"> 
            <h1>Mes Réservations</h1>
        </div>

        <div style="display:flex; flex-direction:column-reverse;">
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

                
                $stmt = $dbh->prepare("SELECT url_detail, l.photo_principale,libelle_logement, f.url_facture, l.id_logement, nom, prenom, c.photo
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
                    <div class="cardresmainP"> 
                        <img class="cardresmainimg" src="../Ressources/Images/<?php echo $reservation['photo_principale']; ?>"> 
                        <section class="logcp">      
                            <div class="logrowb">
                            <div>
                                <h3 class="titrecard"><?php echo $reservation['libelle_logement'] ?></h3>
                                <hr class="hrcard">
                            </div>
                            </div>
                            

                            <div class="rescrow">
                                <a href="../devis/pdf_devis/<?php echo $reservation['lien_devis'];?>" target="_blank"><button class="btn-ajoutlog">CONSULTER DEVIS</button></a>
                                <a href="../Logement/logement_detaille_proprio.php?logement=<?php echo $reservation['id_logement'];?>"><button class="btn-consulter">CONSULTER LOGEMENT</button></a>
                                <a><button class="btn-suppr" disabled>ANNULER</button></a>
                            </div>

                        </secion>
                    </div>
                <?php } ?>
            
        </div>
    </main>
    <?php
        // appel du footer
        include('../header-footer/choose_footer.php'); 
    ?>
</body>

</html>
