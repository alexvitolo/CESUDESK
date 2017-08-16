<?php include '..\PlanilhaTrocas\connection.php'; 

$MATRICULA = $_POST["MATRICULA"]; 
$NOME = $_POST["NOME"]; 
$dtNascimento = $_POST["dtNascimento"]; 
$email = $_POST["email"]; 
$codPortal = $_POST["codPortal"]; 
$loginRede = $_POST["loginRede"]; 
$loginTelefonia = $_POST["loginTelefonia"];
$telefone = $_POST["telefone"];
$STATUS = $_POST["STATUS"]; 
$supervisor = $_POST["supervisor"]; 
$cargo = $_POST["cargo"];
$nivelCargo = $_POST["nivelCargo"]; 
$dtAdmissao = $_POST["dtAdmissao"];
$horario = $_POST["horario"];
$grupo = $_POST["grupo"]; 


$updateSquila = " UPDATE tb_crm_colaborador
                     SET ID_MATRICULA = '{$MATRICULA}'
                        ,LOGIN_REDE = '{$loginRede}'
                        ,NOME = '{$NOME}'
                        ,STATUS_COLABORADOR = '{$STATUS}'
                        ,ID_COLABORADOR_GESTOR = '{$supervisor}'
                        ,ID_CARGO = '{$cargo}'
                        ,NIVEL_CARGO = '{$nivelCargo}'
                        ,ID_GRUPO = '{$grupo}'
                        ,EMAIL = '{$email}'
                        ,TELEFONE = '{$telefone}'
                        ,DT_ADMISSAO = '{$dtAdmissao}'
                        ,DT_NASCIMENTO = '{$dtNascimento}'
                        ,LOGIN_TELEFONIA = '{$loginTelefonia}'
                        ,CODIGO_PORTAL = '{$codPortal}'
                        ,ID_HORARIO = '{$horario}'
                  WHERE  ID_MATRICULA = '{$MATRICULA}'";

 $result_update = sqlsrv_query($conn, $updateSquila);

      if (!($result_update)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
      }   
      else {
            sqlsrv_free_stmt($result_update);
            sqlsrv_close($conn);
            echo  '<script type="text/javascript">alert("Colaborador Atualizado !");</script>';
            echo  '<script type="text/javascript"> window.location.href = "Colaboradores.php" </script>';
        }