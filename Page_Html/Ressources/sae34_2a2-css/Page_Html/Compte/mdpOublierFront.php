<!DOCTYPE html>
<html lang="en">
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
            <a href="connexionFront.php"><img src="../svg/flecheRetour.svg"/></a>
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