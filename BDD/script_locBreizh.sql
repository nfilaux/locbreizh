DROP SCHEMA if exists locbreizh CASCADE;

CREATE SCHEMA locbreizh;

SET SCHEMA 'locbreizh';

/*Creation des tables*/

CREATE TABLE
    _adresse (
        id_adresse SERIAL,
        nom_rue VARCHAR(30) NOT NULL,
        numero_rue NUMERIC(3) NOT NULL,
        code_postal CHAR(5) NOT NULL,
        pays VARCHAR(50) NOT NULL,
        ville VARCHAR(50) NOT NULL,
        constraint adresse_pk primary key (id_adresse)
    );

CREATE TABLE
    _photo (
        url_photo VARCHAR(50) NOT NULL,
        CONSTRAINT photo_fk PRIMARY KEY (url_photo)
    );

CREATE TABLE
    _compte (
        id_compte SERIAL,
        civilite VARCHAR(11) NOT NULL,
        nom VARCHAR(20) NOT NULL,
        prenom VARCHAR(20) NOT NULL,
        mail VARCHAR(50) NOT NULL UNIQUE,
        mot_de_passe VARCHAR(25) NOT NULL,
        pseudo VARCHAR(20) NOT NULL UNIQUE,
        telephone VARCHAR(10) NOT NULL UNIQUE,
        adresse INTEGER NOT NULL,
        photo VARCHAR(50) NOT NULL,
        CONSTRAINT compte_pk PRIMARY KEY (id_compte),
        CONSTRAINT compte_fk_adresse FOREIGN KEY (adresse) REFERENCES _adresse (id_adresse),
        CONSTRAINT compte_fk_photo FOREIGN KEY (photo) REFERENCES _photo (url_photo)
    );

CREATE TABLE
    _proprietaire (
        id_proprietaire SERIAL,
        rib CHAR(34) NOT NULL,
        carte_identite VARCHAR(50) NOT NULL,
        CONSTRAINT proprietaire_pk PRIMARY KEY (id_proprietaire),
        CONSTRAINT proprietaire_fk_id FOREIGN KEY (id_proprietaire) REFERENCES _compte (id_compte)
    );

CREATE TABLE
    _client (
        id_client SERIAL NOT NULL,
        dateNaissance DATE NOT NULL,
        age_legal BOOLEAN NOT NULL,
        CONSTRAINT client_pk PRIMARY KEY (id_client),
        CONSTRAINT client_fk_id FOREIGN KEY (id_client) REFERENCES _compte (id_compte)
    );

CREATE TABLE
    _admin (
        login VARCHAR(20) NOT NULL UNIQUE,
        mdp_admin VARCHAR(25) NOT NULL,
        CONSTRAINT admin_pk PRIMARY KEY (login)
    );

CREATE TABLE
    _langue (
        nom_langue VARCHAR(20) NOT NULL,
        CONSTRAINT langue_pk PRIMARY KEY (nom_langue)
    );

CREATE TABLE
    _parle (
        langue VARCHAR(20) NOT NULL,
        proprietaire INTEGER NOT NULL,
        CONSTRAINT parle_pk PRIMARY KEY (langue, proprietaire),
        CONSTRAINT parle_fk_langue FOREIGN KEY (langue) REFERENCES _langue (nom_langue),
        CONSTRAINT parle_fk_proprio FOREIGN KEY (proprietaire) REFERENCES _proprietaire (id_proprietaire)
    );

CREATE TABLE
    _conversation (
        id_conversation SERIAL NOT NULL,
        compte1 INTEGER NOT NULL,
        compte2 INTEGER NOT NULL,
        CONSTRAINT conversation_pk PRIMARY KEY (id_conversation),
        CONSTRAINT message_fk_compte1 FOREIGN KEY (compte1) REFERENCES _compte (id_compte),
        CONSTRAINT message_fk_compte2 FOREIGN KEY (compte2) REFERENCES _compte (id_compte)
    );

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

CREATE TABLE
    _planning (
        code_planning SERIAL,
        tarif_journalier_base NUMERIC(5, 2) NOT NULL,
        duree_minimale_sejour NUMERIC(2) NOT NULL,
        delai_minimum_heure NUMERIC(2) NOT NULL,
        CONSTRAINT planning_pk PRIMARY KEY (code_planning)
    );

CREATE TABLE
    _plage_ponctuelle (
        id_plage_ponctuelle SERIAL,
        debut_plage_ponctuelle DATE NOT NULL,
        fin_plage_ponctuelle DATE NOT NULL,
        code_planning INTEGER NOT NULL,
        CONSTRAINT plage_ponctuelle_pk PRIMARY KEY (id_plage_ponctuelle),
        CONSTRAINT plage_ponctuelle_fk FOREIGN KEY (code_planning) REFERENCES _planning (code_planning)
    );

CREATE TABLE
    _plage_ponctuelle_indisponibilite (
        id_plage_ponctuelle_indisp INTEGER NOT NULL,
        motif_indisponibilite VARCHAR(255) NOT NULL,
        CONSTRAINT plage_ponctuelle_indisponibilite_pk PRIMARY KEY (motif_indisponibilite),
        CONSTRAINT plage_ponctuelle_indisponibilite_fk_id_plage FOREIGN KEY (id_plage_ponctuelle_indisp) REFERENCES _plage_ponctuelle (id_plage_ponctuelle)
    );

CREATE TABLE
    _contrainte (
        num_contrainte NUMERIC(2) NOT NULL,
        intitule VARCHAR(255) NOT NULL,
        code_planning INTEGER NOT NULL,
        CONSTRAINT contrainte_pk PRIMARY KEY (num_contrainte),
        CONSTRAINT contrainte_fk_planning FOREIGN KEY (code_planning) REFERENCES _planning (code_planning)
    );

CREATE TABLE
    _plage_recurrente (
        id_plage_recurrente SERIAL NOT NULL,
        code_planning INTEGER NOT NULL,
        debut_plage VARCHAR(8) NOT NULL,
        fin_plage VARCHAR(8) NOT NULL,
        type_plage VARCHAR(25) NOT NULL,
        CONSTRAINT plage_recurrente_pk PRIMARY KEY (id_plage_recurrente),
        CONSTRAINT plage_recurrente_fk_code_planning FOREIGN KEY (code_planning) REFERENCES _planning (code_planning)
    );

CREATE TABLE
    _logement (
        id_logement SERIAL NOT NULL,
        libelle_logement VARCHAR(30) NOT NULL,
        tarif_base_HT NUMERIC(5, 2) NOT NULL,
        accroche_logement VARCHAR(255) NOT NULL,
        descriptif_logement VARCHAR(255) NOT NULL,
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
        lave_vaiselle BOOLEAN NOT NULL,
        code_planning INTEGER NOT NULL,
        id_proprietaire INTEGER NOT NULL,
        id_adresse INTEGER NOT NULL,
        photo_principale VARCHAR(50) NOT NULL,
        CONSTRAINT logement_pk PRIMARY KEY (id_logement),
        CONSTRAINT logement_fk_planning FOREIGN KEY (code_planning) REFERENCES _planning (code_planning),
        CONSTRAINT logement_fk_proprietaire FOREIGN KEY (id_proprietaire) REFERENCES _proprietaire (id_proprietaire),
        CONSTRAINT logement_fk_adresse FOREIGN KEY (id_adresse) REFERENCES _adresse (id_adresse),
        CONSTRAINT logement_fk_photo FOREIGN KEY (photo_principale) REFERENCES _photo (url_photo)
    );

CREATE TABLE
    _photos_secondaires (
        logement INTEGER NOT NULL,
        photo VARCHAR(50) NOT NULL,
        CONSTRAINT photos_secondaires_pk PRIMARY KEY (logement, photo),
        CONSTRAINT photos_secondaires_fk_logement FOREIGN KEY (logement) REFERENCES _logement (id_logement),
        CONSTRAINT photos_secondaires_fk_photo FOREIGN KEY (photo) REFERENCES _photo (url_photo)
    );

CREATE TABLE
    _avis (
        id_avis SERIAL NOT NULL,
        contenu_avis VARCHAR(255) NOT NULL,
        note_avis NUMERIC(1) NOT NULL,
        auteur INTEGER NOT NULL,
        logement INTEGER NOT NULL,
        CONSTRAINT avis_pk PRIMARY KEY (id_avis),
        CONSTRAINT avis_fk_auteur FOREIGN KEY (auteur) REFERENCES _client (id_client),
        CONSTRAINT avis_fk_logement FOREIGN KEY (logement) REFERENCES _logement (id_logement)
    );

CREATE TABLE
    _reponse (
        id_reponse SERIAL NOT NULL,
        contenu_reponse VARCHAR(255) NOT NULL,
        avis INTEGER NOT NULL,
        auteur INTEGER NOT NULL,
        CONSTRAINT reponse_pk PRIMARY KEY (id_reponse),
        CONSTRAINT reponse_fk_avis FOREIGN KEY (avis) REFERENCES _avis (id_avis),
        CONSTRAINT reponse_fk_auteur FOREIGN KEY (auteur) REFERENCES _proprietaire (id_proprietaire)
    );

CREATE TABLE
    _signalement (
        id_signalement SERIAL NOT NULL,
        motif VARCHAR(255) NOT NULL,
        date_signalement DATE NOT NULL,
        CONSTRAINT signalement_pk PRIMARY KEY (id_signalement)
    );

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
    _signalement_compte (
        id_signalement INTEGER NOT NULL,
        auteur INTEGER NOT NULL,
        compte_signale INTEGER NOT NULL,
        CONSTRAINT signalement_compte_pk PRIMARY KEY (id_signalement),
        CONSTRAINT signalement_compte_fk_id FOREIGN KEY (id_signalement) REFERENCES _signalement (id_signalement),
        CONSTRAINT ecrit_signalement_fk_compte_signale FOREIGN KEY (compte_signale) REFERENCES _compte (id_compte),
        CONSTRAINT ecrit_signalement_fk_auteur FOREIGN KEY (auteur) REFERENCES _compte (id_compte)
    );

CREATE TABLE
    _charge_additionnelles (
        nom_charges VARCHAR(20) NOT NULL,
        CONSTRAINT charge_additionnelle_pk PRIMARY KEY (nom_charges)
    );

CREATE TABLE
    _carte (
        num_carte_chiffre VARCHAR(50) NOT NULL,
        date_validite_chiffre VARCHAR(50) NOT NULL,
        cryptoramme_chiffre VARCHAR(50) NOT NULL,
        type_carte VARCHAR(50) NOT NULL,
        titulaire VARCHAR(30) NOT NULL,
        CONSTRAINT carte_pk PRIMARY KEY (num_carte_chiffre)
    );

CREATE TABLE
    _paye_avec (
        num_carte_chiffre VARCHAR(50) NOT NULL,
        id_client INTEGER NOT NULL,
        CONSTRAINT paye_avec_pk PRIMARY KEY (num_carte_chiffre, id_client),
        CONSTRAINT paye_avec_fk_client FOREIGN KEY (id_client) REFERENCES _client (id_client),
        CONSTRAINT paye_avec_fk_carte FOREIGN KEY (num_carte_chiffre) REFERENCES _carte (num_carte_chiffre)
    );

CREATE TABLE
    _taxe_sejour (
        id_taxe SERIAL NOT NULL,
        prix_journalier_adulte NUMERIC(5, 2) NOT NULL,
        CONSTRAINT taxe_sejour_pk PRIMARY KEY (id_taxe)
    );

CREATE TABLE
    _demande_devis (
        num_demande_devis SERIAL,
        nb_personnes NUMERIC(3) NOT NULL,
        date_arrivee DATE NOT NULL,
        date_depart DATE NOT NULL,
        client INTEGER NOT NULL,
        logement INTEGER NOT NULL,
        CONSTRAINT demande_devis_pk PRIMARY KEY (num_demande_devis),
        CONSTRAINT demande_devis_fk_client FOREIGN KEY (client) REFERENCES _client (id_client),
        CONSTRAINT demande_devis_fk_logement FOREIGN KEY (logement) REFERENCES _logement (id_logement)
    );

CREATE TABLE
    _devis (
        num_devis INTEGER NOT NULL,
        pseudo_client_devis VARCHAR(20) NOT NULL,
        prix_total_devis NUMERIC(5, 2) NOT NULL,
        tarif_HT_location_nuitee_devis NUMERIC(5, 2) NOT NULL,
        sous_total_HT_devis NUMERIC(5, 2) NOT NULL,
        sous_total_TTC_devis NUMERIC(5, 2) NOT NULL,
        frais_service_platforme_HT_devis NUMERIC(5, 2) NOT NULL,
        fras_service_platforme_TTC_devis NUMERIC(5, 2) NOT NULL,
        date_devis DATE NOT NULL,
        date_validité DATE NOT NULL,
        condition_annulation VARCHAR(255) NOT NULL,
        num_demande_devis INTEGER NOT NULL,
        CONSTRAINT devis_pk PRIMARY KEY (num_devis),
        CONSTRAINT devis_fk_taxe_sejour FOREIGN KEY (num_devis) REFERENCES _taxe_sejour (id_taxe),
        CONSTRAINT devis_fk_demande_devis FOREIGN KEY (num_devis) REFERENCES _demande_devis (num_demande_devis)
    );

CREATE TABLE
    _reservation (
        num_reservation SERIAL NOT NULL,
        date_reservation DATE NOT NULL,
        reservation_annulee BOOLEAN NOT NULL,
        client INTEGER NOT NULL,
        CONSTRAINT reservation_pk PRIMARY KEY (num_reservation),
        CONSTRAINT reservation_fk_client FOREIGN KEY (client) REFERENCES _client (id_client)
    );

CREATE TABLE
    _facture (
        num_facture INTEGER NOT NULL,
        num_devis INTEGER NOT NULL,
        CONSTRAINT facture_pk PRIMARY KEY (num_facture),
        CONSTRAINT facture_fk_devis FOREIGN KEY (num_facture) REFERENCES _devis (num_devis),
        CONSTRAINT facture_fk_rservation FOREIGN KEY (num_facture) REFERENCES _reservation (num_reservation)
    );

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

CREATE TABLE
    _comporte_charges_associee_demande_devis (
        prix_charges NUMERIC(5, 2) NOT NULL,
        num_demande_devis INTEGER NOT NULL,
        nom_charges VARCHAR(20) NOT NULL,
        CONSTRAINT comporte_charges_associee_demande_devis_pk PRIMARY KEY (
            num_demande_devis,
            nom_charges
        ),
        CONSTRAINT comporte_charges_associee_demande_devis_fk_devis FOREIGN KEY (num_demande_devis) REFERENCES _devis (num_devis),
        CONSTRAINT comporte_charges_associee_demande_devis_fk_charges FOREIGN KEY (nom_charges) REFERENCES _charge_additionnelles (nom_charges)
    );

CREATE TABLE
    _comporte_charges_associee_devis (
        prix_charges NUMERIC(5, 2) NOT NULL,
        num_devis INTEGER NOT NULL,
        nom_charges VARCHAR(20) NOT NULL,
        CONSTRAINT comporte_charges_associee_devis_pk PRIMARY KEY (num_devis, nom_charges),
        CONSTRAINT comporte_charges_associee_devis_fk_devis FOREIGN KEY (num_devis) REFERENCES _devis (num_devis),
        CONSTRAINT comporte_charges_associee_devis_fk_charges FOREIGN KEY (nom_charges) REFERENCES _charge_additionnelles (nom_charges)
    );

CREATE TABLE
    _possede_charges_associee_logement (
        prix_charges NUMERIC(5, 2) NOT NULL,
        id_logement INTEGER NOT NULL,
        nom_charges VARCHAR(20) NOT NULL,
        CONSTRAINT possede_charges_associee_logement_pk PRIMARY KEY (id_logement, nom_charges),
        CONSTRAINT possede_charges_associee_logement_fk_logement FOREIGN KEY (id_logement) REFERENCES _logement (id_logement),
        CONSTRAINT possede_charges_associee_logement_fk_charges FOREIGN KEY (nom_charges) REFERENCES _charge_additionnelles (nom_charges)
    );

/* Peuplement de la base */

INSERT INTO _photo VALUES ( 'carte/id/dubois' );

INSERT INTO _photo VALUES ( 'photos/dubois' );

INSERT INTO
    _adresse(
        nom_rue,
        numero_rue,
        code_postal,
        pays,
        ville
    )
VALUES (
        'rue du soleil',
        89,
        '22440',
        'ploufragan',
        'france'
    );

INSERT INTO
    _compte(
        civilite,
        nom,
        prenom,
        mail,
        mot_de_passe,
        pseudo,
        telephone,
        adresse,
        photo
    )
VALUES (
        'Monsieur',
        'Dubois',
        'Jean',
        'jeandubois@gmail.com',
        'jeandubois22',
        'jdubois',
        '0612457889',
        1,
        'photos/dubois'
    );

INSERT INTO _proprietaire
VALUES (
        1,
        '65465654646465',
        'carte/id/dubois'
    );

INSERT INTO
    _compte(
        civilite,
        nom,
        prenom,
        mail,
        mot_de_passe,
        pseudo,
        telephone,
        adresse,
        photo
    )
VALUES (
        'Monsieur',
        'Martin',
        'Pierre',
        'pmartin@gmail.com',
        'martin22',
        'mpierre',
        '0612457823',
        1,
        'photos/dubois'
    );

INSERT INTO _proprietaire
VALUES (
        2,
        '65465654646445',
        'carte/id/dubois'
    );

INSERT INTO
    _compte(
        civilite,
        nom,
        prenom,
        mail,
        mot_de_passe,
        pseudo,
        telephone,
        adresse,
        photo
    )
VALUES (
        'Madame',
        'Grand',
        'Michelle',
        'mgrand@gmail.com',
        'michelle22',
        'mgrand',
        '0712457823',
        1,
        'photos/dubois'
    );

INSERT INTO _proprietaire
VALUES (
        3,
        '00465654646445',
        'carte/id/dubois'
    );

INSERT INTO
    _compte (
        civilite,
        nom,
        prenom,
        mail,
        mot_de_passe,
        pseudo,
        telephone,
        adresse,
        photo
    )
VALUES (
        'Madame',
        'Lucas',
        'Martine',
        'mlucas@gmail.com',
        'martinelucas22',
        'lmartine',
        '0698987845',
        1,
        'photos/dubois'
    );

INSERT INTO _client VALUES (4, '2000-05-15', 'true');

INSERT INTO _langue VALUES ( 'français' );

INSERT INTO _parle VALUES ( 'français', 1 );

INSERT INTO
    _planning(
        tarif_journalier_base,
        duree_minimale_sejour,
        delai_minimum_heure
    )
VALUES (500, 2, 24);

INSERT INTO
    _logement(
        libelle_logement,
        tarif_base_HT,
        accroche_logement,
        descriptif_logement,
        nature_logement,
        type_logement,
        surface_logement,
        en_ligne,
        nb_chambre,
        nb_personnes_logement,
        lit_simple,
        lit_double,
        nb_salle_bain,
        jardin,
        balcon,
        terrasse,
        parking_public,
        parking_privee,
        sauna,
        hammam,
        piscine,
        climatisation,
        jacuzzi,
        television,
        wifi,
        lave_linge,
        lave_vaiselle,
        code_planning,
        id_proprietaire,
        id_adresse,
        photo_principale
    )
VALUES (
        'Manoir Hanté',
        500,
        'Manoir à la campagne avec grand terrain et de muliple pièces',
        'Manoir',
        '',
        'manoir',
        500,
        TRUE,
        10,
        14,
        3,
        7,
        4,
        300,
        FALSE,
        TRUE,
        FALSE,
        TRUE,
        FALSE,
        FALSE,
        TRUE,
        TRUE,
        TRUE,
        TRUE,
        TRUE,
        TRUE,
        TRUE,
        1,
        1,
        1,
        'photos/dubois'
    );

INSERT INTO
    _logement(
        libelle_logement,
        tarif_base_HT,
        accroche_logement,
        descriptif_logement,
        nature_logement,
        type_logement,
        surface_logement,
        en_ligne,
        nb_chambre,
        nb_personnes_logement,
        lit_simple,
        lit_double,
        nb_salle_bain,
        jardin,
        balcon,
        terrasse,
        parking_public,
        parking_privee,
        sauna,
        hammam,
        piscine,
        climatisation,
        jacuzzi,
        television,
        wifi,
        lave_linge,
        lave_vaiselle,
        code_planning,
        id_proprietaire,
        id_adresse,
        photo_principale
    )
VALUES (
        'Maison de campagne',
        250,
        'Maison à la campagne avec grand terrain et de muliple pièces',
        'Maison',
        '',
        'maison',
        125,
        TRUE,
        4,
        6,
        1,
        3,
        2,
        3000,
        FALSE,
        TRUE,
        FALSE,
        TRUE,
        FALSE,
        FALSE,
        TRUE,
        TRUE,
        TRUE,
        TRUE,
        TRUE,
        TRUE,
        TRUE,
        1,
        1,
        1,
        'photos/dubois'
    );

INSERT INTO _conversation(compte1, compte2) VALUES (1, 1, 2);

INSERT INTO
    _message(
        contenu_message,
        date_mess,
        heure_mess,
        auteur,
        conversation
    )
VALUES (
        'un message tres habituel',
        '2023/10/17',
        '21:36',
        1,
        1
    );

INSERT INTO
    _message(
        contenu_message,
        date_mess,
        heure_mess,
        auteur,
        conversation
    )
VALUES (
        'un message tres habituel mais different',
        '2023/10/17',
        '21:38',
        2,
        1
    );

INSERT INTO
    _message(
        contenu_message,
        date_mess,
        heure_mess,
        auteur,
        conversation
    )
VALUES (
        'un message très recent !',
        '2023/10/18',
        '21:38',
        2,
        1
    );

INSERT INTO _conversation(compte1, compte2) VALUES (2, 1, 3);

INSERT INTO
    _message(
        contenu_message,
        date_mess,
        heure_mess,
        auteur,
        conversation
    )
VALUES (
        'salut dubois !',
        '2023/10/01',
        '14:32',
        3,
        2
    );

INSERT INTO
    _message(
        contenu_message,
        date_mess,
        heure_mess,
        auteur,
        conversation
    )
VALUES (
        'bonjour pierre ! vraiment heureux de te parler aujourd hui j espere que ca va mais dis donc ce message est vraiment long ou bien ? On dirait que cest volontaire ..',
        '2023/10/01',
        '22:00',
        1,
        2
    );