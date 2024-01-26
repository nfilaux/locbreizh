<?php
    session_start();
    include('../parametre_connexion.php');
    $err = false;
    if ($_POST['debut_plage_suppr'] === "" || $_POST['fin_plage_suppr'] === ""){
        $_SESSION['erreurs'] = ["plage" => "Veuillez sélectionner une plage\n"];
        $err = true;
    }
    if(!$err){
        $tabJours = [];
        $dateDebut = $_POST['debut_plage_suppr'];
        $dateFin = $_POST['fin_plage_suppr'];
        $dateActuelle = $dateDebut;
        while ($dateActuelle <= $dateFin){
            $tabJours[] = $dateActuelle;
            $dateActuelle = date("Y-m-d", strtotime($dateActuelle.'+ 1 days'));
        }
        try {
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $code = $dbh->prepare("SELECT code_planning FROM locbreizh._planning NATURAL JOIN locbreizh._logement WHERE id_logement = :id_logement;");
            $code->bindParam(':id_logement', $_POST['id_logement']);
            $code->execute();
            $variable = $code->fetch();
            foreach ($tabJours as $key => $elem){
                $stmt = $dbh->prepare("SELECT id_plage_ponctuelle FROM locbreizh._plage_ponctuelle WHERE jour_plage_ponctuelle =  :jour_plage_ponctuelle;");
                $stmt->bindParam(':jour_plage_ponctuelle', $elem);
                $stmt->execute();
                $jour_existant = $stmt->fetchColumn();
                if (!empty($jour_existant)){
                    $code = $dbh->prepare("DELETE FROM locbreizh._plage_ponctuelle WHERE id_plage_ponctuelle = :id_plage_ponctuelle;");
                    $code->bindParam(':id_plage_ponctuelle', $jour_existant);
                    $code->execute();
                }
            }
        } catch (PDOException $e) {
            print "Erreur !:" . $e->getMessage() . "<br/>";
            die();
        }
    }
    header("location: ../Accueil/Tableau_de_bord.php?popup={$_POST['nomPopUp']}&overlay={$_POST['overlayPopUp']}");
?>