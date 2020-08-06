<?php
###############################################################################################################################################################
# Programa...: AutoresExc-COMFuncao(MariaDB).php
# Descricao..: Este programa conecta um banco de dados do MariaDB, inicia um recursivo com DOIS blocos:
#              - captura os vloares de campos em um formulário para os campos da tabela Autores.
#                Para a CE celogradouro monta uma caixa de seleção.
#              - Executa o tratamento de uma transação (inclusão).
# Objetivo...: Construir um program recusivo de Inclusão de Registro.
# Autor......: JMH (Copie e use mas sempre referencie quem fez.
# Criação....: 2020-06-24
# Atualização: 2020-06-24 - Estruturação do Arquivo e desenvolvimento do form.
###############################################################################################################################################################
# Estrutura básica de um programa recursivo: escolha
# Executando o pooling de funções
require_once("../pooling.php");
# ---
startpage("Inclusão logradouro");
# ---
# Determinando o valor da variável de controle de fluxo
# PHP dispõe de uma série de funções de ambiente.
# Entre elas a função ISSET() é usada para determinar se uma variável
# existe no escopo de execução de um programa.
# Comando que faz a divisão da execução em blocos dependendo do valor de $bloco (variável de controle).
$bloco= ( ISSET($_REQUEST['bloco']) ) ? $_REQUEST['bloco'] : 1 ;
switch (TRUE)
{ # Divisor de fluxo de execução
  case ( $bloco==1 ):
  { # Aqui montamos o Formulário de Inclusão
    printf("<form action='LogradourosIncl(MariaDB).php' method='POST'>\n
             <input type='hidden' name='bloco' value=2>\n
             <table>\n
              <tr><td>Código</td><td>Será gerado pelo sistema (informado no final do processo)</td></tr>\n
              <tr><td>Nome Logradouro</td><td><input type='text' name='txnomelogradouro' size=50 maxlength=90></td></tr>\n
              <tr><td>Logradourotipo </td><td>");
    $cmdsql='SELECT cptipologradouro, txnometipologradouro FROM logradourostipos ORDER BY txnometipologradouro';
    # printf("$cmdsql<br>\n");
    $execcmd=mysqli_query($dbm,$cmdsql);
    # Lembre: o retorno da função _QUERY() é um vetor composto: Nome da Tabela | Nomes dos Campos | Endereços de registros
    printf("<select name='cetipologradouro'>\n");
    while ( $le=mysqli_fetch_array($execcmd) )
    {
      printf("<option value='$le[cptipologradouro]'>$le[txnometipologradouro]-($le[cptipologradouro])</option>\n");
    }	
    printf("</select></td></tr>\n
	
	
	<tr><td> cidade </td><td>");
    $cmdsql='SELECT cpcidade, txnome FROM cidades ORDER BY txnome';
    # printf("$cmdsql<br>\n");
    $execcmd=mysqli_query($dbm,$cmdsql);
    # Lembre: o retorno da função _QUERY() é um vetor composto: Nome da Tabela | Nomes dos Campos | Endereços de registros
    printf("<select name='cecidade'>\n");
    while ( $le=mysqli_fetch_array($execcmd) )
    {
      printf("<option value='$le[cpcidade]'>$le[txnome]-($le[cpcidade])</option>\n");
    }	
    printf("</select></td></tr>\n
	
              <tr><td>Cadastro logradouro</td><td><input type='date' name='dtcadlogradouro'></td></tr>\n
             </table>\n
             <input type='submit' value='Incluir Registro'>
            </form>");
    break;
  }
  case ( $bloco==2 ):
  { # aqui faremos o controle da transação que faz a exclusão
    printf("Incluindo registro...<br>\n");
    # O código exemplifica a geração de valores da CP de logradouro controlado pelo programa (sem usar campos autoincremento)
    # Comando para verificar se os campos estão corretamente definidos no form do case 1
    # printf("<pre>\n");print_r($_REQUEST);printf("</pre>\n");
    # Iniciando o controle da transação. Usa-se um booleano $trytrans
    $trytrans=TRUE;
    while ( $trytrans )
    { # Iniciando a transação (O SGBD é avisado para iniciar os logs de transação)
      mysqli_query($dbm,"START TRANSACTION");
      # montando o valor da CP de logradouro: ler o registro com máximo valor de cpautor e depois somar 1 unidade ao valor
      # isso PRECISA ser feito 'dentro' da transação porque caso a transação seja abortada/reiniciada então o $CP deve ser
      # determinado novamente
      $ultimacp=mysqli_fetch_array(mysqli_query($dbm,"SELECT MAX(cplogradouro) AS CpMAX FROM logradouros"));
      $CP=$ultimacp['CpMAX']+1;
      $cmdsql="INSERT INTO logradouros (cplogradouro,
                                    txnomelogradouro,
                                    cetipologradouro,
                                    cecidade,
                                    dtcadlogradouro)
                      VALUES ('$CP',
                              '$_REQUEST[txnomelogradouro]',
                              '$_REQUEST[cetipologradouro]',
                              '$_REQUEST[cecidade]',                             
                              '$_REQUEST[dtcadlogradouro]')";
      # confirmando o comando que será executado no BD
      # printf("$cmdsql<br>\n");
      # Executando o comando no BD
      $execsql=mysqli_query($dbm,$cmdsql);
      # Este comando retorna a situação de erro em duas funções do PHP:
      # _errno() - Número do Erro
      # _error() - Texto com a mensagem de erro
      # Desvio condicional para as situações de erro
      if ( mysqli_errno($dbm)==0 )
      { # A transação deve ser concluida e o laço de repetição deve ser 'quebrado'
        mysqli_query($dbm,"COMMIT");
        $trytrans=FALSE;
        $mens="Registro de logradouro com código='$CP' foi incluído com sucesso";
        # mostraregistro("$CP");
      }
      else
      { if ( mysqli_errno($dbm)==1213 )
        { # DEADLOCK, tenta novamente
          $trytrans=TRUE;
        }
        else
        { # Erro irrecuperavel. Parar de tentar a transação
          $trytrans=FALSE;
          $mens=mysqli_errno($dbm)."-".mysqli_error($dbm);
        }
        # A transação deve ser ABORTADA
        mysqli_query($dbm,"ROLLBACK");
      }
    }

    printf("$mens<br>\n");
    printf("<button type='button' onclick='history.go(-2)'>Sair</button>\n");
  }
}
finalpage();
?>















































