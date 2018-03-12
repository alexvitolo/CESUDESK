<?php include '..\AdmCrm\connectionADM.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; 
}

if ( (date('H:i:s')) >=  (date('H:i:s', strtotime('+55 minute', strtotime($_SESSION['TEMPOSESSION'])))) & ($_SESSION['ACESSO'] <> 1 ) ){
     // Ação a ser executada: encerra a session depois de 15 min
   echo  '<script type="text/javascript"> alert("Tempo de Sessão Expirada"); window.location.href = "http://d42150:8087/cesudesk/AdmCrm/login.php"  </script>'; 
   session_destroy();
 }
 
 $_SESSION['TEMPOSESSION'] = date('H:i:s');






$NOME = $_POST['NOME'];
$USUARIO = $_POST['USUARIO'];
$SENHA = $_POST['SENHA']; 
$ACESSO_ADM = $_POST['ACESSO_ADM'];
$ACESSO_GCO = $_POST['ACESSO_GCO'];



// INSERT TABELA ITENS QUESTOES
 

     $insertSquilaUsuario = "INSERT INTO tb_crm_login
                                        (NOME
                                        ,USUARIO
                                        ,SENHA_USUARIO
                                        ,ACESSO_ADM
                                        ,ACESSO_GCO
                                        ,BO_ATIVO)
                                  VALUES
                                        ('{$NOME}'
                                        ,'{$USUARIO}'
                                        ,'{$SENHA}'
                                        ,{$ACESSO_ADM}
                                        ,'{$ACESSO_GCO}'
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

