<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Page de connexion</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
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
    <header>
        <div> 
            <a href="../Accueil/accueil_visiteur.php"><img src="../svg/flecheRetour.svg"></a>

            <h1>Bienvenue sur Loc’Breizh !</h1>
        </div>
    </header>
    <main>
        <form action="connexionBack.php" method="post">
            <div>
                <input type="text" id="pseudo" name="pseudo" placeholder="Identifiant" value="<?php if(isset($_GET['pseudo'])) { echo htmlentities($_GET['pseudo']);}?>" />
                <?php
                    erreur("pseudo");
                ?>
            </div>
            <div>
                <input type="password" id="motdepasse" name="motdepasse" placeholder="Mot de passe"/>
                <?php
                    erreur("motdepasse");
                ?>
            </div>
            <button type="submit">Se connecter</button>
        </form>
        <article> 
            <span>Nouveau ici ? <a href="CreerCompte.html">Créer un compte</a> <a href="mdpOublierFront.php">Mot de passe oublié ?</a></span>
        </article>
       
    </main>


    <footer>
        <div>   
            <div>
                <p><a href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a></p>
                <p><a href="tel:+33623455689">(+33) 6 23 45 56 89</a></p>
                <p><a href="connexion.html"><img src="../svg/instagram.svg">  @LocBreizh</a></p>
                <p><a href="connexion.html"><img src="../svg/facebook.svg">  @LocBreizh</a></p>
            </div>
            <hr>  
            <div>
                <p>©2023 Loc’Breizh</p>
                <p><a href="connexion.html">Conditions générales</a></p>
                <p>Développé par <a href="connexion.html">7ème sens</a></p>
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