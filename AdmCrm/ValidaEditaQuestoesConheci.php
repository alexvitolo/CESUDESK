<?php include '..\AdmCrm\connectionADM.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; 
}

if ( (date('h:i:s')) >=  (date('h:i:s', strtotime('+55 minute', strtotime($_SESSION['TEMPOSESSION'])))) & ($_SESSION['ACESSO'] <> 1 ) ){
     // Ação a ser executada: encerra a session depois de 15 min
   echo  '<script type="text/javascript"> alert("Tempo de Sessão Expirada"); window.location.href = "http://d42150:8087/cesudesk/AdmCrm/login.php"  </script>'; 
   session_destroy();
 }
 
 $_SESSION['TEMPOSESSION'] = date('h:i:s');






$ID_QUESTAO = $_POST['ID_QUESTAO'];
$QUANTIDADE_ALT = $_POST['QUANTIDADE_ALT'];

$ID_CONHECIMENTO = $_POST['ID_CONHECIMENTO'];
$BO_STATUS = $_POST['BO_STATUS']; 
$DIFICULDADE = $_POST['DIFICULDADE'];
$DESC_QUESTAO = $_POST['DESC_QUESTAO']; 



//correção string BD

$DESC_QUESTAO = str_replace("'", '"', $DESC_QUESTAO);


// INSERT TABELA ITENS QUESTOES
 

     $UpdateSquilaQuestao = "UPDATE tb_ava_questoes_conhecimento
                             SET    ID_CONHECIMENTO = {$ID_CONHECIMENTO}
                                     ,DESCRICAO = '{$DESC_QUESTAO}'
                                     ,BO_ATIVO = '{$BO_STATUS}'
                                     ,DIFICULDADE = {$DIFICULDADE} 
                                     WHERE ID_QUESTAO = {$ID_QUESTAO} ";

      $result_UpdateSquilaQuestao = sqlsrv_query($conn, $UpdateSquilaQuestao); 
 

      if (!($result_UpdateSquilaQuestao)) {
             // echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
             sqlsrv_close($conn); 
             echo  '<script type="text/javascript"> alert("Falha na inclusão do registro"); window.location.href = "questoesConhecimento.php" </script>';
      }   
      else {
                 sqlsrv_free_stmt($result_UpdateSquilaQuestao);
        }


     
 for ($aux = 1; $aux <= $QUANTIDADE_ALT; $aux++ ) {   

            if(isset($_POST['RESP_ALTERNATIVA'. $aux]))
            {
                $sqldicas =  "'S'";
            }
            else
            {
                $sqldicas = "'N'";
            }

            $ID_RESPOSTA = $_POST['ID_RESPOSTA'. $aux];
            $DESC_RESPOSTA = $_POST['ALTERNATIVA'. $aux];
            $DESC_RESPOSTA = str_replace("'", '"', $DESC_RESPOSTA);
     
     $UpdateSquilaQuestaoAlt = "UPDATE tb_ava_questoes_conhecimento_alt
                                   SET   DESC_RESPOSTA = '{$DESC_RESPOSTA}'
                                         ,BO_VERDADEIRO = ".$sqldicas."
                                 WHERE ID_RESPOSTA = {$ID_RESPOSTA} "; 

      $result_UpdateSquilaQuestaoAlt = sqlsrv_query($conn, $UpdateSquilaQuestaoAlt);
     
           if (!($result_UpdateSquilaQuestaoAlt)) {
                  echo ("Falha na inclusão do registro");
                  print_r(sqlsrv_errors());
           }   
 
  }   
      echo  '<script type="text/javascript">alert("Questão Atualizada !");</script>';
      echo  '<script type="text/javascript"> window.location.href = "questoesConhecimento.php" </script>';
     



?>

