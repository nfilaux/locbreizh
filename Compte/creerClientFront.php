<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <main class="container offset-md-2 col-8">
        <?php
            session_start();

            function erreur($nomErreur)
            {
                if(isset($_SESSION["erreurs"][$nomErreur])){
                    ?><p><?php echo $_SESSION["erreurs"][$nomErreur]?></p><?php
                    unset($_SESSION["erreurs"][$nomErreur]);
                }
            }
        ?>
        <div class="mb-5 col-12 row h-10"> 
            <a class="col-1" href="connexionFront.php"><img src="svg/flecheRetour.svg"/></a>
            <h1  class="offset-md-0 col-8">Créer mon compte client  !</h1>
        </div>
        <form class="mt-5" action="creerClientBack.php" method="post" enctype="multipart/form-data">
            
            <div class="col-12">
                <input type="text" id="prenom" name="prenom"  style="height: 6vw;font-size: 2em;"  class="custom-input col-5 text-center" placeholder="Prenom" value="<?php if(isset($_GET['prenom'])) { echo htmlentities($_GET['prenom']);}?>"/>
                <?php
                    erreur("prenom");
                ?>
                <input type="text" id="nom" name="nom"  style="height: 6vw;font-size: 2em;"  class="custom-input col-5 text-center" placeholder="Nom" value="<?php if(isset($_GET['nom'])) { echo htmlentities($_GET['nom']);}?>"/>
                <?php
                    erreur("nom");
                ?>
            </div>
            
            <br />

            <label>Civilité</label>
            <input type="radio" id="genre1" name="genre" value="Homme" <?php if(isset($_GET['genre'])) {if($_GET['genre'] == 'Homme') { ?> checked <?php }}?>/>
            <label for="genre1">Homme</label>
            <input type="radio" id="genre2" name="genre" value="Femme" <?php if(isset($_GET['genre'])) {if($_GET['genre'] == 'Femme') { ?> checked <?php }}?>/>
            <label for="genre2">Femme</label>
            <input type="radio" id="genre3" name="genre" value="Autre" <?php if(isset($_GET['genre'])) {if($_GET['genre'] == 'Autre') { ?> checked <?php }}?>/>
            <label for="genre2">Autre</label>
            <?php
                erreur("genre");
            ?>
        
            <br />

            <label for="fichier">Carte d’identité</label>
            <input type="file" id="carteIdentite" name="carteIdentite"/>
            <?php
                erreur("carteIdentite");
            ?>
            
            <br />

            <input type="email" id="email" name="email" placeholder="Mail" value="<?php if(isset($_GET['email'])) { echo htmlentities($_GET['email']);}?>"/>
            <?php
                erreur("email");
            ?>
            <br />

            <input type="tel" id="telephone" name="telephone" placeholder="Téléphone" value="<?php if(isset($_GET['telephone'])) { echo htmlentities($_GET['telephone']);}?>"/>
            <?php
                erreur("telephone");
            ?>
            <br />

            <input type="text" id="pseudo" name="pseudo" placeholder="Pseudo" value="<?php if(isset($_GET['pseudo'])) { echo htmlentities($_GET['pseudo']);}?>"/>
            <?php
                erreur("pseudo");
            ?>
            <br />

            <label for="fichier">Photo de profil</label>
            <input type="file" id="photoProfil" name="photoProfil" placeholder="Importer le document"/>
            <?php
                erreur("photoProfil");
            ?>
            <br />

            <input type="password" id="motdepasse" name="motdepasse" placeholder="Password"/>
            <?php
                erreur("motdepasse");
            ?>
            <br />

            <input type="password" id="confirmationMDP" name="confirmationMDP" placeholder="Confirmation Mot de passe"/>
            <?php
                erreur("confirmationMDP");
            ?>
            <br />

            <input type="text" id="ville" name="ville" placeholder="Ville" value="<?php if(isset($_GET['ville'])) { echo htmlentities($_GET['ville']);}?>"/>
            <?php
                erreur("ville");
            ?>
            <br />

            <input type="text" id="codePostal" name="codePostal" placeholder="Code postal" value="<?php if(isset($_GET['codePostal'])) { echo htmlentities($_GET['codePostal']);}?>"/>
            <?php
                erreur("codePostal");
            ?>
            <br />

            <input type="text" id="numRue" name="numRue" placeholder="N° Rue" value="<?php if(isset($_GET['numRue'])) { echo htmlentities($_GET['numRue']);}?>"/>
            <?php
                erreur("numRue");
            ?>
            <br />

            <input type="text" id="nomRue" name="nomRue" placeholder="Nom de  la rue" value="<?php if(isset($_GET['nomRue'])) { echo htmlentities($_GET['nomRue']);}?>"/>
            <?php
                erreur("nomRue");
            ?>
            <br />

            <input type="submit" value="Se connecter"/>

        </form>

    </main>

    <footer class="mt-4 container-fluid">
        <div class="mt-4 column">
            <div class="col-12 text-center">
                <a class="col-3" href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a>
                <a class="offset-md-2 col-1" href="tel:+33623455689">(+33) 6 23 45 56 89</a>
                <a class="offset-md-2 col-1" href="connexion.html"><img src="svg/instagram.svg">  @LocBreizh</a>
                <a class="offset-md-2 col-1" href="connexion.html"><img src="svg/facebook.svg">  @LocBreizh</a>
            </div>
            <hr>  
            <div class="offset-md-1 col-10 mt-4 text-center row">
                <p class="offset-md-1 col-2">©2023 Loc’Breizh</p>
                <p class="offset-md-1 col-3" style="text-decoration: underline;"><a href="connexion.html">Conditions générales</a></p>
                <p class="offset-md-1 col-4" >Développé par <a href="connexion.html" style="text-decoration: underline;">7ème sens</a></p>
            </div>
        </div>
    </footer>
</body>
</html>