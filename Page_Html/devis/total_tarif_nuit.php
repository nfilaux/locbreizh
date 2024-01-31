<?php
include('../parametre_connexion.php');

$num_demande = $_GET['num_demande'];
$dateArrivee = $_GET['date_arrivee'];
$dateDepart = $_GET['date_depart'];

try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $stmt = $dbh->prepare("
        SELECT prix_plage_ponctuelle
        FROM locbreizh._demande_devis d
        JOIN locbreizh._logement l ON d.logement = l.id_logement
        JOIN locbreizh._planning p ON p.code_planning = l.code_planning
        JOIN locbreizh._plage_ponctuelle p1 ON p1.code_planning = p.code_planning
        JOIN locbreizh._plage_ponctuelle_disponible p2 ON p2.id_plage_ponctuelle = p1.id_plage_ponctuelle
        WHERE num_demande_devis = :num_demande
        AND jour_plage_ponctuelle >= :date_arrivee::DATE
        AND jour_plage_ponctuelle < :date_depart::DATE
    ");
    $stmt->bindParam(':num_demande', $num_demande);
    $stmt->bindParam(':date_arrivee', $dateArrivee);
    $stmt->bindParam(':date_depart', $dateDepart);
    $stmt->execute();
    $prices = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

    echo json_encode(['prices' => $prices]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>