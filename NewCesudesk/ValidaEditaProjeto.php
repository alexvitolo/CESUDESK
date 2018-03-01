<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; 
}

$ID_LOGIN       = $_SESSION['IDLOGIN'];
$DESC_PROJETO   = $_POST ["DESC_PROJETO"]; 
$DT_INICIO      = $_POST ["DT_INICIO"];
$DT_FECHAMENTO  = $_POST ["DT_FECHAMENTO"];
$INF_COMPL      = $_POST ["INF_COMPL"];
$ANDAMENTO      = $_POST ["ANDAMENTO"];
$COD_PROJETO    = $_POST ["COD_PROJETO"];

$DESC_PROJETO = str_replace("'", '"', $DESC_PROJETO);
$INF_COMPL    = str_replace("'", '"', $INF_COMPL);



$updateProjeto = " UPDATE [DB_CRM_CESUDESK].[dbo].[projeto]
                      SET desc_projeto = '{$DESC_PROJETO}'
                          ,dh_fechamento = '{$DT_FECHAMENTO}'
                          ,dt_inicio = '{$DT_INICIO}'
                          ,inf_complementar = '{$INF_COMPL}'
                          ,tp_statusprojeto = '{$ANDAMENTO}' 
                   WHERE cd_projeto = {$COD_PROJETO}";

                   
 $result_Update = sqlsrv_query($conn, $updateProjeto);

      if (!($result_Update)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());exit;
      }  


  echo  '<script type="text/javascript">alert("Projeto Atualizado !");</script>';
  echo  '<script type="text/javascript"> window.location.href = "Projetos.php" </script>';

?>
