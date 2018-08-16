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




$ID_MATRICULA = $_POST['ID_MATRICULA'];


  $sqlDadoConsultor ="SELECT tc.ID_MATRICULA
                                ,tc.ID_COLABORADOR
                                ,tc.NOME
                                ,tc.DT_NASCIMENTO
                                ,tc.EMAIL
                                ,tr.DESCRICAO as NOME_REGIAO
                                ,tg.DESCRICAO AS NOME_GRUPO
                                ,(SELECT NOME FROM tb_crm_colaborador WHERE tc.ID_COLABORADOR_GESTOR = ID_COLABORADOR ) NOME_GESTOR
                                 ,CASE WHEN tc.ID_GRUPO in (1,2,3,4,5,17) THEN 1 
                                       WHEN tc.ID_GRUPO in (37,38) THEN 8 ELSE tc.ID_GRUPO END ID_GRUPO
                                ,tconhe.ID_CONHECIMENTO
                                ,tconhe.DESCRICAO as NOME_TESTE
                        FROM tb_crm_colaborador tc
                INNER JOIN tb_crm_grupo tg ON tc.ID_GRUPO  = tg.ID_GRUPO
                INNER JOIN tb_crm_regiao tr ON tr.ID_REGIAO = tg.ID_REGIAO
                INNER JOIN tb_ava_conhecimento tconhe  ON CASE 
                                                    WHEN tc.ID_GRUPO IN (1,2,3,4,5,17) THEN 1
                                                    WHEN tc.ID_GRUPO IN (37,38) THEN 8 ELSE tc.ID_GRUPO END  = tconhe.ID_GRUPO
                       WHERE tc.ID_MATRICULA ='{$ID_MATRICULA}'
                         AND tc.STATUS_COLABORADOR = 'ATIVO' "; 

          $stmtValidaConsultor = sqlsrv_prepare($conn, $sqlDadoConsultor);
          sqlsrv_execute($stmtValidaConsultor);
          $resultadoSQLConsultor = sqlsrv_fetch_array($stmtValidaConsultor);

          if ( $resultadoSQLConsultor == 0) {
              echo  '<script type="text/javascript">alert("Erro!");</script>';
              echo  '<script type="text/javascript"> window.location.href = "testeConhecimentoCadastrado.php" </script>';  //veerificar URL
              //header('location: PaginaIni.php');   não funciona
          }
$ID_COLABORADOR_CONSULTOR = $resultadoSQLConsultor['ID_COLABORADOR'];
$NOME_GESTOR =  $resultadoSQLConsultor['NOME_GESTOR'];
$NOME_CONSULTOR =  $resultadoSQLConsultor['NOME'];
$NOME_GRUPO =  $resultadoSQLConsultor['NOME_GRUPO'];
$ID_GRUPO = $resultadoSQLConsultor['ID_GRUPO']; 
$NOME_REGIAO = $resultadoSQLConsultor['NOME_REGIAO']; 
$DT_NASCIMENTO = $resultadoSQLConsultor['DT_NASCIMENTO'];
$EMAIL = $resultadoSQLConsultor['EMAIL'];

$ID_CONHECIMENTO = $resultadoSQLConsultor['ID_CONHECIMENTO'];
$NOME_TESTE = $resultadoSQLConsultor['NOME_TESTE'];


// SQL QUESTOES
   $sqlQuestoes = "SELECT tq.ID_QUESTAO
                                ,tq.DESCRICAO
                        FROM tb_ava_questoes_conhecimento tq
                       WHERE tq.ID_CONHECIMENTO ={$ID_CONHECIMENTO}
                         AND tq.BO_ATIVO = 'S' ";

$result_Questoes = sqlsrv_prepare($conn, $sqlQuestoes);
sqlsrv_execute($result_Questoes);




?>

<html>
<head>
    <title>Teste Conhecimento</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto'>
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->  <!-- pagina  off bloqueada -->
<link rel="stylesheet" href="..\PlanilhaTrocas\font-awesome\css\font-awesome.css">
<style>
html,body,h1,h2,h3,h4,h5,h6 {font-family: "Roboto", sans-serif}
</style>
<body class="w3-light-grey">

<!-- Page Container -->
<div class="w3-content w3-margin-top" style="max-width:1400px;">

  <!-- The Grid -->
  <div class="w3-row-padding">
  
    <!-- Left Column -->
   
    <!-- Right Column -->
    <div class="w3-twothird-center">
    
      <div class="w3-container w3-card-2 w3-white w3-margin-bottom">
        <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-group fa-fw w3-margin-right w3-xxlarge w3-text-teal"></i>Matrícula :<?php echo $ID_MATRICULA ?></h2>
        <div class="w3-container">
          <h5 class="w3-opacity"><b>Informações</b></h5>
          <h6 class="w3-text"><i class="fa fa-user fa-fw w3-margin-right"></i>Nome Consultor: <span class="w3-tag w3-teal w3-round"> <?php echo $NOME_CONSULTOR ?> </span></h6>
          <h6 class="w3-text"><i class="fa fa-user-circle fa-fw w3-margin-right"></i>Nome Gestor: <span class="w3-tag w3-teal w3-round"> <?php echo $NOME_GESTOR ?> </span></h6>
          <h6 class="w3-text"><i class="fa fa-line-chart fa-fw w3-margin-right"></i>Grupo: <span class="w3-tag w3-teal w3-round"> <?php echo $NOME_GRUPO ?> </span></h6>
          <h6 class="w3-text"><i class="fa fa-map-pin fa-fw w3-margin-right"></i>Região: <span class="w3-tag w3-teal w3-round"> <?php echo $NOME_REGIAO ?> </span></h6>
          <h6 class="w3-text"><i class="fa fa-calendar fa-fw w3-margin-right"></i>Data Nascimento: <span class="w3-tag w3-teal w3-round"> <?php echo date_format($DT_NASCIMENTO,"d/m/Y") ?> </span></h6>
          <h6 class="w3-text"><i class="fa fa-share fa-fw w3-margin-right"></i>Email: <span class="w3-tag w3-teal w3-round"> <?php echo $EMAIL ?> </span></h6>
          <hr>
        </div>
      </div>

    <!-- End Right Column -->
    </div>
    
  <!-- End Grid -->
  </div>
  
  <!-- End Page Container -->
</div>

<footer class="w3-container w3-teal w3-center w3-margin-top">
  <p>Início do Teste</p>
</footer>

</body>

<style type='text/css'>
#wrapper {
	
	width:950px;
	 height:auto;
	 padding: 13px;
	 margin-right:auto;
	 margin-left:auto;
	 background-color:#fff;
	
}
</style>
</head>

<body bgcolor='#e1e1e1'>

<br>
<div id='wrapper'>

<center><font face='verdana' size='6'> <b> <?php echo $NOME_TESTE ?> </b></font></center>
<br />
<br />
<br /><br />


<form name="Form" method="post" id="formulario" action="ValidaAvaliacaoConhecimento.php" >	

 
	<ol style="font-size: 15pt">
    
   <?php while ($row = sqlsrv_fetch_array($result_Questoes)){ ?>
                     

    	<li>
        <h3> <?php echo $row['DESCRICAO'] ?> </h3><br>

        <?php

               // SQL QUESTOES RESPOSTAS
                   $sqlQuestoresResp = "SELECT talt.ID_RESPOSTA
                                                ,talt.ID_QUESTAO
                                                ,talt.DESC_RESPOSTA
                                                ,talt.BO_VERDADEIRO
        
                                          FROM tb_ava_questoes_conhecimento_alt talt
                                         WHERE talt.ID_QUESTAO ={$row['ID_QUESTAO']}
                                ";

                $result_QuestoesResp = sqlsrv_prepare($conn, $sqlQuestoresResp);
                sqlsrv_execute($result_QuestoesResp);

        ?>

            <?php while ($row2 = sqlsrv_fetch_array($result_QuestoesResp)){ ?>
        
        <div>
        <input type='radio' name="vetorquestaorepostas[<?php echo $row['ID_QUESTAO']?>]"  value='<?php echo $row2['ID_RESPOSTA']?>'>
        <label for='answer'> <?php echo $row2['DESC_RESPOSTA'] ?></label>
        </div>
        <br>
        

            <?php 
                 }  
             ?>
        
      <?php }  ?>

    </ol>
     <button  type='submit' value=""  name=""> Confirmar </button>
     <input type="hidden" name="ID_CONHECIMENTO" value="<?php echo $ID_CONHECIMENTO?>">
     <input type="hidden" name="ID_COLABORADOR_CONSULTOR" value="<?php echo $ID_COLABORADOR_CONSULTOR?>">
    
</form>



</div><!--end of wrapper div -->

</body>
</html>