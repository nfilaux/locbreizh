<?php 
    // ouverure de la session
    session_start();

    // fonction de verif du nouveau mdp
    function verifMDP($mdp, $confirmMDP){
        $erreur = false;
        // test longueur
        if (strlen($mdp)>25 || strlen($mdp)<12){
            $erreur = true;
            $_SESSION['erreurs'] += ["motdepasse" => "Le mot de passe doit faire entre 12 et 25 caractères"];  
        }
        // test niveau de sécurité avec regex
        else{
            if (!preg_match('/(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{12,25}$/', $mdp)) {
                $erreur = true;
                // save l'erreur pour pouvoir l'afficher plus tard
                $_SESSION['erreurs'] += ["motdepasse" => "Le mot de passe doit comporter 4 caractères de types différents (majuscule, minuscule, chiffre, caractère spécial)"];
            }
            else{
                // test mdp de confirmation
                if (strcmp($mdp, $confirmMDP) !== 0){
                    $erreur = true;
                    // save l'erreur pour pouvoir l'afficher plus tard
                    $_SESSION['erreurs'] += ["confirmationMDP" => "Le mot de passe de confirmation est différent"];
                }
            }
        }
        return $erreur;
    }
    // mise ne place du PDO pour l'accès à la BDD
    try {
        include('../parametre_connexion.php');

        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !:" . $e->getMessage() . "<br/>";
        die();
    }

    // requete pour connaitre le type de compte et s'en servir pour la redirection 
    $stmt = $dbh->prepare(
        "Select * from locbreizh._client
        WHERE id_client = {$_SESSION['id']}"
    );
    $stmt->execute();
    $test_client = $stmt->fetch();

    // recupère l'ancien mdp hashé
    $stmt = $dbh->prepare(
        "Select mot_de_passe from locbreizh._compte
        WHERE id_compte = {$_SESSION['id']}"
    );
    $stmt->execute();
    $ancien_mdp = $stmt->fetch();

    // on hash le nouveau mdp
    $mdp = password_hash($_POST['newMdp'], PASSWORD_DEFAULT);

    // test que le mot de passe actuel est le bon pour erreur
    if(!password_verify($_POST['mdp'], $ancien_mdp['mot_de_passe'])){
        $_SESSION['erreurs'] += ["ancien_motdepasse" => "Le mot de passe actuel n'est pas correct"];
    }

    // appel de la fonction test + test mot de passe actuel
    if((!verifMDP($_POST['newMdp'], $_POST['confirmMdp'])) && (password_verify($_POST['mdp'], $ancien_mdp['mot_de_passe']))){
        // update la BDD
        $stmt = $dbh->prepare(
            "UPDATE locbreizh._compte SET 
            mot_de_passe = :mdp
            WHERE id_compte = {$_SESSION['id']}"
        );
        $stmt->bindParam(':mdp', $mdp);
        $stmt->execute();

        // redirection sur la page de profil en fonction du type de compte
        if(isset($test_client['id_client'])){
            header("Location: consulter_profil_client.php");
        }
        else{
            header("Location: consulter_profil_proprio.php");
        }
    }
    else{
        // redirection sur la page de profil en fonction du type de compte + err en argument
        if(isset($test_client['id_client'])){
            header("Location: consulter_profil_client.php?mdp=err");
        }
        else{
            header("Location: consulter_profil_proprio.php?mdp=err");
        }
    }
?>
