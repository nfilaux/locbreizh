<?php 
    session_start();

    try {
        include('../parametre_connexion.php');

        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !:" . $e->getMessage() . "<br/>";
        die();
    }

    
    $erreur = false; // variable qui permet de savoir si il y a une erreur ou non dans le remplissage du formulaire
    $_SESSION['erreurs'] = []; // la session récupère toutes les erreurs pour les affichées dans le formulaire
    foreach ($_POST as $key => $row){
        if (empty($row)){
            $erreur = true;
            $_SESSION['erreurs'] += [$key => "Veuillez renseigner ce champ."];
        }
        else{
            // tests permettant de savoir si les données respectent les formats demandés
            switch ($key) {       
                case "prenom":
                    $prenom = $_POST["prenom"];
                    $erreurTest = verifPrenom($prenom);
                    break;
                case "nom":
                    $nom = $_POST["nom"];
                    $erreurTest = verifNom($nom);
                    break;
                case "mail":
                    $mail = $_POST["mail"];
                    $erreurTest = verifMail($mail);
                    break;
                case "date":
                    $date = $_POST["date"];
                    $erreurTest = verifDate($date);
                case "telephone":
                    $tel = str_replace(' ', '', $_POST['telephone']);
                    $erreurTest = verifTel($tel);
                    break;
                case "pseudo":
                    $pseudo = $_POST["pseudo"];
                    $erreurTest = verifPseudo($pseudo);
                    break;
                case "ville":
                    $ville = $_POST["ville"];
                    $erreurTest = verifVille($ville);
                    break;
                case "codePostal":
                    $codePostal = $_POST["codePostal"];
                    $erreurTest = verifCodePostal($codePostal);
                    break;
                case "no_rue":
                    $numRue = $_POST["no_rue"];
                    $erreurTest = verifNumRue($numRue);
                    break;
                case "nom_rue":
                    $nomRue = $_POST["nom_rue"];
                    $erreurTest = verifNomRue($nomRue);
                    break;
            }
            if ($erreurTest == true){
                $erreur = true;
            }
        }
    }

    if (!$erreur){
        $ageLegal = ageLegal($date);
        if($ageLegal == ""){
            $ageLegal = 0;
        }
        $stmt = $dbh->prepare(
            "UPDATE locbreizh._client SET 
            age_legal = :age_legal,
            dateNaissance = :dateNaissance
            WHERE id_client = {$_SESSION['id']}"
        );
        $stmt->bindParam(':age_legal', $ageLegal);
        $stmt->bindParam(':dateNaissance', $_POST['date']);
        $stmt->execute();

        $stmt = $dbh->prepare(
            "UPDATE locbreizh._compte SET 
            nom = :nom, 
            prenom = :prenom, 
            mail = :mail, 
            pseudo = :pseudo,
            telephone = :telephone
            WHERE id_compte = {$_SESSION['id']}"
        );
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':mail', $mail);
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->bindParam(':telephone', $tel);
    
        $stmt->execute();
        
        $stmt = $dbh->prepare("Select adresse from locbreizh._compte 
        where id_compte = {$_SESSION['id']}");
        $stmt->execute();
        $id_adresse = $stmt->fetch();
    
    
        $stmt = $dbh->prepare(
            "UPDATE locbreizh._adresse SET 
            nom_rue = :nom_rue,
            numero_rue = :numero_rue, 
            code_postal = :code_postal, 
            ville = :ville
            WHERE id_adresse = {$id_adresse['adresse']}"
        );
        $stmt->bindParam(':nom_rue', $nomRue);
        $stmt->bindParam(':numero_rue', $numRue);
        $stmt->bindParam(':code_postal', $codePostal);
        $stmt->bindParam(':ville', $ville);
    
        $stmt->execute();
    
        
    }
    header("Location: consulter_profil_client.php");

    // définition des fonctions permettant de faire les tests de conformité sur les données
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

    function verifDate($date){
        $erreur = false;
        if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/', $date)) {
            $erreur = true;
            $_SESSION['erreurs'] += ["date" => "La date doit être au format année-mois-jour"];
        }
        else{
            if (strtotime($date) > strtotime(date("Y-m-d"))){
                $erreur = true;
                $_SESSION['erreurs'] += ["date" => "La date doit être inférieure à la date d'aujourd'hui"];
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

    function ageLegal($date){
        $res = false;
        $date1 = date_create($date);
        $date2 = new DateTime("now");
        $interval = date_diff($date1, $date2);
        $diff = $interval->format('%y');
        if ($diff >= 18){
            $res = true;
        }
        return $res;
    }
?>