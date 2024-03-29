DROP SCHEMA if exists locbreizh CASCADE;

CREATE SCHEMA locbreizh;

SET SCHEMA 'locbreizh';

/*Creation des tables*/

/*   table adresse : est utilisée pour le logement et les comptes   */

CREATE TABLE
    _adresse (
        id_adresse SERIAL,
        nom_rue VARCHAR(30),
        numero_rue NUMERIC(3),
        code_postal CHAR(5) NOT NULL,
        pays VARCHAR(50),
        ville VARCHAR(50) NOT NULL,
        CONSTRAINT adresse_pk PRIMARY KEY (id_adresse)
    );

/*   table photo : est utilisée pour image de profil des compte ainsi que pour les photos des logements   */

CREATE TABLE
    _photo (
        url_photo VARCHAR(50) NOT NULL,
        CONSTRAINT photo_fk PRIMARY KEY (url_photo)
    );

/*   table compte : est utilisée comme base pour les compte clients et propriétaires   */

CREATE TABLE
    _compte (
        id_compte SERIAL,
        civilite VARCHAR(11) NOT NULL,
        nom VARCHAR(20) NOT NULL,
        prenom VARCHAR(20) NOT NULL,
        mail VARCHAR(50) NOT NULL UNIQUE,
        mot_de_passe VARCHAR(100) NOT NULL,
        pseudo VARCHAR(20) NOT NULL UNIQUE,
        telephone VARCHAR(10) NOT NULL UNIQUE,
        adresse INTEGER NOT NULL,
        photo VARCHAR(50) NOT NULL,
        CONSTRAINT compte_pk PRIMARY KEY (id_compte),
        CONSTRAINT compte_fk_adresse FOREIGN KEY (adresse) REFERENCES _adresse (id_adresse),
        CONSTRAINT compte_fk_photo FOREIGN KEY (photo) REFERENCES _photo (url_photo)
    );
/* table clefs API */
CREATE TABLE IF NOT EXISTS locbreizh._clefsapi(
    idclef numeric NOT NULL,
    droitgrandeconsultation boolean,
    droitpetiteconsultation boolean,
    droitconsultationcalendrier boolean,
    droitrendreindisponible boolean,
    estadmin boolean,
    id_proprio integer,
    CONSTRAINT _clefsapi_pk PRIMARY KEY (idclef)
);
/*   table proprietaire : est utilisée pour designer un propriétaire   */

CREATE TABLE
    _proprietaire (
        id_proprietaire SERIAL,
        rib CHAR(34) NOT NULL,
        carte_identite VARCHAR(50),
        CONSTRAINT proprietaire_pk PRIMARY KEY (id_proprietaire),
        CONSTRAINT proprietaire_fk_id FOREIGN KEY (id_proprietaire) REFERENCES _compte (id_compte)
    );

CREATE TABLE _icalendar(
    token VARCHAR(41) NOT NULL,
    debut DATE NOT NULL,
    fin DATE NOT NULL,
    reservations boolean NOT NULL,
    demandes boolean NOT NULL,
    indisponibilites boolean NOT NULL,
    CONSTRAINT icalendar_pk PRIMARY KEY (token)
);

CREATE TABLE _proprio_possede_token(
    token VARCHAR(41) NOT NULL, 
    proprio INTEGER NOT NULL,
    CONSTRAINT proprio_possede_token_pk PRIMARY KEY (proprio),
    CONSTRAINT proprio_possede_token_fk_token FOREIGN KEY (token) REFERENCES _icalendar (token),
    CONSTRAINT proprio_possede_token_fk_proprio FOREIGN KEY (proprio) REFERENCES _proprietaire (id_proprietaire)
);

/*   table client : est utilisée pour designer un client   */

CREATE TABLE
    _client (
        id_client SERIAL NOT NULL,
        dateNaissance DATE NOT NULL,
        age_legal BOOLEAN NOT NULL,
        CONSTRAINT client_pk PRIMARY KEY (id_client),
        CONSTRAINT client_fk_id FOREIGN KEY (id_client) REFERENCES _compte (id_compte)
    );

/*   table admin : est utilisée pour se connecter plus tard en tant qu'admin (pour signelements ou autre)   */

CREATE TABLE
    _admin (
        login VARCHAR(20) NOT NULL UNIQUE,
        mdp_admin VARCHAR(25) NOT NULL,
        CONSTRAINT admin_pk PRIMARY KEY (login)
    );

/*   table langue : est utilisée pour renseigner les langues parlés par les propriétaires   */

CREATE TABLE
    _langue (
        nom_langue VARCHAR(20) NOT NULL,
        CONSTRAINT langue_pk PRIMARY KEY (nom_langue)
    );

/*   table parle : fait le lien entre une langue et un propriétaire   */

CREATE TABLE
    _parle (
        langue VARCHAR(20) NOT NULL,
        proprietaire INTEGER NOT NULL,
        CONSTRAINT parle_pk PRIMARY KEY (langue, proprietaire),
        CONSTRAINT parle_fk_langue FOREIGN KEY (langue) REFERENCES _langue (nom_langue),
        CONSTRAINT parle_fk_proprio FOREIGN KEY (proprietaire) REFERENCES _proprietaire (id_proprietaire)
    );

/*   table conversation : est utilisée pour designer une conversation entre deux comptes différents   */

CREATE TABLE
    _conversation (
        id_conversation SERIAL NOT NULL,
        compte1 INTEGER NOT NULL,
        compte2 INTEGER NOT NULL,
        CONSTRAINT conversation_pk PRIMARY KEY (id_conversation),
        CONSTRAINT message_fk_compte1 FOREIGN KEY (compte1) REFERENCES _compte (id_compte),
        CONSTRAINT message_fk_compte2 FOREIGN KEY (compte2) REFERENCES _compte (id_compte)
    );

/*   table message : est utilisée pour stocker les messages des utilisateurs   */

CREATE TABLE
    _message (
        id_message SERIAL NOT NULL,
        contenu_message VARCHAR(255) NOT NULL,
        date_mess DATE NOT NULL,
        heure_mess TIME NOT NULL,
        auteur INTEGER NOT NULL,
        conversation INTEGER NOT NULL,
        CONSTRAINT message_pk PRIMARY KEY (id_message),
        CONSTRAINT message_fk_auteur FOREIGN KEY (auteur) REFERENCES _compte (id_compte),
        CONSTRAINT message_fk_conversation FOREIGN KEY (conversation) REFERENCES _conversation (id_conversation)
    );

/*   table planning : est utilisée pour renseigner les plages de disponibilité d'un logement   */

CREATE TABLE
    _planning (
        code_planning SERIAL,
        delai_depart_arrivee NUMERIC(2) NOT NULL,
        CONSTRAINT planning_pk PRIMARY KEY (code_planning)
        -- disponible ?
        -- si indisponible pourquoi ?
        -- différence de temps entre les départs et les arrivées
    );
    
/*   table plage_ponctuelle : est utilisée pour renseigner les plages de disponibilité et d'indisponibilité de manière ponctuelle  */

CREATE TABLE
    _plage_ponctuelle (
        id_plage_ponctuelle SERIAL,
        jour_plage_ponctuelle DATE NOT NULL,
        code_planning INTEGER NOT NULL,
        CONSTRAINT plage_ponctuelle_pk PRIMARY KEY (id_plage_ponctuelle),
        CONSTRAINT plage_ponctuelle_fk FOREIGN KEY (code_planning) REFERENCES _planning (code_planning)
    );

/*   table plage_ponctuelle_disponible : est utilisée pour renseigner les plages de disponibilité de manière ponctuelle  */

CREATE TABLE
    _plage_ponctuelle_disponible (
        id_plage_ponctuelle INTEGER,
        prix_plage_ponctuelle FLOAT NOT NULL,
        CONSTRAINT _plage_ponctuelle_disponible_pk PRIMARY KEY (id_plage_ponctuelle),
        CONSTRAINT _plage_ponctuelle_disponible_fk FOREIGN KEY (id_plage_ponctuelle) REFERENCES _plage_ponctuelle (id_plage_ponctuelle) ON DELETE CASCADE
    );
    
/*   table plage_ponctuelle_indisponible : est utilisée pour renseigner les plages d'indisponibilité de manière ponctuelle  */

CREATE TABLE
    _plage_ponctuelle_indisponible (
        id_plage_ponctuelle INTEGER,
        libelle_indisponibilite  VARCHAR(255),
        prix_plage_ponctuelle FLOAT,
        CONSTRAINT _plage_ponctuelle_indisponible_pk PRIMARY KEY (id_plage_ponctuelle),
        CONSTRAINT _plage_ponctuelle_indisponible_fk FOREIGN KEY (id_plage_ponctuelle) REFERENCES _plage_ponctuelle (id_plage_ponctuelle) ON DELETE CASCADE
    );

/*   table contrainte : est utilisée pour renseigner une contrainte au niveau du planning (ex : pas après 18h)   */

CREATE TABLE
    _contrainte (
        num_contrainte NUMERIC(2) NOT NULL,
        intitule VARCHAR(255) NOT NULL,
        code_planning INTEGER NOT NULL,
        CONSTRAINT contrainte_pk PRIMARY KEY (num_contrainte),
        CONSTRAINT contrainte_fk_planning FOREIGN KEY (code_planning) REFERENCES _planning (code_planning)
    );

/*   table taxe_sejour : est utilisée pour stocker les possible différentes taxes de séjour   */

CREATE TABLE
    _taxe_sejour (
        id_taxe SERIAL,
        prix_journalier_adulte NUMERIC(7, 2) NOT NULL,
        CONSTRAINT taxe_sejour_pk PRIMARY KEY (id_taxe)
    );

/*   table logement : est utilisée pour stocker les informations lié à un logement   */

CREATE TABLE
    _logement (
        id_logement SERIAL NOT NULL,
        libelle_logement VARCHAR(30) NOT NULL,
        tarif_base_HT NUMERIC(7, 2) NOT NULL,
        accroche_logement VARCHAR(100) NOT NULL,
        descriptif_logement VARCHAR(500) NOT NULL,
        nature_logement VARCHAR(15) NOT NULL,
        type_logement VARCHAR(20),
        surface_logement NUMERIC(4) NOT NULL,
        en_ligne BOOLEAN NOT NULL,
        nb_chambre NUMERIC(3) NOT NULL,
        nb_personnes_logement NUMERIC(3) NOT NULL,
        lit_simple NUMERIC(3) NOT NULL,
        lit_double NUMERIC(3) NOT NULL,
        nb_salle_bain NUMERIC(3) NOT NULL,
        jardin NUMERIC(5) NOT NULL,
        balcon BOOLEAN NOT NULL,
        terrasse BOOLEAN NOT NULL,
        parking_public BOOLEAN NOT NULL,
        parking_privee BOOLEAN NOT NULL,
        sauna BOOLEAN NOT NULL,
        hammam BOOLEAN NOT NULL,
        piscine BOOLEAN NOT NULL,
        climatisation BOOLEAN NOT NULL,
        jacuzzi BOOLEAN NOT NULL,
        television BOOLEAN NOT NULL,
        wifi BOOLEAN NOT NULL,
        lave_linge BOOLEAN NOT NULL,
        lave_vaisselle BOOLEAN NOT NULL,
        code_planning INTEGER,
        -- peut être null si pas de planning
        id_proprietaire INTEGER NOT NULL,
        id_adresse INTEGER NOT NULL,
        photo_principale VARCHAR(50) NOT NULL,
        taxe_sejour integer not null,
        moyenne_avis NUMERIC(2, 1),
        CONSTRAINT logement_pk PRIMARY KEY (id_logement),
        CONSTRAINT logement_fk_planning FOREIGN KEY (code_planning) REFERENCES _planning (code_planning),
        CONSTRAINT logement_fk_proprietaire FOREIGN KEY (id_proprietaire) REFERENCES _proprietaire (id_proprietaire),
        CONSTRAINT logement_fk_adresse FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse),
        CONSTRAINT logement_fk_photo FOREIGN KEY (photo_principale) REFERENCES _photo (url_photo),
        CONSTRAINT logement_fk_taxe foreign key(taxe_sejour) REFERENCES _taxe_sejour(id_taxe)
    );

/*   table photo_secondaires : est utilisée pour faire le lien entre des images et un logement   */

CREATE TABLE
    _photos_secondaires (
        logement INTEGER NOT NULL,
        photo VARCHAR(50) NOT NULL,
        numero INTEGER NOT NULL,
        CONSTRAINT photos_secondaires_pk PRIMARY KEY (logement, photo),
        CONSTRAINT photos_secondaires_fk_logement FOREIGN KEY (logement) REFERENCES _logement (id_logement),
        CONSTRAINT photos_secondaires_fk_photo FOREIGN KEY (photo) REFERENCES _photo (url_photo)
    );

/*   table avis : est utilisée pour stocker les différents avis fait par des clients ayant déja fait une resaervation    */

CREATE TABLE
    _avis (
        id_avis SERIAL NOT NULL,
        contenu_avis VARCHAR(500) NOT NULL,
        note_avis NUMERIC(1) NOT NULL,
        auteur INTEGER NOT NULL,
        logement INTEGER NOT NULL,
        CONSTRAINT avis_pk PRIMARY KEY (id_avis),
        CONSTRAINT avis_fk_auteur FOREIGN KEY (auteur) REFERENCES _client (id_client),
        CONSTRAINT avis_fk_logement FOREIGN KEY (logement) REFERENCES _logement (id_logement)
    );

/*   table reponse : est utilisée pour stocker la réponse d'un propriétaire sur un avis   */

CREATE TABLE
    _reponse (
        id_reponse SERIAL NOT NULL,
        contenu_reponse VARCHAR(500) NOT NULL,
        avis INTEGER NOT NULL,
        auteur INTEGER NOT NULL,
        CONSTRAINT reponse_pk PRIMARY KEY (id_reponse),
        CONSTRAINT reponse_fk_avis FOREIGN KEY (avis) REFERENCES _avis (id_avis),
        CONSTRAINT reponse_fk_auteur FOREIGN KEY (auteur) REFERENCES _proprietaire (id_proprietaire)
    );

/*   table signalement : est utilisée pour servir de base pour les différents types de signalement   */

CREATE TABLE
    _signalement (
        id_signalement SERIAL NOT NULL,
        motif VARCHAR(255) NOT NULL,
        date_signalement DATE NOT NULL,
        CONSTRAINT signalement_pk PRIMARY KEY (id_signalement)
    );

/*   table signalement_message : est utilisée pour rendre compte d'un signalement de message   */

CREATE TABLE
    _signalement_message (
        id_signalement INTEGER NOT NULL,
        auteur INTEGER NOT NULL,
        message INTEGER NOT NULL,
        CONSTRAINT signalement_message_pk PRIMARY KEY (id_signalement),
        CONSTRAINT signalement_message_fk_id FOREIGN KEY (id_signalement) REFERENCES _signalement (id_signalement),
        CONSTRAINT ecrit_signalement_fk_message FOREIGN KEY (message) REFERENCES _message (id_message),
        CONSTRAINT ecrit_signalement_fk_auteur FOREIGN KEY (auteur) REFERENCES _compte (id_compte)
    );

/*   table signalement_avis : est utilisée pour rendre compte d'un signalement d'un avis   */

CREATE TABLE
    _signalement_avis (
        id_signalement INTEGER NOT NULL,
        auteur INTEGER NOT NULL,
        avis INTEGER NOT NULL,
        CONSTRAINT signalement_avis_pk PRIMARY KEY (id_signalement),
        CONSTRAINT signalement_avis_fk_id FOREIGN KEY (id_signalement) REFERENCES _signalement (id_signalement),
        CONSTRAINT ecrit_signalement_fk_avis FOREIGN KEY (avis) REFERENCES _avis (id_avis),
        CONSTRAINT ecrit_signalement_fk_auteur FOREIGN KEY (auteur) REFERENCES _compte (id_compte)
    );

CREATE TABLE
    _signalement_reponse (
        id_signalement INTEGER NOT NULL,
        auteur INTEGER NOT NULL,
        reponse INTEGER NOT NULL,
        CONSTRAINT signalement_reponse_pk PRIMARY KEY (id_signalement),
        CONSTRAINT signalement_reponse_fk_id FOREIGN KEY (id_signalement) REFERENCES _signalement (id_signalement),
        CONSTRAINT ecrit_signalement_fk_reponse FOREIGN KEY (reponse) REFERENCES _reponse (id_reponse),
        CONSTRAINT ecrit_signalement_r_fk_auteur FOREIGN KEY (auteur) REFERENCES _compte (id_compte)
    );

/*   table signalement_compte : est utilisée pour rendre compte d'un signalement d'un compte   */

CREATE TABLE
    _signalement_compte (
        id_signalement INTEGER NOT NULL,
        auteur INTEGER NOT NULL,
        compte_signale INTEGER NOT NULL,
        CONSTRAINT signalement_compte_pk PRIMARY KEY (id_signalement),
        CONSTRAINT signalement_compte_fk_id FOREIGN KEY (id_signalement) REFERENCES _signalement (id_signalement),
        CONSTRAINT ecrit_signalement_fk_compte_signale FOREIGN KEY (compte_signale) REFERENCES _compte (id_compte),
        CONSTRAINT ecrit_signalement_fk_auteur FOREIGN KEY (auteur) REFERENCES _compte (id_compte)
    );

/*   table cherges_additionnelles : est utilisée pour renseigner les différents types de charges   */

CREATE TABLE
    _charge_additionnelles (
        nom_charges VARCHAR(50) NOT NULL,
        CONSTRAINT charge_additionnelle_pk PRIMARY KEY (nom_charges)
    );

/*   table carte : est utilisée pour stocké les informations bancaires d'une carte de manière chiffrée   */

CREATE TABLE
    _carte (
        num_carte_chiffre VARCHAR(50) NOT NULL,
        date_validite_chiffre VARCHAR(50) NOT NULL,
        cryptoramme_chiffre VARCHAR(50) NOT NULL,
        type_carte VARCHAR(50) NOT NULL,
        titulaire VARCHAR(30) NOT NULL,
        CONSTRAINT carte_pk PRIMARY KEY (num_carte_chiffre)
    );

/*   table paye_avec : est utilisée pour faire le lien entre un client et sa carte bancaire   */

CREATE TABLE
    _paye_avec (
        num_carte_chiffre VARCHAR(50) NOT NULL,
        id_client INTEGER NOT NULL,
        CONSTRAINT paye_avec_pk PRIMARY KEY (num_carte_chiffre, id_client),
        CONSTRAINT paye_avec_fk_client FOREIGN KEY (id_client) REFERENCES _client (id_client),
        CONSTRAINT paye_avec_fk_carte FOREIGN KEY (num_carte_chiffre) REFERENCES _carte (num_carte_chiffre)
    );

/*   table demande_devis : est utilisée pour rendre compte d'une demande de devis fait par un client   */

CREATE TABLE
    _demande_devis (
        num_demande_devis SERIAL,
        nb_personnes NUMERIC(3) NOT NULL,
        date_arrivee DATE NOT NULL,
        date_depart DATE NOT NULL,
        client INTEGER NOT NULL,
        logement INTEGER NOT NULL,
        url_detail varchar(50) not null,
        accepte boolean,
        visibleP boolean DEFAULT TRUE NOT NULL,
        visibleC boolean DEFAULT TRUE NOT NULL,
        CONSTRAINT demande_devis_pk PRIMARY KEY (num_demande_devis),
        CONSTRAINT demande_devis_fk_client FOREIGN KEY (client) REFERENCES _client (id_client),
        CONSTRAINT demande_devis_fk_logement FOREIGN KEY (logement) REFERENCES _logement (id_logement)
    );

/*   table devis : est utilisée pour rendre compte d'un devis fait par un proprietaire   */

CREATE TABLE
    _devis (
        num_devis serial,
        client integer NOT NULL,
        date_arrivee DATE NOT NULL,
        date_depart DATE NOT NULL,
        prix_total_devis NUMERIC(7, 2) NOT NULL,
        tarif_HT_location_nuitee_devis NUMERIC(7, 2) NOT NULL,
        sous_total_ht_devis NUMERIC(7, 2) NOT NULL,
        sous_total_ttc_devis NUMERIC(7, 2) NOT NULL,
        frais_service_platforme_ht_devis NUMERIC(7, 2) NOT NULL,
        frais_service_platforme_ttc_devis NUMERIC(7, 2) NOT NULL,
        date_devis DATE NOT NULL,
        date_validite numeric(3) not null,
        condition_annulation VARCHAR(255) NOT NULL,
        num_demande_devis INTEGER NOT NULL,
        taxe_sejour integer not null,
        url_detail varchar(50) not null,
        nb_personnes NUMERIC(3) NOT NULL,
        accepte boolean,
        annule boolean DEFAULT FALSE NOT NULL,
        visibleP boolean DEFAULT TRUE NOT NULL,
        visibleC boolean DEFAULT TRUE NOT NULL,
        CONSTRAINT devis_pk PRIMARY KEY (num_devis),
        CONSTRAINT devis_fk_taxe_sejour FOREIGN KEY (taxe_sejour) REFERENCES _taxe_sejour (id_taxe),
        CONSTRAINT devis_fk_demande_devis FOREIGN KEY (num_demande_devis) REFERENCES _demande_devis (num_demande_devis)
    );

/*   table facture : est utilisée pour stocker les informations une facture   */

CREATE TABLE
    _facture (
        num_facture serial,
        num_devis INTEGER NOT NULL,
        url_facture varchar(50) not null,
        CONSTRAINT facture_pk PRIMARY KEY (num_facture),
        CONSTRAINT facture_fk_devis FOREIGN KEY (num_devis) REFERENCES _devis (num_devis)
    );

/*   table reservation : est utilisée pour rendre compte d'une reservation d'un client pour un logement   */

CREATE TABLE
    _reservation (
        num_reservation SERIAL NOT NULL,
        reservation_annulee BOOLEAN NOT NULL,
        client INTEGER NOT NULL,
        logement integer not null,
        facture INTEGER not null,
        CONSTRAINT reservation_pk PRIMARY KEY (num_reservation),
        CONSTRAINT reservation_fk_client FOREIGN KEY (client) REFERENCES _client (id_client),
        CONSTRAINT reservation_fk_logement FOREIGN KEY (logement) REFERENCES _logement (id_logement),
        CONSTRAINT reservation_fk_facture FOREIGN KEY (facture) REFERENCES _facture (num_facture)
    );

/*   table facure_avoir : est utilisée pour stocker les informations une facture d'avoir en cas d'annulation   */

CREATE TABLE
    _facture_avoir (
        num_facture SERIAL,
        type_remboursement VARCHAR(10) NOT NULL,
        pourcentage_remboursement NUMERIC(3, 2) NOT NULL,
        prix_total_reservation_TTC NUMERIC(7, 2) NOT NULL,
        prix_a_rembourser_TTC NUMERIC(7, 2) NOT NULL,
        reservation INTEGER NOT NULL,
        CONSTRAINT facture_avoir_pk PRIMARY KEY (num_facture),
        CONSTRAINT facture_avoir_fk_reservation FOREIGN KEY (reservation) REFERENCES _reservation (num_reservation)
    );

/*   table comporte_charges_associee_demande_devis : permet de faire le lien entre un demande de devis et les charges additionnelles voulus   */

CREATE TABLE
    _comporte_charges_associee_demande_devis (
        prix_charges NUMERIC(7, 2) NOT NULL,
        num_demande_devis INTEGER NOT NULL,
        nom_charges VARCHAR(50) NOT NULL,
        nombre INTEGER,
        CONSTRAINT comporte_charges_associee_demande_devis_pk PRIMARY KEY (
            num_demande_devis,
            nom_charges
        ),
        CONSTRAINT comporte_charges_associee_demande_devis_fk_devis FOREIGN KEY (num_demande_devis) REFERENCES _demande_devis (num_demande_devis),
        CONSTRAINT comporte_charges_associee_demande_devis_fk_charges FOREIGN KEY (nom_charges) REFERENCES _charge_additionnelles (nom_charges)
    );

/*   table comporte_charges_associee_devis : permet de faire le lien entre un devis et les charges additionnelles decidées   */

CREATE TABLE
    _comporte_charges_associee_devis (
        prix_charges NUMERIC(7, 2) NOT NULL,
        num_devis INTEGER NOT NULL,
        nom_charges VARCHAR(50) NOT NULL,
        nombre INTEGER,
        CONSTRAINT comporte_charges_associee_devis_pk PRIMARY KEY (num_devis, nom_charges),
        CONSTRAINT comporte_charges_associee_devis_fk_devis FOREIGN KEY (num_devis) REFERENCES _devis (num_devis),
        CONSTRAINT comporte_charges_associee_devis_fk_charges FOREIGN KEY (nom_charges) REFERENCES _charge_additionnelles (nom_charges)
    );

/*   table possede_charges_associee_logement : permet de faire le lien entre un logement et les charges additionnelles qu'il possèdent   */

CREATE TABLE
    _possede_charges_associee_logement (
        prix_charges NUMERIC(7, 2) NOT NULL,
        id_logement INTEGER NOT NULL,
        nom_charges VARCHAR(50) NOT NULL,
        CONSTRAINT possede_charges_associee_logement_pk PRIMARY KEY (id_logement, nom_charges),
        CONSTRAINT possede_charges_associee_logement_fk_logement FOREIGN KEY (id_logement) REFERENCES _logement (id_logement),
        CONSTRAINT possede_charges_associee_logement_fk_charges FOREIGN KEY (nom_charges) REFERENCES _charge_additionnelles (nom_charges)
    );
/*table de referencement des services compris dans chaque logement */

CREATE TABLE IF NOT EXISTS locbreizh._services_compris
(
    logement integer NOT NULL,
    nom_service varchar(50) not null,
    CONSTRAINT services_compris_pk PRIMARY KEY (logement, nom_service),
    CONSTRAINT services_compris_fk_logement FOREIGN KEY (logement) REFERENCES locbreizh._logement (id_logement)
);

/* TRIGGER */

CREATE OR REPLACE FUNCTION update_note_logement()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE locbreizh._logement
    SET moyenne_avis = (
        /* COALESCE est utilisé pour mettre 0 si null*/
        SELECT COALESCE(AVG(note_avis), 0)
        FROM locbreizh._avis
        WHERE logement = NEW.logement
    )
    WHERE id_logement = NEW.logement;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER avis_trigger
AFTER INSERT OR UPDATE OR DELETE ON locbreizh._avis
FOR EACH ROW EXECUTE FUNCTION update_note_logement();

/* Peuplement de la base */

INSERT INTO _charge_additionnelles VALUES ( 'menage' );

INSERT INTO _charge_additionnelles VALUES ( 'animaux' );

INSERT INTO
    _charge_additionnelles
VALUES ('personnes_supplementaires');

INSERT INTO _admin VALUES ( 'admin', 'admin');
