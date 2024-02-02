<?php
session_start();
//connexion BDD
include('../parametre_connexion.php');
try {
$dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    print "Erreur !:" . $e->getMessage() . "<br/>";
    die();
}
// initialisation de la variable de $_SESSION 
$_SESSION['erreurs'] = [];
$_SESSION['valeurs_complete'] = [];
// on recupère les informations du formulaire
$libelle = $_POST["nomP"];
$tarif = $_POST["tarif_de_baseP"];
$description = $_POST["descriptionP"];
$ville = $_POST["villeP"];
$cdp = $_POST["code_postalP"];
$id_logement = $_GET["id_logement"];
$prix_max = 100000;
//valeur pour preremplir en cas d'erreur les champs déjà remplis
$_SESSION['valeurs_complete']['libelle'] = $libelle;
$_SESSION['valeurs_complete']['code_postal'] = $cdp;
$_SESSION['valeurs_complete']['ville'] = $ville;
$_SESSION['valeurs_complete']['descriptif_logement'] = $description;
$_SESSION['valeurs_complete']['tarif_base_ht'] = $tarif;

if ($_FILES["image1P"]["tmp_name"] != ""){
    // id_photo = 1 car c'est notre image principale de logement donc la première
    $id_photo = 1;
    // on recupère l'extension de l'image principale dans $_FILES
    $extension_img_1 = explode('/',$_FILES['image1P']['type'])[1];
    // on créer le nom de l'image principale à partir de time() et de l'extension du fichier
    $nom_image_principale = id_photo($id_photo) . '.' . $extension_img_1;
    // on recupère l'url de la photo principale actuelle
    $stmt = $dbh->prepare(
        "SELECT photo_principale FROM locbreizh._logement WHERE id_logement = $id_logement"
    );
    $stmt->execute();
    $main_photo = $stmt->fetchColumn();
    // on insère la nouvelle image principale dans la table photo de la BDD
    $stmt = $dbh->prepare(
        "INSERT INTO locbreizh._photo VALUES('$nom_image_principale');"
    );
    $stmt->execute();
    // on modifie l'image actuelle principale dans le logement qu'on modifie
    $stmt = $dbh->prepare(
        "UPDATE locbreizh._logement SET photo_principale =  '$nom_image_principale' WHERE id_logement = $id_logement"
    );
    $stmt->execute();
    // on supprime l'ancienne photo principale de la table photo
    $stmt = $dbh->prepare(
        "DELETE FROM locbreizh._photo WHERE url_photo =  '$main_photo'"
    );
    $stmt->execute();
    // on recupere le nom temporaire de l'image principale dans $_FILES et on la déplace dans l'endroit du serveur où on stock les images
    $nom_file_principale = $_FILES["image1P"]["tmp_name"];
    move_uploaded_file($nom_file_principale, "../Ressources/Images/" . $nom_image_principale);
    }
// liste des nouvelles images secondaires du logement
$images_secondaires = [];
$id_photo = 2;
// On boucle pour traiter les images du formulaire
for ($i = 2; $i <= 6; $i++) {
    if ($_FILES["image" . $i . "P"]["tmp_name"] != "") {
        $extension_img = pathinfo($_FILES["image" . $i . "P"]['name'], PATHINFO_EXTENSION);
        $images_secondaires["image" . $i . "P"] = id_photo($id_photo) . '.' . $extension_img;
        move_uploaded_file(
            $_FILES["image" . $i . "P"]["tmp_name"],
            "../Ressources/Images/" . $images_secondaires["image" . $i . "P"]
        );
        $id_photo++;
    }
}
// On met à jour ou ajoute les images 2 à 6 en fonction de leur position dans la réponse SQL
for ($i = 2; $i <= 6; $i++) {
    if (isset($images_secondaires["image" . $i . "P"])) {
        $stmt = $dbh->prepare("
            SELECT * 
            from locbreizh._photos_secondaires
            where logement = $id_logement and numero = $i;
        ");
        $stmt->execute();
        $old = $stmt->fetch();
        $stmt = $dbh->prepare("
            INSERT into locbreizh._photo 
            values('{$images_secondaires["image" . $i . "P"]}')
        ");
        $stmt->execute();
        if(isset($old["photo"]) && $old["photo"] != ""){
            
            $stmt = $dbh->prepare("
                UPDATE locbreizh._photos_secondaires
                set photo = '{$images_secondaires["image" . $i . "P"]}'
                where photo = '{$old['photo']}'
            ");
            $stmt->execute();
        }
        else{
            $stmt = $dbh->prepare("
                INSERT into locbreizh._photos_secondaires
                values($id_logement, '{$images_secondaires["image" . $i . "P"]}', $i)
            ");
            $stmt->execute();
        }
    }
}
function id_photo($id_photo)
{
    return time() . $id_photo;
}
if (preg_match('/[^a-zA-Z1-9-" "\'À-ÖØ-öø-ÿ]+/', $libelle)){
    $_SESSION['erreurs']['libelle'] = 'Le libelle ne doit pas contenir de caractères spéciaux autre que des - et des \' il peut contenir des chiffres';
}
if ((preg_match('/^[0-9]{5}$/', $cdp))){
} else {
    $_SESSION['erreurs']['code_postal'] = 'le code postal doit être entier, ne doit pas contenir d\'espaces et ne doit pas avoir plus de 5 chiffres';
}
if ($tarif > $prix_max){
    $_SESSION['erreurs']['tarif_base_ht'] = 'le prix doit être entier et inférieur à 100 000 € ';
}
if ($_SESSION['erreurs'] != []){
    header("Location: modifierLogement.php?id_logement=" . $id_logement);
}
else {
    include('../parametre_connexion.php');
try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $reqadresse = $dbh->prepare("SELECT id_adresse FROM locbreizh._logement WHERE id_logement = $id_logement");
    $reqadresse->execute();
    $id_adresse = $reqadresse->fetchColumn();
    $stmt = $dbh->prepare(
        "UPDATE locbreizh._logement SET libelle_logement = '$libelle',tarif_base_ht = $tarif,descriptif_logement = '$description' WHERE id_logement = $id_logement"
    );
    $stmt->execute();
    $stmt = $dbh->prepare(
        "UPDATE locbreizh._adresse SET code_postal = $cdp , ville = '$ville' where id_adresse = $id_adresse"
    );
    $stmt->execute();
} catch (PDOException $e) {
    print "Erreur !:" . $e->getMessage() . "<br/>";
    die();
}
    header("Location: modifierLogement.php?modif=1&id_logement=$id_logement");
} 
?>