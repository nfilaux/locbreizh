<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>

<body>
    <header>
        <img src="/Ressources/Images/logo.svg">
        <h2>Loc'Breizh</h2>

        <img src="/Ressources/Images/filtre.svg">

        <input id="searchbar" type="text" name="search">
        <img src="/Ressources/Images/loupe.svg">

        <img src="/Ressources/Images/booklet-fill 1.svg">
        <h4>Accèder à mes logements</h4>


        <img src="/Ressources/Images/message.svg">
        <img src="/Ressources/Images/compte.svg">
        <hr>
    </header>
    <main>
        <?php
        try {
            include_once '../BDD/script_locBreizh.php';

            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $password);

            foreach ($dbh->query(
                'SELECT photo_principale, libelle_logement, tarif_base_HT, capacite, note_avis, debut_plage_ponctuelle, fin_plage_ponctuelle
                from _logement 
                    INNER JOIN _avis ON id_avis = id_logement 
                    INNER JOIN _planning ON id_planning = id_logement 
                    INNER JOIN _plage_ponctuelle ON id_plage_ponctuelle = id_planning',
                PDO::FETCH_ASSOC
            ) as $card) {
                echo '<div class="card">';
                echo '<img src="' . $card['photo_principale'] . '">';
                echo '<h3>' . $card['libelle_logement'] . '</h3>';
                echo '<img src="/Ressources/Images/star.svg"> . <h4>' . $card['note_avis'] . '</h4>';
                echo '<h4>' . $card['tarif_base_HT'] . '€</h4>';
                echo '<h4>' . $card['debut_plage_ponctuelle'] . ' - ' . $card['fin_plage_ponctuelle'] . '</h4>';
                echo '<h4>' . $card['capacite'] . ' personnes</h4>';
            }
        } catch (PDOException $e) {
            print "Erreur !:" . $e->getMessage() . "<br/>";
            die();
        }
        ?>
    </main>
    <footer>

    </footer>
</body>

</html>