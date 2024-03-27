#define _XOPEN_SOURCE
#include <getopt.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <stdio.h>
#include <stdlib.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <unistd.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <string.h>
#include <ctype.h>
#include <stdbool.h>
#include <postgresql/libpq-fe.h>
#include <time.h>


void envoyer_morceau(const char *morceau, int cnx) {
    write(cnx, morceau, strlen(morceau));
    char newline = '\n';
    write(cnx, &newline, sizeof(newline));
}

int main(int argc, char **argv){

    char opt;
    int verbose = 0;
    const char *conninfo;
    PGconn     *conn;
    PGresult   *res,*resultat,*infos_clef,*disponible,*indisponible;

    int sock, ret, size, cnx; // Initialisation des variables
    int port; // Initialisation du port
    char id_proprio[120] =""; // id du propriétaire connecté avec sa clef à l'API

    printf("connexion avec la BDD en cours.....\n");

    // Information de connexion à la base de donnée
    conninfo = "host = localhost port = 5432 dbname = sae user = sae password = ieTeec3thah3sho4";

    conn = PQconnectdb(conninfo);

    if (PQstatus(conn) != CONNECTION_OK)
    {
        fprintf(stderr, "%s", PQerrorMessage(conn));
    } else {
        printf("Connexion avec la base de donnée établie\n");
    }

    // Gestion des arguments
    while((opt = getopt(argc, argv, "abvc:dhp:012:")) != -1) {
       
       if (opt == -1){
            break;
       }
       switch (opt) {

       case 'h':
            printf("Options:\n");
            printf("  -v  --verbose         Print verbose messages\n");
            printf("  -p  --port=PORT        Specify port\n");
            exit(0);

        case 'p':
            port = atoi(optarg);
            printf("connexion établi sur le port : %d\n",port);
            break;
        
        case 'v':
            verbose = 1;
            break;

        case '?':
            break;

        default:
            fprintf(stderr, "Usage: %s -p <port> -v -o <output_file>\n", argv[0]);
            exit(EXIT_FAILURE);
        }
    }

    // Gestion des erreurs de port
    if (port < 1024 || port > 65535){
        printf("Le port doit être compris entre 1024 et 65535\n");
        exit(1);
    }
    
    struct sockaddr_in addr;
    char r_buffer[1000]; // Initialisation du buffer
    int r;
    bool admin = false; // Initialisation de la variable admin

    // Utilisation des sockets
    sock = socket(AF_INET, SOCK_STREAM, 0);

    if (sock == -1) {
        perror("sock :");
        exit(1);
    }
    
    cnx = socket(AF_INET, SOCK_STREAM, 0);

    if (cnx == -1) {
        perror("cnx :");
        exit(1);
    }

    addr.sin_addr.s_addr = INADDR_ANY;
    addr.sin_family = AF_INET;
    addr.sin_port = htons(port);

    ret = bind(sock, (struct sockaddr *)&addr, sizeof(addr));

    if (ret == -1)
    {
        perror("Erreur lors du bind\n");
        exit(1);
    }

    ret = listen(sock, 1);

    if (ret == -1)
    {
        perror("Erreur lors du listen\n");
        exit(1);
    }

    struct sockaddr_in conn_addr;
    size = sizeof(conn_addr);
    
    cnx = accept(sock, (struct sockaddr *)&conn_addr, (socklen_t *)&size);

    if (cnx == -1)
    {
        perror("Erreur lors de l'acceptation\n");
        exit(1);
    }

    int h, min, s, day, mois, an,max_id;
    time_t now;
    bool cle_valide = false,droit_liste_biens = false ,droit_liste_proprio = false ,droit_consult = false,droit_indispo = false,droit_dispo = false,prix_saisi = false;
        
    // Renvoie l'heure actuelle
    time(&now);

    // Convertion au format heure locale
    struct tm *local = localtime(&now);
    h = local->tm_hour;        
    min = local->tm_min;       
    s = local->tm_sec;       
    day = local->tm_mday;          
    mois = local->tm_mon + 1;     
    an = local->tm_year + 1900;

    char commande_en_cours[500];

    // Boucle de lecture de la clé
    while(cle_valide == false) {
        memset(r_buffer, 0, sizeof(r_buffer));
        char validation[6] = "";

        printf("Lecture de la clef propriétaire\n");

        r = read(cnx, r_buffer, sizeof(r_buffer));

        // Vérifification des caractères spéciaux ou des espaces dans la clé lue
        /*for (size_t i = 0; i < strlen(r_buffer); i++) {
            if (!isalnum(r_buffer[i])) {
                printf("Caractère non-alphanumérique détecté dans la clé.\n");
                strcpy(validation, "false");
                write(cnx, validation, strlen(validation));
                break;
            }
        }

        // Si la clé n'est pas valide, on passe à la prochaine itération de la boucle
        if (strcmp(validation, "") != 0) {
            continue;
        }*/

        char request[10000] =""; // Requête sur la BDD

        snprintf(request, sizeof(request), "SELECT * FROM locbreizh._clefsapi WHERE idclef = '%s'", r_buffer);                                                                                                                                                                                                                                                                         

        res = PQexec(conn, request);

        strcpy(request,"");

        // Vérification du résultat de la requête
        if (PQresultStatus(res) == PGRES_TUPLES_OK) {
            int nombre_de_lignes = PQntuples(res);


            // On accède aux colonnes uniquement si des lignes sont présentes
            if (nombre_de_lignes > 0) {

                // Vérification du nombre de lignes pour la clé
                if (nombre_de_lignes == 1) {
                    strcpy(validation, "true");
                    write(cnx, validation, strlen(validation));
                    snprintf(request, sizeof(request), "SELECT * FROM locbreizh._clefsapi WHERE idclef = '%s' and estadmin = true", r_buffer);
                    res = PQexec(conn, request);

                    strcpy(request,"");

                    snprintf(request, sizeof(request), "SELECT * FROM locbreizh._clefsapi WHERE idclef = '%s'", r_buffer);
                    infos_clef = PQexec(conn, request);

                    printf("true? %c false?\n",PQgetvalue(infos_clef,0,1)[0]);
                    printf("true? %c false?\n",PQgetvalue(infos_clef,0,2)[0]);
                    printf("true? %c false?\n",PQgetvalue(infos_clef,0,3)[0]);
                    printf("true? %c false?\n",PQgetvalue(infos_clef,0,4)[0]);
                    if (PQgetvalue(infos_clef,0,1)[0] == 't'){
                        droit_liste_biens = true;
                    }
                    if (PQgetvalue(infos_clef,0,2)[0] == 't'){
                        droit_liste_proprio = true;
                    }
                    if (PQgetvalue(infos_clef,0,3)[0] == 't'){
                        droit_consult = true;
                    }
                    if (PQgetvalue(infos_clef,0,4)[0] == 't'){
                        droit_indispo = true;
                    }
                    if (PQgetvalue(infos_clef,0,7)[0] == 't'){
                        droit_dispo = true;
                    }

                    printf("test : %d %d %d %d %d\n",droit_liste_biens,droit_liste_proprio,droit_consult,droit_indispo,droit_dispo);
                    

                    if (PQntuples(res) == 1){
                        admin = true;
                        write(cnx, "admin", strlen("admin"));
                    } else {
                        write(cnx, "proprio", strlen("proprio"));
                    }

                    cle_valide = true;
                } else {
                    printf("Erreur : Nombre de lignes inattendu dans le résultat.\n");
                    strcpy(validation, "false");
                    write(cnx, validation, strlen(validation));
                }
            } else {
                printf("Erreur : La clé n'existe pas dans la base de données (nombre de lignes = 0).\n");
                strcpy(validation, "false");
                write(cnx, validation, strlen(validation));
            }
        } else {
            fprintf(stderr, "Erreur lors de l'exécution de la requête : %s\n", PQerrorMessage(conn));
            strcpy(validation, "false");
            write(cnx, validation, strlen(validation));
        }
    }

    // Récupération de l'id du propriétaire
    char id_prop[10000] = "";
    int prix;
    if (admin == false){

        snprintf(id_prop, sizeof(id_prop), "SELECT id_proprio FROM locbreizh._clefsapi WHERE idclef = '%s'", r_buffer);
        res = PQexec(conn, id_prop);
        strcpy(id_proprio,PQgetvalue(res,0,0));
    }

    // Boucle de lecture des commandes
    while (true) {

        // Réinitialisation le buffer à chaque itération
        memset(r_buffer, 0, sizeof(r_buffer));
        r = read(cnx, r_buffer, sizeof(r_buffer));

        strcpy(commande_en_cours,r_buffer);
        
        if (r <= 0) {
            perror("Erreur lors de la lecture du socket ou connexion fermée !\n");
            break;
        }

        ssize_t bytes_sent;

        if (r > 0) {

            r_buffer[r] = '\0'; // Assurez-vous que la chaîne est terminée par un caractère nul
            printf("buffer : %s\n",r_buffer);
            // Vérifiez si toute la chaîne ne contient que des chiffres
            int estNombre = 1; // Supposons d'abord que la chaîne est un nombre
            for (int i = 0; r_buffer[i] != '\0'; i++) {
                if (!isdigit(r_buffer[i])) {
                    estNombre = 0; // La chaîne ne contient pas seulement des chiffres
                    break;
                }
            }

            char chaine[3000] = "";
            char chaine1[1000] = "";
            char chaine2[1000] = "";
            char chaine3[1000] = "";

            memset(chaine2, 0, sizeof(chaine2));
            memset(chaine3, 0, sizeof(chaine3));

            // Si la chaîne est un nombre, convertissez-la en entier
            if (estNombre) {
                prix = atoi(r_buffer);
                prix_saisi = true;
                printf("le prix est de : %d\n",prix);
            }

            else if (strncmp(r_buffer, "--informations", strlen("--informations")) == 0) {

                if(droit_liste_biens){
                    printf("damien dort\n");
                }
                
                if (verbose == 1){
                    printf("%02d:%02d:%d %02d:%02d:%02d reçu du client la commande --infos\n",day, mois, an,h, min, s);
                }

                snprintf(chaine, sizeof(chaine), "\033[1m\nListe des commandes :\n\033[0;33m");
                write(cnx, chaine, strlen(chaine));
                fflush(stdout);

                if (admin)
                {
                    snprintf(chaine1, sizeof(chaine1), "\033[0;33m\t--ListeLogements     Affichez la liste de tous les biens\n\033[0m\n");
                    write(cnx, chaine1, strlen(chaine1));
                    fflush(stdout);
                }
                else
                {
                    snprintf(chaine1, sizeof(chaine1), "\033[0;33m\t--MesLogements    Affichez la liste de vos biens\n\033[0m\n");
                    write(cnx, chaine1, strlen(chaine1));
                    fflush(stdout);
                }



                snprintf(chaine, sizeof(chaine), "\033[0;34m\t--ConsulterPlage|<logement>|<debut>|<fin>\n");
                ssize_t bytes_sent1 = write(cnx, chaine, strlen(chaine) + 1);
                if (bytes_sent1 == -1) {
                    perror("Erreur lors de l'envoi de chaine au client");
                }

                fflush(stdout);

                snprintf(chaine, sizeof(chaine), "   Dates au format annee-mois-jour, Affichez le calendrier des disponibilités du logement sélectionné\n\033[0m");
                printf("Longueur du message chaine : %zu\n", strlen(chaine));
                ssize_t bytes_sent2 = write(cnx, chaine, strlen(chaine) + 1);
                if (bytes_sent2 == -1) {
                    perror("Erreur lors de l'envoi de chaine au client");
                }

                fflush(stdout);

                snprintf(chaine, sizeof(chaine), "\033[38;2;139;69;19m\t--RendrePlageIndisponible|<nom_du_logement>|<date_debut_plage>|<date_fin>\n");
                printf("Longueur du message chaine : %zu\n", strlen(chaine));
                ssize_t bytes_sent3 = write(cnx, chaine, strlen(chaine) + 1);
                if (bytes_sent3 == -1) {
                    perror("Erreur lors de l'envoi de chaine au client");
                }

                fflush(stdout);

                snprintf(chaine, sizeof(chaine), "   Dates au format annee-mois-jour, Rendez indisponible votre logement sur la période sélectionné\n\033[0m");
                printf("Longueur du message chaine : %zu\n", strlen(chaine));
                ssize_t bytes_sent4 = write(cnx, chaine, strlen(chaine) + 1);
                fflush(stdout);
                if (bytes_sent4 == -1) {
                    perror("Erreur lors de l'envoi de chaine au client");
                }

                if (verbose == 1){
                    printf("%02d:%02d:%d %02d:%02d:%02d réponse du serveur envoyé au client\n",day, mois, an,h, min, s);
                }

                const char *fin_message = "\nfin\n";
                printf("fin : %s",fin_message);
                bytes_sent = write(cnx, fin_message, strlen(fin_message));

            } else if (strncmp(r_buffer, "--MesLogements", strlen("--MesLogements")) == 0) {
                    
                if(droit_liste_proprio){
                
                    if (verbose == 1){
                        printf("%02d:%02d:%d %02d:%02d:%02d reçu du client la commande --MesLogements\n",day, mois, an,h, min, s);
                    }
                    char requete[1000] = "SELECT * from locbreizh._logement where id_proprietaire ="; // Requete sur la bdd
                    strcat(requete,id_proprio);
                    res = PQexec(conn,requete);

                    if (PQresultStatus(res) != PGRES_TUPLES_OK)
                    {
                        fprintf(stderr, "BEGIN command failed: %s\n", PQerrorMessage(conn));
                        PQclear(res);
                    }

                    int nombres_lignes_req = PQntuples(res);
                    printf("nombre de comptes : %d\n",nombres_lignes_req);
                    
                    snprintf(chaine, sizeof(chaine), "\033[1m\nListe de vos biens :\n\033[0m");
                    write(cnx, chaine, strlen(chaine));
                    fflush(stdout);
                    
                    // Affichage des lignes du résultat de la requête
                    for(int i = 0; i < nombres_lignes_req ;i++){
                        snprintf(chaine, sizeof(chaine), "\t%s -- %s € -- %s -- %s -- %s -- %s m²\n",PQgetvalue(res,i,1),PQgetvalue(res,i,2),PQgetvalue(res,i,3),PQgetvalue(res,i,5),PQgetvalue(res,i,6),PQgetvalue(res,i,7));
                        write(cnx, chaine, strlen(chaine));
                        fflush(stdout);  
                    }

                    if (verbose == 1){
                        printf("%02d:%02d:%d %02d:%02d:%02d affichage des logements du propriétaire d'id %s\n",day, mois, an,h, min, s,id_proprio);
                    }

                    const char *fin_message = "\nfin\n";
                    bytes_sent = write(cnx, fin_message, strlen(fin_message));
                } else {
                    snprintf(chaine,sizeof(chaine),"\033[31m\nVotre clef ne vous octroie pas les droits pour cette commande !\033[0m");
                    write(cnx,chaine,sizeof(chaine));
                    write(cnx,"fin",sizeof("fin"));
                }

            } else if (strncmp(r_buffer, "--ListeLogements", strlen("--ListeLogements")) == 0) {
            
                if (droit_liste_biens == 1){
                
                    if (verbose == 1){
                        printf("%02d:%02d:%d %02d:%02d:%02d reçu du client un --ListeLogements \n",day, mois, an,h, min, s);
                    }

                    char requete[1000] = "SELECT * from locbreizh._logement"; // Requete sur la bdd
                    res = PQexec(conn,requete);

                    if (PQresultStatus(res) != PGRES_TUPLES_OK)
                    {
                        fprintf(stderr, "BEGIN command failed: %s\n", PQerrorMessage(conn));
                        PQclear(res);
                    }

                    int nombres_lignes_req = PQntuples(res);

                    if (verbose == 1){
                        printf("%02d:%02d:%d %02d:%02d:%02d envoi de la liste des logements au client \n",day, mois, an,h, min, s);
                    }
                    
                    // Affichage des lignes du résultat de la requête
                    for(int i = 0; i < nombres_lignes_req ;i++){
                        
                        printf("Nom: %s, Prix: %s, Description: %s, Chambres: %s, Salles de bains: %s, Superficie: %s m²\n",
                        PQgetvalue(res, i, 1), PQgetvalue(res, i, 2), PQgetvalue(res, i, 3),
                        PQgetvalue(res, i, 5), PQgetvalue(res, i, 6), PQgetvalue(res, i, 7));

                        snprintf(chaine, sizeof(chaine), "%s -- %s € -- %s -- %s -- %s -- %s m²\n",PQgetvalue(res,i,1),PQgetvalue(res,i,2),PQgetvalue(res,i,3),PQgetvalue(res,i,5),PQgetvalue(res,i,6),PQgetvalue(res,i,7));
                        write(cnx, chaine, strlen(chaine));
                        fflush(stdout);
                        strcpy(chaine,"");
                    }

                    write(cnx,"fin",sizeof("fin"));

                } else {
                    snprintf(chaine,sizeof(chaine),"\033[31m\nVotre clef ne vous octroie pas les droits pour cette commande !\033[0m");
                    write(cnx,chaine,sizeof(chaine));
                    write(cnx,"fin",sizeof("fin"));
                }

            } else if (strncmp(r_buffer, "--ConsulterPlage", sizeof(200)) == 0) {
            
                if (droit_consult == 1){
                
                    if (verbose == 1){
                        printf("%02d:%02d:%d %02d:%02d:%02d reçu du client un --ConsulterPlage \n",day, mois, an,h, min, s);
                    }
                    
                    char elements[5][100];
                    int i = 0;

                    // La définition des séparateurs connus.
                    const char *separators = "|";

                    // On cherche à récupérer, un à un, tous les mots (token) de la phrase et on commence par le premier.
                    char *strToken = strtok(r_buffer, separators);
                    while (strToken != NULL && i < 4) {
                        printf("%s\n", strToken);

                        strcpy(elements[i], strToken);
                        
                        // On demande le token suivant.
                        strToken = strtok(NULL, separators);
                        i++;
                    }

                    for (int j = 0; j < i; j++) {
                        printf("Element %d : %s\n", j, elements[j]);
                    }

                    char requete[1000];
                    snprintf(requete, sizeof(requete), "SELECT code_planning FROM locbreizh._logement WHERE libelle_logement='%s'", elements[1]);
                    res = PQexec(conn,requete);

                    if (PQresultStatus(res) != PGRES_TUPLES_OK)
                    {
                        fprintf(stderr, "BEGIN command failed: %s\n", PQerrorMessage(conn));
                        PQclear(res);
                    }

                    printf("id du planning du logement : %s\n",PQgetvalue(res,0,0));

                    snprintf(requete, sizeof(requete), "SELECT * FROM locbreizh._plage_ponctuelle WHERE code_planning='%s' and jour_plage_ponctuelle >= '%s' and jour_plage_ponctuelle <= '%s'",PQgetvalue(res,0,0),elements[2],elements[3]);
                    printf("la requete : %s\n",requete);
                    res = PQexec(conn,requete);

                    int nombres_lignes_req = PQntuples(res);
                    printf("nombres de plages : %d\n",nombres_lignes_req);

                    if (verbose == 1){
                        printf("%02d:%02d:%d %02d:%02d:%02d envoit au client la liste des disponibilités du logement : %s du %s au %s \n",day, mois, an,h, min, s,elements[1],elements[2],elements[3]);
                    }

                    for(int i = 0; i < nombres_lignes_req ;i++){
                    
                    snprintf(requete, sizeof(requete), "SELECT * FROM locbreizh._plage_ponctuelle_disponible WHERE id_plage_ponctuelle = %s",PQgetvalue(res,i,0));
                    printf("la requete : %s\n",requete);
                    resultat = PQexec(conn,requete);

                    int nombres_lignes_req_result = PQntuples(resultat);

                    if (nombres_lignes_req_result != 0){

                            printf("la plage disponable : %s\n",PQgetvalue(res,i,1));
                            strcpy(chaine, "");
                            snprintf(chaine,sizeof(chaine),"\033[38;2;0;0;139m%s :\033[0m \033[32mdisponible\033[0m\n",PQgetvalue(res,i,1));
                            write(cnx,chaine,sizeof(chaine));
                            fflush(stdout);
                    }

                    }

                    for(int i = 0; i < nombres_lignes_req ;i++){
                    
                        snprintf(requete, sizeof(requete), "SELECT * FROM locbreizh._plage_ponctuelle_indisponible WHERE id_plage_ponctuelle = '%s'",PQgetvalue(res,i,0));
                        printf("la requete : %s\n",requete);
                        resultat = PQexec(conn,requete);

                        int nombres_lignes_req_result = PQntuples(resultat);

                        if (nombres_lignes_req_result != 0){
                        
                        printf("la plage indispinable : %s\n",PQgetvalue(res,i,1));

                        strcpy(chaine, "");
                        snprintf(chaine,sizeof(chaine),"\033[38;2;0;0;139m%s :\033[0m \033[31mindisponible\033[0m\n",PQgetvalue(res,i,1));
                        write(cnx,chaine,sizeof(chaine));
                        fflush(stdout);
                        }

                        if (i==nombres_lignes_req-1){
                                if (verbose == 1){
                                    printf("%02d:%02d:%d %02d:%02d:%02d fin du planning de disponibilité : %s du %s au %s \n",day, mois, an,h, min, s,elements[1],elements[2],elements[3]);
                                }
                                write(cnx,"fin",sizeof("fin"));
                                fflush(stdout);
                        }

                    }
                } else {
                        snprintf(chaine,sizeof(chaine),"\033[31m\nVotre clef ne vous octroie pas les droits de consultation !\033[0m");
                        write(cnx,chaine,sizeof(chaine));
                        write(cnx,"fin",sizeof("fin"));
                }
                
            }

            else if (strncmp(r_buffer, "--RendrePlageIndisponible", strlen("--RendrePlageIndisponible")) == 0) {
                printf("c moi le buffer : %s\n",r_buffer);
                if (droit_indispo){
                
                    if (verbose == 1){
                        printf("%02d:%02d:%d %02d:%02d:%02d reçu du client un --RendreIndisponible : \n",day, mois, an,h, min, s);
                    }
                
                    char elements[5][100];
                    int i = 0;

                    const char *separators = "|";

                    // On cherche à récupérer, un à un, tous les mots (token) de la phrase et on commence par le premier.
                    char *strToken = strtok(r_buffer, separators);
                    while (strToken != NULL && i < 4) {
                        printf("%s\n", strToken);

                        strcpy(elements[i], strToken);
                        
                        // On demande le token suivant.
                        strToken = strtok(NULL, separators);
                        i++;
                    }

                    for (int j = 0; j < i; j++) {
                        printf("Element %d : %s\n", j, elements[j]);
                    }

                    char requete[1000];
                    snprintf(requete, sizeof(requete), "SELECT code_planning FROM locbreizh._logement WHERE libelle_logement='%s'", elements[1]);
                    res = PQexec(conn,requete);

                    if (PQresultStatus(res) != PGRES_TUPLES_OK)
                    {
                        fprintf(stderr, "BEGIN command failed: %s\n", PQerrorMessage(conn));
                        PQclear(res);
                    }

                    char code_planning[100] = "";
                    strcpy(code_planning,PQgetvalue(res,0,0));

                    printf("id du planning du logement : %s\n",PQgetvalue(res,0,0));

                    snprintf(requete, sizeof(requete), "SELECT * FROM locbreizh._plage_ponctuelle WHERE code_planning='%s' and jour_plage_ponctuelle >= '%s' and jour_plage_ponctuelle <= '%s'",code_planning,elements[2],elements[3]);
                    printf("la requete : %s\n",requete);
                    res = PQexec(conn,requete);

                    int nombres_lignes_req = PQntuples(res);
                    printf("nombres de plages : %d\n",nombres_lignes_req);

                    struct tm tm_start, tm_end;
                    memset(&tm_start, 0, sizeof(struct tm));
                    memset(&tm_end, 0, sizeof(struct tm));

                    // Convertir les chaînes de date en structures tm
                    strptime(elements[2], "%Y-%m-%d", &tm_start);
                    strptime(elements[3], "%Y-%m-%d", &tm_end);

                    // Convertir en timestamps
                    time_t start_timestamp = mktime(&tm_start);
                    time_t end_timestamp = mktime(&tm_end);

                    // Vérifier si les dates sont valides
                    if (start_timestamp == -1 || end_timestamp == -1) {
                        fprintf(stderr, "Dates invalides.\n");
                        return EXIT_FAILURE;
                    }

                    snprintf(requete, sizeof(requete), "SELECT * FROM locbreizh._plage_ponctuelle");
                    printf("la requete : %s\n",requete);
                    res = PQexec(conn,requete);

                    int nombres_plages = PQntuples(res);
                    printf("nombre de plages déjà existante : %d\n",nombres_plages);

                    if (verbose == 1){
                        printf("%02d:%02d:%d %02d:%02d:%02d début de la mise en indisponibilité du logement %s du %s au %s: \n",day, mois, an,h, min, s,elements[1],elements[2],elements[3]);
                    }
                    
                    // Boucle pour chaque jour entre les deux dates
                    for (time_t current_timestamp = start_timestamp; current_timestamp <= end_timestamp; current_timestamp += 86400) {
                        struct tm *current_tm = localtime(&current_timestamp);

                        // Afficher la date au format YYYY-MM-DD
                        char formatted_date[11];
                        strftime(formatted_date, sizeof(formatted_date), "%Y-%m-%d", current_tm);
                        printf("%s\n", formatted_date);

                        //on recup l'id de la plage associé au jour et au planning du logement sélectionné dans la commande
                        snprintf(requete, sizeof(requete), "SELECT id_plage_ponctuelle FROM locbreizh._plage_ponctuelle WHERE code_planning='%s' and jour_plage_ponctuelle = '%s'",code_planning,formatted_date);
                        printf("la requete : %s\n",requete);
                        res = PQexec(conn,requete);

                        int nombres_lignes_req = PQntuples(res);
                        
                        char id_plage[100] = "";
                        
                        if (nombres_lignes_req == 1 ){
                            strcpy(id_plage,PQgetvalue(res,0,0));
                        }

                        printf("ligne requete : %d",nombres_lignes_req);
                        
                        //si la plage n'existe pas on la créer
                        if(nombres_lignes_req < 1){
                            nombres_plages++;
                            snprintf(requete, sizeof(requete), "INSERT INTO locbreizh._plage_ponctuelle(id_plage_ponctuelle,jour_plage_ponctuelle,code_planning) VALUES (%d,'%s',%s)",nombres_plages+1,formatted_date,code_planning);
                            printf("la requete : %s\n",requete);
                            res = PQexec(conn,requete);

                            snprintf(requete, sizeof(requete), "INSERT INTO locbreizh._plage_ponctuelle_indisponible(id_plage_ponctuelle,libelle_indisponibilite) Values(%d,'%s')",nombres_plages+1,"le propriétaire est indisponible");
                            printf("la requete : %s\n",requete);
                            res = PQexec(conn,requete);
                        } else if(nombres_lignes_req == 1){
                            snprintf(requete, sizeof(requete), "SELECT * FROM locbreizh._plage_ponctuelle_indisponible WHERE id_plage_ponctuelle = '%s'",id_plage);
                            printf("la requete : %s\n",requete);
                            res = PQexec(conn,requete);

                            int plage_indisponible = PQntuples(res);

                            if (plage_indisponible != 1){

                                snprintf(requete, sizeof(requete), "DELETE from locbreizh._plage_ponctuelle_disponible where id_plage_ponctuelle = '%s'",id_plage);
                                printf("la requete : %s\n",requete);
                                res = PQexec(conn,requete);

                                snprintf(requete, sizeof(requete), "INSERT INTO locbreizh._plage_ponctuelle_indisponible(id_plage_ponctuelle,libelle_indisponibilite) Values(%s,'%s')",id_plage,"le propriétaire est indisponible");
                                printf("la requete : %s\n",requete);
                                res = PQexec(conn,requete);
                                
                            }
                        }
                    }

                    if (verbose == 1) {
                        printf("%02d:%02d:%d %02d:%02d:%02d fin de la mise en indisponibilité du logement %s du %s au %s : \n",day, mois, an,h, min, s,elements[1],elements[2],elements[3]);
                    }

                    snprintf(chaine, sizeof(chaine), "\033[32mla mise en indisponibilité de votre logement %s du %s au %s est bien effective\033[0m",elements[1],elements[2],elements[3]);
                    write(cnx, chaine, sizeof(chaine));

                    ssize_t bytes_written = write(cnx, chaine, sizeof(chaine));
                    
                    // Vérifier si l'écriture précédente s'est bien passée
                    if (bytes_written == -1) {
                        perror("Erreur lors de l'envoi du message 'mise en indisponibilité' au client");
                        // Gérer l'erreur ici
                    } else {
                        // Envoyer le message "fin" uniquement si tout s'est bien passé précédemment
                        ssize_t bytes_written_fin = write(cnx, "fin", sizeof("fin"));
                        if (bytes_written_fin == -1) {
                            perror("Erreur lors de l'envoi du message 'fin' au client");
                            // Gérer l'erreur ici
                        }
                    }

                
                } else {
                        snprintf(chaine,sizeof(chaine),"\033[31m\nVotre clef ne vous octroie pas les droits d'indispo pour cette commande !\033[0m");
                        write(cnx,chaine,sizeof(chaine));
                        write(cnx,"fin",sizeof("fin"));
                }
            }

            else if (strncmp(r_buffer, "--RendrePlageDisponible", sizeof(200)) == 0 || strncmp(r_buffer, "--RendrePlageDisponible", sizeof(200)) == 0) {
                printf("droits dispo : %d\n",droit_dispo);
                if (droit_dispo){

                    char saisie[8];
                    snprintf(saisie, sizeof(saisie), "saisie\n");
                    write(cnx,saisie,sizeof(saisie));

                    //int prix_saisie = 20;

                    char user_input[500]; // Adapter la taille selon vos besoins
                    ssize_t bytes_read = read(cnx, user_input, sizeof(user_input));
                    if (bytes_read == -1) {
                        perror("Erreur lors de la lecture de la saisie de l'utilisateur");
                        return EXIT_FAILURE;
                    }

                    // Convertir la saisie en entier
                    int prix_saisi = atoi(user_input);
                    printf("Prix saisi par l'utilisateur : %d\n", prix_saisi);

                    // Mettre à jour la variable prix avec le prix saisi
                    prix = prix_saisi;
                
                    if (verbose == 1){
                        printf("%02d:%02d:%d %02d:%02d:%02d reçu du client un --RendreDisponible : \n",day, mois, an,h, min, s);
                    }
                
                    char elements[5][100];
                    int i = 0;

                    const char *separators = "|";

                    // On cherche à récupérer, un à un, tous les mots (token) de la phrase et on commence par le premier.
                    char *strToken = strtok(commande_en_cours, separators);
                    while (strToken != NULL && i < 4) {
                        printf("%s\n", strToken);

                        strcpy(elements[i], strToken);
                        
                        // On demande le token suivant.
                        strToken = strtok(NULL, separators);
                        i++;
                    }

                    for (int j = 0; j < i; j++) {
                        printf("Element %d : %s\n", j, elements[j]);
                    }

                    char requete[1000];
                    snprintf(requete, sizeof(requete), "SELECT code_planning FROM locbreizh._logement WHERE libelle_logement='%s'", elements[1]);
                    res = PQexec(conn,requete);

                    if (PQresultStatus(res) != PGRES_TUPLES_OK)
                    {
                        fprintf(stderr, "BEGIN command failed: %s\n", PQerrorMessage(conn));
                        PQclear(res);
                    }

                    char code_planning[100] = "";
                    strcpy(code_planning,PQgetvalue(res,0,0));

                    printf("id du planning du logement : %s\n",PQgetvalue(res,0,0));

                    snprintf(requete, sizeof(requete), "SELECT * FROM locbreizh._plage_ponctuelle WHERE code_planning='%s' and jour_plage_ponctuelle >= '%s' and jour_plage_ponctuelle <= '%s'",code_planning,elements[2],elements[3]);
                    printf("la requete : %s\n",requete);
                    res = PQexec(conn,requete);

                    int nombres_lignes_req = PQntuples(res);
                    printf("nombres de plages : %d\n",nombres_lignes_req);

                    struct tm tm_start, tm_end;
                    memset(&tm_start, 0, sizeof(struct tm));
                    memset(&tm_end, 0, sizeof(struct tm));

                    // Convertir les chaînes de date en structures tm
                    strptime(elements[2], "%Y-%m-%d", &tm_start);
                    strptime(elements[3], "%Y-%m-%d", &tm_end);

                    // Convertir en timestamps
                    time_t start_timestamp = mktime(&tm_start);
                    time_t end_timestamp = mktime(&tm_end);

                    // Vérifier si les dates sont valides
                    if (start_timestamp == -1 || end_timestamp == -1) {
                        fprintf(stderr, "Dates invalides.\n");
                        return EXIT_FAILURE;
                    }

                    snprintf(requete, sizeof(requete), "SELECT MAX(id_plage_ponctuelle) FROM locbreizh._plage_ponctuelle;");
                    printf("la requete : %s\n",requete);
                    res = PQexec(conn,requete);

                    // Exécuter la requête SQL
                    // Vérifier si la requête a réussi
                    if (PQresultStatus(res) == PGRES_TUPLES_OK) {
                        // Vérifier s'il y a au moins une ligne retournée
                        if (PQntuples(res) > 0) {
                        // Récupérer la valeur maximale de l'ID de plage
                        char *max_id_str = PQgetvalue(res, 0, 0);

                        // Convertir la valeur en entier
                        max_id = atoi(max_id_str);

                        // Utiliser max_id comme valeur maximale de l'ID de plage
                        printf("Valeur maximale de l'ID de plage : %d\n", max_id);
                        } else {
                            printf("Aucun résultat retourné.\n");
                        }
                    } else {
                        printf("Erreur lors de l'exécution de la requête : %s\n", PQerrorMessage(conn));
                    }

                    snprintf(requete, sizeof(requete), "SELECT * from locbreizh._plage_ponctuelle where code_planning = %s order by jour_plage_ponctuelle;",code_planning);
                    printf("la requete : %s\n",requete);
                    res = PQexec(conn,requete);

                    int nombres_lignes_req_result = PQntuples(res);
                    printf("nombres : %d\n",nombres_lignes_req_result);

                    for(int i = 0; i < nombres_lignes_req_result ;i++){
                    
                        snprintf(requete, sizeof(requete), "SELECT * FROM locbreizh._plage_ponctuelle_disponible WHERE id_plage_ponctuelle = %s",PQgetvalue(res,i,0));
                        printf("la requete : %s\n",requete);
                        disponible = PQexec(conn,requete);

                        int plage_disponible = PQntuples(disponible);

                        snprintf(requete, sizeof(requete), "SELECT * FROM locbreizh._plage_ponctuelle_disponible WHERE id_plage_ponctuelle = %s",PQgetvalue(res,i,0));
                        printf("la requete : %s\n",requete);
                        indisponible = PQexec(conn,requete);

                        int plage_indisponible = PQntuples(indisponible);

                        if (plage_disponible != 0){
                                printf("la plage disponable : %s\n",PQgetvalue(res,i,1));
                                strcpy(chaine, "");
                                snprintf(chaine,sizeof(chaine),"\033[38;2;0;0;139m%s :\033[0m \033[32mdisponible\033[0m\n",PQgetvalue(res,i,1));
                                write(cnx,chaine,sizeof(chaine));
                                fflush(stdout);
                        } else {
                                strcpy(chaine, "");
                                snprintf(chaine,sizeof(chaine),"\033[38;2;0;0;139m%s :\033[0m \033[31mindisponible\033[0m\n",PQgetvalue(res,i,1));
                                write(cnx,chaine,sizeof(chaine));
                                fflush(stdout);
                        }
                    }

                    // Boucle pour chaque jour entre les deux dates
                    for (time_t current_timestamp = start_timestamp; current_timestamp <= end_timestamp; current_timestamp += 86400) {
                        struct tm *current_tm = localtime(&current_timestamp);

                        // Afficher la date au format YYYY-MM-DD
                        char formatted_date[11];
                        strftime(formatted_date, sizeof(formatted_date), "%Y-%m-%d", current_tm);
                        printf("%s\n", formatted_date);

                        //on recup l'id de la plage associé au jour et au planning du logement sélectionné dans la commande
                        snprintf(requete, sizeof(requete), "SELECT id_plage_ponctuelle FROM locbreizh._plage_ponctuelle WHERE code_planning='%s' and jour_plage_ponctuelle = '%s'",code_planning,formatted_date);
                        //printf("la requete : %s\n",requete);
                        res = PQexec(conn,requete);

                        int nombres_lignes_req = PQntuples(res);
                        
                        char id_plage[100] = "";
                        
                        if (nombres_lignes_req == 1 ){
                            strcpy(id_plage,PQgetvalue(res,0,0));
                        }
                        
                        //si la plage n'existe pas on la créer
                        if(nombres_lignes_req < 1){
                            max_id++;
                            snprintf(requete, sizeof(requete), "INSERT INTO locbreizh._plage_ponctuelle(id_plage_ponctuelle,jour_plage_ponctuelle,code_planning) VALUES (%d,'%s',%s)",max_id+1,formatted_date,code_planning);
                            //printf("la requete : %s\n",requete);
                            res = PQexec(conn,requete);

                            snprintf(requete, sizeof(requete), "INSERT INTO locbreizh._plage_ponctuelle_disponible(id_plage_ponctuelle,prix_plage_ponctuelle) Values(%d,'%d')",max_id+1,prix_saisi);
                            printf("la requete : %s\n",requete);
                            res = PQexec(conn,requete);
                        } else if(nombres_lignes_req == 1){
                            snprintf(requete, sizeof(requete), "SELECT * FROM locbreizh._plage_ponctuelle_disponible WHERE id_plage_ponctuelle = '%s'",id_plage);
                            //printf("la requete : %s\n",requete);
                            res = PQexec(conn,requete);

                            int plage_indisponible = PQntuples(res);

                            if (plage_indisponible != 1){

                                snprintf(requete, sizeof(requete), "DELETE from locbreizh._plage_ponctuelle_indisponible where id_plage_ponctuelle = '%s'",id_plage);
                                //printf("la requete : %s\n",requete);
                                res = PQexec(conn,requete);

                                snprintf(requete, sizeof(requete), "INSERT INTO locbreizh._plage_ponctuelle_disponible(id_plage_ponctuelle,prix_plage_ponctuelle) Values(%s,'%d')",id_plage,prix_saisi);
                                //printf("la requete : %s\n",requete);
                                res = PQexec(conn,requete);
                                
                            }
                        }
                    }

                    if (verbose == 1){
                        printf("%02d:%02d:%d %02d:%02d:%02d fin de la mise en disponibilité du logement %s du %s au %s : \n",day, mois, an,h, min, s,elements[1],elements[2],elements[3]);
                    }
                    snprintf(chaine, sizeof(chaine), "\033[32mla mise en disponibilité de votre logement %s du %s au %s est bien effective\033[0m",elements[1],elements[2],elements[3]);
                    write(cnx,chaine,sizeof(chaine));
                    memset(0,chaine,0);
                    write(cnx,"fin",sizeof("fin"));
                
                } else {
                        snprintf(chaine,sizeof(chaine),"\033[31m\nVotre clef ne vous octroie pas les droits pour la commande rendre disponible !\033[0m");
                        write(cnx,chaine,sizeof(chaine));
                        snprintf(chaine,sizeof(chaine),"droit rendre disponible: %d",droit_dispo);
                        write(cnx,chaine,sizeof(chaine));
                        write(cnx,"fin",sizeof("fin"));
                }
            }
                
            else if (r == 0) {
                printf("La connexion a été fermée par le client !\n");
                break;
            } else {
                perror("Erreur lors de la lecture du socket !\n");
                exit(1);
            }
    }
}

return 0;

};