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
    int sock,cnx;
    int port = atoi(argv[1]);
    printf("port : %d\n",port);
    struct sockaddr_in addr;

    // Initialisation des sockets
    sock = socket(AF_INET, SOCK_STREAM, 0);

    printf("socket : %d",sock);

    if (errno == -1) {
        perror("socket :");
        exit(1);
    }
     
    addr.sin_addr.s_addr = inet_addr("127.0.0.1");
    addr.sin_family = AF_INET;
    addr.sin_port = htons(port);

    printf("avant connexion !\n");
    
    cnx = connect(sock, (struct sockaddr *)&addr, sizeof(addr));

    perror("connect");
    
    /*while (sock == -1)
    {
        sock = connect(sock, (struct sockaddr *)&addr, sizeof(addr));
        perror("connect");
        //exit(1);
        printf("après connexion");
    }*/

    printf("sock : %d\n",cnx);

    printf("avant boucle\n");

    int r,rcv,wr;
    char r_buffer[500]; // Initialisation du buffer
    char response_buffer[500];
    //char w_buffer[500];

    while (true) {
        //printf("entrer de la boucle\n");
        
        // Réinitialiser le buffer à chaque itération
        memset(r_buffer, 0, sizeof(r_buffer));

        //printf("avant la lecture\n");

        r = read(sock, r_buffer, sizeof(r_buffer)-1);
        r_buffer[r] = '\0';
        //perror("lecture ");
        
        /*printf("lu : %d\n",r);
        printf("lu : %s",r_buffer);*/

        if (r > 0) {
            printf("test\n");

            // Envoie du message
            wr = write(sock, &r_buffer, sizeof(r_buffer)-1);
            r_buffer[wr]='\0';
            printf("ecrit : %d\n",wr);
            perror("envoit");
            printf("Message envoyé au serveur :%s\n", r_buffer);

            // Lire et afficher la réponse du serveur
            rcv = read(sock, &response_buffer, sizeof(response_buffer));
            //response_buffer[rcv] = '\0';
            printf("lu : %d\n",rcv);
            perror("lecture :");
            printf("%s\n", response_buffer);
            //printf("ecrit : %s\n",r_buffer);
            //perror("ecriture ");
            //sleep(5);
            /*rcv = recv(sock, &response_buffer, sizeof(response_buffer),0);
            printf("receive : %d\n",rcv);
            perror("receive error : ");
            //response_buffer[rcv] = '\0';
            printf("buffer : \"%s\"\n",response_buffer);
            printf("Réponse du serveur : %s\n", response_buffer);*/
    
    }

    close(sock);

    //return 0;
    
}

}