<?php include '..\AdmCrm\connectionADM.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
 // Ação a ser executada: mata o script e manda uma mensagem
echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; }



$ID_CONHECIMENTO = $_POST['ID_CONHECIMENTO'];
$BO_STATUS = $_POST['BO_STATUS'];
$DESC_QUESTAO = $_POST['DESC_QUESTAO']; 


$ALTERNATIVA_A = $_POST['ALTERNATIVA_A']; 
$ALTERNATIVA_B = $_POST['ALTERNATIVA_B']; 
$ALTERNATIVA_C = $_POST['ALTERNATIVA_C']; 
$ALTERNATIVA_D = $_POST['ALTERNATIVA_D']; 
$ALTERNATIVA_E = $_POST['ALTERNATIVA_E']; 


//correção string BD

$DESC_QUESTAO = str_replace("'", '"', $DESC_QUESTAO);


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

