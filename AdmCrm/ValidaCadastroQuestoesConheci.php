<?php include '..\AdmCrm\connectionADM.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
 // Ação a ser executada: mata o script e manda uma mensagem
echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; }



$ID_CONHECIMENTO = $_POST['ID_CONHECIMENTO'];
$BO_STATUS = $_POST['BO_STATUS'];
$DESC_QUESTAO = $_POST['DESC_QUESTAO']; 
$DIFICULDADE = $_POST['DIFICULDADE'];

$NUM_ALT = $_POST['NUM_ALT']; 



//correção string BD

$DESC_QUESTAO = str_replace("'", '"', $DESC_QUESTAO);


// INSERT TABELA ITENS QUESTOES
 

     $insertSquilaQuestao = "INSERT INTO tb_ava_questoes_conhecimento
                                     (ID_CONHECIMENTO
                                     ,DESCRICAO
                                     ,BO_ATIVO
                                     ,DIFICULDADE)
                              VALUES 
                                    ({$ID_CONHECIMENTO}
                                    ,'{$DESC_QUESTAO}'
                                    ,'{$BO_STATUS}'
                                    ,{$DIFICULDADE})  ";

      $result_InsertSquilaQuestao = sqlsrv_query($conn, $insertSquilaQuestao); 
 


      if (!($result_InsertSquilaQuestao)) {
             // echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
             sqlsrv_close($conn);
             echo  '<script type="text/javascript"> alert("Falha na inclusão do registro"); window.location.href = "questoesConhecimento.php" </script>';
      }   
      else {
                 sqlsrv_free_stmt($result_InsertSquilaQuestao);
        }


     
 for ($aux = 1; $aux <= $NUM_ALT; $aux++ ) {      

            if(isset($_POST['RESP_ALTERNATIVA'. $aux]))
            {
                $sqldicas2 =  ",'S'  ";
            }
            else
            {
                $sqldicas2 = ",'N' ";
            }


            $DESC_RESPOSTA = $_POST['ALTERNATIVA'. $aux];
            $DESC_RESPOSTA = str_replace("'", '"', $DESC_RESPOSTA);
     
     $insertSquilaQuestaoAlt = "INSERT INTO tb_ava_questoes_conhecimento_alt
                                     (ID_QUESTAO
                                     ,DESC_RESPOSTA
                                     ,BO_VERDADEIRO
                                     )
                              VALUES 
                                    ( (SELECT TOP 1 ID_QUESTAO FROM tb_ava_questoes_conhecimento ORDER BY 1 DESC )
                                    ,'{$DESC_RESPOSTA}'
                                    ".$sqldicas2."
                                     )  ";

      $result_InsertSquilaQuestaoAlt = sqlsrv_query($conn, $insertSquilaQuestaoAlt);
     
           if (!($result_InsertSquilaQuestaoAlt)) {
                  echo ("Falha na inclusão do registro");
                  print_r(sqlsrv_errors());
           }   
 
  }   
      echo  '<script type="text/javascript">alert("Questão Cadastrada !");</script>';
      echo  '<script type="text/javascript"> window.location.href = "questoesConhecimento.php" </script>';
     



?>

