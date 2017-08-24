<?php include '..\AdmCrm\connectionADM.php'; 
session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
 // Ação a ser executada: mata o script e manda uma mensagem
echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; }


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