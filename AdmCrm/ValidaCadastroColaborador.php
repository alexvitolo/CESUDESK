<?php include '..\AdmCrm\connectionADM.php'; 
session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://d42150:8087/CESUDESK/NewCesudesk/index.php"  </script>'; 
}




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



if ($nivelCargo == 'null') {
        $validaNivelCargo = null;
      }else{
        $validaNivelCargo = "'{$nivelCargo}'";
      }


$insertSquila = " INSERT INTO tb_crm_colaborador
                              (ID_MATRICULA
                              ,LOGIN_REDE
                              ,NOME
                              ,STATUS_COLABORADOR
                              ,ID_COLABORADOR_GESTOR
                              ,ID_CARGO
                              ,NIVEL_CARGO
                              ,ID_GRUPO
                              ,EMAIL
                              ,TELEFONE
                              ,DT_ADMISSAO
                              ,DT_NASCIMENTO
                              ,LOGIN_TELEFONIA
                              ,CODIGO_PORTAL
                              ,ID_HORARIO)
                        
                       VALUES
                        ('{$MATRICULA}'
                        ,'{$loginRede}'
                        ,'{$NOME}'
                        ,'{$STATUS}'
                        ,{$supervisor}
                        ,'{$cargo}'
                        ,".$validaNivelCargo."
                        ,'{$grupo}'
                        ,'{$email}'
                        ,'{$telefone}'
                        ,'{$dtAdmissao}'
                        ,'{$dtNascimento}'
                        ,'{$loginTelefonia}'
                        ,'{$codPortal}'
                        ,'{$horario}' )";

                   
 $result_insert = sqlsrv_query($conn, $insertSquila);

      if (!($result_insert)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
      }   
      elseif  ($_SESSION['ACESSO'] == 1 ) {
            sqlsrv_free_stmt($result_insert);
            sqlsrv_close($conn);
            echo  '<script type="text/javascript">alert("Colaborador cadastrado !");</script>';
            echo  '<script type="text/javascript"> window.location.href = "Colaboradores.php" </script>';
        }

        else  {
          // Ação a ser executada: mata o script e manda uma mensagem
          sqlsrv_free_stmt($result_insert);
          sqlsrv_close($conn);
          echo  '<script type="text/javascript">alert("Colaborador cadastrado !");</script>';
          echo  '<script type="text/javascript"> window.location.href = "index.php"  </script>';
         }