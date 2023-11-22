<?php
    session_start();

    $erreur = false; // variable qui permet de savoir si il y a une erreur ou non dans le remplissage du formulaire
    $url = "?"; // variable qui permet de créer un url de redirection vers le formulaire avec tous les champs reremplis
    $_SESSION['erreurs'] = []; // la session récupère toutes les erreurs pour les affichées dans le formulaire
    
    // tests déterminant si les données sont renseignées ou non
    if (!isset($_POST['conditions'])){
        $erreur = true;
        $_SESSION['erreurs'] += ["conditions" => "Veuillez accepter les conditions générales d'utilisation"];
    }
    if (strcmp($_POST['langue'], 'none') == 0){
        $erreur = true;
        $_SESSION['erreurs'] += ["langue" => "Veuillez sélectionner une langue"];
    }
    foreach ($_POST as $key => $row){
        if (empty($row)){
            $erreur = true;
            $_SESSION['erreurs'] += [$key => "Veuillez renseigner ce champ."];
        }
        // tests permettant de savoir si les données respectent les formats demandés
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
                case "date":
                    $date = $_POST["date"];
                    $erreurTest = verifDate($date);
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
                    else{
                        $erreurTest = true;
                    }
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
                case "conditions":
                    $conditions = $_POST["conditions"];
                    $erreurTest = verifCondition($conditions);
                    break;
            }
            if ($erreurTest == true){
                $erreur = true;
            }
            // ajout des données dans l'url de redirection
            if (strcmp($key, "motdepasse") !== 0 && strcmp($key, "confirmationMDP") !== 0){
                $url .= "$key=$row&";
            }
        }
    }
    
    // tests permettant de savoir si les images envoyées utilisent les bonnes extensions
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
    $arrayNom3 = explode('.', $_FILES['rib']['name']);
    $extension3 = $arrayNom3[sizeof($arrayNom3)-1];
    if ($extension3 == "png" or $extension3 == "gif" or $extension3 == "jpg" or $extension3 == "jpeg"){
        $temps3 = time();
    }
    else{
        if (!empty($extension3)){
            $_SESSION['erreurs'] += ["rib" => "mauvaise extension de fichiers"];
        }
        $erreur = true;
    }

    // si il y a aucune érreur on vérifie que les contraintes d'unicité sont respectées
    if (!$erreur){
        include('../parametre_connexion.php');
        $langue = $_POST['langue'];

        try {
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $verifMail = $dbh->prepare("SELECT count(*) FROM locbreizh._compte WHERE _compte.mail = '{$mail}';");
            $verifMail->execute();
            $res = $verifMail->fetchColumn();
            if ($res != 0){
                $_SESSION['erreurs'] += ["email" => "mail déjà existant"];
                $erreur = true;
            }
            $verifTel = $dbh->prepare("SELECT count(*) FROM locbreizh._compte WHERE _compte.telephone = '{$tel}';");
            $verifTel->execute();
            $res = $verifTel->fetchColumn();
            if ($res != 0){
                $_SESSION['erreurs'] += ["telephone" => "telephone déjà existant"];
                $erreur = true;
            }
            $verifPseudo = $dbh->prepare("SELECT count(*) FROM locbreizh._compte WHERE _compte.pseudo = '{$pseudo}';");
            $verifPseudo->execute();
            $res = $verifPseudo->fetchColumn();
            if ($res != 0){
                $_SESSION['erreurs'] += ["pseudo" => "pseudo déjà existant"];
                $erreur = true;
            }
            $dbh = null;
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // si il y a toujours pas d'érreur on peuple la base avec les données
    if(!$erreur){
        try {
            $cheminProfil = '../Ressources/Images/';
            $nom_profil =   $temps1 . '1' . '.' . $extension2;

            $cheminIdentite = '../Ressources/carte_identite/' ;
            $nom_identite =  $temps1 . '2'. '.' . $extension1;

            $cheminRIB = '../Ressources/rib/';
            $nom_rib = $temps1 . '3' . '.' . $extension3;

            move_uploaded_file($_FILES['photoProfil']['tmp_name'], $cheminProfil . $nom_profil);
            move_uploaded_file($_FILES['carteIdentite']['tmp_name'], $cheminIdentite . $urlIdentite);
            move_uploaded_file($_FILES['rib']['tmp_name'], $cheminRIB . $nom_rib);

            $mdp = password_hash($mdp, PASSWORD_DEFAULT);

            include('../parametre_connexion.php');
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $requetePhotos = $dbh->prepare("INSERT INTO locbreizh._photo(url_photo) VALUES ('{$nom_profil}'), ('{$nom_identite}'), ('{$nom_rib}');");
            $requetePhotos->execute();

            $requeteAdresse = $dbh->prepare("INSERT INTO locbreizh._adresse(nom_rue, numero_rue, code_postal, pays, ville) VALUES ('{$nomRue}', {$numRue}, '{$codePostal}', 'France', '{$ville}');");
            $requeteAdresse->execute();

            $requeteIDAdresse = $dbh->prepare("SELECT id_adresse FROM locbreizh._adresse WHERE nom_rue = '{$nomRue}';");
            $requeteIDAdresse->execute();
            $idAdresse = $requeteIDAdresse->fetchColumn();

            $requeteCompte = $dbh->prepare("INSERT INTO locbreizh._compte(civilite, nom, prenom, mail, mot_de_passe, pseudo, telephone, adresse, photo) VALUES ('{$genre}', '{$nom}','{$prenom}', '{$mail}', '{$mdp}', '{$pseudo}', '{$tel}', {$idAdresse}, '{$nom_profil}');");
            $requeteCompte->execute();

            $requeteIDCompte = $dbh->prepare("SELECT id_compte FROM locbreizh._compte WHERE pseudo = '{$pseudo}';");
            $requeteIDCompte->execute();
            $idCompte = $requeteIDCompte->fetchColumn();

            $requeteProprio = $dbh->prepare("INSERT INTO locbreizh._proprietaire VALUES ('{$idCompte}' ,'{$nom_rib}', '{$nom_identite}');");
            $requeteProprio->execute();

            $requeteLangueExiste = $dbh->prepare("SELECT COUNT(*) FROM locbreizh._langue WHERE nom_langue = '{$langue}';");
            $requeteLangueExiste->execute();
            $nbLangue = $requeteLangueExiste->fetchColumn();

            if ($nbLangue == 0){
                $requeteLangue = $dbh->prepare("INSERT INTO locbreizh._langue VALUES ('{$langue}');");
                $requeteLangue->execute();
            }
            $requeteParle = $dbh->prepare("INSERT INTO locbreizh._parle VALUES ('{$langue}', '{$idCompte}');");
            $requeteParle->execute();
            $dbh = null;

            header("Location: ./connexionFront.php");
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // si il y a eu une érreur durant les test on renvoie l'utilisateur sur le formulaire
    if ($erreur){
        $url = substr($url, 0, -1);
        header("Location: ./creerProprietaireFront.php$url");
        exit;
    }

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

    function verifLangue($langue){
        $erreur = false;
        if (!preg_match('/^(Français|Anglais|Espagnol|Allemand|Italien|Japonais|Chinois|Portugais)$/', $langue)) {
            $erreur = true;
            $_SESSION['erreurs'] += ["langue" => "Veuillez choisir une langue de la liste"];
        }
        return $erreur;
        return $erreur;
    }

    function verifCondition($conditions){
        $erreur = false;
        if (strcmp("accepter", $conditions) !== 0) {
            $erreur = true;
            $_SESSION['erreurs'] += ["conditions" => "Veuillez accepter les conditions générales d'utilisation"];
        }
        return $erreur;
    }
?>