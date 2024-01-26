#include <stdio.h>     /* for printf */
#include <stdlib.h>    /* for exit */
#include <getopt.h>

int
main(int argc, char **argv)
{
    char c;
    int port;
    //int digit_optind = 0;

   while ((c = getopt(argc, argv, "abc:dp:012")) != -1) {
        //int this_option_optind = optind ? optind : 1;
        //int option_index = 0;
       switch (c) {
        /*case 0:
            printf("option %s", long_options[option_index].name);
            if (optarg)
                printf(" with arg %s", optarg);
            printf("\n");
            break;

       case '0':
        case '1':
        case '2':
            if (digit_optind != 0 && digit_optind != this_option_optind)
              printf("digits occur in two different argv-elements.\n");
            digit_optind = this_option_optind;
            printf("option %c\n", c);
            break;*/

       case 'a':
            printf("option a\n");
            break;

       case 'b':
            printf("option b\n");
            break;

       case 'c':
            printf("option c with value '%s'\n", optarg);
            break;

       case 'd':
            printf("option d with value '%s'\n", optarg);
            break;

       case 'p':
          printf("Optarg : %s\n", optarg);
          port = atoi(optarg);
          printf("le port utilisé est:%d",port);
          break;
       
       case '?':
            break;

       default:
            printf("?? getopt returned character code 0%o ??\n", c);
        }
    }

   /*if (optind < argc) {
        printf("non-option ARGV-elements: ");
        while (optind < argc)
            printf("%s ", argv[optind++]);
        printf("\n");
    }*/

   exit(EXIT_SUCCESS);
}