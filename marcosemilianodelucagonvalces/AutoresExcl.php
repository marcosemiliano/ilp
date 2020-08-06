<?php
#####################################################################################################################################################
# Programa...: AutoresExcl.php
# Descricao..: Este programa conecta um banco de dados do MariaDB, inicia um recursivo com TRÊS blocos:
#              - primeiro: mostra uma lista de dados em uma caixa de seleção, permitindo a escolha de um registro
#              - segundo: captura o valor indicadao no primeiro bloco e busca o registro no BD e exibe os dados montando um form
#                para confirmar exclusão do registro escolhido, neste form há a passagem do valor da CP do registro.
#              - Executa o tratamento de uma transação (exclusão).
# Objetivo...: Construir um programa recusivo de Exclusão de Registro.
# Autor......: JMH (Copie e use mas sempre referencie quem fez.
# Criação....: 2020-06-07
# Atualização: 2020-06-10 - inclusão das TAGs HTML que iniciam e terminam a página.
#              2020-06-11 - escrevi o bloco 2 com uso de <TABLE> para formatar a saída dos dados.
#              2020-06-19 - Derivei deste programa o programa que usa função. Melhorei os comandos de início|fim da página.
#              2020-06-23 - Introduzi o cabeçalho.
#####################################################################################################################################################
# Estrutura básica de um programa recursivo:
# Executando o pooling de funções
require_once("../pooling.php");
# ---
startpage("Exclusão logradouros");
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
  { # Pesquisa na base de dados lendo a uma tabela e montando um form com uma picklist
    # Comando para leitura de dados de uma tabela
    $cmdsql="SELECT cplogradouro, txnomelogradouro FROM logradouros ORDER BY txnomelogradouro";
    # SE for necessário é possível exibir o comando que será executado
    # printf("$cmdsql\n");
    # 'Executando' a variável:
    $execcmd=mysqli_query($dbm,"$cmdsql");
    # função _QUERY() retorna um 'vetor' em três partes:
    # - Identificação das tabelas lidas.
    # - Identificação dos campos que foram lidos (no exemplo foi feita a projeção de cpautor e txnomeautor.
    # - Lista dos endereços de memória onde o PG escreveu os endereços dos registros que atenderam à operação feita sobre os dados.
    printf("  <form action='./AutoresExcl.php' method='POST'>\n");
    printf("   <input type='hidden' name='bloco' value='2'>\n");
    printf("    Escolha o logradouro a ser Excluído:<br>\n");
    printf("   <select name='cplogradouro'>\n");
    # dentro da TAG <select> deve-se mostrar as linhas com o cpautor e txnomeautor.]
    # estas linhas serão montadas dentro de um while.
    while ( $reg=mysqli_fetch_array($execcmd) )
    {
      printf("    <option value='$reg[cplogradouro]'>$reg[txnomelogradouro]-($reg[cplogradouro])</option>\n");
    }
    printf("   </select>\n");
    printf("   <button type='reset'>Reiniciar</button><button type='button' onclick='history.go(-1)'>Sair</button><button type='submit'>Excluir</button>\n");
    printf("  </form>\n");
    break;
  }
  case ( $bloco==2 ):
  { # Aqui podemos exibir os valores digitados nos campos do formulário do bloco1
    $reg=mysqli_fetch_array(mysqli_query($dbm,"select logradouros.*, txnomelogradouro from logradouros, logradourostipos where cetipologradouro=cptipologradouro AND cplogradouro='$_REQUEST[cplogradouro]'"));
    # mostrando o registro
    printf("  <table>\n");
    printf("   <tr><td>Código:</td>         <td>$reg[cplogradouro]</td></tr>\n");
    printf("   <tr><td>Nome do Logradouro:</td><td>$reg[txnomelogradouro]</td></tr>\n");
    printf("   <tr><td>Tipologradouro:</td>    <td>$reg[cetipologradouro]</td></tr>\n");
    printf("   <tr><td>Cidade do Logradouro:</td>    <td>$reg[cecidade]</td></tr>\n");
    printf("   <tr><td>Cadastro logradouro:</td>       <td>$reg[dtcadlogradouro] (AAAA-MM-DD)</td></tr>\n");
    printf("  </table>\n");
	# Depois de consultado o registro montamos o formulário de confirmação da exclusão
    printf("  <form action='./AutoresExcl.php' method='POST'>\n");
    printf("   <input type='hidden' name='bloco' value='3'>\n");
    printf("   <input type='hidden' name='cplogradouro' value='$_REQUEST[cplogradouro]'>\n");
    printf("   <button type='button' onclick='history.go(-2)'>Sair</button><button type='submit'>Confirma Exclus&atilde;o</button>\n");
    printf("  </form>\n");
    break;
  }
  case ( $bloco==3 ):
  { # aqui faremos o controle da transação que faz a exclusão
    #
    printf("<br>Excluindo registro...<br>\n");
    # Montando o comando que vai deletar o registro
    $cmdsql="DELETE FROM logradouros WHERE cplogradouro='$_REQUEST[cplogradouro]'";
    # Iniciando o controle de laço de repetição para a execução da transação.
    $trytrans=TRUE;
    while ( $trytrans )
    { # O controle de repatição por conclusão e/ou abortagem será feito neste loop.
      mysqli_query($dbm,"START TRANSACTION");
      # Executando o comando no BD
      $execsql=mysqli_query($dbm,$cmdsql);
      # Este comando retorna a situação de erro em duas funções do PHP:
      # _errno() - Número do Erro
      # _error() - Texto com a mensagem de erro
      # Desvio condicional para as situacoes de erro
      if ( mysqli_errno($dbm)==0 )
      { # A transacao deve ser concluida e o laco de repeticao deve ser 'quebrado'
        mysqli_query($dbm,"COMMIT");
        $trytrans=FALSE;
        $mens="Registro de logradouros com código='$_REQUEST[cplogradouro]' foi excluído com sucesso.";
      }
      else
      { if ( mysqli_errno($dbm)==1213 )
        { # DEADLOCK, tenta novamente
          $trytrans=TRUE;
        }
        else
        { # Erro irrecuperavel. Parar de tentar a transacao
          $trytrans=FALSE;
          $mens=mysqli_errno($dbm)."-".mysqli_error($dbm);
        }
        # A transação deve ser cancelada
        mysqli_query($dbm,"ROLLBACK");
      }
    }
    printf("$mens<br>\n");
    printf("<button type='button' onclick='history.go(-2)'>Voltar</button>\n");
    printf("<button type='button' onclick='history.go(-3)'>menu</button>\n");
  }
}
finalpage();
?>
