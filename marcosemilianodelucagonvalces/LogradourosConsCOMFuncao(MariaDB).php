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
# Usado aqui o REQUIRE para garantir mais controle de execução para programa e subprograma.
# ---
# Emissão das TAGs de início de uma página HTML
startpage("Consulta Logradouro");
# O segmento de código foi definido como função no POOLING de funções - Traduzido do inglês
# - No gerenciamento de recursos, o pool é o agrupamento de recursos com o objetivo de maximizar a vantagem ou minimizar o risco para os usuários.
#   O termo é usado em finanças, computação e gerenciamento de equipamentos
# ---
# Determinando o valor da variável de controle de fluxo. A PHP dispõe de uma série de funções de ambiente.
# Entre elas a função ISSET() é usada para determinar se uma variável existe no escopo de execução de um programa.
$bloco= ( ISSET($_REQUEST['bloco']) ) ? $_REQUEST['bloco'] : 1 ;
# Na 'primeira' execução a variável $bloco vale 1. Deve ser executado o bloco lógico da primeira 'passada' no programa.
# Comando que faz a divisão da execução em blocos dependendo do valor de $bloco (variável de controle).
switch (TRUE)
{ # 1 - Divisor de fluxo de execução
  case ( $bloco==1 ):
  { # 1.1 - Pesquisa na base de dados lendo a uma tabela e montando um form com uma picklist
    # Comando para leitura de dados de uma tabela
    $cmdsql="SELECT cplogradouro, txnomelogradouro FROM logradouros ORDER BY txnomelogradouro";
    # SE for necessário é possível exibir o comando que será executado
    # printf("$cmdsql\n");
    # 'Executando' a variável:
    $execcmd=mysqli_query($dbm,"$cmdsql");
    # função _QUERY() retorna um 'vetor' em três partes:
    # - Identificação das tabelas lidas.
    # - Identificação dos campos que foram lidos (no exemplo foi feita a projeção de cplogradouro e txnomelogradouro.
    # - Lista dos endereços de memória onde o SGBD escreveu os endereços dos registros que atenderam à operação feita sobre os dados.
    printf("  <form action='./LogradourosConsCOMFuncao(MariaDB).php' method='POST'>\n");
    # No atributo action deve ser escrito o nome do ARQUIVO PHP carregado recursivamente.
    printf("   <input type='hidden' name='bloco' value='2'>\n");
    printf("    Escolha o logradouro a ser Consultado:<br>\n");
    printf("   <select name='cplogradouro'>\n");
    # dentro da TAG <select> deve-se mostrar as linhas com o cplogradouro e txnomelogradouro.]
    # estas linhas serão montadas dentro de um while.
    while ( $reg=mysqli_fetch_array($execcmd) )
    { # a _fetch_array() pega o primeiro endereço disponivel no retorno do _QUERY(), busca na memória (RAM|HD - no SGBD))
      # os valores dos campos que formam o registro, depois carrega isso em um Vetor[] no ambiente do PA em execução.
      printf("    <option value='$reg[cplogradouro]'>$reg[txnomelogradouro]-($reg[cplogradouro])</option>\n");
      # cada linha formada neste while deve apontar para um endereço de registro, por isso o value aponta para os valores da CP da tabela.
    }
    printf("   </select>\n
               <input type='submit' value='Enviar escolha'>\n
               </form>\n");
    break;
  } # 1.1 - Fim do Case 1
  case ( $bloco==2 ):
  { # 1.2 - Aqui podemos exibir os valores digitados nos campos do formulário do bloco1
    $reg=mysqli_fetch_array(mysqli_query($dbm,"SELECT logradouros.*, txnometipologradouro, txnome FROM logradourostipos, logradouros, cidades WHERE cptipologradouro=cetipologradouro AND cpcidade=cecidade AND cplogradouro='$_REQUEST[cplogradouro]'"));
    # mostrando o registro
    printf("  <table>\n
               <tr><td>Código:</td>         <td>$reg[cplogradouro]</td></tr>\n
               <tr><td>Nome do Logradouro:</td><td>$reg[txnomelogradouro]</td></tr>\n
			   <tr><td>Tipologradouro:</td>    <td>$reg[cetipologradouro] - $reg[txnometipologradouro]</td></tr>\n
               <tr><td>Cidade do Logradouro:</td>    <td>$reg[cecidade] - $reg[txnome]</td></tr>\n
               <tr><td>Cadastro logradouro:</td>       <td>$reg[dtcadlogradouro] (AAAA-MM-DD)</td></tr>\n
              </table>\n");
    # Aqui escrevi um só comando printf("") com todas as TAGs que formam a tabela que exibe o registro lido do BD.
    break;
  }
}
# Executando a função que emite as TAGs de fim de arquivo.
finalpage();
?>






