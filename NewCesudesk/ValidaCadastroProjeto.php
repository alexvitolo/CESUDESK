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

$DESC_PROJETO = str_replace("'", '"', $DESC_PROJETO);
$INF_COMPL    = str_replace("'", '"', $INF_COMPL);



$insertProjeto = " INSERT INTO [DB_CRM_CESUDESK].[dbo].[projeto]
                              (desc_projeto
                              ,dh_fechamento
                              ,dt_inicio
                              ,inf_complementar
                              ,tp_statusprojeto)
                      VALUES
                             ('{$DESC_PROJETO}'
                             ,'{$DT_FECHAMENTO}'
                             ,'{$DT_INICIO}'
                             ,'{$INF_COMPL}'
                             ,'Andamento')";

                   
 $result_insert = sqlsrv_query($conn, $insertProjeto);

      if (!($result_insert)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());exit;
      }  


  echo  '<script type="text/javascript">alert("Projeto Cadastrado !");</script>';
  echo  '<script type="text/javascript"> window.location.href = "Projetos.php" </script>';

?>
