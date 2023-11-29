<?php 
    session_start();

    include('../parametre_connexion.php');

    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $code = $dbh->prepare("SELECT code_planning FROM locbreizh._planning NATURAL JOIN locbreizh._logement WHERE id_logement = {$_POST['id_logement']};");

        $stmt = $dbh->prepare("INSERT INTO locbreizh._plage_ponctuelle(debut_plage_ponctuelle, fin_plage_ponctuelle, prix_plage_ponctuelle, disponible, code_planning)
        VALUES (:debut_plage_ponctuelle, :fin_plage_ponctuelle, :prix_plage_ponctuelle, :disponible, :code_planning);");


    } catch (PDOException $e) {
        print "Erreur !:" . $e->getMessage() . "<br/>";
        die();
    }

    $code->execute();
    $variable = $code->fetch();

    if (isset($_POST['indisponible'])) {
        $indisponible = $_POST['indisponible'];
    } else {
        $indisponible = true; 
    }

    $stmt->bindValue(':debut_plage_ponctuelle', $_POST['dateDeb']);
    $stmt->bindValue(':fin_plage_ponctuelle', $_POST['dateFin']);
    $stmt->bindValue(':prix_plage_ponctuelle', $_POST['prix']);
    $stmt->bindValue(':disponible', $indisponible);
    $stmt->bindValue(':code_planning', $variable['code_planning']);
    $stmt->execute();

    header("location: ../Accueil/Tableau_de_bord.php");


?>