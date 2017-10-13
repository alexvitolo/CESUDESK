<?php include '..\AdmCrm\connectionADM.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
 // Ação a ser executada: mata o script e manda uma mensagem
echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; }


 $ID_CONHECIMENTO =  $_POST['ID_CONHECIMENTO'];
 $ID_COLABORADOR_CONSULTOR =  $_POST['ID_COLABORADOR_CONSULTOR'];

 $NotaFinal = 0;
$QUEM_REALIZOU = $_SESSION["USUARIO"];


$insertTestePai = "INSERT INTO tb_ava_teste_conhecimento
                                       (ID_CONHECIMENTO
                                       ,ID_COLABORADOR
                                       ,QUEM_REALIZOU
                                         )
                                 VALUES
                                       ({$ID_CONHECIMENTO}
                                       ,{$ID_COLABORADOR_CONSULTOR}
                                       ,'{$QUEM_REALIZOU}'
                                        ) ";

 $result_insertTestePAi = sqlsrv_query($conn, $insertTestePai);

      if (!($result_insertTestePAi)) {
             // echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
             sqlsrv_close($conn);
             echo  '<script type="text/javascript"> alert("Falha na inclusão do registro"); window.location.href = "testeconhecimento.php" </script>';
      }   
      else {
            sqlsrv_free_stmt($result_insertTestePAi);
        }



//INSERT TABELA PESQUISA

foreach( $_POST['vetorquestaorepostas'] as $key => $value ){ 
      $QUESTAO = $key; // id_questao
      $REPOSTA = $value; // id_resp


       $sqlCalculaNota ="SELECT tq.ID_RESPOSTA
                                   ,tq.BO_VERDADEIRO
                           FROM tb_ava_questoes_conhecimento_alt tq
                          WHERE tq.ID_RESPOSTA ={$REPOSTA}
                         ";

          $stmtNota = sqlsrv_prepare($conn, $sqlCalculaNota);
          sqlsrv_execute($stmtNota);
          $resultadoNota = sqlsrv_fetch_array($stmtNota);

          if ($resultadoNota['BO_VERDADEIRO'] == 'S'){
          	$NotaFinal = $NotaFinal + 1  ;
          }

$insertSquilaRespostas = " INSERT INTO tb_ava_teste_conhecimento_resp
                                       (ID_TESTE
                                       ,ID_QUESTAO
                                       ,ID_RESPOSTA
                                       )
                                 VALUES
                                       ((SELECT TOP 1 ID_TESTE FROM tb_ava_teste_conhecimento ORDER BY 1 DESC)
                                       ,{$QUESTAO}
                                       ,{$REPOSTA}
                                      )";
                            
                   
 $result_insertRespostas = sqlsrv_query($conn, $insertSquilaRespostas);

      if (!($result_insertRespostas)) {
             // echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
             sqlsrv_close($conn);
             echo  '<script type="text/javascript"> alert("Falha na inclusão do registro"); window.location.href = "testeconhecimento.php" </script>';
      }   
      else {
            sqlsrv_free_stmt($result_insertRespostas);
        }


} 


$updateNotaFinal = " UPDATE tb_ava_teste_conhecimento
                              SET 
                            NOTA_FINAL = '{$NotaFinal}'
                                 
                          WHERE  ID_TESTE = (SELECT TOP 1 ttes.ID_TESTE FROM tb_ava_teste_conhecimento_resp ttes ORDER BY 1 DESC) ";

  $result_updateNotaFinal = sqlsrv_query($conn, $updateNotaFinal);
   sqlsrv_free_stmt($result_updateNotaFinal);



 echo  '<script type="text/javascript">alert("Formulário Cadastrado Com Sucesso");</script>';
 // echo  '<script type="text/javascript"> window.location.href = "testeconhecimento.php" </script>';


?>


<html>
<head>

<style type="text/css">
#wrapper {
    
    width:950px;
     height:350;
     padding: 13px;
     margin-right:auto;
     margin-left:auto;
     background-color:#fff;
}
</style>
<body bgcolor="#e1e1e1">
<div id="wrapper">
<center><font face="Berlin Sans FB" size="5">Resultado Teste Avaliação</font></center>
<br />
<br />

<?php
    $NotaFinal = $NotaFinal*10;
    echo "<center><font face='Berlin Sans FB' size='8'>Pontuação: <br> $NotaFinal/100</font></center>";
?>
<br>

</div><!-- end of wrapper div -->
<center><font face="Berlin Sans FB" size="5">Por Favor Aguarde</font></center>
</body>
</head>
</html>


    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>

    <!--common script for all pages-->
    <script src="assets/js/common-scripts.js"></script>


  <script src="assets/js/form-component.js"></script>   
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <?php

     header("refresh: 8;testeconhecimento.php");

  ?>

  <div class="container">
  <h2>Carregando...</h2>
  <div class="progress">
    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:40%">
      40%

                <!-- jQuery Script auto load -->
            <script type="text/javascript">
              var i = 0;
              function makeProgress(){
                if(i < 100){
                  i = i + 2;
                  $(".progress-bar").css("width", i + "%").text(i + " %");
                }
                // Wait for sometime before running this script again
                setTimeout("makeProgress()", 100);
              }
              makeProgress();
            </script>


    </div>
  </div>
</div>




<script type="text/javascript">

  document.onkeydown = function () { 
           switch (event.keyCode) {
             case 116 :  
                event.returnValue = false;
                event.keyCode = 0;           
                return false;             
              case 82 : 
                if (event.ctrlKey) {  
                   event.returnValue = false;
                  event.keyCode = 0;             
                  return false;
           }
         }
     } 


</script>