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
        "SELECT  nom, prenom, pseudo, mail, telephone, photo, nom_rue, numero_rue, code_postal, pays, ville, rib, carte_identite
        from locbreizh._compte
        join locbreizh._proprietaire on id_proprietaire = id_compte
        join locbreizh._adresse on _adresse.id_adresse = _compte.adresse
        where id_compte = {$_SESSION['id']};"
    );
    $stmt->execute();
    $infos = $stmt->fetch();

    // fontion pour afficher les erreurs de modification
    function erreur($nomErreur){
        if(isset($_SESSION["erreurs"][$nomErreur])){
            ?><p class="profil-erreurs"><?php echo $_SESSION["erreurs"][$nomErreur]?></p><?php
            unset($_SESSION["erreurs"][$nomErreur]);
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="../style.css">
    <script src="mdpPopUp.js"></script>
    <script src="../scriptPopup.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil</title>
</head>
<body>
    <!-- overlay utiliser pour fermer la popup -->
    <div id="overlay" onclick="closeMdpPopup()"></div>
    <!--contenu de la popup pour le changement de mdp-->
    <div id="mdpPopup" class="mdp-popup">
        <!-- croix pour la fermeture de la popup -->
        <div class="mdpCroix" onclick="closeMdpPopup()"><img src="../svg/croix.svg" alt="croix"></div>
        <h2>Changer le mot de passe</h2>
        <form action="changer_mdp_back.php" method="post" enctype="multipart/form-data">
            
            <label for="mdp">Mot de passe actuel :</label>
            <input type="password" id="mdp" name="mdp" required>
            <?php if(isset($_GET['mdp'])){ erreur('ancien_motdepasse');} ?>

            <label for="newMdp">Nouveau mot de passe :</label>
            <input type="password" id="newMdp" name="newMdp" required>
            <?php if(isset($_GET['mdp'])){ erreur('motdepasse');} ?>

            <label for="confirmMdp">Confirmer le nouveau mot de passe :</label>
            <input type="password" id="confirmMdp" name="confirmMdp" required>
            <?php if(isset($_GET['mdp'])){ erreur('confirmationMDP');} ?>

            <input type="submit" value="Changer le mot de passe">
        </form>
    </div>

    <?php 
        include('../header-footer/choose_header.php');
    ?>
    <main class="main-profil">
        <h1 class="header-profilP">Mon compte</h1>
        <!-- debut du formulaire pour afficher/modifier les informations "simples" du compte -->
        <form action="modifier_proprio.php" method="post" enctype="multipart/form-data" >
            <div class="profil-form profil-formP">
                <div class="compte-infos">
                    <div class="row-profil">
                        <label for="prenom">Prénom</label>
                        <!-- input pour pouvoir modifier l'information + pré-replissage -->
                        <input type="text" id="prenom" name="prenom" maxlength="20" value="<?php echo $infos['prenom'];?>" required>
                        <!-- affichage des possibles erreurs (même chose pour les prochains appels de la fonction) -->
                    </div>
                    <?php erreur("prenom");?>
                    <div class="row-profil">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" maxlength="20" value="<?php echo $infos['nom'];?>" required>
                    </div>
                    <?php erreur("nom");?>
                    <div class="row-profil">
                        <label for="pseudo">Pseudo</label>
                        <input type="text"  id="pseudo" name="pseudo" maxlength="19" value="<?php echo $infos['pseudo'];?>" required>   
                    </div>
                    <?php erreur("pseudo");?>
                    <div class="row-profil">
                        <label for="mail">E-mail</label>
                        <input type="email" id="mail" maxlength="50" name="mail" value="<?php echo $infos['mail'];?>" required>
                    </div>
                    <?php erreur("email");?>
                    <div class="row-profil">
                        <label for="telephone">Téléphone</label>
                        <input type="tel" id="telephone" name="telephone" value="<?php echo substr($infos['telephone'], 0,2) . ' ' . substr($infos['telephone'], 2,2) . ' ' . substr($infos['telephone'], 4,2) . ' ' . substr($infos['telephone'], 6,2) . ' ' . substr($infos['telephone'], 8,2);?>" required>
                    </div>
                    <?php erreur("telephone");?>

                    <!-- lien cliquable vers le RIB + input pour le modifier -->
                    <div class="row-profil">
                        <label for="rib">RIB</label>
                        <div class="doc-profilP">
                            <a class="voir-docP" href="../Ressources/rib/<?php echo $infos['rib']; ?>" target="_blank">
                            <p>Voir le RIB</p>
                            <img src="../svg/eye.svg" alt="voir"></a>
                            <input type="file" id="rib" name="rib"/>
                        </div>     
                    </div>
                    <?php erreur("rib"); ?>

                    <!-- section pour les informations de l'adresse -->
                    <hr class="hr" style="margin: 2em 0 0.5em 0 ;">
                    <h2 class="potitre" style="padding:0.75em;">Adresse</h2>
                    <div class="ligne_adresse">
                        <div class="numero_rue">
                            <label for="no_rue">N°</label>
                            <input type="text" id="no_rue" name="no_rue" maxlength="3" value="<?php echo $infos['numero_rue'];?>" required>
                        </div>
                        

                        <div class="nom_rue">
                            <label for="nom_rue">Rue</label>
                            <input type="text" id="nom_rue" name="nom_rue" maxlength="30" value="<?php echo $infos['nom_rue'];?>" required>
                        </div>
                    </div> 
                    <?php erreur("numRue");?>
                    <?php erreur("nomRue");?>

                    <div class="ligne_adresse">  
                        <div class="code_postal">
                            <label for="codePostal">Code postal</label>
                            <input type="text" maxlength="5" id="codePostal" name="codePostal" value="<?php echo $infos['code_postal'];?>" required> 
                        </div>
                        <div class="adresse_ville">
                            <label for="ville">Ville</label>
                            <input type="text" id="ville" maxlength="50" name="ville" value="<?php echo $infos['ville'];?>" required>
                        </div>
                    </div>
                    <?php erreur("codePostal");?>
                    <?php erreur("ville");?>
                </div>
                <!-- affichage de la photo de profil + input pour la modifier -->
                <div>
                    <img src="../Ressources/Images/<?php echo $infos['photo']; ?>" id="avatar" title="photo" alt="photo de profil">
                    <label for="photo">Photo de profil</label>
                    <input type="file" id="photo" name="photo"/>
                    <?php erreur("photo"); ?>
                </div>
                
            </div>
            <!-- boutton pour valider les changements -->
            <input class="submit-profil submit-profilP" type="submit" value="Enregistrer les modifications">
        </form>
        <!-- section pour modifier le mot de passe -->
        <div class="profil-mdp">
            <h2>Mot de passe :</h2>
            <!-- boutton pour ouvrir la popup -->
            <button class="btn-mdpP" type="button" onclick="openMdpPopup()">Changer le mot de passe</button>
        </div>
    </main>
    <?php
        // appel du footer
        include('../header-footer/choose_footer.php'); 
    ?>
</body>
<?php
    // ouverture automatique de la popup du changement de mdp si une erreur a été trouvée
    if(isset($_GET['mdp'])){?>
        <script>openMdpPopup();</script>
    <?php } ?>
</html>

<script src="./actualiserPhoto.js" defer></script>