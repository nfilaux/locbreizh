<?php
    session_start();
    function erreur($nomErreur)
    {
        if(isset($_SESSION["erreurs"][$nomErreur])){
            ?><p class="err"><?php echo $_SESSION["erreurs"][$nomErreur]?></p><?php
            unset($_SESSION["erreurs"][$nomErreur]);
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <script src="../scriptMDP.js"></script>
    <title>Création du compte</title>
</head>
<body  class="pagecompte">
    <header class="headconn">
            <a href="CreerCompte.html"><img src="../svg/flecheRetour.svg" alt="fleche de retour"/></a>
            <h1>Créer mon compte propriétaire  !</h1>
    </header>
    <main>
        <?php
        // creation du formulaire, si la valeur entré n'est pas bonne on affiche l'erreur de l'attribut en question ?>
            <h1 class="header-profil">Mon compte</h1>
            <!-- debut du formulaire pour afficher/modifier les informations "simples" du compte -->
            <form action="creerProprietaireBack.php" method="post" enctype="multipart/form-data">
                <div class="profil-form">
                    <div class="compte-infos">
                        <div class="row-profil">
                            <label for="prenom">Prénom</label>
                            <!-- input pour pouvoir modifier l'information + pré-replissage -->
                            <input type="text" id="prenom" name="prenom" maxlength="20" placeholder="Prénom" value="<?php if(isset($_GET['prenom'])) { echo htmlentities($_GET['prenom']);}?>" title="Votre prénom" required>
                            <!-- affichage des possibles erreurs (même chose pour les prochains appels de la fonction) -->
                        </div>
                        <?php erreur("prenom");?>

                        <div class="row-profil">
                            <label for="nom">Nom</label>
                            <input type="text" id="nom" name="nom" maxlength="20" placeholder="Nom" value="<?php if(isset($_GET['nom'])) { echo htmlentities($_GET['nom']);}?>" title="Votre nom" required>
                        </div>
                        <?php erreur("nom");?>

                        <div class="row-profil">
                            <label for="pseudo">Pseudo</label>
                            <input type="text"  id="pseudo" name="pseudo" maxlength="20" placeholder="Pseudo" value="<?php if(isset($_GET['pseudo'])) { echo htmlentities($_GET['pseudo']);}?>" title="Le pseudo désiré que vous aurez sur l'application" required>
                        </div>
                        <?php erreur("pseudo");?>

                        <div class="cp">
                            <label for="genre1">Civilité</label>
                            <div class="cprow">
                                <input type="radio" id="genre1" name="genre" value="Homme" <?php if(isset($_GET['genre'])) {if($_GET['genre'] == 'Homme') { ?> checked <?php }}?>/>
                                <label for="genre1">Homme</label>
                                <input type="radio" id="genre2" name="genre" value="Femme" <?php if(isset($_GET['genre'])) {if($_GET['genre'] == 'Femme') { ?> checked <?php }}?>/>
                                <label for="genre2">Femme</label>
                                <input type="radio" id="genre3" name="genre" value="Autre" <?php if(isset($_GET['genre'])) {if($_GET['genre'] == 'Autre') { ?> checked <?php }}?>/>
                                <label for="genre3">Autre</label>
                            </div>   
                            <?php erreur("genre");?>

                        </div> 

                        <div class="row-profil">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" maxlength="50" name="email" placeholder="E-Mail" value="<?php if(isset($_GET['email'])) { echo htmlentities($_GET['email']);}?>" title="Email devant contenir au moins un @ et un ." required>
                        </div>
                        <?php erreur("email");?>
                        

                        <div class="row-profil">
                            <label for="telephone">Téléphone</label>
                            <input type="tel" id="telephone" name="telephone" placeholder="XX XX XX XX XX" value="<?php if(isset($_GET['telephone'])) { echo htmlentities($_GET['telephone']);}?>" title="Numéro de téléphone en 10 chiffres" required>
                        </div>
                        <?php erreur("telephone");?>


                        <div class="row-profil">
                            <label for="date">Date de naissance</label>
                            <input type="date" id="date" name="date" value="<?php if(isset($_GET['date'])) { echo htmlentities($_GET['date']);}?>" required>
                            
                        </div>
                        <?php erreur("date");?>

                        <div class="row-profil-mdp">
                            <label for="motdepasse">Mot de passe</label>
                            <div class="mdpeye">
                                <input type="password" id="motdepasse" name="motdepasse" placeholder="Mot de passe" title="Votre mot de passe doit contenir au moins : une majuscule,une minuscule, un chiffre et caractère spécial" required/>
                                <img id="eye1" src="../svg/oeil.svg" alt="voir le mot de passe" onClick="changer('motdepasse', 'eye1')"/>
                            </div>
                        </div>
                        <?php erreur("motdepasse");?>

                        <div class="row-profil-mdp">
                            <label for="confirmationMDP">Confirmation du mot de passe</label>
                            <div class="mdpeye">
                                <input type="password" id="confirmationMDP" name="confirmationMDP" placeholder="Confirmation mot de passe " title="Votre mot de passe doit contenir au moins : une majuscule,une minuscule, un chiffre et caractère spécial" required/>
                                <img id="eye2" src="../svg/oeil.svg" alt="voir le mot de passe" onClick="changer('confirmationMDP', 'eye2')"/>
                            </div>
                        </div>
                        <?php erreur("confirmationMDP");?>

                        <div class="row-profil">
                        <label for="langue">Langue</label>
                            <select id="langue" name="langue">
                                <option value="none" hidden>Langue</option>
                                <option value="Français" <?php if(isset($_GET['langue'])) {if($_GET['langue'] == 'Français') { ?> selected <?php }}?>>Français</option>
                                <option value="Anglais" <?php if(isset($_GET['langue'])) {if($_GET['langue'] == 'Anglais') { ?> selected <?php }}?>>Anglais</option>
                                <option value="Espagnol" <?php if(isset($_GET['langue'])) {if($_GET['langue'] == 'Espagnol') { ?> selected <?php }}?>>Espagnol</option>
                                <option value="Allemand" <?php if(isset($_GET['langue'])) {if($_GET['langue'] == 'Allemand') { ?> selected <?php }}?>>Allemand</option>
                                <option value="Italien" <?php if(isset($_GET['langue'])) {if($_GET['langue'] == 'Italien') { ?> selected <?php }}?>>Italien</option>
                                <option value="Japonais" <?php if(isset($_GET['langue'])) {if($_GET['langue'] == 'Japonais') { ?> selected <?php }}?>>Japonais</option>
                                <option value="Chinois" <?php if(isset($_GET['langue'])) {if($_GET['langue'] == 'Chinois') { ?> selected <?php }}?>>Chinois</option>
                                <option value="Portugais" <?php if(isset($_GET['langue'])) {if($_GET['langue'] == 'Portugais') { ?> selected <?php }}?>>Portugais</option>
                                <!-- Ajoutez plus d'options ici -->
                            </select>
                        </div>
                        <?php erreur("langue");?>

                        <!-- section pour les informations de l'adresse -->
                        <hr class="hr" style="margin: 2em 0 0.5em 0 ;">
                        <h2 class="potitre" style="padding:0.75em;">Adresse</h2>
                        <div class="ligne_adresse">
                            <div class="numero_rue">
                                <label for="numRue">N° :</label>
                                <input type="text" id="numRue" name="numRue" maxlength="3" value="<?php if(isset($_GET['numRue'])) { echo htmlentities($_GET['numRue']);}?>" title="Numéro de votre rue" required>
                            </div>
                            

                            <div class="nom_rue">
                                <label for="nomRue">Rue :</label>
                                <input type="text" id="nomRue" name="nomRue" maxlength="30" value="<?php if(isset($_GET['nomRue'])) { echo htmlentities($_GET['nomRue']);}?>" title="Nom de votre rue" required>
                            </div>
                        </div> 
                        <?php erreur("numRue");?>
                        <?php erreur("nomRue");?>

                        <div class="ligne_adresse">  
                            <div class="code_postal">
                                <label for="codePostal">Code postal :</label>
                                <input type="text" maxlength="5" id="codePostal" name="codePostal" value="<?php if(isset($_GET['codePostal'])) { echo htmlentities($_GET['codePostal']);}?>" title="Code postal de votre ville en 5 chiffres" required> 
                            </div>
                            <div class="adresse_ville">
                                <label for="ville">Ville :</label>
                                <input type="text" id="ville" maxlength="50" name="ville" value="<?php if(isset($_GET['ville'])) { echo htmlentities($_GET['ville']);}?>" title="Nom de votre ville" required>
                            </div>
                        </div>
                        <?php erreur("codePostal");?>
                        <?php erreur("ville");?>


                    </div>
                    <!-- affichage de la photo de profil + input pour la modifier -->
                    <div>
                        <label for="photo">  <img src="../svg/anonyme.svg" id="avatar" class=".photo-avatar" title="photo de profil" alt="photo de profil"> Photo de profil</label>
                        <input type="file" id="photo" name="photo" required/>
                        <?php erreur("photo"); ?>
                        <hr class="hr2">
                        <div>
                            <label for="rib" class="label">RIB</label>
                            <input type="file" id="rib" name="rib" placeholder="Importer le document"/>
                            <?php erreur("rib");?>
                        </div>
                    </div>
                </div>

                <div class="condition">
                    <input type="checkbox" id="conditions" name="conditions" value="accepter" <?php if(isset($_GET['conditions'])) {if($_GET['conditions'] == 'accepter') { ?> checked <?php }}?> required/>
                    <label for="conditions">Accepter les conditions générales d'utilisations</label>
                    <?php
                        erreur("conditions");
                    ?>
                </div>

                <!-- boutton pour valider les changements -->
                <input class="submit-profil" type="submit" value="Créer le compte">
            </form>
    </main>



    <?php
        // appel du footer
        include('../header-footer/choose_footer.php'); 
    ?>
</body>

</html>

<script src="./actualiserPhoto.js" defer></script>