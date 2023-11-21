<?php 
    session_start();
    try {
        include('../parametre_connexion.php');

        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !:" . $e->getMessage() . "<br/>";
        die();
    }

    $stmt = $dbh->prepare(
        "SELECT  nom, prenom, pseudo, mail, telephone, photo, dateNaissance, nom_rue, numero_rue, code_postal, pays, ville
        from locbreizh._compte
        join locbreizh._client on _client.id_client = _compte.id_compte
        join locbreizh._adresse on _adresse.id_adresse = _compte.adresse
        where id_compte = {$_SESSION['id']};"
    );
// p sdmfjhH54##!54JJ
    $stmt->execute();
    $infos = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="../style.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil</title>
</head>
<body>
    <header>
        <h1>Mon compte</h1>
    </header>
    
    <main>
        <form action="modifier_client.php" method="post" enctype="multipart/form-data">
            <div class="rowcompte">
                <p>Prénom</p>
                <input type="text" id="prenom" name="prenom" value=<?php echo $infos['prenom'];?> required>
            </div>
            <div class="rowcompte">
                <p>Nom</p>
                <input type="text" id="nom" name="nom" value=<?php echo $infos['nom'];?> required>       
            </div>
            <div class="rowcompte">
                <p>Pseudo</p>
                <input type="text"  id="pseudo" name="pseudo" value=<?php echo $infos['pseudo'];?> required>
            </div>
            <div class="rowcompte">
                <p>E-mail</p>
                <input type="email" id="mail" name="mail" value=<?php echo $infos['mail'];?> required>
            </div>
            <div class="rowcompte">
                <p>Téléphone</p>
                <input type="tel" id="telephone" name="telephone" value=<?php echo substr($infos['telephone'], 0,2) . ' ' . substr($infos['telephone'], 2,2) . ' ' . substr($infos['telephone'], 4,2) . ' ' . substr($infos['telephone'], 6,2) . ' ' . substr($infos['telephone'], 8,2);?> required>
            </div>
            <div class="rowcompte">
                <p>Mot de passe</p>
                <p>XXXXXXXXXXXX</p>
            </div>
            <div class="rowcompte">
                <p>Date de naissance</p>
                <input type="date" id="date" name="date" value=<?php 
                    //on cree un objet date pour changer sa forme
                    $date = new DateTime($infos['dateNaissance']);
                    // le format jour mois
                    $date_formatee = $date->format('Y-m-d');
                    echo $date_formatee;
                ?> required>
            </div>
            <div class="rowcompte">
                <p>Adresse</p>
                <div>
                <p>N° :</p><input type="text" id="no_rue" name="no_rue" value=<?php echo $infos['numero_rue'];?> required>
                <p>Rue :</p><input id="nom_rue" name="nom_rue" value=<?Php echo $infos['nom_rue'];?> required>
                </div>
                <div>
                <p>Ville :</p><input type="text" id="ville" name="ville" value=<?php echo $infos['ville'];?> required>
                <p>Pays :</p><input type="text" id="pays" name="pays" value=<?php echo $infos['pays'];?> required>
                </div>

            </div>
            <div>
                <img src="../Ressources/Images/<?php echo $infos['photo']; ?>" title="photo" alt="photo de profil">
                <p>Photo de profil</p>
                <input type="file" id="photo" name="photo"/>
            </div>
            <input type="submit" value="Enregistrer les modifications">
        </form>
        <div>
            <div>
                <h2>Suppression du compte</h2>
                <p>DICLAIMER : La suppression du compte est définitive.</p>
                <p>Condition requise : aucun logement en ligne et aucune réservation prévue.</p>
            </div>
            <button disabled>Supprimer le compte</button>
        </div>
    </main>
    
</body>
</html>