-- Table: locbreizh._logement

-- DROP TABLE IF EXISTS locbreizh._logement;

CREATE TABLE IF NOT EXISTS locbreizh._logement
(
    id_logement integer NOT NULL DEFAULT nextval('locbreizh._logement_id_logement_seq'::regclass),
    libelle_logement character varying(30) COLLATE pg_catalog."default" NOT NULL,
    tarif_base_ht numeric(7,2) NOT NULL,
    accroche_logement character varying(255) COLLATE pg_catalog."default" NOT NULL,
    descriptif_logement character varying(255) COLLATE pg_catalog."default" NOT NULL,
    nature_logement character varying(15) COLLATE pg_catalog."default" NOT NULL,
    type_logement character varying(20) COLLATE pg_catalog."default",
    surface_logement numeric(4,0) NOT NULL,
    en_ligne boolean NOT NULL,
    nb_chambre numeric(3,0) NOT NULL,
    nb_personnes_logement numeric(3,0) NOT NULL,
    lit_simple numeric(3,0) NOT NULL,
    lit_double numeric(3,0) NOT NULL,
    nb_salle_bain numeric(3,0) NOT NULL,
    jardin numeric(5,0) NOT NULL,
    balcon boolean NOT NULL,
    terrasse boolean NOT NULL,
    parking_public boolean NOT NULL,
    parking_privee boolean NOT NULL,
    sauna boolean NOT NULL,
    hammam boolean NOT NULL,
    piscine boolean NOT NULL,
    climatisation boolean NOT NULL,
    jacuzzi boolean NOT NULL,
    television boolean NOT NULL,
    wifi boolean NOT NULL,
    lave_linge boolean NOT NULL,
    lave_vaisselle boolean NOT NULL,
    code_planning integer,
    id_proprietaire integer NOT NULL,
    id_adresse integer NOT NULL,
    photo_principale character varying(50) COLLATE pg_catalog."default" NOT NULL,
    taxe_sejour integer NOT NULL,
    CONSTRAINT logement_pk PRIMARY KEY (id_logement),
    CONSTRAINT logement_fk_adresse FOREIGN KEY (id_adresse)
        REFERENCES locbreizh._adresse (id_adresse) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION,
    CONSTRAINT logement_fk_photo FOREIGN KEY (photo_principale)
        REFERENCES locbreizh._photo (url_photo) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION,
    CONSTRAINT logement_fk_planning FOREIGN KEY (code_planning)
        REFERENCES locbreizh._planning (code_planning) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION,
    CONSTRAINT logement_fk_proprietaire FOREIGN KEY (id_proprietaire)
        REFERENCES locbreizh._proprietaire (id_proprietaire) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION,
    CONSTRAINT logement_fk_taxe FOREIGN KEY (taxe_sejour)
        REFERENCES locbreizh._taxe_sejour (id_taxe) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS locbreizh._logement
    OWNER to sae;