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
    $filtre='';
    include('../header-footer/choose_header.php');
    ?>
    <main class="MainTablo">
        <div class="headtablo"> 
            <h1>Mes Réservations</h1>
        </div>
        <div class="filters">
            <form action="filtrage.php" method="post" class="menu-filtreR" onsubmit="return verifierChamps()">
                <div class="filR">
                <div class="input-group">
                    <div class="input-group-prepend">
                            <label for="prix_min">min<img src="../svg/money.svg" width="12" height="12"></label>
                        </div>
                        <input type="number" id="prix_min" name="prix_min" placeholder="<?php if (isset($_GET['prixMin'])){echo $_GET['prixMin'];} else {echo 0;} ?>" min="0"/>
                    </div>  
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <label for="prix_max">max<img src="../svg/money.svg" width="12" height="12"></label>
                        </div>
                        <input type="number" id="prix_max" name="prix_max" placeholder="<?php if (isset($_GET['prixMax'])){echo $_GET['prixMax'];} else {echo 0;} ?>" min="0"/>
                    </div>
                    <div class="input-group-pers">
                        <div class="input-group-prepend">
                            <label for="date">Date</label>
                        </div>
                        <input type="Date" id="date" name="date" placeholder="<?php if (isset($_GET['date'])){echo $_GET['date'];}  ?>"/>   
                    </div>
                        <button class="btn-fill" type="submit" id="filtrage">Filtrer</button>
                </div>
                <?php if (isset($_GET['erreur'])){ ?>
                    <p class='err'>Le prix min doit être inférieur au prix max</p>
                <?php }?>
            </form>
        </div>
        
        
        <div style="display:flex; flex-direction:column-reverse;"><?php
                try {
                    $filtre = '';

                    if (sizeof($_GET)==1){
                        foreach ($_GET as $NomFiltre => $choix) {
                            switch($NomFiltre){
                                case 'prixMin' :    $filtre = "and d.prix_total_devis>=$choix";  break;
                                case 'prixMax' :    $filtre = "and d.prix_total_devis<=$choix"; break;
                                case 'date' :       $filtre = "and d.date_depart>='$choix' AND d.date_arrivee<='$choix'"; break;
                            }
                        }
                    } else if (sizeof($_GET)>1){
                        $prix1 = $_GET['prixMin'];
                        $prix2 = $_GET['prixMax'];
                        $filtre = "and d.prix_total_devis>=$prix1 AND d.prix_total_devis<=$prix2";
                    }

                    $stmt = $dbh->prepare("SELECT url_detail, l.photo_principale, libelle_logement, f.url_facture, l.id_logement, nom, prenom, c.photo
                        from locbreizh._reservation r
                        join locbreizh._logement l on l.id_logement = r.logement
                        join locbreizh._proprietaire p on l.id_proprietaire = p.id_proprietaire
                        join locbreizh._compte c on c.id_compte = p.id_proprietaire
                        join locbreizh._adresse a on l.id_adresse = a.id_adresse
                        join locbreizh._facture f on f.num_facture = r.facture
                        join locbreizh._devis d on d.num_devis = f.num_devis
                        where r.client = {$_SESSION['id']} $filtre;");     

                } catch (PDOException $e) {
                    print "Erreur !:" . $e->getMessage() . "<br/>";
                    die();
                } 

                
                
                $stmt->execute();
                $reservations = $stmt->fetchAll();

                foreach ($reservations as $reservation) {

                    ?>
                    <div class="cardresmain"> 
                        <img class="cardresmainimg" src="../Ressources/Images/<?php echo $reservation['photo_principale']; ?>"> 
                        <section class="rescol">      
                            <div class="logrowb">
                            <div>
                            <h3 class="titrecard"><?php echo $reservation['libelle_logement'] ?></h3>
                            <hr class="hrcard">
                            </div>
                            <div class="resrow">
                                <img class="imgprofil" src=<?php echo '../Ressources/Images/' . $reservation['photo']; ?> alt="photo de profil"  width="50" height="50">
                                <p class="restitre resplustaille">Par <?php echo $reservation['nom'] . ' ' . $reservation['prenom'];?></p>
                            </div>
                            </div>
                            

                            <div class="rescrow">
                                <a href="../devis/pdf_devis/<?php echo $reservation['url_detail'];?>" target="_blank"><button class="btn-ajoutlog">CONSULTER LA FACTURE</button></a>
                                <a href="../Logement/logement_detaille_client.php?logement=<?php echo $reservation['id_logement'];?>"><button class="btn-consulter">CONSULTER LOGEMENT</button></a>
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
<script src="./actualiserFiltre.js" defer></script>