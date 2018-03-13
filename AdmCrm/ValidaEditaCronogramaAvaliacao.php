<?php include '..\AdmCrm\connectionADM.php'; 
session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://d42150:8087/CESUDESK/NewCesudesk/index.php"  </script>'; 
}

if ( (date('H:i:s')) >=  (date('H:i:s', strtotime('+55 minute', strtotime($_SESSION['TEMPOSESSION'])))) & ($_SESSION['ACESSO'] <> 1 ) ){
     // Ação a ser executada: encerra a session depois de 15 min
   echo  '<script type="text/javascript"> alert("Tempo de Sessão Expirada"); window.location.href = "http://d42150:8087/CESUDESK/NewCesudesk/main.php"  </script>'; 
   session_destroy();
 }
 
 $_SESSION['TEMPOSESSION'] = date('H:i:s');





$ID_AVALIACAO = $_POST["ID_AVALIACAO"]; 
$NUMERO = $_POST["NUMERO"]; 
$ID_CARGO = $_POST["ID_CARGO"]; 
$BO_STATUS = $_POST["BO_STATUS"]; 




$updateSquilaCron = " UPDATE tb_qld_cronograma_avaliacao
                   SET   NUMERO = '{$NUMERO}'
                      ,ID_CARGO = {$ID_CARGO}
                     ,BO_STATUS = '{$BO_STATUS}'

              WHERE  ID_AVALIACAO =  {$ID_AVALIACAO} ";


 $result_updateCron = sqlsrv_query($conn, $updateSquilaCron);

      if (!($result_updateCron)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
      }   
      else {
            sqlsrv_free_stmt($result_updateCron);
            sqlsrv_close($conn);
            echo  '<script type="text/javascript">alert("Questão Atualizada !");</script>';
            echo  '<script type="text/javascript"> window.location.href = "cronogramaAvaliacao.php" </script>';
        }


?>