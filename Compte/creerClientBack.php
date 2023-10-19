<?php
    session_start();
    $erreur = false;
    $url = "?";
    $_SESSION['erreurs'] = [];
    foreach ($_POST as $key => $row){
        if (empty($row)){
            $erreur = true;
            $_SESSION['erreurs'] += [$key => "Veuillez renseigner ce champ."];
        }
        else{
            switch ($key) {       
                case "prenom":
                    $prenom = $_POST["prenom"];
                    $erreurTest = verifPrenom($prenom);
                    break;
                case "nom":
                    $nom = $_POST["nom"];
                    $erreurTest = verifNom($nom);
                    break;
                case "genre":
                    $genre = $_POST["genre"];
                    $erreurTest = verifGenre($genre);
                    break;
                case "email":
                    $mail = $_POST["email"];
                    $erreurTest = verifMail($mail);
                    break;
                case "telephone":
                    $tel = $_POST["telephone"];
                    $erreurTest = verifTel($tel);
                    break;
                case "pseudo":
                    $pseudo = $_POST["pseudo"];
                    $erreurTest = verifPseudo($pseudo);
                    break;
                case "motdepasse":
                    $mdp = $_POST["motdepasse"];
                    if (isset($_POST["confirmationMDP"])){
                        $confirmMDP = $_POST["confirmationMDP"];
                        $erreurTest = verifMDP($mdp, $confirmMDP);
                    }
                    $erreurTest = true;
                    break;
                case "ville":
                    $ville = $_POST["ville"];
                    $erreurTest = verifVille($ville);
                    break;
                case "codePostal":
                    $codePostal = $_POST["codePostal"];
                    $erreurTest = verifCodePostal($codePostal);
                    break;
                case "numRue":
                    $numRue = $_POST["numRue"];
                    $erreurTest = verifNumRue($numRue);
                    break;
                case "nomRue":
                    $nomRue = $_POST["nomRue"];
                    $erreurTest = verifNomRue($nomRue);
                    break;
            }
            if ($erreurTest == true){
                $erreur = true;
            }
            if (strcmp($key, "motdepasse") !== 0 && strcmp($key, "confirmationMDP") !== 0){
                $url .= "$key=$row&";
            }
        }
    }
    $arrayNom1 = explode('.', $_FILES['carteIdentite']['name']);
    $extension1 = $arrayNom1[sizeof($arrayNom1)-1];
    if ($extension1 == "png" or $extension1 == "gif" or $extension1 == "jpg" or $extension1 == "jpeg"){
        $temps1 = time();
    }
    else{
        if (!empty($extension1)){
            $_SESSION['erreurs'] += ["carteIdentite" => "mauvaise extension de fichiers"];
        }
        $erreur = true;
    }
    $arrayNom2 = explode('.', $_FILES['photoProfil']['name']);
    $extension2 = $arrayNom2[sizeof($arrayNom2)-1];
    if ($extension2 == "png" or $extension2 == "gif" or $extension2 == "jpg" or $extension2 == "jpeg"){
        $temps2 = time();
    }
    else{
        if (!empty($extension2)){
            $_SESSION['erreurs'] += ["photoProfil" => "mauvaise extension de fichiers"];
        }
        $erreur = true;
    }

    /*if (!$erreur){
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
    }*/

    if ($erreur){
        $url = substr($url, 0, -1);
        header("Location: ./creerClientFront.php$url");
        exit;
    }

    function verifPrenom($prenom){
        $erreur = false;
        if (strlen($prenom)>20){
            $erreur = true;
            $_SESSION['erreurs'] += ["prenom" => "Le prenom doit être de 20 caractères au maximum"];  
        }
        else{
            if (!preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ]+([- ][A-Za-zÀ-ÖØ-öø-ÿ]+)*$/', $prenom)) {
                $erreur = true;
                $_SESSION['erreurs'] += ["prenom" => "Le prenom doit comporter que des lettres ou des ( , -) entre des lettres"];
            }
        }
        return $erreur;
    }

    function verifNom($nom){
        $erreur = false;
        if (strlen($nom)>20){
            $erreur = true;
            $_SESSION['erreurs'] += ["nom" => "Le nom doit être de 20 caractères au maximum"];  
        }
        else{
            if (!preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ]+([- ][A-Za-zÀ-ÖØ-öø-ÿ]+)*$/', $nom)) {
                $erreur = true;
                $_SESSION['erreurs'] += ["nom" => "Le nom doit comporter que des lettres ou des ( ,-) entre des lettres"];
            }
        }
        return $erreur;
    }
    
    function verifGenre($genre){
        $erreur = false;
        if (!preg_match('/^(Homme|Femme|Autre)$/', $genre)) {
            $erreur = true;
            $_SESSION['erreurs'] += ["genre" => "La civilité doit être 'Homme', 'Femme' ou 'Autre'"];
        }
        return $erreur;
    }
    
    function verifMail($mail){
        $erreur = false;
        if (strlen($mail)>50){
            $erreur = true;
            $_SESSION['erreurs'] += ["email" => "L'email doit être de 50 caractères au maximum"];  
        }
        else{
            if (!preg_match('/^[A-Za-z]{1}[A-Za-z0-9._%+-]*@[A-Za-z]{1}[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/', $mail)) {
                $erreur = true;
                $_SESSION['erreurs'] += ["email" => "Le email doit comporter un @ puis un . entre des (lettres, chiffres, ., _, %, +, -)"];
            }
        }
        return $erreur;
    }
    
    function verifTel($tel){
        $erreur = false;
        if (!preg_match('/^\d{10}$/', $tel)) {
            $erreur = true;
            $_SESSION['erreurs'] += ["telephone" => "Le numéro de téléphone doit être composé de 10 chiffres"];
        }
        return $erreur;
    }

    function verifPseudo($pseudo){
        $erreur = false;
        if (strlen($pseudo)>20){
            $erreur = true;
            $_SESSION['erreurs'] += ["pseudo" => "Le pseudo doit être de 20 caractères au maximum"];  
        }
        return $erreur;
    }

    function verifMDP($mdp, $confirmMDP){
        $erreur = false;
        if (strlen($mdp)>25 || strlen($mdp)<12){
            $erreur = true;
            $_SESSION['erreurs'] += ["motdepasse" => "Le mot de passe doit faire entre 12 et 25 caractères"];  
        }
        else{
            if (!preg_match('/(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{12,25}$/', $mdp)) {
                $erreur = true;
                $_SESSION['erreurs'] += ["motdepasse" => "Le mot de passe doit comporter 4 caractères de types différents (majuscule, minuscule, chiffre, caractère spécial)"];
            }
            else{
                if (strcmp($mdp, $confirmMDP) !== 0){
                    $erreur = true;
                    $_SESSION['erreurs'] += ["confirmationMDP" => "Le mot de passe de confirmation ne correspond pas au mot de passe"];
                }
            }
        }
        return $erreur;
    }

    function verifVille($ville){
        $erreur = false;
        if (strlen($ville)>50){
            $erreur = true;
            $_SESSION['erreurs'] += ["ville" => "La ville doit être de 50 caractères au maximum"];  
        }
        else{
            if (!preg_match("/^[A-Za-zÀ-ÖØ-öø-ÿ]+([- '][A-Za-zÀ-ÖØ-öø-ÿ]+)*$/", $ville)) {
                $erreur = true;
                $_SESSION['erreurs'] += ["ville" => "La ville doit comporter que des lettres ou des ( , -, ') entre des lettres"];
            }
        }
        return $erreur;
    }
    
    function verifCodePostal($codePostal){
        $erreur = false;
        if (!preg_match('/^\d{5}$/', $codePostal)) {
            $erreur = true;
            $_SESSION['erreurs'] += ["codePostal" => "Le code postal est composé de 5 chiffres"];
        }
        return $erreur;
    }

    function verifNumRue($numRue){
        $erreur = false;
        if (!preg_match('/^\d{1,3}$/', $numRue)) {
            $erreur = true;
            $_SESSION['erreurs'] += ["numRue" => "Le numéro de rue est composé entre 1 et 3 chiffres"];
        }
        return $erreur;
    }

    function verifNomRue($nomRue){
        $erreur = false;
        if (strlen($nomRue)>30){
            $erreur = true;
            $_SESSION['erreurs'] += ["nomRue" => "Le nom de rue doit être de 30 caractères au maximum"];  
        }
        else{
            if (!preg_match("/^[A-Za-zÀ-ÖØ-öø-ÿ]+([- '][A-Za-zÀ-ÖØ-öø-ÿ]+)*$/", $nomRue)) {
                $erreur = true;
                $_SESSION['erreurs'] += ["nomRue" => "Le nom de rue doit comporter que des lettres ou des ( , -, ') entre des lettres"];
            }
        }
        return $erreur;
    }
?>