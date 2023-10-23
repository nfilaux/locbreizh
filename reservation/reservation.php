<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserver</title>
    <!--paypal-->
    
</head>
<body>
    <header class="row col-12">
        <div class="row col-3">
            <img src="svg//logo.svg">
            <h2 style="margin-top: auto; margin-bottom: auto; margin-left: 10px;">Loc'Breizh</h2>
        </div>

        <div class="row col-3">
            <img class="col-2" src="svg//filtre.svg">
            <input class="col-7" id="searchbar" type="text" name="search" style="height: 50px; margin-top: auto; margin-bottom: auto;">
            <img class="col-2" src="svg//loupe.svg">
        </div>

        <div class="row col-5 offset-md-1">
            <a class="col-4 offset-md-3 row btn-accueilins"><h5 style="margin-top: auto; margin-bottom: auto;">S'inscrire</h5></a>
            <a class="col-4 offset-md-1 row btn-accueil"><h5 style="margin-top: auto; margin-bottom: auto;">Se connecter</h5></a>
        </div>
    </header>
    <main>
        <h1>Paiement de la reservation</h1>
        <div id="paypal-button-container"></div>
        <script src="https://www.paypal.com/sdk/js?client-id=AZ9f4oyPDCMS1YixHmqPR4wPGz2OM5CC59wpo0KTCHc2jaOMYZivqTjz-Wj52tAprCRF5PkLZD1dnn9s&currency=EUR"></script>
        <script src="paypal.js"></script>
    </main>
    <footer class="container-fluid" >
        <div class="column">   
            <div class="text-center row">
                <p class="testfoot col-2"><a href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a></p>
                <p class="testfoot offset-md-2 col-2"><a href="tel:+33623455689">(+33) 6 23 45 56 89</a></p>
                <p class="testfoot offset-md-1 col-2"><a href="connexion.html"><img src="svg/instagram.svg">  @LocBreizh</a></p>
                <p class="testfoot offset-md-1 col-2  "><a href="connexion.html"><img src="svg/facebook.svg">  @LocBreizh</a></p>
            </div>
            <hr>  
            <div class="text-center row">
                <p class="offset-md-1 col-2 testfooter">©2023 Loc’Breizh</p>
                <p class="offset-md-1 col-3 testfooter" style="text-decoration: underline;"><a href="connexion.html">Conditions générales</a></p>
                <p class="offset-md-1 col-4 testfooter" >Développé par <a href="connexion.html" style="text-decoration: underline;">7ème sens</a></p>
            </div>
        </div>
    </footer>
</body>
</html>