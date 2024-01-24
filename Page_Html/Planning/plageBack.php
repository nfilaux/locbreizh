<?php 
    session_start();

    include('../parametre_connexion.php');

    $err = false;
    if ($_POST['prix']<=0 && !isset($_POST['indisponible'])){
        $_SESSION['erreurs'] = ["prix" => "Le prix doit être supérieur à 0\n"];
        $err = true;
    }
    else if ($_POST['libelleIndispo'] === ""){
        $_SESSION['erreurs'] = ["libelleIndispo" => "Veuillez renseigner la raison de l'indisponibilitée\n"];
        $err = true;
    }
    if ($_POST['debut_plage_ponctuelle'] === "" || $_POST['fin_plage_ponctuelle'] === ""){
        $_SESSION['erreurs'] = ["plage" => "Veuillez sélectionner une plage\n"];
        $err = true;
    }

    if(!$err){
        $tabJours = [];
        $dateDebut = $_POST['debut_plage_ponctuelle'];
        $dateFin = $_POST['fin_plage_ponctuelle'];
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

            if (isset($_POST['indisponible'])) {
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

                    $stmt = $dbh->prepare("INSERT INTO locbreizh._plage_ponctuelle(jour_plage_ponctuelle, code_planning)
                    VALUES (:jour_plage_ponctuelle, :code_planning);");
                    $stmt->bindParam(':jour_plage_ponctuelle', $elem);
                    $stmt->bindParam(':code_planning', $variable['code_planning']);
                    $stmt->execute();

                    $stmt = $dbh->prepare("SELECT currval('locbreizh._plage_ponctuelle_id_plage_ponctuelle_seq');");
                    $stmt->execute();
                    $id_plage = $stmt->fetchColumn();

                    $stmt = $dbh->prepare("INSERT INTO locbreizh._plage_ponctuelle_indisponible(id_plage_ponctuelle, libelle_indisponibilite)
                    VALUES (:id_plage_ponctuelle, :libelle_indisponibilite);");
                    $stmt->bindParam(':id_plage_ponctuelle', $id_plage);
                    $stmt->bindParam(':libelle_indisponibilite', $_POST['libelleIndispo']);
                    $stmt->execute();
                }
            } else {
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

                    $stmt = $dbh->prepare("INSERT INTO locbreizh._plage_ponctuelle(jour_plage_ponctuelle, code_planning)
                    VALUES (:jour_plage_ponctuelle, :code_planning);");
                    $stmt->bindParam(':jour_plage_ponctuelle', $elem);
                    $stmt->bindParam(':code_planning', $variable['code_planning']);
                    $stmt->execute();

                    $stmt = $dbh->prepare("SELECT currval('locbreizh._plage_ponctuelle_id_plage_ponctuelle_seq');");
                    $stmt->execute();
                    $id_plage = $stmt->fetchColumn();
                    
                    $stmt = $dbh->prepare("INSERT INTO locbreizh._plage_ponctuelle_disponible(id_plage_ponctuelle, prix_plage_ponctuelle)
                    VALUES (:id_plage_ponctuelle, :prix_plage_ponctuelle);");
                    $stmt->bindParam(':id_plage_ponctuelle', $id_plage);
                    $stmt->bindParam(':prix_plage_ponctuelle', $_POST['prix']);
                    $stmt->execute();
                }
            }

        } catch (PDOException $e) {
            print "Erreur !:" . $e->getMessage() . "<br/>";
            die();
        }
    }
    header("location: ../Accueil/Tableau_de_bord.php?popup={$_POST['nomPopUp']}&overlay={$_POST['overlayPopUp']}");
?>