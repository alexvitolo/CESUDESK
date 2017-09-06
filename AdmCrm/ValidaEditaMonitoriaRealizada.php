<?php include '..\AdmCrm\connectionADM.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
 // Ação a ser executada: mata o script e manda uma mensagem
echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; }


$ID_PESQUISA = $_POST['ID_PESQUISA'];  
$ID_RESULT_LIG = $_POST['ID_RESULT_LIG'];
$OBSERVACAO_PESQUISA = $_POST['OBSERVACAO_PESQUISA'];


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


        $notaFinalSoma = $NOTA_RESULTADO + $notaFinalSoma;

     $updateSquilaResp = "UPDATE tb_qld_itens_questoes
                                    SET  ID_QUESTAO = {$key}
                                      ,RESPOSTA = '{$value2}'
                                      ,ID_PESQUISA = {$ID_PESQUISA}
                                      ,NOTA_RESULTADO = {$NOTA_RESULTADO}
                            WHERE ID_PESQUISA = {$ID_PESQUISA} AND ID_QUESTAO = {$key} 
                          ";

      $result_updateSquilaResp = sqlsrv_query($conn, $updateSquilaResp);
      sqlsrv_free_stmt($result_updateSquilaResp);

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



     $updateSquilaRespCrit = "UPDATE tb_qld_itens_questoes
                                    SET  ID_QUESTAO = {$key}
                                      ,RESPOSTA = '{$value2}'
                                      ,ID_PESQUISA = {$ID_PESQUISA}
                                      ,NOTA_RESULTADO = {$NOTA_RESULTADO}
                            WHERE ID_PESQUISA = {$ID_PESQUISA} AND ID_QUESTAO = {$key} 
                              ";

      $result_updateSquilaRespCrit = sqlsrv_query($conn, $updateSquilaRespCrit);
      sqlsrv_free_stmt($result_updateSquilaRespCrit);

   }

}



//UPDATE NOTA FINAL TABELA PESQUISA

$updateSquilaPesquisa = " UPDATE tb_qld_pesquisa
                              SET 
                                 NOTA_FINAL = {$notaFinalSoma}
                                ,ID_RESULT_LIG = {$ID_RESULT_LIG}
                                ,OBSERVACAO_PESQUISA = '{$OBSERVACAO_PESQUISA}'                                 
                            WHERE  ID_PESQUISA = {$ID_PESQUISA} ";

  $result_updatePesquisa = sqlsrv_query($conn, $updateSquilaPesquisa);
   sqlsrv_free_stmt($result_updatePesquisa);



 echo  '<script type="text/javascript">alert("Pesquisa Atualizada");</script>';
 echo  '<script type="text/javascript"> window.location.href = "monitoriaRealizada.php" </script>';
?>
