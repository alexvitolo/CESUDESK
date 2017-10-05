<?php include '..\AdmCrm\connectionADM.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
 // Ação a ser executada: mata o script e manda uma mensagem
echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; }


 $ID_CONHECIMENTO =  $_POST['ID_CONHECIMENTO'];
 $ID_COLABORADOR_CONSULTOR =  $_POST['ID_COLABORADOR_CONSULTOR'];

 $NotaFinal = 0;
$QUEM_REALIZOU = $_SESSION["USUARIO"];


$insertTestePai = "INSERT INTO tb_ava_teste_conhecimento
                                       (ID_CONHECIMENTO
                                       ,ID_COLABORADOR
                                       ,QUEM_REALIZOU
                                         )
                                 VALUES
                                       ({$ID_CONHECIMENTO}
                                       ,{$ID_COLABORADOR_CONSULTOR}
                                       ,'{$QUEM_REALIZOU}'
                                        ) ";

 $result_insertTestePAi = sqlsrv_query($conn, $insertTestePai);

      if (!($result_insertTestePAi)) {
             // echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
             sqlsrv_close($conn);
             echo  '<script type="text/javascript"> alert("Falha na inclusão do registro"); window.location.href = "testeconhecimento.php" </script>';
      }   
      else {
            sqlsrv_free_stmt($result_insertTestePAi);
        }



//INSERT TABELA PESQUISA

foreach( $_POST['vetorquestaorepostas'] as $key => $value ){ 
      $QUESTAO = $key; // id_questao
      $REPOSTA = $value; // id_resp


       $sqlCalculaNota ="SELECT tq.ID_RESPOSTA
                                   ,tq.BO_VERDADEIRO
                           FROM tb_ava_questoes_conhecimento_alt tq
                          WHERE tq.ID_RESPOSTA ={$REPOSTA}
                         ";

          $stmtNota = sqlsrv_prepare($conn, $sqlCalculaNota);
          sqlsrv_execute($stmtNota);
          $resultadoNota = sqlsrv_fetch_array($stmtNota);

          if ($resultadoNota['BO_VERDADEIRO'] == 'S'){
          	$NotaFinal = $NotaFinal + 1  ;
          }

$insertSquilaRespostas = " INSERT INTO tb_ava_teste_conhecimento_resp
                                       (ID_TESTE
                                       ,ID_QUESTAO
                                       ,ID_RESPOSTA
                                       )
                                 VALUES
                                       ((SELECT TOP 1 ID_TESTE FROM tb_ava_teste_conhecimento ORDER BY 1 DESC)
                                       ,{$QUESTAO}
                                       ,{$REPOSTA}
                                      )";
                            
                   
 $result_insertRespostas = sqlsrv_query($conn, $insertSquilaRespostas);

      if (!($result_insertRespostas)) {
             // echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
             sqlsrv_close($conn);
             echo  '<script type="text/javascript"> alert("Falha na inclusão do registro"); window.location.href = "testeconhecimento.php" </script>';
      }   
      else {
            sqlsrv_free_stmt($result_insertRespostas);
        }


} 


$updateNotaFinal = " UPDATE tb_ava_teste_conhecimento
                              SET 
                            NOTA_FINAL = '{$NotaFinal}'
                                 
                          WHERE  ID_TESTE = (SELECT TOP 1 ttes.ID_TESTE FROM tb_ava_teste_conhecimento_resp ttes ORDER BY 1 DESC) ";

  $result_updateNotaFinal = sqlsrv_query($conn, $updateNotaFinal);
   sqlsrv_free_stmt($result_updateNotaFinal);



 echo  '<script type="text/javascript">alert("Formulário Cadastrado");</script>';
 echo  '<script type="text/javascript"> window.location.href = "testeconhecimento.php" </script>';




?>