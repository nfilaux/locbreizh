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

        
        <a href="../Accueil/Tableau_de_bord.php"><div style="display:flex; flex-direction:row;"><img src="../svg/logement.svg"><h4>Logements</h4></div></a>
        <a href="../gestion_devis/gestion_des_devis_proprio.php"><div style="display:flex; flex-direction:row;"><img src="../svg/gestionP.svg"><h4>Devis</h4></div></a>
        <a href="../reservation/liste_reservations_proprio.php"><div style="display:flex; flex-direction:row;"><img src="../svg/Reservation.svg"><h4>Réservations</h4></div></a>
        <div class="imghead">
            <a onclick="openPopup('popup', 'overlay_profil-deconnexion')">
                <img  class="imgprofilP"  id="pp" src="../Ressources/Images/<?php echo $photo['photo']; ?>" alt="avatar" width="50" height="50">
            </a>
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

<script src="../scriptPopup.js" defer></script>
