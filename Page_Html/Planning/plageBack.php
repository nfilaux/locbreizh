<?php 
    session_start();
    include('../parametre_connexion.php');
    $err = false;
    if (isset($_POST['prix']) && $_POST['prix']<=0){
        $_SESSION['erreurs'] = ["prix" => "Le prix doit être supérieur à 0\n"];
        $err = true;
    }
    if ((isset($_POST['debut_plage_ponctuelle']) && $_POST['debut_plage_ponctuelle'] === "" )|| (isset($_POST['fin_plage_ponctuelle']) && $_POST['fin_plage_ponctuelle'] === "")){
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
            $code = $dbh->prepare("SELECT code_planning FROM locbreizh._logement WHERE id_logement = :id_logement;");
            $code->bindParam(':id_logement', $_POST['id_logement']);
            $code->execute();
            $variable = $code->fetch();
            foreach ($tabJours as $key => $elem){
                $res = true;
                $stmt = $dbh->prepare("SELECT id_plage_ponctuelle FROM locbreizh._plage_ponctuelle WHERE jour_plage_ponctuelle = :jour_plage_ponctuelle  AND code_planning = :code_planning;");
                $stmt->bindParam(':jour_plage_ponctuelle', $elem);
                $stmt->bindParam(':code_planning', $variable['code_planning']);
                $stmt->execute();
                $jour_existant = $stmt->fetchColumn();
                if (!empty($jour_existant)){
                    $code = $dbh->prepare("SELECT p.id_plage_ponctuelle FROM locbreizh._plage_ponctuelle p WHERE id_plage_ponctuelle = :id_plage_ponctuelle AND id_plage_ponctuelle NOT IN (
                    SELECT p.id_plage_ponctuelle FROM locbreizh._plage_ponctuelle p INNER JOIN locbreizh._plage_ponctuelle_indisponible i
                    ON p.id_plage_ponctuelle = i.id_plage_ponctuelle
                    AND (i.libelle_indisponibilite = 'Réservation' OR i.libelle_indisponibilite = 'Demande devis'));");
                    $code->bindParam(':id_plage_ponctuelle', $jour_existant);
                    $code->execute();
                    $res = $code->fetchColumn();
                    if (!empty($res)){
                        $code = $dbh->prepare("DELETE FROM locbreizh._plage_ponctuelle WHERE id_plage_ponctuelle = :id_plage_ponctuelle;");
                        $code->bindParam(':id_plage_ponctuelle', $jour_existant);
                        $res = $code->execute();

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
                else{
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
            $plageDispo = $dbh->prepare("SELECT COUNT(*) FROM locbreizh._plage_ponctuelle INNER JOIN locbreizh._plage_ponctuelle_disponible
            ON _plage_ponctuelle.id_plage_ponctuelle = _plage_ponctuelle_disponible.id_plage_ponctuelle WHERE code_planning = :code_planning ;");
            $plageDispo->bindParam(':code_planning', $variable['code_planning']);
            $plageDispo->execute();
            $plageDispo = $plageDispo->fetchColumn();
            if ($plageDispo == 0){
                $enLigne = false;
                $stmt = $dbh->prepare("UPDATE locbreizh._logement SET en_ligne = :enLigne WHERE id_logement = :id_logement;");
                $stmt->bindParam(':enLigne', $enLigne, PDO::PARAM_BOOL);
                $stmt->bindParam(':id_logement', $_POST['id_logement']);
                $stmt->execute();
            }
            
        } catch (PDOException $e) {
            print "Erreur !:" . $e->getMessage() . "<br/>";
            die();
        }
    }
    header("location: ../Accueil/Tableau_de_bord.php?popup={$_POST['nomPopUp']}&overlay={$_POST['overlayPopUp']}");
?>