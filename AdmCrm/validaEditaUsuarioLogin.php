<?php include '..\AdmCrm\connectionADM.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://dd42150:8087/CESUDESK/NewCesudesk/index.php"  </script>'; 
}

if ( (date('H:i:s')) >=  (date('H:i:s', strtotime('+55 minute', strtotime($_SESSION['TEMPOSESSION'])))) & ($_SESSION['ACESSO'] <> 1 ) ){
     // Ação a ser executada: encerra a session depois de 15 min
   echo  '<script type="text/javascript"> alert("Tempo de Sessão Expirada"); window.location.href = "http://dd42150:8087/CESUDESK/NewCesudesk/main.php"  </script>'; 
   session_destroy();
 }
 
 $_SESSION['TEMPOSESSION'] = date('H:i:s');





$ID = $_POST['ID'];

$NOME = $_POST['NOME'];
$USUARIO = $_POST['USUARIO'];
$SENHA = $_POST['SENHA']; 
$ACESSO_ADM = $_POST['ACESSO_ADM'];
$ATIVO = $_POST['ATIVO'];
$RECEBE_TRIAGEM = $_POST['RECEBE_TRIAGEM'];

// CONDIÇÃO PARA VALIDAR SE O ACESSO É NULO

 if ($ACESSO_ADM == null){
     $ACESSO_ADM = "null";
 }else{
     $ACESSO_ADM = "{$ACESSO_ADM}";
 }


// INSERT TABELA ITENS QUESTOES
 

     $UpdateSquilaUsuario = "UPDATE tb_crm_login
                                SET NOME = '{$NOME}'
                                   ,USUARIO = '{$USUARIO}'
                                   ,SENHA_USUARIO = '{$SENHA}'
                                   ,ACESSO_ADM = ".$ACESSO_ADM."
                                   ,BO_ATIVO = '{$ATIVO}'
                                   ,RECEBE_TRIAGEM = '{$RECEBE_TRIAGEM}'
                             WHERE ID = {$ID} ";

      $result_UpdateSquilaUsuario = sqlsrv_query($conn, $UpdateSquilaUsuario); 
 

      if (!($result_UpdateSquilaUsuario)) {
             // echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
             sqlsrv_close($conn);
             echo  '<script type="text/javascript"> alert("Falha na inclusão do registro"); window.location.href = "usuarioLogin.php" </script>';
      }   
      else {
                 sqlsrv_free_stmt($result_UpdateSquilaUsuario);
                   echo  '<script type="text/javascript">alert("Usuário Atualizado !");</script>';
                    echo  '<script type="text/javascript"> window.location.href = "usuarioLogin.php" </script>';
        }

    

?>

