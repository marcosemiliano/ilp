<?php
function startpage($titulo)
{ # Emiss�o das TAGs de in�cio de uma p�gina HTML
  printf("<!DOCTYPE html>\n");
  printf("<html>\n");
  printf(" <head>\n");
  printf("  <meta charset='utf-8'>\n");
  printf("  <title>$titulo</title>\n");
  printf("  <link rel='stylesheet' type='text/css' href='../estilo.css'>\n");
  printf(" </head>\n");
  if ( $titulo=='Impressao' )
  { 
    printf(" <body class='branco'>\n<red>Impress&atilde;o</red><br>\n");
  }
  else
  { 
    printf(" <body>\n<red>$titulo</red><br>\n");
  }
}
function finalpage()
{ # Comandos de exibi��o do final HTML
  printf(" </body>\n</html>\n");
}
function conectamy($host,$dbname,$user,$senha)
{
  ###############################################
  # SE for necess�rio a intera��o com algum SGBD podemos escrever aqui
  # os comandos de conex�o e configura��o de uma sess�o com o BD
  # Conectando o Banco de Dados trabalho no SGBD MariaDB.
  # No php usamos a fun��o mysqli_connect() que recebe quatro par�metros com os dados:
  # servidor, usu�rio, senha e nome da base de dados.
  # Montando a vari�veis com as 'string' com os par�metros de conex�o do PHP para o SGBD Maria
  # $host="localhost"; $dbname="ilp"; $user="root"; $senha=""; # $porta="3306";
  # No MariaDB n�o � necess�rio informar o n�mero da porta onde o SGBD est� em execu��o,
  # � princ�pio o PHP vai reconhecer o MariaDB somente em UMA porta no computador.
  # a biblioteca do PHP recenhece e faz a conex�o por esta porta.
  # Fazendo a conex�o com o banco de dados.
  GLOBAL $dbm;
  $dbm = mysqli_connect("$host", "$user", "$senha", "$dbname") or die ("Problemas para Conectar no Banco de Dados MariaDB.<br>");
  # Agora vamos 'ajustar' os caracteres acentuados
  # Acertando a tabela de caracteres que sera usada no MySQL
  # O MySQL trabalha com v�rios tipos de caracteres em idiomas diferentes.
  # Os comandos seguintes 'calibram' o MySQL para caracteres do Portugues-Brasil.
  mysqli_query($dbm,"SET NAMES 'utf8'");
  mysqli_query($dbm,'SET character_set_connection=utf8');
  mysqli_query($dbm,'SET character_set_client=utf8');
  mysqli_query($dbm,'SET character_set_results=utf8');
}
# executando a fun��o de conex�o com o SGBD MariaDB
conectamy("localhost","ijd028","root","");
?>