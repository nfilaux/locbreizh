<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="pagecompte">
    <?php
        session_start();
        function erreur($nomErreur)
        {
            // si on trouve une erreur dans la variable $_SESSION 
            if(isset($_SESSION["erreurs"][$nomErreur])){
                // on affiche l'erreur en question
                ?><p><?php echo $_SESSION["erreurs"][$nomErreur]?></p><?php
                /* puis on supprime l'erreur car il n'y a plus d'erreur à afficher si l'utilisateur retape bien l'attribut
                 sinon l'erreur sera à nouveau affiché */
                unset($_SESSION["erreurs"][$nomErreur]);
            }
        }
    ?>
    <header class="headconn">
            <a href="CreerCompte.html"><img src="../svg/flecheRetour.svg"/></a>
            <h1>Créer mon compte client  !</h1>
    </header>
    <main class="container offset-md-1 col-12">
        <?php // creation du formulaire, si la valeur entré n'est pas bonne on affiche l'erreur de l'attribut en question ?>
        <form action="creerClientBack.php" method="post" enctype="multipart/form-data">
            
            <div>
                <?php // si le prenom est rempli alors sa valeur est la valeur rempli
                // celà sert à remettre la valeur du prénom en cas d'erreur sur un autre champs, ça évite à l'utilisateur de réentrer toutes ses infomations à chaque erreur ?>
                <input type="text" id="prenom" name="prenom" class="custom-input col-5 text-center" placeholder="Prenom" value="<?php if(isset($_GET['prenom'])) { echo htmlentities($_GET['prenom']);}?>" />
                <?php
                // appel de la fonction erreur qui va permettre d'afficher un message d'erreur en cas de format du prénom invalide
                    erreur("prenom");
                ?>
                <input type="text" id="nom" name="nom" class="custom-input col-5 text-center" placeholder="Nom" value="<?php if(isset($_GET['nom'])) { echo htmlentities($_GET['nom']);}?>" />
                <?php
                    erreur("nom");
                ?>
            </div>
                <div class="row text-center">
                    <div class="column col-5">
                        <label >Civilité</label>
                        <div>
                            <input type="radio" id="genre1" name="genre" value="Homme" <?php if(isset($_GET['genre'])) {if($_GET['genre'] == 'Homme') { ?> checked <?php }}?>/>
                            <label for="genre1">Homme</label>
                            <input type="radio" id="genre2" name="genre" value="Femme" <?php if(isset($_GET['genre'])) {if($_GET['genre'] == 'Femme') { ?> checked <?php }}?>/>
                            <label for="genre2">Femme</label>
                            <input type="radio" id="genre3" name="genre" value="Autre" <?php if(isset($_GET['genre'])) {if($_GET['genre'] == 'Autre') { ?> checked <?php }}?>/>
                            <label for="genre3">Autre</label>
                            <?php
                                erreur("genre");
                            ?>
                        </div>   
                    </div>    
                    <div class="row col-5">
                            <label for="carteIdentite">Carte d’identité</label>
                            <input class="offset-md-1 col-5" type="file" id="carteIdentite" name="carteIdentite" value="Importer le document" />
                            <?php
                                erreur("carteIdentite");
                            ?>
                            <label class="text-center" for="photoProfil">Photo de profil</label>
                            <input class="offset-md-1 col-5" type="file" id="photoProfil" name="photoProfil" placeholder="Importer le document"/>
                            <?php
                                erreur("photoProfil");
                            ?>
                    </div>   
                </div>

                <div>
                    
                    <input type="text" id="pseudo" name="pseudo" class="col-5 text-center custom-input" placeholder="Pseudo" value="<?php if(isset($_GET['pseudo'])) { echo htmlentities($_GET['pseudo']);}?>" />
                    <?php
                        erreur("pseudo");
                    ?>
                    <input type="text" id="telephone" name="telephone"   class="col-5 text-center custom-input" placeholder="Téléphone" value="<?php if(isset($_GET['telephone'])) { echo htmlentities($_GET['telephone']);}?>"/>
                    <?php
                        erreur("telephone");
                    ?>
                </div>

            <div> 
                <input type="text" id="email" name="email"   class="custom-input col-5 text-center" placeholder="Mail" value="<?php if(isset($_GET['email'])) { echo htmlentities($_GET['email']);}?>"/>
                <?php
                    erreur("email");
                ?>
                <input type="date" id="date" name="date"   class="custom-input col-5 text-center" placeholder="Date" value="<?php if(isset($_GET['date'])) { echo htmlentities($_GET['date']);}?>"/>
                <?php
                    erreur("date");
                ?>
            </div>
            </div>

            <div>
                <input type="password" id="motdepasse" name="motdepasse" class="custom-input col-5 text-center" placeholder="Mot de passe"/>
                <?php
                    erreur("motdepasse");
                ?>
                <input type="password" id="confirmationMDP" name="confirmationMDP"   class="custom-input col-5 text-center" placeholder="Confirmation Mot de passe"/>
                <?php
                    erreur("confirmationMDP");
                ?>
            </div>

            <div>
            <input type="text" id="ville" name="ville"   class="custom-input col-5 text-center" placeholder="Ville" value="<?php if(isset($_GET['ville'])) { echo htmlentities($_GET['ville']);}?>" />
            <?php
                erreur("ville");
            ?>
            <input type="text" id="codePostal" name="codePostal"   class="custom-input col-5 text-center" placeholder="Code postal" value="<?php if(isset($_GET['codePostal'])) { echo htmlentities($_GET['codePostal']);}?>" />
            <?php
                erreur("codePostal");
            ?>
            </div>

            <div>
            <input type="text" id="numRue" name="numRue"   class="custom-input col-5 text-center" placeholder="N° Rue" value="<?php if(isset($_GET['numRue'])) { echo htmlentities($_GET['numRue']);}?>" />
            <?php
                erreur("numRue");
            ?>
            <input type="text" id="nomRue" name="nomRue" class="custom-input col-5 text-center" placeholder="Nom de  la rue" value="<?php if(isset($_GET['nomRue'])) { echo htmlentities($_GET['nomRue']);}?>" />
            <?php
                erreur("nomRue");
            ?>
            </div>

            <div>
                <input type="checkbox" id="conditions" name="conditions" value="accepter" <?php if(isset($_GET['conditions'])) {if($_GET['conditions'] == 'accepter') { ?> checked <?php }}?>/>
                <label for="conditions">Accepter les conditions générales d'utilisations</label>
                <?php
                    erreur("conditions");
                ?>
            </div>

            <input class="btn-compte offset-md-3 col-4 mb-5" type="submit" value="Se connecter" />

        </form>

    </main>

    <footer class="container-fluid" >
        <div class="column">   
            <div class="text-center row">
                <p class="testfoot col-2"><a href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a></p>
                <p class="testfoot offset-md-2 col-2"><a href="tel:+33623455689">(+33) 6 23 45 56 89</a></p>
                <p class="testfoot offset-md-1 col-2"><a href="connexion.html"><img src="../svg/instagram.svg">  @LocBreizh</a></p>
                <p class="testfoot offset-md-1 col-2  "><a href="connexion.html"><img src="../svg/facebook.svg">  @LocBreizh</a></p>
            </div>
            <hr>  
            <div class="text-center row">
                <p class="offset-md-1 col-2 testfooter">©2023 Loc’Breizh</p>
                <p class="offset-md-1 col-3 testfooter" style="text-decoration: underline;"><a href="connexion.html">Conditions générales</a></p>
                <p class="offset-md-1 col-4 testfooter" >Développé par <a href="connexion.html" style="text-decoration: underline;">7ème sens</a></p>
            </div>
        </div>
    </footer>
</body>

</html>


<style>
    .popup {
        display: none;
        position: fixed;
        top: 15%;
        left: 91%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        border: 1px solid #ccc;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }
</style>
<script>
// Ouvrir la popup
function openPopup() {
var popup = document.getElementById('popup');
popup.style.display = 'block';
}

// Fermer la popup
function closePopup() {
var popup = document.getElementById('popup');
popup.style.display = 'none';
}

// Ajouter des gestionnaires d'événements aux boutons
var profilButton = document.getElementById('profilButton');
profilButton.addEventListener('click', function() {
alert('Accéder au profil');
closePopup();
});

var deconnexionButton = document.getElementById('deconnexionButton');
deconnexionButton.addEventListener('click', function() {
alert('Se déconnecter');
closePopup();
});
</Script>