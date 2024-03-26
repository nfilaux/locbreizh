<?php
    // n°1 -> check si token déja existant & créer si besoin (dans BDD)
    // n°2 -> la page d'admin avec les paramètre du calendar change quels event choisir
    // n°3 -> le token.ics doit automatiquement se mettre à jour quand on y accède
    //Variables
    $date_debut = mktime(14, 30, 00, 03, 26, 2024);
    $date_fin = mktime(15, 45, 00, 03, 26, 2024);
    $objet = "Objet test";
    $lieu = "IUT Lannion";
    $details = "Test pour la création d'un ics";
    //Evenèment au format ICS
    $ics  = "BEGIN:VCALENDAR\n";
    $ics .= "VERSION:2.0\n";
    $ics .= "PRODID:-//hacksw/handcal//NONSGML v1.0//EN\n";
    $ics .= "BEGIN:VEVENT\n";
    $ics .= "X-WR-TIMEZONE:Europe/Paris\n";
    $ics .= "DTSTART:".date('Ymd',$date_debut)."T".date('His',$date_debut)."\n";
    $ics .= "DTEND:".date('Ymd',$date_fin)."T".date('His',$date_fin)."\n";
    $ics .= "SUMMARY:".$objet."\n";
    $ics .= "LOCATION:".$lieu."\n";
    $ics .= "DESCRIPTION:".$details."\n";
    $ics .= "END:VEVENT\n";
    $ics .= "END:VCALENDAR\n";
    
    $file = 'calendar_ics/test.ics';
    file_put_contents($file, $ics);
    header('Location: ./calendar_ics/test.ics');
?>