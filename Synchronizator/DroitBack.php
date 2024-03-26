<?php

    // Fonction pour générer une clé API unique
    function uniqueId() {
        return bin2hex(random_bytes(16));
    }

    session_start();
    //$_SESSION['id'] = 1;
    include('../Page_Html/parametre_connexion.php');

    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print "Erreur !:" . $e->getMessage() . "<br/>";
            die();
        }

        // On ajoute une nouvelle clé API dans la base de données si le bouton "Générer une nouvelle clé API" est cliqué
        if (isset($_POST['nouvelleClefAPI'])) {
            // Générer une nouvelle clé API
            $apiKey = uniqueId();
    
            // Insérer la nouvelle clé API dans la base de données
            $stmt = $dbh->prepare("INSERT INTO locbreizh._clefsapi (idclef) VALUES (:apiKey)");
            $stmt->bindParam(':apiKey', $apiKey);
            $stmt->execute();
        }

        $id_compte = ($_SESSION['id']);
        $stmt = $dbh->prepare("SELECT id_compte FROM locbreizh._compte where id_compte = $id_compte;");
        $stmt->execute();
        $idCompte = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des droits</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>
<body>
    
    <main>
        <?php
            // récupération des données des clefs API dans la base de donnée

            try {
                include('../Page_Html/parametre_connexion.php');

                $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                $stmt = $dbh->prepare(
                    "SELECT IdClef, droitGrandeConsultation, droitPetiteConsultation, droitConsultationCalendrier, droitRendreIndisponible, droitRendreDisponible
                    FROM locbreizh._clefsapi;"
                );

                $stmt->execute();
                $IdClef = $stmt->fetch();
                $droitGrandeConsultation = $stmt->fetch();
                $droitPetiteConsultation = $stmt->fetch();
                $droitConsultationCalendrier = $stmt->fetch();
                $droitRendreIndisponible = $stmt->fetch();
                $droitRendreDisponible = $stmt->fetch();
            } catch (PDOException $e) {
                print "Erreur !:" . $e->getMessage() . "<br/>";
                die();
            }
        ?>
        <h1>Gestion des droits</h1>

        <?php
            include('../Page_Html/parametre_connexion.php');

            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $stmt = $dbh->prepare(
                "SELECT idclef,droitgrandeconsultation,droitpetiteconsultation,droitconsultationcalendrier,droitrendreindisponible,estAdmin,droitrendredisponible FROM locbreizh._clefsapi where id_proprio = {$_SESSION['id']};"
            );

            $stmt->execute();
            $droitClef = $stmt->fetchAll();
        ?>
        
        <!-- Table qui permet de visualiser et d'accorder les droits aux utilisateurs -->
        <table id="droitsTable">
            <tr>
                <th>Clef API</th>
                <th>Consultation des biens du propriétaire</th>
                <th>Consultation du calendrier</th>
                <th>Rendre indisponible</th>
                <th>Rendre disponible</th>
            </tr>
            <?php
            foreach ($droitClef as $clef => $value){ ?>
                    <tr>
                        <td> <?php echo $value['idclef']; ?> </td>
                        <td> <input type="checkbox" name="droitPetiteConsultation" id="petiteConsultation" <?php if ($value['droitpetiteconsultation'] == true){ echo "checked"; } ?>> </td>
                        <td> <input type="checkbox" name="droitConsultationCalendrier" id="consultationCalendrier" <?php if ($value['droitconsultationcalendrier'] == true){ echo "checked"; }?>></td>
                        <td> <input type="checkbox" name="droitRendreIndisponible" id="droitRendreIndisponible" <?php if ($value['droitrendreindisponible'] == true){ echo "checked"; } ?>></td>
                        <td> <input type="checkbox" name="droitRendredisponible" id="droitRendredisponible" <?php if ($value['droitrendredisponible'] == true){ echo "checked"; } ?>></td>
                    </tr>
                <?php
            };

            ?>
        </table>

        <button id="nouvelleClefAPI" >Nouvelle Clef API</button>
        <button id="boutonSauvegarder">Sauvegarder</button>
    </main>
        <script src="script4.js"></script>
        <?php 

        /*if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            // C'est une requête AJAX
            echo "bonjour :"  . print_r($_POST);
        } else {
            // Ce n'est pas une requête AJAX
            exit;
        }*/
        
        if (isset($_POST['action']) && $_POST['action'] == 'fonctionSauvegarder') {
            fonctionSauvegarder();
            print_r($_POST);
            echo 'fonction sauvegrder';
        }
        
        function fonctionSauvegarder() {

            echo "Fonction de sauvegarde exécutée avec succès!";

            $grandeConsultation = false;
            $petiteConsultation = isset($_POST['petiteConsultation']) ? $_POST['petiteConsultation'] : false;
            $consultationCalendrier = isset($_POST['consultationCalendrier']) ? $_POST['consultationCalendrier'] : false;
            $rendreIndisponible = isset($_POST['rendreIndisponible']) ? $_POST['rendreIndisponible'] : false;
            $rendreDisponible = isset($_POST['rendreDisponible']) ? $_POST['rendreDisponible'] : false;
            $estAdmin = false;

            $id_clef = $_POST['derniereClefId'];

            include('../Page_Html/parametre_connexion.php');
            
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $stmt = $dbh->prepare(
                "SELECT * FROM locbreizh._clefsapi WHERE idclef = $id_clef;"
            );

            $stmt->execute();
            $cle_presente = $stmt->fetch();

            echo 'cle presente ?';
            print_r($cle_presente);

            if($cle_presente!=null){
                echo "la clef est présente !!!!!!!\n";
                try {
                    include('../Page_Html/parametre_connexion.php');

                    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                    $stmt = $dbh->prepare(
                        "UPDATE locbreizh._clefsapi
                        SET droitGrandeConsultation = :grandeConsultation, droitPetiteConsultation = :petiteConsultation, droitConsultationCalendrier = :consultationCalendrier, droitRendreIndisponible = :rendreIndisponible, droitRendreDisponible = :rendreDisponible
                        WHERE IdClef = :id_clef;"
                    );

                    $stmt->bindParam(':id_clef', $id_clef);
                    $stmt->bindParam(':grandeConsultation', $grandeConsultation, PDO::PARAM_BOOL);
                    $stmt->bindParam(':petiteConsultation', $petiteConsultation, PDO::PARAM_BOOL);
                    $stmt->bindParam(':consultationCalendrier', $consultationCalendrier, PDO::PARAM_BOOL);
                    $stmt->bindParam(':rendreIndisponible', $rendreIndisponible, PDO::PARAM_BOOL);
                    $stmt->bindParam(':rendreDisponible', $rendreDisponible, PDO::PARAM_BOOL);
                    $stmt->execute();

                } catch (PDOException $e) {
                    print "Erreur !:" . $e->getMessage() . "<br/>";
                    die();
                }
            } else {

                try {
                    include('../Page_Html/parametre_connexion.php');
    
                    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
                    $stmt = $dbh->prepare(
                        "INSERT INTO locbreizh._clefsapi (IdClef, droitGrandeConsultation, droitPetiteConsultation, droitConsultationCalendrier, droitRendreIndisponible, estadmin, id_proprio, droitRendreDisponible)
                        VALUES (:IdClef, :grandeConsultation, :petiteConsultation, :consultationCalendrier, :rendreIndisponible, :estAdmin, :id_proprio, :rendreDisponible);"
                    );
                    
                    $stmt->bindParam(':IdClef', $id_clef);
                    $stmt->bindParam(':grandeConsultation', $grandeConsultation, PDO::PARAM_BOOL);
                    $stmt->bindParam(':petiteConsultation', $petiteConsultation, PDO::PARAM_BOOL);
                    $stmt->bindParam(':consultationCalendrier', $consultationCalendrier, PDO::PARAM_BOOL);
                    $stmt->bindParam(':rendreIndisponible', $rendreIndisponible, PDO::PARAM_BOOL);
                    $stmt->bindParam(':estAdmin', $estAdmin, PDO::PARAM_BOOL);
                    $stmt->bindParam(':id_proprio', $_SESSION['id']);
                    $stmt->bindParam(':rendreDisponible', $rendreDisponible, PDO::PARAM_BOOL);
                    $stmt->execute();                
    
                } catch (PDOException $e) {
                    print "Erreur !:" . $e->getMessage() . "<br/>";
                    die();
                }
            }
        }
        
       ?>
    
    
</body>
</html>