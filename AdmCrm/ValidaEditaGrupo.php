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



$ID_GRUPO = $_POST["ID_GRUPO"];

$DESCRI = $_POST["DESCRI"];
$regiao = $_POST["regiao"];
$unidade = $_POST["unidade"];


$insertSquila = " UPDATE [DB_CRM_REPORT].[dbo].[tb_crm_grupo]
                     SET DESCRICAO = '{$DESCRI}' ,
                         ID_REGIAO = '{$regiao}' ,
                         ID_UNIDADE = {$unidade}
                     WHERE ID_GRUPO = {$ID_GRUPO} ";

                   
 $result_insert = sqlsrv_query($conn, $insertSquila);

      if (!($result_insert)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
      }   
      else {
            sqlsrv_free_stmt($result_insert);
            sqlsrv_close($conn);
            echo  '<script type="text/javascript">alert("Grupo Atualizado !");</script>';
            echo  '<script type="text/javascript"> window.location.href = "grupo.php" </script>';
        }