<?php include '..\AdmCrm\connectionADM.php'; 
session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; 
}

if ( (date('H:i:s')) >=  (date('H:i:s', strtotime('+55 minute', strtotime($_SESSION['TEMPOSESSION'])))) & ($_SESSION['ACESSO'] <> 1 ) ){
     // Ação a ser executada: encerra a session depois de 15 min
   echo  '<script type="text/javascript"> alert("Tempo de Sessão Expirada"); window.location.href = "http://d42150:8087/cesudesk/AdmCrm/login.php"  </script>'; 
   session_destroy();
 }
 
 $_SESSION['TEMPOSESSION'] = date('H:i:s');





$ID_AVALIACAO = $_POST["ID_AVALIACAO"];
$ID_PROCESSO = $_POST["ID_PROCESSO"]; 
$ATIVO = $_POST["ATIVO"]; 
$DT_INI = $_POST["DT_INI"]; 
$DT_FIM = $_POST["DT_FIM"]; 
$ID_DT_CRONO = $_POST["ID_DT_CRONO"];


$insertSquila = " UPDATE  tb_qld_cronograma_avaliacao_prazo
                            SET  ID_AVALIACAO = {$ID_AVALIACAO}
                                ,ID_PROCESSO  = {$ID_PROCESSO}
                                ,DT_INICIO    = '{$DT_INI}'
                                ,DT_FIM       = '{$DT_FIM}'
                                ,BO_STATUS    = '{$ATIVO}'
                             WHERE ID_DT_CRONO = '{$ID_DT_CRONO}' ";

                   
 $result_insert = sqlsrv_query($conn, $insertSquila);

      if (!($result_insert)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
      }   
      else {
            sqlsrv_free_stmt($result_insert);
            sqlsrv_close($conn);
            echo  '<script type="text/javascript">alert("Prazo cadastrado !");</script>';
            echo  '<script type="text/javascript"> window.location.href = "prazoAvaliacao.php" </script>';
        }