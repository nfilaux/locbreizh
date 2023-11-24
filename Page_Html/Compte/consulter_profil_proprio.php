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
        "SELECT  nom, prenom, pseudo, mail, telephone, photo, nom_rue, numero_rue, code_postal, pays, ville, rib, carte_identite
        from locbreizh._compte
        join locbreizh._proprietaire on id_proprietaire = id_compte
        join locbreizh._adresse on _adresse.id_adresse = _compte.adresse
        where id_compte = {$_SESSION['id']};"
    );
// nfi mlkjJMLJ465##!!
    $stmt->execute();
    $infos = $stmt->fetch();

    function erreur($nomErreur){
        if(isset($_SESSION["erreurs"][$nomErreur])){
            ?><p><?php echo $_SESSION["erreurs"][$nomErreur]?></p><?php
            unset($_SESSION["erreurs"][$nomErreur]);
        }
}
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
        <form action="modifier_proprio.php" method="post" enctype="multipart/form-data">
            <div class="rowcompte">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" maxlength="20" value="<?php echo $infos['prenom'];?>" required>
                <?php erreur("prenom");?>
            </div>
            <div class="rowcompte">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" maxlength="20" value="<?php echo $infos['nom'];?>" required>
                <?php erreur("nom");?>
            </div>
            <div class="rowcompte">
                <label for="pseudo">Pseudo</label>
                <input type="text"  id="pseudo" name="pseudo" maxlength="20" value="<?php echo $infos['pseudo'];?>" required>
                <?php erreur("pseudo");?>
            </div>
            <div class="rowcompte">
                <label for="mail">E-mail</label>
                <input type="email" id="mail" maxlength="50" name="mail" value="<?php echo $infos['mail'];?>" required>
                <?php erreur("email");?>
            </div>
            <div class="rowcompte">
                <label for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone" value="<?php echo substr($infos['telephone'], 0,2) . ' ' . substr($infos['telephone'], 2,2) . ' ' . substr($infos['telephone'], 4,2) . ' ' . substr($infos['telephone'], 6,2) . ' ' . substr($infos['telephone'], 8,2);?>" required>
                <?php erreur("telephone");?>
            </div>
            <div class="rowcompte">
                <p>Mot de passe</p>
                <p>XXXXXXXXXXXX</p>
            </div>
            <div class="rowcompte">
                <p>Adresse</p>
                <div>
                    <label for="no_rue">N° :</label><input type="text" id="no_rue" name="no_rue" maxlength="3" value="<?php echo $infos['numero_rue'];?>" required>
                    <?php erreur("numRue");?>
                    <label for="nom_rue">Rue :</label><input type="text" id="nom_rue" name="nom_rue" maxlength="30" value="<?php echo $infos['nom_rue'];?>" required>
                    <?php erreur("nomRue");?>
                </div>
                <div>
                    <label for="codePostal">Code postal :</label><input type="text" maxlength="5" id="codePostal" name="codePostal" value="<?php echo $infos['code_postal'];?>" required> 
                    <?php erreur("codePostal");?>
                    <label for="ville">Ville :</label><input type="text" id="ville" maxlength="50" name="ville" value="<?php echo $infos['ville'];?>" required>
                    <?php erreur("ville");?>
                </div>
            </div>
            <div>
                <label for="carteIdentite">Carte d'identite</label>
                <a href="../Ressources/carte_identite/<?php echo $infos['carte_identite']; ?>">Voir ma carte d'identité</a>
                <input type="file" id="carteIdentite" name="carteIdentite"/>
                <?php erreur("carteIdentite"); ?>
            </div>
            <div>
                <label for="rib">RIB :</label>
                <a href="../Ressources/rib/<?php echo $infos['rib']; ?>">Voir le RIB</a>
                <input type="file" id="rib" name="rib"/>
                <?php erreur("rib"); ?>
            </div>
            <div>
                <img src="../Ressources/Images/<?php echo $infos['photo']; ?>" title="photo" alt="photo de profil">
                <label for="photo">Photo de profil</label>
                <input type="file" id="photo" name="photo"/>
                <?php 
                erreur("photo"); ?>
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