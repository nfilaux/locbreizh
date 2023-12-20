<?php 
    if(!isset($_SESSION['id'])){
        echo file_get_contents('../header-footer/footer.html');
    }
    else{
        include('../parametre_connexion.php');
        try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print "Erreur !:" . $e->getMessage() . "<br/>";
            die();
        }

        $stmt = $dbh->prepare("SELECT id_compte from locbreizh._compte c join locbreizh._client on c.id_compte = id_client where id_compte = :id ;");
        $stmt->bindParam(':id', $_SESSION['id']);
        $stmt->execute();
        $est_client = $stmt->fetch();

        if(isset($est_client['id_compte'])){
            $est_client = True;
        }
        else{
            $est_client = False;
        }

        if($est_client){
            ob_start(); // Start output buffering
            include 'footer.html'; // Include the PHP file
            $output = ob_get_clean();
            echo $output;
        }
        else{
            ob_start(); // Start output buffering
            include 'footerP.html'; // Include the PHP file
            $output = ob_get_clean();
            echo $output;
        }
    } 
?>