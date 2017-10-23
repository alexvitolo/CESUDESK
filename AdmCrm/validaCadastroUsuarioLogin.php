<?php include '..\AdmCrm\connectionADM.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
 // Ação a ser executada: mata o script e manda uma mensagem
echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; }



$NOME = $_POST['NOME'];
$USUARIO = $_POST['USUARIO'];
$SENHA = $_POST['SENHA']; 
$ACESSO_ADM = $_POST['ACESSO_ADM'];



// INSERT TABELA ITENS QUESTOES
 

     $insertSquilaUsuario = "INSERT INTO tb_crm_login
                                        (NOME
                                        ,USUARIO
                                        ,SENHA_USUARIO
                                        ,ACESSO_ADM
                                        ,BO_ATIVO)
                                  VALUES
                                        ('{$NOME}'
                                        ,'{$USUARIO}'
                                        ,'{$SENHA}'
                                        ,'{$ACESSO_ADM}'
                                        ,'S' )  ";

      $result_InsertSquilaUsuario = sqlsrv_query($conn, $insertSquilaUsuario); 
 


      if (!($result_InsertSquilaUsuario)) {
             // echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
             sqlsrv_close($conn);
             echo  '<script type="text/javascript"> alert("Falha na inclusão do registro"); window.location.href = "usuarioLogin.php" </script>';
      }   
      else {
                 sqlsrv_free_stmt($result_InsertSquilaUsuario);
                   echo  '<script type="text/javascript">alert("Usuário Cadastrado !");</script>';
                    echo  '<script type="text/javascript"> window.location.href = "usuarioLogin.php" </script>';
        }

    

?>

