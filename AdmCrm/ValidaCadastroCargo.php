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





$DESCRI = $_POST["DESCRI"];
$BOHORARIO = $_POST["BOHORARIO"]; 
$BOGESTOR = $_POST["BOGESTOR"]; 



$insertSquila = " INSERT INTO tb_crm_cargo
                              (DESCRICAO
                              ,BO_TROCA_HORARIO
                              ,BO_GESTOR)
                        
                       VALUES
                        ('{$DESCRI}'
                        ,'{$BOHORARIO}'
                        ,'{$BOGESTOR}' )";

                   
 $result_insert = sqlsrv_query($conn, $insertSquila);

      if (!($result_insert)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
      }   
      else {
            sqlsrv_free_stmt($result_insert);
            sqlsrv_close($conn);
            echo  '<script type="text/javascript">alert("Cargo cadastrado !");</script>';
            echo  '<script type="text/javascript"> window.location.href = "cargo.php" </script>';
        }