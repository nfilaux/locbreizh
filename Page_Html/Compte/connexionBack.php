<?php
    session_start();

    $erreur = false; // variable qui permet de savoir si il y a une erreur ou non dans le remplissage du formulaire
    $url = "?"; // variable qui permet de créer un url de redirection vers le formulaire avec tous les champs reremplis
    $_SESSION['erreurs'] = []; // la session récupère toutes les erreurs pour les affichées dans le formulaire
    
    // tests déterminant si les données sont renseignées ou non
    foreach ($_POST as $key => $row){
        if (empty($row)){
            $erreur = true;
            $_SESSION['erreurs'] += [$key => "Veuillez renseigner ce champ."];
        }
        // ajout des données dans l'url de redirection
        else if (strcmp($key, "motdepasse") !== 0){
            $url .= "$key=$row&";
        }
    }
    // si il n'y as pas d'érreur on vérifie que le pseudo et le mot de passe correspondent
    if (!$erreur){
        $pseudo = $_POST["pseudo"];
        $mdp = $_POST["motdepasse"];
        include('../parametre_connexion.php');
        try {
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $verifAdmin = $dbh->prepare("SELECT * FROM locbreizh._admin;");
            $verifAdmin->execute();
            $admin = $verifAdmin->fetch();
            if($pseudo == $admin['login'] && $mdp == $admin['mdp_admin']){
                header("Location: ../Accueil/admin.php");
                exit();
            }

            $verifPseudo = $dbh->prepare("SELECT count(*) FROM locbreizh._compte WHERE pseudo = '{$pseudo}';");
            $verifPseudo->execute();
            $res = $verifPseudo->fetchColumn();
            if ($res !== 1){
                $_SESSION['erreurs'] += ["pseudo" => "pseudo inéxistant"];
                header("Location: ./connexionFront.php");
                exit;
            }
            else{
                $verifMDP = $dbh->prepare("SELECT mot_de_passe FROM locbreizh._compte WHERE pseudo = '{$pseudo}';");
                $verifMDP->execute();
                $res = $verifMDP->fetchColumn();
                if (!password_verify($mdp, $res)){
                    $_SESSION['erreurs'] += ["motdepasse" => "Mot de passe incorrect"];
                    $erreur = true;
                }

                // la session garde l'id de la personne qui se connecte
                else{
                    $recupID = $dbh->prepare("SELECT id_compte FROM locbreizh._compte WHERE pseudo = '{$pseudo}';");
                    $recupID->execute();
                    $id = $recupID->fetchColumn();
                    $_SESSION['id'] = $id;

                    $stmt = $dbh->prepare("SELECT id_proprietaire FROM locbreizh._proprietaire WHERE id_proprietaire = {$_SESSION['id']};");
                    $stmt->execute();
                    $proprio = $stmt->fetch();

                    if(isset($proprio['id_proprietaire'])){
                        header("Location: ../Accueil/Tableau_de_bord.php");
                    }
                    else{
                        header("Location: ../Accueil/accueil_client.php");
                    }
                }
            }
            $dbh = null;
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // si il y a eu une érreur durant les test on renvoie l'utilisateur sur le formulaire
    if ($erreur){
        $url = substr($url, 0, -1);
        header("Location: ./connexionFront.php$url");
        exit;
    }
?>