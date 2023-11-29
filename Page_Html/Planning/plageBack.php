<?php 
    session_start();

    include('../parametre_connexion.php');

    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $code = $dbh->prepare("SELECT code_planning FROM locbreizh._planning NATURAL JOIN locbreizh._logement WHERE id_logement = $_POST[id_logement];");

        $stmt = $dbh->prepare("INSERT INTO locbreizh._plage_ponctuelle(debut_plage_ponctuelle, fin_plage_ponctuelle, prix_plage_ponctuelle, disponible, code_planning)
        VALUES ((:debut_plage_ponctuelle), (:fin_plage_ponctuelle), (:prix_plage_ponctuelle), (:disponible), (:code_planning);");


    } catch (PDOException $e) {
        print "Erreur !:" . $e->getMessage() . "<br/>";
        die();
    }

    print_r($_POST);

    $code->execute();
    $variable = $code->fetch();

    $stmt->bindParam(':debut_plage_ponctuelle', $_POST['dateDeb']);
    $stmt->bindParam(':fin_plage_ponctuelle', $_POST['dateFin']);
    $stmt->bindParam(':prix_plage_ponctuelle', $_POST['prix']);
    $stmt->bindParam(':disponible', $_POST['disponible']);
    $stmt->bindParam(':code_planning', $variable);
    $stmt->execute();


?>