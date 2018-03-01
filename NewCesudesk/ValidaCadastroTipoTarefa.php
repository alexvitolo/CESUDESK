<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; 
}


$DESC_TAREFA   = $_POST ["DESC_TAREFA"];

$DESC_TAREFA = str_replace("'", '"', $DESC_TAREFA);


$insertTipoTarefa = " INSERT INTO [DB_CRM_CESUDESK].[dbo].[tipotarefa]
                                  (desc_tipotarefa)
                          VALUES
                                 ('{$DESC_TAREFA}')";

                   
 $result_insert = sqlsrv_query($conn, $insertTipoTarefa);

      if (!($result_insert)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());exit;
      }  


  echo  '<script type="text/javascript">alert("Tarefa Cadastrada !");</script>';
  echo  '<script type="text/javascript"> window.location.href = "TipoTarefa.php" </script>';

?>
