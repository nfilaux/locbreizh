<?php 
    // ouverure de la session
    session_start();

    // mise ne place du PDO pour l'accès à la BDD
    try {
        include('../parametre_connexion.php');

        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !:" . $e->getMessage() . "<br/>";
        die();
    }
    // recupération des infos du compte pour les afficher
    $stmt = $dbh->prepare(
        "SELECT  nom, prenom, pseudo, mail, telephone, photo, dateNaissance, nom_rue, numero_rue, code_postal, pays, ville
        from locbreizh._compte
        join locbreizh._client on _client.id_client = _compte.id_compte
        join locbreizh._adresse on _adresse.id_adresse = _compte.adresse
        where id_compte = {$_SESSION['id']};"
    );
    $stmt->execute();
    $infos = $stmt->fetch();

    // fontion pour afficher les erreurs de modification
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
    <script src="mdpPopUp.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil</title>
</head>
<body>
    <header>
        <h1>Mon compte</h1>
    </header>

    <!-- overlay utiliser pour fermer la popup -->
    <div id="overlay" onclick="closeMdpPopup()"></div>
    <!--contenu de la popup pour le changement de mdp-->
    <div id="mdpPopup" class="mdp-popup">
        <!-- croix pour la fermeture de la popup -->
        <div class="mdpCroix" onclick="closeMdpPopup()"><img src="../svg/croix.svg" alt="croix"></div>
        <h2>Changer le mot de passe</h2>
        <form action="changer_mdp_back.php" method="post" enctype="multipart/form-data">
            
            <label for="mdp">Mot de passe actuel:</label>
            <input type="password" id="mdp" name="mdp" required>
            <?php if(isset($_GET['mdp'])){ erreur('ancien_motdepasse');} ?>

            <label for="newMdp">Nouveau mot de passe:</label>
            <input type="password" id="newMdp" name="newMdp" required>
            <?php if(isset($_GET['mdp'])){ erreur('motdepasse');} ?>

            <label for="confirmMdp">Confirmer le nouveau mot de passe:</label>
            <input type="password" id="confirmMdp" name="confirmMdp" required>
            <?php if(isset($_GET['mdp'])){ erreur('confirmationMDP');} ?>

            <input type="submit" value="Changer le mot de passe">
        </form>
    </div>

    <main>
        <!-- debut du formulaire pour afficher/modifier les informations "simples" du compte -->
        <form action="modifier_client.php" method="post" enctype="multipart/form-data">
            <div class="rowcompte">
                <label for="prenom">Prénom</label>
                <!-- input pour pouvoir modifier l'information + pré-replissage -->
                <input type="text" id="prenom" name="prenom" maxlength="20" value="<?php echo $infos['prenom'];?>" required>
                <!-- affichage des possibles erreurs (même chose pour les prochains appels de la fonction) -->
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
            <label for="date">Date de naissance</label>
                <input type="date" id="date" name="date" value=<?php 
                    //on cree un objet date pouvoir l'afficher dans l'input
                    $date = new DateTime($infos['datenaissance']);

                    // le format annee-mois-jour
                    $date_formatee = $date->format('Y-m-d');
                
                    echo $date_formatee;
                ?> required>
                <?php erreur("date");?>
            </div>
            <!-- section pour les informations de l'adresse -->
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
            <!-- affichage de la photo de profil + input pour la modifier -->
            <div>
                <img src="../Ressources/Images/<?php echo $infos['photo']; ?>" title="photo" alt="photo de profil">
                <label for="photo">Photo de profil</label>
                <input type="file" id="photo" name="photo"/>
                <?php 
                erreur("photo"); ?>
            </div>
            <!-- boutton pour valider les changements -->
            <input type="submit" value="Enregistrer les modifications">
        </form>
        <!-- section pour modifier le mot de passe -->
        <div class="rowcompte">
            <p>Mot de passe</p>
            <!-- boutton pour ouvrir la popup -->
            <button type="button" onclick="openMdpPopup()">Changer le mot de passe</button>
        </div>
        <!-- section suppression du compte -->
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

<?php
    // ouverture automatique de la popup du changement de mdp si une erreur a été trouvée
    if(isset($_GET['mdp'])){?>
        <script>openMdpPopup();</script>
    <?php } ?>
</html>
