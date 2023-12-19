<?php
    session_start();
    function erreur($nomErreur)
    {
        // si on trouve une erreur dans la variable $_SESSION 
        if(isset($_SESSION["erreurs"][$nomErreur])){
            // on affiche l'erreur en question
            ?><p><?php echo $_SESSION["erreurs"][$nomErreur]?></p><?php
            /* puis on supprime l'erreur car il n'y a plus d'erreur à afficher si l'utilisateur retape bien l'attribut
                sinon l'erreur sera à nouveau affiché */
            unset($_SESSION["erreurs"][$nomErreur]);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../scriptMDP.js"></script>
</head>
<body class="pagecompte">
    <header class="headconn">
            <a href="CreerCompte.html"><img src="../svg/flecheRetour.svg"/></a>
            <h1>Créer mon compte client  !</h1>
    </header>
    <main>
        <!--
        <?php /* creation du formulaire, si la valeur entré n'est pas bonne on affiche l'erreur de l'attribut en question ?>
        <form action="creerClientBack.php" method="post" enctype="multipart/form-data">
            
            <div class="rowcompte">
                <?php // si le prenom est rempli alors sa valeur est la valeur rempli
                // celà sert à remettre la valeur du prénom en cas d'erreur sur un autre champs, ça évite à l'utilisateur de réentrer toutes ses infomations à chaque erreur ?>
            <div style="width: 48%;" class="messerr">   
                <input class="testinput" type="text" id="prenom" name="prenom" placeholder="Prenom" value="<?php if(isset($_GET['prenom'])) { echo htmlentities($_GET['prenom']);}?>" required/>
                <?php
                // appel de la fonction erreur qui va permettre d'afficher un message d'erreur en cas de format du prénom invalide
                    erreur("prenom");
                ?>
                </div>
                <div style="width: 48%;" class="messerr">
                <input class="testinput" type="text" id="nom" name="nom" placeholder="Nom" value="<?php if(isset($_GET['nom'])) { echo htmlentities($_GET['nom']);}?>" required/>
                <?php
                    erreur("nom");
                ?>
                </div>
            </div>
                <div class="spbt">
                    <div class="cp">
                        <label class="center">Civilité</label>
                        <div class="cprow">
                            <input type="radio" id="genre1" name="genre" value="Homme" <?php if(isset($_GET['genre'])) {if($_GET['genre'] == 'Homme') { ?> checked <?php }}?>/>
                            <label for="genre1">Homme</label>
                            <input type="radio" id="genre2" name="genre" value="Femme" <?php if(isset($_GET['genre'])) {if($_GET['genre'] == 'Femme') { ?> checked <?php }}?>/>
                            <label for="genre2">Femme</label>
                            <input type="radio" id="genre3" name="genre" value="Autre" <?php if(isset($_GET['genre'])) {if($_GET['genre'] == 'Autre') { ?> checked <?php }}?>/>
                            <label for="genre3">Autre</label>
                            <?php
                                erreur("genre");
                            ?>
                        </div>   
                    </div>  

                    <div class="cp">
                        <div class="cprow">
                            <label for="carteIdentite">Carte d’identité</label>
                            <input class="margl" type="file" id="carteIdentite" name="carteIdentite" value="Importer le document" required/>
                            <?php
                                erreur("carteIdentite");
                            ?>
                        </div>
                        <div class="cprow">
                            <label for="photoProfil">Photo de profil</label>
                            <input class="margl" type="file" id="photoProfil" name="photoProfil" placeholder="Importer le document" required/>
                            <?php
                                erreur("photoProfil");
                            ?>
                        </div>
                    </div>   
                </div>

                <div class="rowcompte">
                    <div style="width: 48%;" class="messerr">
                    <input class="testinput"  type="text" id="pseudo" name="pseudo" placeholder="Pseudo" value="<?php if(isset($_GET['pseudo'])) { echo htmlentities($_GET['pseudo']);}?>" required/>
                    <?php
                        erreur("pseudo");
                    ?>
                    </div>
                    <div style="width: 48%;" class="messerr">
                    <input class="testinput"  type="text" id="telephone" name="telephone" placeholder="Téléphone" value="<?php if(isset($_GET['telephone'])) { echo htmlentities($_GET['telephone']);}?>" required/>
                    <?php
                        erreur("telephone");
                    ?>
                    </div>
                </div>

            <div class="rowcompte"> 
                <div style="width: 48%;" class="messerr">
                <input class="testinput"  type="text" id="email" name="email" placeholder="Mail" value="<?php if(isset($_GET['email'])) { echo htmlentities($_GET['email']);}?>" required/>
                <?php
                    erreur("email");
                ?>
                </div>
                <div style="width: 48%;" class="messerr">
                <input class="testinput"  type="date" id="date" name="date" placeholder="Date" value="<?php if(isset($_GET['date'])) { echo htmlentities($_GET['date']);}?>" required/>
                <?php
                    erreur("date");
                ?>
                </div>
            </div>

            <div class="rowcompte">
                <div style="width: 48%;" class="messerr">
                <div>
                <input class="testinput" type="password" id="motdepasse" name="motdepasse" placeholder="Mot de passe" required/>
                <img id="eye1" src="../svg/oeil.svg" onClick="changer('motdepasse', 'eye1')"/></div>
                <?php
                    erreur("motdepasse");
                ?>
                </div>
                <div style="width: 48%;" class="messerr">
                <div>
                <input class="testinput" type="password" id="confirmationMDP" name="confirmationMDP" placeholder="Confirmation Mdp" required/>
                <img id="eye2" src="../svg/oeil.svg"  onClick="changer('confirmationMDP', 'eye2')"/></div>
                <?php
                    erreur("confirmationMDP");
                ?>
                </div>
            </div>


            <div class="rowcompte">
                <div style="width: 48%;" class="messerr">
                <input class="testinput" type="text" id="ville" name="ville" placeholder="Ville" value="<?php if(isset($_GET['ville'])) { echo htmlentities($_GET['ville']);}?>" required/>
                <?php
                    erreur("ville");
                ?>
                </div>
                <div style="width: 48%;" class="messerr">
                <input class="testinput" type="text" id="codePostal" name="codePostal" placeholder="Code postal" value="<?php if(isset($_GET['codePostal'])) { echo htmlentities($_GET['codePostal']);}?>" required/>
                <?php
                    erreur("codePostal");
                ?>
                </div>
            </div>

            <div class="rowcompte">
                <div style="width: 20%;" class="messerr">
                <input class="nbrueinput" type="text" id="numRue" name="numRue" placeholder="N° Rue" value="<?php if(isset($_GET['numRue'])) { echo htmlentities($_GET['numRue']);}?>" required/>
                <?php
                    erreur("numRue");
                ?>
                </div>
                <div style="width: 100%;" class="messerr">
                <input class="nomrueinput" type="text" id="nomRue" name="nomRue" placeholder="Nom de  la rue" value="<?php if(isset($_GET['nomRue'])) { echo htmlentities($_GET['nomRue']);}?>" required/>
                <?php
                    erreur("nomRue");
                ?>
                </div>
            </div>

            <div class="condition">
                <input type="checkbox" id="conditions" name="conditions" value="accepter" <?php if(isset($_GET['conditions'])) {if($_GET['conditions'] == 'accepter') { ?> checked <?php }}?> required/>
                <label for="conditions">Accepter les conditions générales d'utilisations</label>
                <?php
                    erreur("conditions");
                */?>
            </div>

            <input class="btn-input" type="submit" value="Créer le compte" />

        </form>-->

        <h1 class="header-profil">Mon compte</h1>
        <!-- debut du formulaire pour afficher/modifier les informations "simples" du compte -->
        <form action="modifier_client.php" method="post" enctype="multipart/form-data">
            <div class="profil-form">
                <div class="compte-infos">
                    <div class="row-profil">
                        <label for="prenom">Prénom</label>
                        <!-- input pour pouvoir modifier l'information + pré-replissage -->
                        <input type="text" id="prenom" name="prenom" maxlength="20" value="<?php echo $infos['prenom'];?>" required>
                        <!-- affichage des possibles erreurs (même chose pour les prochains appels de la fonction) -->
                    </div>
                    <?php erreur("prenom");?>
                    <div class="row-profil">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" maxlength="20" value="<?php echo $infos['nom'];?>" required>
                        
                    </div>
                    <?php erreur("nom");?>
                    <div class="row-profil">
                        <label for="pseudo">Pseudo</label>
                        <input type="text"  id="pseudo" name="pseudo" maxlength="20" value="<?php echo $infos['pseudo'];?>" required>
                        
                    </div>
                    <?php erreur("pseudo");?>
                    <div class="row-profil">
                        <label for="mail">E-mail</label>
                        <input type="email" id="mail" maxlength="50" name="mail" value="<?php echo $infos['mail'];?>" required>
                        
                    </div>
                    <?php erreur("email");?>
                    <div class="row-profil">
                        <label for="telephone">Téléphone</label>
                        <input type="tel" id="telephone" name="telephone" value="<?php echo substr($infos['telephone'], 0,2) . ' ' . substr($infos['telephone'], 2,2) . ' ' . substr($infos['telephone'], 4,2) . ' ' . substr($infos['telephone'], 6,2) . ' ' . substr($infos['telephone'], 8,2);?>" required>
                        
                    </div>
                    <?php erreur("telephone");?>
                    <div class="row-profil">
                        <label for="date">Date de naissance</label>
                        <input type="date" id="date" name="date" value=<?php 
                            //on cree un objet date pouvoir l'afficher dans l'input
                            $date = new DateTime($infos['datenaissance']);

                            // le format annee-mois-jour
                            $date_formatee = $date->format('Y-m-d');
                        
                            echo $date_formatee;
                        ?> required>
                        
                    </div>
                    <?php erreur("date");?>
                    <!-- section pour les informations de l'adresse -->
                    <div class="row-profil div-adresse">
                        <p>Adresse</p>
                        <div>
                            <div class="row-adresse">
                                <div>
                                    <label for="no_rue">N° :</label>
                                    <input type="text" id="no_rue" name="no_rue" maxlength="3" value="<?php echo $infos['numero_rue'];?>" required>
                                </div>
                                    
                                <div>
                                    <label for="nom_rue">Rue :</label>
                                    <input type="text" id="nom_rue" name="nom_rue" maxlength="30" value="<?php echo $infos['nom_rue'];?>" required>
                                    
                                </div>
                            </div>
                            <?php erreur("numRue");?>
                            <?php erreur("nomRue");?>
                            <div class="row-adresse">
                                <div>
                                    <label for="codePostal">Code postal :</label>
                                    <input type="text" maxlength="5" id="codePostal" name="codePostal" value="<?php echo $infos['code_postal'];?>" required> 
                                </div>
                                <div>
                                    <label for="ville">Ville :</label>
                                    <input type="text" id="ville" maxlength="50" name="ville" value="<?php echo $infos['ville'];?>" required>
                                </div>
                            </div>
                            <?php erreur("codePostal");?>
                            <?php erreur("ville");?>
                        </div>
                    </div>
                </div>
                <!-- affichage de la photo de profil + input pour la modifier -->
                <div>
                    <img src="../Ressources/Images/<?php echo $infos['photo']; ?>" title="photo" alt="photo de profil">
                    <label for="photo">Photo de profil</label>
                    <input type="file" id="photo" name="photo"/>
                    <?php erreur("photo"); ?>
                </div>
            </div>
            <!-- boutton pour valider les changements -->
            <input class="submit-profil" type="submit" value="Créer le compte">
        </form>
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

