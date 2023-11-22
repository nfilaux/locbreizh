<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Page de connexion</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="pagecompte">
        <?php
        // On démarre la session
            session_start();

            // On vérifie si les variables de session et les cookies existent
            function erreur($nomErreur)
            {
                if(isset($_SESSION["erreurs"][$nomErreur])){
                    ?><p><?php echo $_SESSION["erreurs"][$nomErreur]?></p><?php
                    unset($_SESSION["erreurs"][$nomErreur]);
                }
            }
        ?>
    <header class="headconn">
            <a href="../Accueil/accueil_visiteur.php"><img src="../svg/flecheRetour.svg"></a>

            <h1>Bienvenue sur Loc’Breizh !</h1>

    </header>
    <main class="Maincompte">
        <section>
        <form action="connexionBack.php" method="post">
            <article>
                <input type="text" id="pseudo" name="pseudo" placeholder="Identifiant" class="custom-input" value="<?php if(isset($_GET['pseudo'])) { echo htmlentities($_GET['pseudo']);}?>" />
                <?php
                    erreur("pseudo");
                ?>
            </article>
            <br>
            <article>
                <input type="password" id="motdepasse" name="motdepasse" class="custom-input" placeholder="Mot de passe"/>
                <?php
                    erreur("motdepasse");
                ?>
            </article>
            <br>
            <button class="btn-compte" type="submit">Se connecter</button>
        </form>

        <article> 
            <div class="rowcompte">
                <p>Nouveau ici ?</p>
                <a href="CreerCompte.html">Créer un compte</a> 
                <a href="mdpOublierFront.php">Mot de passe oublié ?</a>
            </div>
        </article>
        </section>
    </main>
    <footer>
        <div class="tfooter">
            <p><a href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a></p>
            <p><a href="tel:+33623455689">(+33) 6 23 45 56 89</a></p>
            <a class="margintb" href="connexion.html"><img src="../svg/instagram.svg">  <p>@LocBreizh</p></a>
            <a  class="margintb" href="connexion.html"><img src="../svg/facebook.svg">  <p>@LocBreizh</p></a>
        </div>
        <hr>  
        <div class="bfooter">
            <p>©2023 Loc’Breizh</p>
            <p style="text-decoration: underline;"><a href="connexion.html">Conditions générales</a></p>
            <p>Développé par <a href="connexion.html" style="text-decoration: underline;">7ème sens</a></p>
        </div>
    </footer>
</body>

</html>

<!-- Partie stylisé des popup-->
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

<!-- Partie animé du profil d'une personne connecter -->
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