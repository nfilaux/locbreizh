<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Page de connexion</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        <div class="col-12 row text-center"> 
            <a class="offset-md-2 titre" href="connexionFront.php"><img src="svg/flecheRetour.svg"></a>

            <h1 class="col-8 text-center titre">Bienvenue sur Loc’Breizh !</h1>
        </div>
    </header>
    <main class="container offset-md-2 col-8">
        <form action="connexionBack.php" method="post">
            <div class="form-group mt-2">
                <input type="text" id="Login" style="font-size: 2em;" name="Login" class=" offset-md-2 col-8 text-center custom-input mb-5" placeholder="Identifiant" required />
            </div>
            <div class="form-group mt-2">
                <input type="password" id="motdepasse" style="font-size: 2em;" name="motdepasse" class=" offset-md-2 col-8  text-center custom-input mb-5" placeholder="Mot de passe" required/>
            </div>
            <button type="submit" class="btn-compte offset-md-3 col-6 mb-5 mt-5">Se connecter</button>
        </form>
        <article class="text-center mt-2"> 
            <span>Nouveau ici ? <a class="col-2" href="creerCompte.html">Créer un compte</a> <a class="offset-md-1 col-2" href="mdpOublierFront.php">Mot de passe oublié ?</a></span>
        </article>
       
    </main>


    <footer class="mt-4 container-fluid">
        <div class="mt-4 column">   
            <div class="col-12 text-center">
                <a class="col-2" href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a>
                <a class="offset-md-1 col-2" href="tel:+33623455689">(+33) 6 23 45 56 89</a>
                <a class="offset-md-1 col-1" href="connexion.html"><img src="svg/instagram.svg">  @LocBreizh</a>
                <a class="offset-md-1 col-1" href="connexion.html"><img src="svg/facebook.svg">  @LocBreizh</a>
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
