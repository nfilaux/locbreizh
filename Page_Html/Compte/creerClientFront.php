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
    <main class="Maincompte">
        <?php // creation du formulaire, si la valeur entré n'est pas bonne on affiche l'erreur de l'attribut en question ?>
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
                ?>
            </div>

            <input class="btn-input" type="submit" value="Créer le compte" />

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