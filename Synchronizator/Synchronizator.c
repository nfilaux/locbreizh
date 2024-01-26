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

int main(int argc, char **argv){

    char opt;
    int verbose = 0;
    char *output_filename = NULL;
    const char *conninfo;
    PGconn     *conn;
    PGresult   *res;

    int sock, ret, size, cnx; // Initialisation des variables
    int port; // Initialisation du port

    printf("connexion avec la BDD en cours.....\n");

    // Information de connexion à la base de donnée
    conninfo = "host = localhost port = 5432 dbname = sae user = sae password = ieTeec3thah3sho4";

    conn = PQconnectdb(conninfo);

    if (PQstatus(conn) != CONNECTION_OK)
    {
        fprintf(stderr, "%s", PQerrorMessage(conn));
    } else {
        printf("Connexion établie\n");
    }

    char id_proprio[30] = "7";
    char requete[150] = "SELECT * from locbreizh._logement where id_proprietaire ="; // Requete sur la bdd
    strcat(requete,id_proprio);
    res = PQexec(conn,requete);

    if (PQresultStatus(res) != PGRES_TUPLES_OK)
    {
        fprintf(stderr, "BEGIN command failed: %s\n", PQerrorMessage(conn));
        PQclear(res);
    }

    int nombres_lignes_req = PQntuples(res);
    printf("nombre de comptes : %d\n",nombres_lignes_req);

    // Affichage des lignes du résultat de la requête
    for(int i = 0; i < nombres_lignes_req ;i++){

        printf("%s -- %s € -- %s -- %s -- %s -- %s m²\n",PQgetvalue(res,i,1),PQgetvalue(res,i,2),PQgetvalue(res,i,3),PQgetvalue(res,i,5),PQgetvalue(res,i,6),PQgetvalue(res,i,7));
        
    }

    // Gestion des arguments
    while((opt = getopt(argc, argv, "abc:dp:012")) != -1) {

       printf("%s",optarg);
       
       if (opt == -1)
            break;
       switch (opt) {

       case 'h':
            printf("Options:\n");
            printf("  -h, --help       Show this help message\n");
            printf("  -o, --output=FILE  Specify output file\n");
            printf("  -c, --create=FILE  Create file\n");
            printf("  -d, --delete=FILE  Delete file\n");
            printf("  -a, --add=FILE     Add file\n");
            printf("  -b, --append=FILE  Append file\n");
            printf("  -v  --verbose         Print verbose messages\n");
            printf("  -f  --file=FILE        Specify input file\n");
            printf("  -p  --port=PORT        Specify port\n");
            break;

        case 'o':
            //output_filename = optarg;
            break;

        case 'p':
            printf("%s",optarg);
            port = atoi(optarg);
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

    // Gestion des arguments non reconnus
   /*if (optind < argc) {
        printf("non-option ARGV-elements: ");
        while (optind < argc)
            printf("%s ", argv[optind++]);
        printf("\n");
    }*/

    // Vérification du nombre d'arguments
    /*if (argc - optind != 1 || port == -1){
        printf("Usage: %s -p <port>\n", argv[0]);
        exit(1);
    }*/

    // Gestion des erreurs de port
    if (port < 1024 || port > 65535){
        printf("Le port doit être compris entre 1024 et 65535\n");
        exit(1);
    }
    
    struct sockaddr_in addr;
    char r_buffer[500]; // Initialisation du buffer
    int r;
    bool admin = true; // Initialisation de la variable admin

    // printf("descripteur de fichier : %d\n",sock);

    // Affichage du menu
    /*printf("Bonjour ! C'est moi le serveur ! Voilà la liste des services proposés :\n");

    if (admin)
    {
        printf("- Affichez la liste de tous les biens\n");
    }
    else
    {
        printf("- Affichez la liste de vos biens\n");
    }

    printf("- Consulter la disponibilité d'un bien sur une période de votre choix\n");
    printf("- Rendre un bien indisponible sur une période de votre choix\n");*/

    // Utilisation des sockets
    sock = socket(AF_INET, SOCK_STREAM, 0);

    addr.sin_addr.s_addr = inet_addr("127.0.0.1");
    addr.sin_family = AF_INET;
    addr.sin_port = htons(port);

    ret = bind(sock, (struct sockaddr *)&addr, sizeof(addr));
    printf("ret bind: %d\n", ret);

    if (ret == -1)
    {
        perror("Erreur lors du bind\n");
        exit(1);
    }

    ret = listen(sock, 1);
    printf("ret listen: %d\n", ret);

    if (ret == -1)
    {
        perror("Erreur lors du listen\n");
        exit(1);
    }

    struct sockaddr_in conn_addr;
    size = sizeof(conn_addr);

    printf("avant accept\n");
    
    cnx = accept(sock, (struct sockaddr *)&conn_addr, (socklen_t *)&size);
    
    printf("cnx accept: %d\n", cnx);

    if (cnx == -1)
    {
        perror("Erreur lors de l'acceptation\n");
        exit(1);
    }

    printf("Connexion etablie : en attente de vos commandes\n");
    printf("Pour plus d'informations entrez la commande --INFOS\n");

    // Boucle de lecture des commandes
    while (true) {
        // Réinitialiser le buffer à chaque itération
        memset(r_buffer, 0, sizeof(r_buffer));

        printf("lire\n");

        r = read(cnx, r_buffer, sizeof(r_buffer)-1);
        r_buffer[r] = '\0';
        perror("read :");

        printf("lu : %d\n",r);

        if (r > 0) {
            // r_buffer[r] = '\0'; // Pas nécessaire car read ne lit pas le caractère nul
            //fflush(stdin);
            printf("Message lu par le programme : %s\n", r_buffer);

            if (strncmp(r_buffer, "--INFOS", strlen("--INFOS")) == 0) {
                printf("test\n");
                char chaine[200];
                snprintf(chaine, sizeof(chaine), "Bonjour ! C'est moi le serveur ! Voilà la liste des services proposés :\n");
                write(cnx, chaine, strlen(chaine));
                fflush(stdout);
            }
        } else if (r == 0) {
            printf("La connexion a été fermée par le client !\n");
            break;
        } else {
            perror("Erreur lors de la lecture du socket !\n");
            exit(1);
        }
    
    }

    // unique id pour générer des clefs API
    // page web avec les clefs a gauche et les checkbox a droite pour les droit
    // port 5432 pour se connecter a la base de donnée (Check)
    // getopt() pour les arguments / getopt_long() pour les arguments long (Check)

    return 0;
}
