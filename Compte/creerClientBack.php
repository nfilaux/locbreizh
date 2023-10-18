<?php
    session_start();
    $erreur = false;
    $_SESSION['erreurs'] = [];
    foreach ($_POST as $key => $row){
        if (empty($row)){
            $erreur = true;
            $_SESSION['erreurs'] += [$key => "Veuillez renseigner ce champ."];
        }
    }
    if (!$erreur){
        $prenom = htmlentities($_POST["prenom"]);
        $nom = htmlentities($_POST["nom"]);
        $genre = htmlentities($_POST["genre"]);
        $mail = htmlentities($_POST["email"]);
        $tel = htmlentities($_POST["telephone"]);
        $pseudo = htmlentities($_POST["pseudo"]);
        $mdp = htmlentities($_POST["motdepasse"]);
        $confirmMDP = htmlentities($_POST["confirmationMDP"]);
        $ville = htmlentities($_POST["ville"]);
        $codePostal = htmlentities($_POST["codePostal"]);
        $numRue = htmlentities($_POST["numRue"]);
        $nomRue = htmlentities($_POST["nomRue"]);

        include('connect_params.php');
        try {
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $verifMail = $dbh->prepare("SELECT count(*) FROM locbreizh._compte WHERE _compte.mail = '$mail';");
            $verifMail->execute();
            $res = $verifMail->fetch();
            if ($res['count'] != 0){
                $_SESSION['erreurs'] += ["email" => "mail déjà existant"];
                header("Location: ./creerClientFront.php");
                exit;
            }
            $verifTel = $dbh->prepare("SELECT count(*) FROM locbreizh._compte WHERE _compte.telephone = '$tel';");
            $verifTel->execute();
            $res = $verifTel->fetch();
            if ($res['count'] != 0){
                $_SESSION['erreurs'] += ["telephone" => "telephone déjà existant"];
                header("Location: ./creerClientFront.php");
                exit;
            }
            $verifPseudo = $dbh->prepare("SELECT count(*) FROM locbreizh._compte WHERE _compte.pseudo = '$pseudo';");
            $verifPseudo->execute();
            $res = $verifPseudo->fetch();
            if ($res['count'] != 0){
                $_SESSION['erreurs'] += ["pseudo" => "pseudo déjà existant"];
                header("Location: ./creerClientFront.php");
                exit;
            }
            $dbh = null;
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }



        $arrayNom1 = explode('.', $_FILES['carteIdentite']['name']);
        $extension1 = $arrayNom1[sizeof($arrayNom1)-1];
        if ($extension1 == "png" or $extension1 == "gif" or $extension1 == "jpg" or $extension1 == "jpeg"){
            $temps1 = time();
        }
        else{
            $_SESSION['erreurs'] += ["carteIdentite" => "mauvaise extension de fichiers"];
            header("Location: ./creerClientFront.php");
            exit;
        }
        $arrayNom2 = explode('.', $_FILES['photoProfil']['name']);
        $extension2 = $arrayNom2[sizeof($arrayNom2)-1];
        if ($extension2 == "png" or $extension2 == "gif" or $extension2 == "jpg" or $extension2 == "jpeg"){
            $temps2 = time();
        }
        else{
            $_SESSION['erreurs'] += ["photoProfil" => "mauvaise extension de fichiers"];
            header("Location: ./creerClientFront.php");
            exit;
        }

    }
    else{
        header("Location: ./creerClientFront.php");
    }
?>