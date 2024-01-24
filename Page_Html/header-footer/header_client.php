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

    <a href="../gestion_devis/gestion_des_devis_client.php">
        <div style="display:flex; flex-direction:row;">
            <img src="../svg/gestion.svg">
            <h4>Devis</h4>
        </div>
    </a>
    <a href="../reservation/liste_reservations.php">
        <div style="display:flex; flex-direction:row;">
            <img src="../svg/ReservationC.svg">
            <h4>Réservations</h4>
        </div>
    </a>

    <div class="imghead">
        <a class="margleft" onclick="openPopup('popup', 'overlay_profil-deconnexion')">
            <img id="pp" class="imgprofilP" alt="avatar" src="../Ressources/Images/<?php echo $photo['photo']; ?>" width="50" height="50">
        </a>
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
                    <a id="deconnexion" href="../Compte/seDeconnecter.php">Se déconnecter</a>
                </td>  
            </tr>
        </table>
    </div>
</header>

<script src="../scriptPopup.js" defer></script>