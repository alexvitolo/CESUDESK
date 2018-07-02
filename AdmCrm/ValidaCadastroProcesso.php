<?php include '..\AdmCrm\connectionADM.php'; 
session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://dd42150:8087/CESUDESK/NewCesudesk/index.php"  </script>'; 
}

if ( (date('H:i:s')) >=  (date('H:i:s', strtotime('+55 minute', strtotime($_SESSION['TEMPOSESSION'])))) & ($_SESSION['ACESSO'] <> 1 ) ){
     // Ação a ser executada: encerra a session depois de 15 min
   echo  '<script type="text/javascript"> alert("Tempo de Sessão Expirada"); window.location.href = "http://dd42150:8087/CESUDESK/NewCesudesk/main.php"  </script>'; 
   session_destroy();
 }
 
 $_SESSION['TEMPOSESSION'] = date('H:i:s');





$NOME = $_POST["NOME"]; 
$MODALIDADE = $_POST["MODALIDADE"]; 
$ATIVO = $_POST["ATIVO"]; 
$DT_INI = $_POST["DT_INI"]; 
$DT_FIM = $_POST["DT_FIM"]; 



$insertSquila = " INSERT INTO tb_crm_processo
                              (NOME
                              ,MODALIDADE
                              ,ATIVO
                              ,DATA_INICIO
                              ,DATA_FIM
                               )
                        
                       VALUES
                        ('{$NOME}'
                         ,'{$MODALIDADE}'
                         ,'{$ATIVO}'
                         ,'{$DT_INI}'
                         ,'{$DT_FIM}'
                         )";

                   
 $result_insert = sqlsrv_query($conn, $insertSquila);

      if (!($result_insert)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
      }   
      else {
            sqlsrv_free_stmt($result_insert);
            sqlsrv_close($conn);
            echo  '<script type="text/javascript">alert("Processo cadastrada !");</script>';
            echo  '<script type="text/javascript"> window.location.href = "processo.php" </script>';
        }