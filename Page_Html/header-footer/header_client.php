<?php
    $stmt = $dbh->prepare("SELECT photo from locbreizh._compte where id_compte = {$_SESSION['id']};");
    $stmt->execute();
    $photo = $stmt->fetch(); 
?>

<header>
    <a href="../Accueil/accueil_client.php">
        <img class="logot" src="../svg/logo.svg">
        <h2>Loc'Breizh</h2>
    </a>
    <div class="brecherche">
        <img src="../svg/filtre.svg">
        <input id="searchbar" type="text" name="search">
        <img src="../svg/loupe.svg">
    </div>

    <img src="../svg/booklet-fill 1.svg">
    <a href="../reservation/liste_reservations.php"><h4>Accèder à mes réservations</h4></a>

    <div class="imghead">
        <a href="../messagerie/messagerie.php"><img src="../svg/message.svg"></a>
        <a class="margleft" onclick="openPopup('popup', 'overlay_profil-deconnexion')"><img id="pp" class="imgprofil" src="../Ressources/Images/<?php echo $photo['photo']; ?>" width="50" height="50"></a>
    </div>
    </div>
    <div id="overlay_profil-deconnexion" onclick="closePopup('popup', 'overlay_profil-deconnexion')"></div>
    <div id="popup" class="popup">
        <table id="tableProfil">
            <tr>
                <td>
                    <a id="monprofil" href="../Compte/consulter_profil_client.php">Accéder au profil</a>
                </td>
            </tr>
            <tr>
                <td> 
                    <a id="deconnexion" href="../Compte/SeDeconnecter.php">Se déconnecter</a>
                </td>  
            </tr>
        </table>
    </div>
</header>
