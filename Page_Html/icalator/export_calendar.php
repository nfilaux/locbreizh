<?php
    session_start();

    if(!isset($_SESSION['id'])){
        header('Location: ../Accueil/accueil_visiteur.php');
    }
    else{
        include('../parametre_connexion.php');
        try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print "Erreur !:" . $e->getMessage() . "<br/>";
            die();
        }

        $stmt = $dbh->prepare("SELECT id_compte from locbreizh._compte c join locbreizh._proprietaire on c.id_compte = id_proprietaire where id_compte = :id ;");
        $stmt->bindParam(':id', $_SESSION['id']);
        $stmt->execute();
        $est_proprio = $stmt->fetch();

        if(!isset($est_proprio['id_compte'])){
            header('Location: ../Accueil/accueil_client.php');
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abonnement calendrier</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../scriptPopup.js"></script>
</head>
<body>
    <?php 
        include('../header-footer/choose_header.php');
    ?>
    <main>
        <h1>Gestion des abonnements de votre calendrier iCalendar</h1>
        <h2>Les abonnements auquels vous souhaités souscrire</h2>
        <form name="formulaire" action="generate_calendar.php" method="post">
            <input type="checkbox" id="reservations_confirmees" name="reservations_confirmees"/>
            <label for="reservations_confirmees">Réservations confirmées</label>
            <br>
            <input type="checkbox" id="demandes_de_reservation" name="demandes_de_reservation"/>
            <label for="demandes_de_reservation">Demandes de réservation</label>
            <br>
            <input type="checkbox" id="indisponibilites" name="indisponibilites"/>
            <label for="indisponibilites">Indisponibilités</label>
            <br>
            <h2>Période du calendrier</h2>
            <div class="calendar_date">
                <label for="date_debut">Du</label>
                <input type="date" id="date_debut" name="date_debut" required/>
                <label for="date_fin">au</label>
                <input type="date" id="date_fin" name="date_fin" required/>
            </div>
            <!-- seulement visible si calendrier jamais genere -->
            <input type="submit" value="Générer le calendrier" name="submit"></input>
        </form>

    </main>
    <?php
    echo  bin2hex(random_bytes(20));
        // appel du footer
        include('../header-footer/choose_footer.php'); 
    ?>
</body>
</html>