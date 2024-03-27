<?php
    include('../parametre_connexion.php');
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $stmt = $dbh->prepare("SELECT avis, c1.id_compte, date_signalement, c1.nom, c1.prenom, c1.photo, c2.id_compte id_compte_sig, c2.photo photo_sig, c2.nom nom_sig, c2.prenom prenom_sig,note_avis, contenu_avis, motif, s.id_signalement, date_signalement, s.id_signalement
        from locbreizh._signalement s
        join locbreizh._signalement_avis sa on s.id_signalement = sa.id_signalement
        join locbreizh._avis a on a.id_avis = sa.avis
        join locbreizh._compte c1 on a.auteur = c1.id_compte
        join locbreizh._compte c2 on sa.auteur = c2.id_compte");
    $stmt->execute();
    $signalementAvis = $stmt->fetchAll();

    $stmt = $dbh->prepare("SELECT reponse, c1.id_compte, date_signalement, c1.nom, c1.prenom, c1.photo, c2.id_compte id_compte_sig, c2.photo photo_sig, c2.nom nom_sig, c2.prenom prenom_sig, contenu_reponse, motif, s.id_signalement, date_signalement, s.id_signalement
        from locbreizh._signalement s
        join locbreizh._signalement_reponse sr on s.id_signalement = sr.id_signalement
        join locbreizh._reponse r on r.id_reponse = sr.reponse
        join locbreizh._compte c1 on r.auteur = c1.id_compte
        join locbreizh._compte c2 on sr.auteur = c2.id_compte");
    $stmt->execute();
    $signalementReponse = $stmt->fetchAll();
    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page admin</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<header>
    <a href="../Accueil/accueil_visiteur.php">
        <img class="logot" src="../svg/logobleu.svg" alt="logo de Loc'Breizh">
        <h2 style="color:#274065;">Loc'Breizh</h2>
    </a>
    <div style="display : flex; align-items : center">
        <img style="width : 80px; height : 80px" src="../Ressources/Images/admin.gif" alt="administration">
        <h2 style="color:#274065;">Connecté en tant qu'admin</h2>
    </diV>
</header>
    <main>
        <h1 id="titre-signalement">Liste des signalements avis/reponse</h1>
        <div class="all-avis">
                <?php
                if($signalementAvis === []){ ?>
                    <h4>Aucun signalement en cours</h4>
                <?php
                }
                foreach ($signalementAvis as $avi) {
                ?>
                <div class="box-avis">
                    <div class="avis-box-space-between">
                        <div class="header-box infoC">
                            <img src="../Ressources/Images/<?php echo $avi['photo'];?>" alt="Image de profil" title="Photo">
                            <div>
                                <p><?php echo $avi['prenom'] . ' ' . $avi['nom'];?></p>
                                <hr>
                            </div>
                        </div>
                        <div class="header-box">
                            <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid" fill="#ffa723">
                            <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path></svg>
                            <p><?php echo $avi['note_avis'];?>/5</p>
                        </div>
                    </div>
                    <p><?php echo $avi['contenu_avis'];?></p>
                
                    <hr class="hr">
                    <div class="avis-box-space-between">
                        <p>Signalement fait par</p>
                        <div class="header-box infoC">
                            
                            <img src="../Ressources/Images/<?php echo $avi['photo_sig'];?>" alt="Image de profil" title="Photo">
                            <div>
                                <p><?php echo $avi['prenom_sig'] . ' ' . $avi['nom_sig'];?></p>
                                <hr>
                            </div>
                        </div>
                        <p>le <?php echo $avi['date_signalement'];?></p>
                    </div>

                    <h4 class="sign-h4">- Informations utiles -</h4>
                    <div>
                        <p>Motif du signalement &rarr; <?php echo $avi['motif'];?></p>
                        <ul>
                            <li>Type de message &rarr; avis</li>
                            <li>Id du signalement &rarr; <?php echo $avi['id_signalement'];?></li>
                            <li>Id de l'avis &rarr; <?php echo $avi['avis'];?></li>
                            <li>Id du compte signalé &rarr; <?php echo $avi['id_compte'];?></li>
                            <li>Id du compte qui a signalé &rarr; <?php echo $avi['id_compte_sig'];?></li>
                        </ul>
                    </div>
                </div>

                <?php } 
                foreach ($signalementReponse as $reponse) {
                ?>
                <div class="box-avis">
                    <div class="avis-box-space-between">
                        <div class="header-box infoC">
                            <img src="../Ressources/Images/<?php echo $reponse['photo'];?>" alt="Image de profil" title="Photo">
                            <div>
                                <p><?php echo $reponse['prenom'] . ' ' . $reponse['nom'];?></p>
                                <hr>
                            </div>
                        </div>
                    </div>
                    <p><?php echo $avi['contenu_avis'];?></p>
                
                    <hr class="hr">
                    <div class="avis-box-space-between">
                        <p>Signalement fait par</p>
                        <div class="header-box infoC">
                            
                            <img src="../Ressources/Images/<?php echo $reponse['photo_sig'];?>" alt="Image de profil" title="Photo">
                            <div>
                                <p><?php echo $reponse['prenom_sig'] . ' ' . $reponse['nom_sig'];?></p>
                                <hr>
                            </div>
                        </div>
                        <p>le <?php echo $reponse['date_signalement'];?></p>
                    </div>

                    <h4 class="sign-h4">- Informations utiles -</h4>
                    <div>
                        <p>Motif du signalement &rarr; <?php echo $reponse['motif'];?></p>
                        <ul>
                            <li>Type de message &rarr; réponse à un avis</li>
                            <li>Id du signalement &rarr; <?php echo $reponse['id_signalement'];?></li>
                            <li>Id de la réponse &rarr; <?php echo $reponse['reponse'];?></li>
                            <li>Id du compte signalé &rarr; <?php echo $reponse['id_compte'];?></li>
                            <li>Id du compte qui a signalé &rarr; <?php echo $reponse['id_compte_sig'];?></li>
                        </ul>
                    </div>
                </div>

            <?php } ?>
            </div>
    </main>
    <footer class="footerP">
    <div class="tfooter">
        <p><a href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a></p>
        <p><a href="tel:+33623455689">(+33) 6 23 45 56 89</a></p>
        <a class="margintb" href=""><img src="../svg/instagrambleu.svg" alt="instagram">  <p>@LocBreizh</p></a>
        <a  class="margintb" href=""><img src="../svg/facebookbleu.svg" alt="Facebook">  <p>@LocBreizh</p></a>
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