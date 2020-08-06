<?php
#####################################################################################################################################################
# Programa...: AutoresList.php
# Descricao..: Este programa conecta um banco de dados do MariaDB, inicia um recursivo com DOIS blocos:
#              - captura os valores de campos em um formulário para os campos da tabela Autores.
#                Para a CE celogradouro monta uma caixa de seleção.
#              - Executa o tratamento de uma transação (inclusão).
# Objetivo...: Construir um program recusivo de Inclusão de Registro.
# Autor......: JMH (Copie e use mas sempre referencie quem fez.
# Criação....: 2020-06-24
# Atualização: 2020-06-24 - Estruturação do Arquivo e desenvolvimento do form.
#####################################################################################################################################################
# Estrutura básica de um programa recursivo:
# Executando o pooling de funções
require_once("../pooling.php");
# Determinando o valor da variável de controle de fluxo
# PHP dispõe de uma série de funções de ambiente.
# Entre elas a função ISSET() é usada para determinar se uma variável
# existe no escopo de execução de um programa.
# Comando que faz a divisão da execução em blocos dependendo do valor de $bloco (variável de controle).
$bloco= ( ISSET($_REQUEST['bloco']) ) ? $_REQUEST['bloco'] : 1 ;
# ---
($bloco<3) ? startpage("Listagem logradouros") : startpage("Impressao");
# ---
switch (TRUE)
{ # Divisor de fluxo de execução
  case ( $bloco==1 ):
  { # Aqui montamos o Formulário de escolha da seleção dos dados da tabela de autores
    printf("<form action='AutoresList.php' method='POST'>\n");
    printf(" <input type='hidden' name='bloco' value=2>\n");
    printf(" <table>\n");
    printf("  <tr><td colspan=2>Escolha a ordenação dos dados da Listagem</td></tr>\n");
    printf("  <tr><td>Código</td><td>       <input type='radio' name='ordem' value='logradouros.cplogradouro' checked></td></tr>\n");
    printf("  <tr><td>Nome</td><td>         <input type='radio' name='ordem' value='logradouros.txnomelogradouro'></td></tr>\n");
    printf("  <tr><td>Data Cadastro</td><td><input type='radio' name='ordem' value='logradouros.dtcadlogradouro'></td></tr>\n");
    printf("  <tr><td colspan=2>Escolha o intervalo de datas de cadastro do autor</td><tr>\n");
    $today = date("Y-m-d");
    printf("  <tr><td colspan=2>de <input type='date' name='dtini' value='1901-01-01'> até <input type='date' name='dtfim' value='$today'></td><tr>\n");
    printf(" </table>\n");
    printf(" <button type='reset'>Reiniciar</button><button type='button' onclick='history.go(-$bloco)'>Sair</button><button type='submit'>Gerar Listagem</button>\n");
    printf("</form>\n");
    break;
  }
  case ( $bloco==2 || $bloco==3 ):
  { # Este bloco vai processar a junção de medicos com instituicaoensino, logradouros (moradia e clinica) e especiaidadesmedicas.
    # Depois monta a tabela com os dados e a seguir um form permitindo que a listagem seja exibida para impressão em uma nova aba.
    $cmdsql="SELECT A.*,
                    L.txnometipologradouro
                    FROM logradouros AS A INNER JOIN logradourostipos AS L ON A.cetipologradouro=L.cptipologradouro
                    WHERE A.dtcadlogradouro between '$_REQUEST[dtini]' AND '$_REQUEST[dtfim]'";
    $execsql=mysqli_query($dbm,$cmdsql);
    printf("<table border=1 style=' border-collapse: collapse; '>
            <tr><td>Cod.</td>\n
                <td>Nome</td>\n
                <td>Logr.Moradia</td>\n
                <td>Cidade</td>\n
                <td>Dt.Cad.</td></tr>\n");
    while ( $le=mysqli_fetch_array($execsql) )
    {
      printf("<tr><td>$le[cplogradouro]</td>\n
                  <td>$le[txnomelogradouro]</td>\n
                  <td>$le[cetipologradouro]-($le[txnometipologradouro])</td>\n
                  <td>$le[cecidade]</td>\n
                  <td>$le[dtcadlogradouro]</td></tr>\n");
    }
    printf("<table>\n");
    if ( $bloco==2 )
    {
      printf("<form action='./AutoresList.php' method='POST' target='_NEW'>\n");
      printf("<input type='hidden' name='bloco' value=3>\n");
      printf("<input type='hidden' name='ordem' value='$_REQUEST[ordem]'>\n");
      printf("<input type='hidden' name='dtini' value='$_REQUEST[dtini]'>\n");
      printf("<input type='hidden' name='dtfim' value='$_REQUEST[dtfim]'>\n");
      printf("<button type='button' onclick='history.go(-($bloco-1))'>Voltar</button><button type='button' onclick='history.go(-$bloco)'>menu</button> ou Gerar cópia para <button type='submit'>Impressão</button>");
      printf("</form>\n");
    }
    else
    {
      printf("<button type='submit' onclick='window.print();'>Imprimir</button> - Corte a folha abaixo da linha no final da página<br>\n<hr>\n");
    }
    break;
  }
}
finalpage();
?>
