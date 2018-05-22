<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://dd42150:8087/CESUDESK/NewCesudesk/index.php"  </script>'; 
}

$ID_LOGIN       = $_SESSION['IDLOGIN'];
$DESC_MODULO  = $_POST ["DESC_MODULO"]; // getdate();
$INF_COMPL   = $_POST ["INF_COMPL"];

$INF_COMPL = str_replace("'", '"', $INF_COMPL);
$DESC_MODULO   = str_replace("'", '"', $DESC_MODULO);



$insertModulo = " INSERT INTO [DB_CRM_CESUDESK].[dbo].[modulo]
                              (desc_modulo
                              ,inf_complementar
                              ,bo_ativo)
                      VALUES
                             ('{$INF_COMPL}'
                             ,'{$DESC_MODULO}'
                             ,0)";

                   
 $result_insert = sqlsrv_query($conn, $insertModulo);

      if (!($result_insert)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());exit;
      }  


  echo  '<script type="text/javascript">alert("Módulo Cadastrado !");</script>';
  echo  '<script type="text/javascript"> window.location.href = "Modulos.php" </script>';

?>
