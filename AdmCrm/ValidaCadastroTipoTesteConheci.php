<?php include '..\AdmCrm\connectionADM.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; 
}

if ( (date('h:i:s')) >=  (date('h:i:s', strtotime('+15 minute', strtotime($_SESSION['TEMPOSESSION'])))) & ($_SESSION['ACESSO'] <> 1 ) ){
     // Ação a ser executada: encerra a session depois de 15 min
   echo  '<script type="text/javascript"> alert("Tempo de Sessão Expirada"); window.location.href = "http://d42150:8087/cesudesk/AdmCrm/login.php"  </script>'; 
   session_destroy();
 }
 
 $_SESSION['TEMPOSESSION'] = date('h:i:s');






$ID_GRUPO = $_POST['ID_GRUPO'];
$BO_STATUS = $_POST['BO_STATUS'];
$ID_PROCESSO = $_POST['ID_PROCESSO']; 
$DESC_CONHE = $_POST['DESC_CONHE'];



//correção string BD

$DESC_CONHE = str_replace("'", '"', $DESC_CONHE);


// INSERT TABELA ITENS QUESTOES
 

     $insertSquilaConhe = "INSERT INTO tb_ava_conhecimento
                                     ( ID_PROCESSO 
                                            ,DESCRICAO 
                                            ,BO_STATUS 
                                            ,ID_GRUPO )
                              VALUES 
                               ({$ID_PROCESSO}
                                      ,'{$DESC_CONHE}'
                                      ,'{$BO_STATUS}'
                                      ,{$ID_GRUPO}
                                       )";

      $result_InsertSquilaConhe = sqlsrv_query($conn, $insertSquilaConhe); 
 


      if (!($result_InsertSquilaConhe)) {
             // echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
             sqlsrv_close($conn);
             echo  '<script type="text/javascript"> alert("Falha na inclusão do registro"); window.location.href = "tipoTesteConhecimento.php" </script>';
      }   
      else {
                 sqlsrv_free_stmt($result_InsertSquilaConhe);
        }


 echo  '<script type="text/javascript">alert("Tipo Teste Cadastrado !");</script>';
 echo  '<script type="text/javascript"> window.location.href = "tipoTesteConhecimento.php" </script>';




?>

