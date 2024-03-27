<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe Oublié</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body  class="pagecompte">
    <?php
            session_start();
            if(isset($_SESSION["msg"])){
                ?><p><?php echo $_SESSION["msg"]?></p><?php
                unset($_SESSION["msg"]);
            }
    ?>
    <header class="headconn">
            <a href="connexionFront.php"><img src="../svg/flecheRetour.svg" alt="fleche de retour"/></a>
            <h1>Récupération de compte</h1>
    </header>
    <main class="Maincompte">
        <section>
            <form action="mdpOublierBack.php" method="post">
                <article class="mdp">
                    <h2 class="policetitre">Renseignez votre adresse mail ou votre numéro de téléphone </h2>

                    <input class="custom-input" type="text" id="mail" name="mail" placeholder="Adresse mail ou numéro" required />

                    <input class="btn-compte" type="submit" value="Lancer la récupération" />
                    
                    <a href="connexionFront.php">Revenir à la page de connexion</a>
                </article>
            </form>
        </section>
    </main>

    <?php
        // appel du footer
        include('../header-footer/choose_footer.php'); 
    ?>
</body>
</html>