<?php
        // On démarre la session
            session_start();

            // On vérifie si les variables de session et les cookies existent
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
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Page de connexion</title>
    <link rel="stylesheet" href="../style.css">

<script src="../scriptMDP.js"></script>

</head>
<body class="pagecompte">
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
            <article id="mdp">
                <input type="password" id="motdepasse" name="motdepasse" class="custom-input" placeholder="Mot de passe"/>
                <img src="../svg/oeil.svg" id="eye" onClick="changer('motdepasse', 'eye')"/>
            </article>
            <?php
                    erreur("motdepasse");
                ?>

            <br>
            <button class="btn-compte" type="submit">Se connecter</button>
        </form>

        <article> 
            <div class="rowcompte">
                <p>Nouveau ici ?</p>
                <a href="CreerCompte.html">Créer un compte</a> 
                <a href="">Mot de passe oublié ?</a>
            </div>
        </article>
        </section>
    </main>
    <?php 
        // appel du footer
        echo file_get_contents('../header-footer/footer.html');
    ?>
</body>

</html>