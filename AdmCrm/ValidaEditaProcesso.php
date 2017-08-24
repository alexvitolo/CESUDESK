<?php include '..\AdmCrm\connectionADM.php'; 
session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
 // Ação a ser executada: mata o script e manda uma mensagem
echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; }

date_default_timezone_set('America/Sao_Paulo');

$ID = $_POST["ID"]; 
$NOME = $_POST["NOME"]; 
$MODALIDADE = $_POST["MODALIDADE"]; 
$ATIVO = $_POST["ATIVO"]; 
$DT_INI = $_POST["DT_INI"]; 
$DT_FIM = $_POST["DT_FIM"]; 




$updateSquila = " UPDATE tb_crm_processo
                     SET NOME = '{$NOME}'
                        ,MODALIDADE = '{$MODALIDADE}'
                        ,ATIVO = '{$ATIVO}'
                        ,DATA_INICIO = '{$DT_INI}'
                        ,DATA_FIM = '{$DT_FIM}'
      
                   WHERE ID = '{$ID}' ";

 $result_update = sqlsrv_query($conn, $updateSquila);

      if (!($result_update)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
      }   
      else {
            sqlsrv_free_stmt($result_update);
            sqlsrv_close($conn);
            echo  '<script type="text/javascript">alert("Processo Atualizado !");</script>';
            echo  '<script type="text/javascript"> window.location.href = "processo.php" </script>';
        }

?>
