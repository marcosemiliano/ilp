<?php
###############################################################################################################################################################
# Programa...: AutoresCons-COMFuncao(MariaDB).php
# Descricao..: Este programa conecta um banco de dados do MariaDB, inicia um recursivo com dois blocos: primeiro mostra uma lista de dados em uma
#              caixa de seleção e a seguir permite a escolha de uma registro; no segundo bloco captura o valor indicadao no primeiro bloco e busca o registro
#              inteiro na tabale da base de dados e mostra os valores dos campos da tabela.
# Objetivo...: Construir um program recusivo de Consulta de Registro USANDO o conceito de execução de FUNÇÃO.
# Autor......: JMH (Copie e use mas sempre referencie quem fez.
# Criação....: 2020-06-09
# Atualização: 2020-06-09 - Primeira Aproximação: O consulta é 1/3 do programa.
#              2020-06-11 - Reescrevi o programa usando funções.
#              2020-06-19 - Derivei deste programa o programa que usa função. Melhorei os comandos de início|fim da página.
#              2020-06-23 - Introduzi o cabeçalho.
###############################################################################################################################################################
# Nota: a PHP dispõe de dois modos de alternar a execução para sub programas escritos em outros arquivos:
# INCLUDE("Arquivo.php"); - Se o arquivo.php tiver erro, para a exeção do arquivo.php mas CONTINUA o programa principal
# REQUIRE("Arquivo.php"); - Se o arquivo.php tiver erro, para a execução do programa principal, também.
# A cada vez que esses comandos são encontrados o ARQUIVO.PHP é lido do HD. A PHP tem uma forma de fazer a leitura acontecer só uma vez.
# include_once("");  ou  require_once(""); escolha
# Executando o pooling de funções
require_once("../pooling.php");

startpage("Consulta Logradouro");

$bloco= ( ISSET($_REQUEST['bloco']) ) ? $_REQUEST['bloco'] : 1 ;

switch (TRUE)
{ 
  case ( $bloco==1 ):
  { 
    $cmdsql="SELECT cplogradouro, txnomelogradouro FROM logradouros ORDER BY txnomelogradouro";
    $execcmd=mysqli_query($dbm,"$cmdsql");

    printf("  <form action='./LogradourosConsCOMFuncao(MariaDB).php' method='POST'>\n");
    printf("   <input type='hidden' name='bloco' value='2'>\n");
    printf("    Escolha o logradouro a ser Consultado:<br>\n");
    printf("   <select name='cplogradouro'>\n");
    while ( $reg=mysqli_fetch_array($execcmd) )
    { 
      printf("    <option value='$reg[cplogradouro]'>$reg[txnomelogradouro]-($reg[cplogradouro])</option>\n");
    }
    printf("   </select>\n
               <input type='submit' value='Enviar escolha'>\n
               </form>\n");
    break;
  } 
  case ( $bloco==2 ):
  { 
    $reg=mysqli_fetch_array(mysqli_query($dbm,"SELECT logradouros.*, txnometipologradouro, txnome FROM logradourostipos, logradouros, cidades WHERE cptipologradouro=cetipologradouro AND cpcidade=cecidade AND cplogradouro='$_REQUEST[cplogradouro]'"));

    printf("  <table>\n
               <tr><td>Código:</td>         <td>$reg[cplogradouro]</td></tr>\n
               <tr><td>Nome do Logradouro:</td><td>$reg[txnomelogradouro]</td></tr>\n
			   <tr><td>Tipologradouro:</td>    <td>$reg[cetipologradouro] - $reg[txnometipologradouro]</td></tr>\n
               <tr><td>Cidade do Logradouro:</td>    <td>$reg[cecidade] - $reg[txnome]</td></tr>\n
               <tr><td>Cadastro logradouro:</td>       <td>$reg[dtcadlogradouro] (AAAA-MM-DD)</td></tr>\n
              </table>\n");
   
    break;
  }
}

finalpage();
?>






