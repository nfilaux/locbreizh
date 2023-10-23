<?php
    session_start();
    $url = "?";
    $_SESSION['erreurs'] = [];
    $erreur = false;
    foreach ($_POST as $key => $row){
        if (empty($row)){
            $erreur = true;
            $_SESSION['erreurs'] += [$key => "Veuillez renseigner ce champ."];
        }
        else if (strcmp($key, "motdepasse") !== 0){
            $url .= "$key=$row&";
        }
    }

    if (!$erreur){
        $pseudo = $_POST["pseudo"];
        $mdp = $_POST["motdepasse"];
        include('connect_params.php');
        try {
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
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
                    header("Location: ./connexionFront.php");
                    exit;
                }
                else{
                    echo "connexion réussie";
                }
            }
            $dbh = null;
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
    }
    else{
        $url = substr($url, 0, -1);
        header("Location: ./connexionFront.php$url");
        exit;
    }
?>