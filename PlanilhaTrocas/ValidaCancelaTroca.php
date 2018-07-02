<?php include '..\PlanilhaTrocas\connection.php';
session_start();
setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");
date_default_timezone_set('America/Sao_Paulo');

$dataValida = date("Y-m-d" ,strtotime("+2 days")); // variavel criada para definir a data minima para selecionar no campo date 

if($_SESSION["USUARIO"] =='alexandre.vitolo'){  //validar usuario para realizar trocas qualquer dia
$dataValida = date("Y-m-d" ,strtotime("-1 year"));
}

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://dd42150:8087/CESUDESK/NewCesudesk/index.php"  </script>'; 
}


$ID_TROCA = $_POST["ID_TROCA"];




$updateSquila = " UPDATE DB_CRM_REPORT.dbo.tb_crm_trocas_new
                     SET TP_STATUS = 'CANCELADO'
                   WHERE ID_TROCA =  {$ID_TROCA} ";


 $result_update = sqlsrv_query($conn, $updateSquila);

      if (!($result_update)) {
             echo ("Falha no cancelamento da troca");
             print_r(sqlsrv_errors());
      }   
      else {
            sqlsrv_free_stmt($result_update);
            sqlsrv_close($conn);
            echo  '<script type="text/javascript">alert("Troca Cancelada !");</script>';
            echo  '<script type="text/javascript"> window.location.href = "CancelaTroca.php" </script>';
        }




?>