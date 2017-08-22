<?php include '..\PlanilhaTrocas\connection.php'; 


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
            echo  '<script type="text/javascript">alert("Colaborador cadastrado !");</script>';
            echo  '<script type="text/javascript"> window.location.href = "cargo.php" </script>';
        }