#include <sys/types.h>
#include <sys/socket.h>
#include <stdio.h>
#include <stdlib.h>
#include <netinet/in.h>
#include <unistd.h>
#include <arpa/inet.h>
#include <stdbool.h>
#include <string.h>
#include <postgresql/libpq-fe.h>
#include <errno.h>

int main(int argc, char *argv[]){

    // Vérification du nombre d'arguments
    if (argc != 2) {
        printf("Usage: %s <port>\n", argv[0]);
        exit(1);
    }

    // Initialisation des variables
    bool admin = false,cle_valide = false; // Initialisation de la variable admin
    int r,cnx,port = atoi(argv[1]); // Le port est entré par le client comme une option du style :  -p 8080
    struct sockaddr_in addr;

    // Initialisation des sockets
    cnx = socket(AF_INET, SOCK_STREAM, 0);
    if (cnx == -1) {
        perror("Socket :");
        exit(1);
    }
     
    addr.sin_addr.s_addr = INADDR_ANY;
    addr.sin_family = AF_INET;
    addr.sin_port = htons(port);
    
    if (connect(cnx, (struct sockaddr *)&addr, sizeof(addr)) == -1) {
        printf("Erreur de connexion au serveur !\n");
        perror("Connect");
        close(cnx);
        exit(EXIT_FAILURE);
    }

    // Authentification de l'utilisateur
    while(cle_valide != true){

        printf("Veuillez vous authentifier à l'aide de votre clef pour accéder au service :");
        char user_input[500];
        char validation[5] = "";
        fgets(user_input, sizeof(user_input), stdin);

        // Supprime le saut de ligne à la fin de la saisie
        size_t input_length = strlen(user_input);

        if (input_length > 0 && user_input[input_length - 1] == '\n') {
            user_input[input_length - 1] = '\0';
        }

        if (strcmp(user_input,"")==0){
            printf("veuillez entrer une clef !\n");
        } else {
            ssize_t bytes_written = write(cnx, user_input, strlen(user_input));

            if (bytes_written == -1) {
                perror("Erreur lors de l'envoi du message au serveur\n");
                break;
            }

            char server_response[7];
            ssize_t bytes_read = read(cnx, server_response, sizeof(server_response));
            if (bytes_read <= 0) {
                perror("Erreur lors de la lecture de la réponse du serveur ou connexion fermée !\n");
                break;
            }

            server_response[bytes_read] = '\0';
            strcpy(validation, server_response);

            if (strcmp(validation,"true")==0){
                printf("\033[1;32m");
                printf("\nConnexion réussi l'accès à l'API est dévérouillé !\n");
                printf("\033[0m");
                cle_valide = true;
            } else {
                printf("\nClef de connexion invalide veuillez réessayer\n");
            }

            strcpy(validation,"");

            bytes_read = read(cnx, server_response, sizeof(server_response));
            if (bytes_read <= 0) {
                perror("Erreur lors de la lecture de la réponse du serveur ou connexion fermée !\n");
                break;
            }

            server_response[bytes_read] = '\0';

            if (strcmp(server_response,"admin")==0){
                printf("\nVous êtes connecté en tant qu'admin !");
                admin = true;
            } else {
                printf("\nVous êtes connecté en tant que propriétaire\n");
            }
        }

    }

    // Affichage du menu
    printf("\nBonjour ! C'est moi le serveur ! Voilà la liste des services proposés :\n");

    if (admin == true)
    {
        printf("\033[0;33m\t- Affichez la liste de tous les biens\n\033[0m");
    }
    else
    {
        printf("\033[0;33m\t- Affichez la liste de vos biens\n\033[0m");
    }

    printf("\033[0;34m\t- Consulter la disponibilité d'un bien sur une période de votre choix\n\033[0m");
    printf("\033[38;2;255;165;0m\t- Rendre un bien disponible sur une période de votre choix\n\033[0m");
    printf("\033[38;2;139;69;19m\t- Rendre un bien indisponible sur une période de votre choix\n\033[0m");
    printf("Pour la liste des commandes tappez \033[32m--informations\n\033[0m");
    
    while(true){
        bool fin = false;
       // Lecture depuis l'entrée utilisateur ou un fichier, etc.
        printf("\nEntrez votre message : ");
        char user_input[500];
        fgets(user_input, sizeof(user_input), stdin);

        // Supprime le saut de ligne à la fin de la saisie
        size_t input_length = strlen(user_input);
        if (input_length > 0 && user_input[input_length - 1] == '\n') {
            user_input[input_length - 1] = '\0';
        }

        // Envoie ce qui a été lu au serveur
        ssize_t bytes_written = write(cnx, user_input, strlen(user_input));
        if (bytes_written == -1) {
            perror("Erreur lors de l'envoi du message au serveur");
            break;
        }

        char server_response[200];
        ssize_t bytes_read;

        //boucle de lecture des données venant d'une commande du serveur
        
        do {

            //si la fonctionnalité a envoyé son signal de fin on arrête la lecture
            
            if(fin){
                break;
            }

            ssize_t bytes_read = read(cnx, server_response, sizeof(server_response));

            if (bytes_read < 0) {
                perror("Erreur lors de la lecture de la réponse du serveur ou connexion fermée !\n");
                break;
            } else if (bytes_read == 0) {
                printf("La connexion a été fermée par le serveur.\n");
                break;
            }

            server_response[bytes_read] = '\0';

            // on formate le buffer afin qu'on puisse afficher les données
            char *line = strtok(server_response, "\n");
            while (line != NULL) {
                // vérifie si une saisie est demandé au clavier par une commande
                //saisie sera affiché avant la saisie au clavier nous n'avons malheuresement pas pu l'afficher
                if (strcmp(line, "saisie") == 0) {
                    printf("\nEntrez votre prix : ");
                    // Lecture de la saisie
                    fgets(user_input, sizeof(user_input), stdin);

                    // Supprime le saut de ligne à la fin de la saisie
                    size_t input_length = strlen(user_input);
                    if (input_length > 0 && user_input[input_length - 1] == '\n') {
                        user_input[input_length - 1] = '\0';
                    }

                    // Envoie ce qui a été lu au serveur
                    ssize_t bytes_written = write(cnx, user_input, strlen(user_input));
                    if (bytes_written == -1) {
                        perror("Erreur lors de l'envoi du message au serveur");
                        return -1;
                    }
                }

                // vérifie si le signal de fin a été envoyé
                    
                if (strcmp(line, "fin") == 0) {
                    fin = true;
                    break;
                }

                //affiche la ligne en lecture dans le while

                printf("%s\n", line);

                //on reset la ligne en lecture dans le while afin d'éviter les problèmes de concaténation avec les prochaines lignes

                line = strtok(NULL, "\n");
            }

            memset(0,line,0);

        } while (true);
    }
        
    close(cnx);
    return 0;
}

