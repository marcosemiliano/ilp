<?php
#####################################################################################################################################################
# Programa...: AutoresAlte.php
# Descricao..: Este programa conecta um banco de dados do MariaDB, inicia um recursivo com TRÊS blocos:
#              - primeiro: mostra uma lista de dados em uma caixa de seleção para escolha do registro a alterar.
#              - segundo: captura o valor indicadao no primeiro bloco e busca o registro no BD e exibe os dados montando um form
#                com os valores dos campos preenchidos para alterar. A CP não é alterada. No final oferece botão para confirmar alteração
#              - terceiro: executa o tratamento de uma transação (alteração).
# Objetivo...: Construir um programa recusivo de Alteração de Registro.
# Autor......: JMH (Copie e use mas sempre referencie quem fez.
# Criação....: 2020-06-24
# Atualização: 2020-06-24 - Estruturação do Arquivo e desenvolvimento do form.
#####################################################################################################################################################
# Estrutura básica de um programa recursivo:
# Executando o pooling de funções
require_once("../pooling.php");
# ---
startpage("Alteração logradouros");
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
  { # 1.1 - Pesquisa na base de dados lendo a uma tabela e montando um form com uma picklist autores
    # Comando para leitura de dados de uma tabela
    $cmdsql="SELECT cplogradouro, txnomelogradouro FROM logradouros ORDER BY txnomelogradouro";
    # SE for necessário é possível exibir o comando que será executado
    # printf("$cmdsql\n");
    # 'Executando' a variável:
    $execcmd=mysqli_query($dbm,"$cmdsql");
    # função _QUERY() retorna um 'vetor' em três partes:
    # - Identificação das tabelas lidas.
    # - Identificação dos campos que foram lidos (no exemplo foi feita a projeção de cplogradouro e txnomeautor. 
    # - Lista dos endereços de memória onde o SGBD escreveu os endereços dos registros que atenderam à operação feita sobre os dados.
    printf("  <form action='./LogradourosAlte.php' method='POST'>\n");
    # No atributo action deve ser escrito o nome do ARQUIVO PHP carregado recursivamente.
    printf("   <input type='hidden' name='bloco' value='2'>\n");
    printf("    Escolha o logradouro a ser Consultado:<br>\n");
    printf("   <select name='cplogradouro'>\n");
    # dentro da TAG <select> deve-se mostrar as linhas com o cpautor e txnomeautor.]
    # estas linhas serão montadas dentro de um while.
    while ( $reg=mysqli_fetch_array($execcmd) )
    { # a _fetch_array() pega o primeiro endereço disponivel no retorno do _QUERY(), busca na memória (RAM|HD - no SGBD))
      # os valores dos campos que formam o registro, depois carrega isso em um Vetor[] no ambiente do PA em execução.
      printf("    <option value='$reg[cplogradouro]'>$reg[txnomelogradouro]-($reg[cplogradouro])</option>\n");
      # cada linha formada neste while deve apontar para um endereço de registro, por isso o value aponta para os valores da CP da tabela.
    }
    printf("   </select>\n");
    printf("   <button type='reset'>Reiniciar</button><button type='button' onclick='history.go(-1)'>Sair</button>");
    printf("   <button type='submit'>Alterar</button>\n");
    printf("   </form>\n");
    break;
  } # 1.1 - Fim do Case 1
  case ( $bloco==2 ):
  { # Aqui montamos o Formulário de Alteração
    # Trazendo o registro da tabela para mostrar os campos já preenchidos no formulário de alteração.
    $reg=mysqli_fetch_array(mysqli_query($dbm,"select * from logradouros where cplogradouro='$_REQUEST[cplogradouro]'"));
    # mostrando o registro

    printf("<form action='AutoresAlte.php' method='POST'>\n");
    printf(" <input type='hidden' name='bloco' value=3>\n");
    printf(" <input type='hidden' name='cplogradouro' value='$_REQUEST[cplogradouro]'>\n");
    printf(" <table>\n");
    printf("  <tr><td>Código</td><td>$reg[cplogradouro]</td></tr>\n");
    printf("  <tr><td>Nome</td><td><input type='text' name='txnomelogradouro' value='$reg[txnomelogradouro]' size=50 maxlength=90></td></tr>\n");
    printf("  <tr><td>Logradouro tipo </td><td>");
    $cmdsql='SELECT cptipologradouro, txnometipologradouro FROM logradourostipos ORDER BY txnometipologradouro';
    # printf("$cmdsql<br>\n");
    $execcmd=mysqli_query($dbm,$cmdsql);
    # Lembre: o retorno da função _QUERY() é um vetor composto: Nome da Tabela | Nomes dos Campos | Endereços de registros
    printf("<select name='cetipologradouro'>\n");
    while ( $le=mysqli_fetch_array($execcmd) )
    {
      $selected=( $le['cptipologradouro']==$reg['cetipologradouro'] ) ? " selected" : "";
      printf("<option value='$le[cptipologradouro]'$selected>$le[txnometipologradouro]-($le[cptipologradouro])</option>\n");
    }
    printf("</select></td></tr>\n");
	
	printf("  <tr><td>Cidade </td><td>");
    $cmdsql='SELECT cpcidade, txnome FROM cidades ORDER BY txnome';
    # printf("$cmdsql<br>\n");
    $execcmd=mysqli_query($dbm,$cmdsql);
    # Lembre: o retorno da função _QUERY() é um vetor composto: Nome da Tabela | Nomes dos Campos | Endereços de registros
    printf("<select name='cecidade'>\n");
    while ( $le=mysqli_fetch_array($execcmd) )
    {
      $selected=( $le['cpcidade']==$reg['cecidade'] ) ? " selected" : "";
      printf("<option value='$le[cpcidade]'$selected>$le[txnome]-($le[cpcidade])</option>\n");
    }
    printf("</select></td></tr>\n");
    printf("  <tr><td>Cadastro</td><td><input type='date' name='dtcadlogradouro' value='$reg[dtcadlogradouro]'></td></tr>\n");
    printf("  <tr><td></td><td><button type='reset'>Reiniciar</button><button type='button' onclick='history.go(-1)'>Voltar</button><button type='button' onclick='history.go(-2)'>Sair</button><button type='submit'>Alterar</button></td></tr>");
    printf(" </table>\n");
    printf("</form>\n");
    break;
  }
  case ( $bloco==3 ):
  { # aqui faremos o controle da transação que faz a exclusão
    printf("Alterando registro...<br>\n");
    # Antes de construir o comando que faz a alteração podemos verificar se
    # os campos do form estão corretamente definidos no form do case 2
    # printf("<pre>\n");print_r($_REQUEST);printf("</pre>\n");
    # A linha acima pode ser 'descomentada' para ver os valores dos campos do form em 'tempo de execução'.
    # 'Montando' o comando que faz a alteração dos valores dos campos na linha da tabela
    $cmdsql="UPDATE logradouros SET txnomelogradouro='$_REQUEST[txnomelogradouro]',
                                cetipologradouro='$_REQUEST[cetipologradouro]',
                                cecidade='$_REQUEST[cecidade]',
                                dtcadlogradouro='$_REQUEST[dtcadlogradouro]'
                            WHERE cplogradouro='$_REQUEST[cplogradouro]'";
    # Se for preciso confirmar se o comando montado está correto retire o comentário da linha a seguir
    # printf("$cmdsql<br>\n");
    # Iniciando o controle da transação. Usa-se um booleano $trytrans
    $trytrans=TRUE;
    while ( $trytrans )
    { # Iniciando a transação (O SGBD é avisado para iniciar os logs de transação)
      mysqli_query($dbm,"START TRANSACTION");
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
        $mens="Registro de logradouros com código='$_REQUEST[cplogradouro]' foi Alterado com sucesso";
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
    printf("<button type='button' onclick='history.go(-1)'>Voltar</button><button type='button' onclick='history.go(-2)'>Escolher outro</button><button type='button' onclick='history.go(-3)'>menu</button>\n");
  }
}

finalpage();
?>
