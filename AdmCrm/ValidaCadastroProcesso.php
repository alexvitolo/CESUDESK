<?php include '..\AdmCrm\connectionADM.php'; 
session_start();


$NOME = $_POST["NOME"]; 
$MODALIDADE = $_POST["MODALIDADE"]; 
$ATIVO = $_POST["ATIVO"]; 
$DT_INI = $_POST["DT_INI"]; 
$DT_FIM = $_POST["DT_FIM"]; 



$insertSquila = " INSERT INTO tb_crm_processo
                              (NOME
                              ,MODALIDADE
                              ,ATIVO
                              ,DATA_INICIO
                              ,DATA_FIM
                               )
                        
                       VALUES
                        ('{$NOME}'
                         ,'{$MODALIDADE}'
                         ,'{$ATIVO}'
                         ,'{$DT_INI}'
                         ,'{$DT_FIM}'
                         )";

                   
 $result_insert = sqlsrv_query($conn, $insertSquila);

      if (!($result_insert)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
      }   
      else {
            sqlsrv_free_stmt($result_insert);
            sqlsrv_close($conn);
            echo  '<script type="text/javascript">alert("Processo cadastrada !");</script>';
            echo  '<script type="text/javascript"> window.location.href = "processo.php" </script>';
        }