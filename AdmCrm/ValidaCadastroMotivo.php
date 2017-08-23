<?php include '..\AdmCrm\connectionADM.php'; 


$DESCRI = $_POST["DESCRI"]; 




$insertSquila = " INSERT INTO tb_crm_desligamento_motivo
                              (MOTIVO
                               )
                        
                       VALUES
                        ('{$DESCRI}'
                         )";

                   
 $result_insert = sqlsrv_query($conn, $insertSquila);

      if (!($result_insert)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
      }   
      else {
            sqlsrv_free_stmt($result_insert);
            sqlsrv_close($conn);
            echo  '<script type="text/javascript">alert("Motivo cadastrado !");</script>';
            echo  '<script type="text/javascript"> window.location.href = "motivo.php" </script>';
        }