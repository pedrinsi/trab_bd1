#include <stdio.h>
#include <stdlib.h>

	struct Hospede{
	  int id;
	  char nome[100];
	  char sexo[1];
	  int idade;
	  int CPF;
	}hospede[50];
	
	struct Quarto{
		int identificador;
		float custo;
		int status;
		char descricao[500];
		int dias;
	}quarto[50];
	
	int i=1;
	int j=1;
	
	void checkIn (struct Quarto quarto[50]){
		int valor_final;
		int m;
		int aux=0;
		if (!quarto[50]){ // ARRUMAR ESSE IF
			printf("\n-- Quartos desocupados --");
			for(m=1;m<=50;m++){
				if(quarto[m].status==0){
					aux++;
					printf("\nQuarto %d desocupado \n",quarto[m].identificador);
					printf ("\nDigite o numero do quarto: ");
					scanf ("%d", &quarto[m].identificador);
					while (quarto[m].status==1){
						printf ("\nEste quarto ja esta sendo utilizado! Digite outro quarto: ");
						scanf ("%d", &quarto[m].identificador);
					}
					
					printf("Digite o numero de dias alugados: ");
					scanf("%d",&quarto[m].dias);
					valor_final = quarto[m].custo*quarto[m].dias;
					printf("Valor final da hospedagem: %d\n\n", valor_final);
				}
			}
		} else{
			int novo;
			printf("\nSem quartos cadastrados \n\n");
			printf("\nDeseja cadastrar quarto agora? (0 - Nao/ 1 - Sim): ");
			scanf("%d", &novo);
			if(novo==1){
				cadastra_quarto();
				printf("\nCadastro efetuado com sucesso! \n\n");
				i--;
			} else {
				printf("\nHospede não cadastrado! \n\n");
				i--;
			}
		}
		system("PAUSE");
	}
	
	int cadastra_hospede(){
		hospede[i].id = i;
		
		printf("\n-- Dados do Hospede %d --\n",hospede[i].id);
		printf("Digite o nome do hospede: ");
		scanf("%s", &hospede[i].nome);
		printf("Digite o sexo do hospede (M/F): ");
		scanf("%s", &hospede[i].sexo);
		printf("Digite a idade do hospede: ");
		scanf("%d", &hospede[i].idade);
		printf("Digite o CPF do hospede: ");
		scanf("%d", &hospede[i].CPF);
		
		checkIn(quarto);
		
		i++;

		system("PAUSE");
	}
    		
	int cadastra_quarto(){
        quarto[j].identificador = j;
        int valor_final;
		
        printf("\n-- Registro do quarto %d --\n",quarto[j].identificador);
        printf("Entre com a descricao do quarto: ");
        scanf("%s",&quarto[j].descricao);
        printf("Digite o valor da diaria do quarto: ");
        scanf("%f", &quarto[j].custo);
        quarto[j].status = 0;
		
        j++;
		
		if(j>50){
			printf("\n\n-- Sem vagas --\n\n");
		}			
			system("PAUSE");
	}
	
	int saida(){
		int tempo;        
		printf("O hospede ficou mais tempo que o registrado? (0 - Nao/ 1 - Sim): ");
		scanf("%d", &tempo);
        
        if(tempo==1){
            for(j=1;j<=50;j++) {
				int passou;
                int valor_final;
                printf("Digite o numero de dias ultrapassados:");
                scanf("%d", &passou);   
                valor_final = (passou * 10)+(quarto[j].custo*quarto[j].dias);
				printf("Valor final da hospedagem: %d\n\n", valor_final);
                quarto[j].status = 0;
                printf("O quarto %d foi desoculpado!\n", quarto[j].identificador);
            }   
        }                 
        else {
            for(j=1;j<=50;j++) {
                int valor_final;
            	valor_final = quarto[j].custo*quarto[j].dias;
				printf("Valor final da hospedagem: %d\n\n", valor_final);
                quarto[j].status=0;
                printf("O quarto %d foi desoculpado!\n", quarto[j].identificador);
            }
        }
	}
	
	int lista_estadias(){
		int cont=0;
		int n;
		printf("-- Quartos ocupados --");
		for(n=1;n<=50;n++){
			if(quarto[n].status==1){
				cont++;
				printf("\nQuarto %d ocupado \n",quarto[n].identificador);
			}

            if(n==50 && cont == 0){
				printf("\nSem quartos ocupados \n\n");
			}
		}
		system("PAUSE");
	}


int main(int argc, char *argv[]){
    
    printf("#########################################################");  printf("\n");
	printf("##           Faculdade Federal de Uberlandia           ##");  printf("\n");
    printf("##               Introducao a Programacao              ##");  printf("\n");
    printf("#########################################################");  printf("\n");
	printf("##                            ##                       ##");  printf("\n");
    printf("##                            ##                       ##");  printf("\n");
	printf("##                            ##                       ##");  printf("\n");
    printf("#########################################################");  printf("\n");
    
    int count=1;
    while(count) {
		int selection;    
		printf("---------------------------------------------------------"); printf("\n");
		printf("---------------------------------------------------------"); printf("\n");
		printf("                          HOTEL                          "); printf("\n");
		printf("---------------------------------------------------------"); printf("\n");
		printf("---------------------------------------------------------"); printf("\n");
		printf("                          Menu                           "); printf("\n");
		printf(" 1. Registro de estadia                                  "); printf("\n");
		printf(" 2. Registro de saida                                    "); printf("\n");
		printf(" 3. Cadastro de quartos                                  "); printf("\n");
		printf(" 4. Listagem de todas as estadias do momento             "); printf("\n"); printf("\n");
		printf("                    0. Fexa programa                     "); printf("\n");
		printf("---------------------------------------------------------"); printf("\n");
		printf("---------------------------------------------------------"); printf("\n");
		scanf("%d", &selection);
		

		switch(selection) {
			//Saída do programa
			case 0 :
				count = 0;
				break;
				
			//Registra entrada do hospede
			case 1:
				system("CLS");
					cadastra_hospede();
				break;
				
			//Registra saida do hospede
			 case 2:
				system("CLS");
					saida();
				break;
				
			//Registra quarto
			case 3:
				system("CLS");
					cadastra_quarto();
				break;
				
			//Lista todos os quartos ocupados
			case 4:
				system("CLS");
					lista_estadias();
				break;
		}

	}

  system("PAUSE");
    return EXIT_SUCCESS;
}
