<?php
function startpage($titulo)
{ # Emissão das TAGs de início de uma página HTML
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
{ # Comandos de exibição do final HTML
  printf(" </body>\n</html>\n");
}
function conectamy($host,$dbname,$user,$senha)
{
  ###############################################
  # SE for necessário a interação com algum SGBD podemos escrever aqui
  # os comandos de conexão e configuração de uma sessão com o BD
  # Conectando o Banco de Dados trabalho no SGBD MariaDB.
  # No php usamos a função mysqli_connect() que recebe quatro parâmetros com os dados:
  # servidor, usuário, senha e nome da base de dados.
  # Montando a variáveis com as 'string' com os parâmetros de conexão do PHP para o SGBD Maria
  # $host="localhost"; $dbname="ilp"; $user="root"; $senha=""; # $porta="3306";
  # No MariaDB não é necessário informar o número da porta onde o SGBD está em execução,
  # à princípio o PHP vai reconhecer o MariaDB somente em UMA porta no computador.
  # a biblioteca do PHP recenhece e faz a conexão por esta porta.
  # Fazendo a conexão com o banco de dados.
  GLOBAL $dbm;
  $dbm = mysqli_connect("$host", "$user", "$senha", "$dbname") or die ("Problemas para Conectar no Banco de Dados MariaDB.<br>");
  # Agora vamos 'ajustar' os caracteres acentuados
  # Acertando a tabela de caracteres que sera usada no MySQL
  # O MySQL trabalha com vários tipos de caracteres em idiomas diferentes.
  # Os comandos seguintes 'calibram' o MySQL para caracteres do Portugues-Brasil.
  mysqli_query($dbm,"SET NAMES 'utf8'");
  mysqli_query($dbm,'SET character_set_connection=utf8');
  mysqli_query($dbm,'SET character_set_client=utf8');
  mysqli_query($dbm,'SET character_set_results=utf8');
}
# executando a função de conexão com o SGBD MariaDB
conectamy("localhost","ijd028","root","");
?>