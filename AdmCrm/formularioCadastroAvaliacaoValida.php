<?php include '..\AdmCrm\connectionADM.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://dd42150:8087/CESUDESK/NewCesudesk/index.php"  </script>'; 
}




$ID_AVALIADOR = $_SESSION['ID_COLABORADOR'];

$ID_MATRICULA_CONSULTOR = $_POST['ID_MATRICULA_CONSULTOR'];
$ID_CONSULTOR = $_POST['ID_CONSULTOR'];



$ID_AVALIACAO = $_POST['ID_AVALIACAO'];
$ID_OBJETO_TALISMA = $_POST['ID_OBJETO_TALISMA'];
$DESC_ID_TALISMA = $_POST['DESC_ID_TALISMA'];
$CPF_MONITORIA = $_POST['CPF_MONITORIA'];
$ID_GRAVADOR = $_POST['ID_GRAVADOR'];
$RAMAL_PA = $_POST['RAMAL_PA'];
$DT_ATENDIMENTO = $_POST['DT_ATENDIMENTO'];

$OBSERVACAO_PESQUISA = $_POST['OBSERVACAO_PESQUISA'];
$ID_RESULT_LIG = $_POST['ID_RESULT_LIG'];

$OBSERVACAO_PESQUISA = str_replace("'", '"', $OBSERVACAO_PESQUISA);



// verifica se é processo presecial


  $sqlValidaPresencial ="SELECT tg.ID_UNIDADE
                        FROM tb_crm_colaborador tc
                  INNER JOIN tb_crm_grupo tg ON tg.ID_GRUPO = tc.ID_GRUPO 
                       WHERE ID_MATRICULA = {$ID_MATRICULA_CONSULTOR} ";

          $stmtValidaPresencial = sqlsrv_prepare($conn, $sqlValidaPresencial);
          $resultValida = sqlsrv_execute($stmtValidaPresencial);
          $resultadoSQLPre = sqlsrv_fetch_array($stmtValidaPresencial);

 if ($resultadoSQLPre['ID_UNIDADE'] == 2) {


    $squilaProcesso = "SELECT ID
                             ,NOME
                             ,MODALIDADE
                             ,ATIVO
                        FROM tb_crm_processo
                       WHERE MODALIDADE = 'Presencial'
                         AND ATIVO = '1'   ";

    $result_squilaProcesso = sqlsrv_prepare($conn, $squilaProcesso);
    sqlsrv_execute($result_squilaProcesso);
    $resultadoSQL = sqlsrv_fetch_array($result_squilaProcesso);


}else{


    $squilaProcesso = "SELECT ID
                             ,NOME
                             ,MODALIDADE
                             ,ATIVO
                        FROM tb_crm_processo
                       WHERE MODALIDADE = 'Graduação'
                         AND ATIVO = '1'   ";

    $result_squilaProcesso = sqlsrv_prepare($conn, $squilaProcesso);
    sqlsrv_execute($result_squilaProcesso);
    $resultadoSQL = sqlsrv_fetch_array($result_squilaProcesso);



}



 $ID_PROCESSO = $resultadoSQL['ID'];

 $ID_GRUPO =  $_POST['ID_GRUPO'];



//INSERT TABELA PESQUISA


$insertSquilaPesquisa = " INSERT INTO tb_qld_pesquisa
                                            (ID_COLABORADOR
                                            ,ID_COLABORADOR_APLICA
                                            ,ID_PROCESSO
                                            ,ID_GRUPO
                                            ,ID_AVALIACAO
                                            ,ID_OBJETO_TALISMA
                                            ,DESC_ID_TALISMA
                                            ,CPF_MONITORIA
                                            ,RAMAL_PA
                                            ,ID_GRAVADOR
                                            ,DT_ATENDIMENTO
                                            ,ID_RESULT_LIG
                                            ,OBSERVACAO_PESQUISA
                                            )
                                      VALUES
                                            ({$ID_CONSULTOR}
                                            ,{$ID_AVALIADOR}
                                            ,{$ID_PROCESSO}
                                            ,{$ID_GRUPO}
                                            ,{$ID_AVALIACAO}
                                            ,{$ID_OBJETO_TALISMA}
                                            ,replace('{$DESC_ID_TALISMA}','-','')
                                            ,'{$CPF_MONITORIA}'
                                            ,'{$RAMAL_PA}'
                                            ,'{$ID_GRAVADOR}'
                                            ,'{$DT_ATENDIMENTO}'
                                            ,{$ID_RESULT_LIG}
                                            ,'{$OBSERVACAO_PESQUISA}'
                                            )";

                   
 $result_insertPesquisa = sqlsrv_query($conn, $insertSquilaPesquisa);

      if (!($result_insertPesquisa)) {
             // echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
             sqlsrv_close($conn);
             echo  '<script type="text/javascript"> alert("Falha na inclusão do registro"); window.location.href = "formularioAvaliacao.php" </script>';
      }   
      else {
            sqlsrv_free_stmt($result_insertPesquisa);
        }



$notaFinalSoma = 0;
$NOTA_RESULTADO = 0;
// INSERT TABELA ITENS QUESTOES

     
// foreach( $_POST['vetorRespostas'] as $key => $value ){ 
//       print_r($key); // id_questao
//   foreach ($value as $key2 => $value2) {
//      print_r($key2); // peso
//      print_r($value2); // RESPOSTA
//   }


foreach( $_POST['vetorRespostas'] as $key => $value ){ 
  foreach ($value as $key2 => $value2) {

     //condição para calcular a nota do item
        if($value2 == 'P'){
          $NOTA_RESULTADO = $key2/2;
        }
        elseif($value2 == 'N'){
          $NOTA_RESULTADO = 0;
        }
        elseif($value2 == 'S'){
          $NOTA_RESULTADO = $key2;
        }
        elseif($value2 == ''){
          $NOTA_RESULTADO = 0;
        }

        $notaFinalSoma = $NOTA_RESULTADO + $notaFinalSoma;

     $insertSquilaResp = "INSERT INTO tb_qld_itens_questoes
                                      (ID_QUESTAO
                                      ,RESPOSTA
                                      ,ID_PESQUISA
                                      ,NOTA_RESULTADO)
                               VALUES
                                      ({$key}
                                      ,'{$value2}'
                                      ,(SELECT TOP 1 ID_PESQUISA FROM tb_qld_pesquisa ORDER BY 1 DESC)
                                      ,{$NOTA_RESULTADO})";

      $result_insertSquilaResp = sqlsrv_query($conn, $insertSquilaResp);
      sqlsrv_free_stmt($result_insertSquilaResp);

   }
  } 

foreach( $_POST['vetorRespostasCrit'] as  $key => $value ){
    foreach ($value as $key2 => $value2) {

     //condição para calcular a nota do item
      
        if($value2 == 'N'){
          $NOTA_RESULTADO = 0;
        }
        elseif($value2 == 'S'){
          $NOTA_RESULTADO = $key2;
           $notaFinalSoma = 0;
        }



     $insertSquilaRespCrit = "INSERT INTO tb_qld_itens_questoes
                                      (ID_QUESTAO
                                      ,RESPOSTA
                                      ,ID_PESQUISA
                                      ,NOTA_RESULTADO)
                               VALUES
                                      ({$key}
                                      ,'{$value2}'
                                      ,(SELECT TOP 1 ID_PESQUISA FROM tb_qld_pesquisa ORDER BY 1 DESC)
                                      ,{$NOTA_RESULTADO})";

      $result_insertSquilaRespCrit = sqlsrv_query($conn, $insertSquilaRespCrit);
      sqlsrv_free_stmt($result_insertSquilaRespCrit);

   }

}


//UPDATE NOTA FINAL TABELA PESQUISA

$updateSquilaPesquisa = " UPDATE tb_qld_pesquisa
                              SET 
                                 NOTA_FINAL = {$notaFinalSoma}
                                 
                            WHERE  ID_PESQUISA = (SELECT TOP 1 ttp.ID_PESQUISA FROM tb_qld_pesquisa ttp ORDER BY 1 DESC) ";

  $result_updatePesquisa = sqlsrv_query($conn, $updateSquilaPesquisa);
   sqlsrv_free_stmt($result_updatePesquisa);






// anexo


if ($_FILES['anexo']['size'] <> 0) {

foreach( $_FILES['anexo'] as $key => $value ){ 
  // print_r($key);
  // print_r($value);
      
      foreach( $value as $key2 => $value2 ){ // 
         
         if($key == 'name') { 
                $nomeArq[$key2] = $value2; // Vetor para armazenar nome correto do arquivo (por causa do foreack do tipo file)
          }
           
         if($key == 'tmp_name' and $value2 <> null) {    // vetor com o conteudo do anexo
               //  print_r($key2);
               // print_r($value2);


              $value2= file_get_contents($value2);
              $value2 = unpack('H*hex', $value2);
              $contents = '0x'.$value2['hex'];

              $insertARQ = "INSERT INTO [DB_CRM_REPORT].[dbo].[tb_qld_pesquisa_anexo]
                                         (anexo
                                         ,dh_upload
                                         ,nm_anexo
                                         ,ID_PESQUISA
                                         )
                               VALUES
                                      (CONVERT (varbinary(max),?,1) 
                                      ,GETDATE()
                                      ,'{$nomeArq[$key2]}'
                                      ,(SELECT TOP 1 ID_PESQUISA FROM [DB_CRM_REPORT].[dbo].[tb_qld_pesquisa] ORDER BY 1 DESC) ) ";  

              $result_insertARQ = sqlsrv_query($conn, $insertARQ ,array($contents));
      


              if (!($result_insertARQ)) {
                     echo ("Falha na inclusão do anexo, por favor, edite a monitoria");
                     print_r(sqlsrv_errors());exit;
              }


          }
      }


}

}


 echo  '<script type="text/javascript">alert("Formulário Cadastrado");</script>';
 echo  '<script type="text/javascript"> window.location.href = "formularioAvaliacao.php" </script>';
?>

