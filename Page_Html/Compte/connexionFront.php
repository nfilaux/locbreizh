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

            <h1 class="col-8 text-center titre">Bienvenue sur Loc’Breizh !</h1>
        </div>
    </header>
    <main class="container offset-md-2 col-8">
        <form action="connexionBack.php" method="post">
            <div class="form-group mt-2">
                <input type="text" id="pseudo" style="font-size: 2em;" name="pseudo" class=" offset-md-2 col-8 text-center custom-input mb-5" placeholder="Identifiant" value="<?php if(isset($_GET['pseudo'])) { echo htmlentities($_GET['pseudo']);}?>" />
                <?php
                    erreur("pseudo");
                ?>
            </div>
            <div class="form-group mt-2">
                <input type="password" id="motdepasse" style="font-size: 2em;" name="motdepasse" class=" offset-md-2 col-8  text-center custom-input mb-5" placeholder="Mot de passe"/>
                <?php
                    erreur("motdepasse");
                ?>
            </div>
            <button type="submit" class="btn-compte offset-md-3 col-6 mb-5 mt-5">Se connecter</button>
        </form>
        <article class="text-center mt-2"> 
            <span>Nouveau ici ? <a class="col-2" href="CreerCompte.html">Créer un compte</a> <a class="offset-md-1 col-2" href="mdpOublierFront.php">Mot de passe oublié ?</a></span>
        </article>
       
    </main>
    <?php 
        echo file_get_contents('../header-footer/footer.html');
    ?>
</body>

</html>