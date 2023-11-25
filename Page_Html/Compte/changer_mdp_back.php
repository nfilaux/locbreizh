<?php 
session_start();

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
                $_SESSION['erreurs'] += ["confirmationMDP" => "Le mot de passe de confirmation est différent"];
            }
        }
    }
    return $erreur;
}
try {
    include('../parametre_connexion.php');

    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    print "Erreur !:" . $e->getMessage() . "<br/>";
    die();
}

$stmt = $dbh->prepare(
    "Select * from locbreizh._client
    WHERE id_client = {$_SESSION['id']}"
);
$stmt->execute();
$test_client = $stmt->fetch();

$stmt = $dbh->prepare(
    "Select mot_de_passe from locbreizh._compte
    WHERE id_compte = {$_SESSION['id']}"
);
$stmt->execute();
$ancien_mdp = $stmt->fetch();

$mdp = password_hash($_POST['newMdp'], PASSWORD_DEFAULT);

if(!password_verify($_POST['mdp'], $ancien_mdp['mot_de_passe'])){
    $_SESSION['erreurs'] += ["ancien_motdepasse" => "Le mot de passe actuel n'est pas correct"];
}

if((!verifMDP($_POST['newMdp'], $_POST['confirmMdp'])) && (password_verify($_POST['mdp'], $ancien_mdp['mot_de_passe']))){
    $stmt = $dbh->prepare(
        "UPDATE locbreizh._compte SET 
        mot_de_passe = :mdp
        WHERE id_compte = {$_SESSION['id']}"
    );
    $stmt->bindParam(':mdp', $mdp);
    $stmt->execute();

    if(isset($test_client['id_client'])){
        header("Location: consulter_profil_client.php");
    }
    else{
        header("Location: consulter_profil_proprio.php");
    }
}
else{
    if(isset($test_client['id_client'])){
        header("Location: consulter_profil_client.php?mdp=err");
    }
    else{
        header("Location: consulter_profil_proprio.php?mdp=err");
    }
}
?>
