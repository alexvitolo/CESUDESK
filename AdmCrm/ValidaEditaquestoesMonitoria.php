<?php include '..\AdmCrm\connectionADM.php'; 
session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; 
}

if ( (date('H:i:s')) >=  (date('H:i:s', strtotime('+40 minute', strtotime($_SESSION['TEMPOSESSION'])))) & ($_SESSION['ACESSO'] <> 1 ) ){
     // Ação a ser executada: encerra a session depois de 40 min
   echo  '<script type="text/javascript"> alert("Tempo de Sessão Expirada"); window.location.href = "http://d42150:8087/cesudesk/AdmCrm/login.php"  </script>'; 
   session_destroy();
 }




$ID_QUESTAO = $_POST["ID_QUESTAO"]; 
$DESCRICAO = $_POST["DESCRICAO"]; 
$DESC_OBSERVACAO = $_POST["DESC_OBSERVACAO"]; 
$ID_GRUPO = $_POST["ID_GRUPO"]; 
$PESO = $_POST["PESO"]; 
$BO_FALHA_CRITICA = $_POST["BO_FALHA_CRITICA"]; 
$BO_PARCIAL = $_POST["BO_PARCIAL"];  
$BO_QUESTAO_ATIVA = $_POST["BO_QUESTAO_ATIVA"];




$updateSquila = " UPDATE tb_qld_questoes
                   SET   DESCRICAO = '{$DESCRICAO}'
                    ,DESC_OBSERVACAO = '{$DESC_OBSERVACAO}' 
                    ,ID_GRUPO = {$ID_GRUPO}
                    ,PESO = {$PESO}
                    ,BO_FALHA_CRITICA =  '{$BO_FALHA_CRITICA}'
                    ,BO_PARCIAL = '{$BO_PARCIAL}' 
                    ,BO_QUESTAO_ATIVA =  '{$BO_QUESTAO_ATIVA}' 
              WHERE  ID_QUESTAO =  {$ID_QUESTAO} ";


 $result_update = sqlsrv_query($conn, $updateSquila);

      if (!($result_update)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
      }   
      else {
            sqlsrv_free_stmt($result_update);
            sqlsrv_close($conn);
            echo  '<script type="text/javascript">alert("Questão Atualizada !");</script>';
            echo  '<script type="text/javascript"> window.location.href = "questoesMonitoria.php" </script>';
        }


?>