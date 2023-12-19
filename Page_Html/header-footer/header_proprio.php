<?php
    $stmt = $dbh->prepare("SELECT photo from locbreizh._compte where id_compte = {$_SESSION['id']};");
    $stmt->execute();
    $photo = $stmt->fetch(); 
?>

<header>
    <a href="../Accueil/Tableau_de_bord.php">
        <img class="logot" src="../svg/logobleu.svg">
        <h2 style="color:#274065;">Loc'Breizh</h2>
    </a>
        <div class="brecherche">
            <img src="../svg/filtrebleu.svg">
            <input id="searchbar" type="text" name="search">
            <img src="../svg/loupebleu.svg">
        </div>

        <img src="../svg/booklet-fillbleu.svg">
        <a href="../Accueil/Tableau_de_bord.php"><h4>Accéder à mon tableau de bord</h4></a>

        <div class="imghead">
            <a href="../messagerie/messagerie.php"><img src="../svg/messagebleu.svg"></a>
            <a class="margleft" onclick="openPopup('popup', 'overlay_profil-deconnexion')"><img id="pp" class="imgprofilP" src="../Ressources/Images/<?php echo $photo['photo']; ?>" width="50" height="50"></a>
        </div>
        </div>
        <div id="overlay_profil-deconnexion" onclick="closePopup('popup', 'overlay_profil-deconnexion')"></div>
        <div id="popup" class="popup">
            <table id="tableProfil">
                <tr>
                    <td>
                        <a id="monprofil" href="../Compte/consulter_profil_proprio.php">Accéder au profil</a>
                    </td>
                </tr>
                <tr>
                    <td> 
                        <a id="deconnexion" href="../Compte/seDeconnecter.php">Se déconnecter</a>
                    </td>  
                </tr>
            </table>
        </div>
</header>
