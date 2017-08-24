<?php include '..\AdmCrm\connectionADM.php'; 
session_start();


$DESCRI = $_POST["DESCRI"]; 



$insertSquila = " INSERT INTO tb_crm_regiao
                              (DESCRICAO
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
            echo  '<script type="text/javascript">alert("Região cadastrada !");</script>';
            echo  '<script type="text/javascript"> window.location.href = "regiao.php" </script>';
        }