<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="style_proprio-client.css">
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
            include('../Connexion/page_connexion.php');

            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $stmt = $dbh->prepare(
                'SELECT photo_principale, libelle_logement, tarif_base_ht, nb_personnes_logement, note_avis, debut_plage_ponctuelle, fin_plage_ponctuelle
                from locbreizh._logement 
                    INNER JOIN locbreizh._avis ON logement = id_logement
                    INNER JOIN locbreizh._planning ON _planning.code_planning = _logement.code_planning
                    INNER JOIN locbreizh._plage_ponctuelle ON _planning.code_planning = _plage_ponctuelle.code_planning;'
            );
        } catch (PDOException $e) {
            print "Erreur !:" . $e->getMessage() . "<br/>";
            die();
        }

        function formatDate($start, $end)
        {
            $startDate = date('j', strtotime($start));
            $endDate = date('j', strtotime($end));
            $month = date('M', strtotime($end));

            return "$startDate-$endDate $month";
        }

        $stmt->execute();
        foreach ($stmt->fetchAll() as $card) {
            echo '<div class="card">';
            echo '<img src="' . $card['photo_principale'] . '">';
            echo '<h3>' . $card['libelle_logement'] . '</h3>';
            echo '<h4>' . $card['tarif_base_ht'] . '€</h4>';
            echo '<img src="/Ressources/Images/star.svg"> . <h4>' . $card['note_avis'] . '</h4>';
            echo '<h4>' . formatDate($card['debut_plage_ponctuelle'], $card['fin_plage_ponctuelle']) . '</h4>';
            echo '<h4>' . $card['nb_personnes_logement'] . ' personnes</h4>';
        }
        ?>

        <div class='voir_plus'>
            <hr>
            <h4>Voir plus</h4>
            <hr>
        </div>
    </main>
    <footer class="mt-4 container-fluid">
        <div class="mt-4 column">
            <div class="col-12 text-center">
                <a class="col-2" href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a>
                <a class="offset-md-1 col-2" href="tel:+33623455689">(+33) 6 23 45 56 89</a>
                <a class="offset-md-1 col-1" href="connexion.html"><img src="svg/instagram.svg"> @LocBreizh</a>
                <a class="offset-md-1 col-1" href="connexion.html"><img src="svg/facebook.svg"> @LocBreizh</a>
            </div>
            <hr>
            <div class="logo">
                <img src="/Ressources/Images/logo2.svg">
            </div>
            <div class="offset-md-1 col-10 mt-4 text-center row">
                <p class="offset-md-1 col-2">©2023 Loc’Breizh</p>
                <p class="offset-md-1 col-3" style="text-decoration: underline;"><a href="connexion.html">Conditions générales</a></p>
                <p class="offset-md-1 col-4">Développé par <a href="connexion.html" style="text-decoration: underline;">7ème sens</a></p>
            </div>
        </div>
    </footer>
</body>

</html>