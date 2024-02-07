<?php
    include('../parametre_connexion.php');
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $stmt = $dbh->prepare("SELECT * 
        from locbreizh._signalement s
        join locbreizh._signalement_avis sa on s.id_signalement = sa.id_signalement");
    $stmt->execute();
    $signalementAvis = $stmt->fetch();
    print_r($signalementAvis);
    $stmt = $dbh->prepare("SELECT * 
        from locbreizh._signalement s
        join locbreizh._signalement_reponse sr on s.id_signalement = sr.id_signalement");
    $stmt->execute();
    $signalementReponse = $stmt->fetch();
    print_r($signalementReponse);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page admin</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<header>
    <a href="../Accueil/accueil_visiteur.php">
        <img class="logot" src="../svg/logobleu.svg">
        <h2 style="color:#274065;">Loc'Breizh</h2>
    </a>
    <div style="display : flex; align-items : center">
        <img style="width : 80px; height : 80px" src="../Ressources/Images/admin.gif">
        <h2 style="color:#274065;">Connecté en tant qu'admin</h2>
    </diV>
</header>
    <main>
        <h1>Liste des signalements</h1>
    </main>
    <footer class="footerP">
    <div class="tfooter">
        <p><a href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a></p>
        <p><a href="tel:+33623455689">(+33) 6 23 45 56 89</a></p>
        <a class="margintb" href=""><img src="../svg/instagrambleu.svg">  <p>@LocBreizh</p></a>
        <a  class="margintb" href=""><img src="../svg/facebookbleu.svg">  <p>@LocBreizh</p></a>
    </div>
    <hr>  
    <div class="bfooter">
        <p>©2023 Loc’Breizh</p>
        <p style="text-decoration: underline;"><a href="../Ressources/conditions/CGV.pdf" target="_blank" >Conditions générales</a></p>
        <p style="text-decoration: underline; margin-left: 30px;"><a href="../Ressources/conditions/Mentions_légales.pdf" target="_blank" >Mentions légales</a></p>
        <p>Développé par <a href="" style="text-decoration: underline;">7ème sens</a></p>
    </div>
</footer>

</body>
</html>