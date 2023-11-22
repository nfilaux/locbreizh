<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe Oublié</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php
            session_start();
            if(isset($_SESSION["msg"])){
                ?><p><?php echo $_SESSION["msg"]?></p><?php
                unset($_SESSION["msg"]);
            }
    ?>
    <header>
        
        <div> 
            <a href="connexionFront.php"><img src="svg/flecheRetour.svg"/></a>
            <h1>Récupération de compte</h1>
        </div>
    </header>
    <main>

        <form action="mdpOublierBack.php" method="post">

        <h2>Renseignez votre adresse mail ou votre numéro de téléphone </h2>

        <input type="text" id="mail" name="mail" placeholder="Adresse mail ou numéro" required />

        <input type="submit" value="Lancer la récupération" />
        </form>
        <article> 
            <a href="connexionFront.php">Revenir à la page de connexion</a>
        </article>
    </main>

    <footer>
        <div>   
            <div>
                <a href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a>
                <a href="tel:+33623455689">(+33) 6 23 45 56 89</a>
                <a href="connexion.html"><img src="svg/instagram.svg">  @LocBreizh</a>
                <a href="connexion.html"><img src="svg/facebook.svg">  @LocBreizh</a>
            </div>
            <hr>  
            <div>
                <p>©2023 Loc’Breizh</p>
                <p><a href="connexion.html">Conditions générales</a></p>
                <p>Développé par <a href="connexion.html" style="text-decoration: underline;">7ème sens</a></p>
            </div>
        </div>
    </footer>
</body>
</html>