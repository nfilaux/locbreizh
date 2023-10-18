<?php
    session_start();
    $trouve = false;
    foreach ($_POST as $key => $row){
        if (empty($row)){
            $trouve = true;
            break;
        }
    }
    if (!$trouve){
        $prenom = $_POST["prenom"];
        $nom = $_POST["nom"];
        $genre = $_POST["genre"];
        $mail = $_POST["email"];
        $tel = $_POST["telephone"];
        $pseudo = $_POST["pseudo"];
        $mdp = $_POST["motdepasse"];
        $confirmMDP = $_POST["confirmationMDP"];
        $ville = $_POST["ville"];
        $codePostal = $_POST["codePostal"];
        $numRue = $_POST["numRue"];
        $nomRue = $_POST["nomRue"];

        include('connect_params.php');
        try {
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $verifPseudo = $dbh->prepare("SELECT count(*) FROM locbreizh._compte WHERE _compte.pseudo = 'thr';");
            $verifPseudo->execute();
            $res = $verifPseudo->fetch();
            echo $res['count'];
            $dbh = null;
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }


        
        $arrayNom = explode('.', $_FILES['carteIdentite']['name']);
        $extension = $arrayNom[sizeof($arrayNom)-1];
        if ($extension == "png" or $extension == "gif" or $extension == "jpg" or $extension == "jpeg"){
            $temps = time();
            move_uploaded_file($_FILES['fichier']['tmp_name'],'../avatars/' . $temps . '.' . $extension);

            if ($_FILES){
                header("location: succes.html");
            }
            else{
                header("location: echec.html");
            }
        }

    }
    else{
        $_SESSION['msg'] = "l'élément $key est manquant";
        header("Location: ./creerClientFront.php");
    }
?>