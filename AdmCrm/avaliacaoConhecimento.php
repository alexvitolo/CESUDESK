<?php include '..\AdmCrm\connectionADM.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
 // Ação a ser executada: mata o script e manda uma mensagem
echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; }

$ID_MATRICULA_CONSULTOR = $_POST['ID_MATRICULA_CONSULTOR'];



  $sqlDadosConsultor ="SELECT tc.ID_MATRICULA
                                ,tc.ID_COLABORADOR
                                ,tg.ID_GRUPO
                                ,tc.NOME
                                ,tg.DESCRICAO AS NOME_GRUPO
                                ,(SELECT NOME FROM tb_crm_colaborador WHERE tc.ID_COLABORADOR_GESTOR = ID_COLABORADOR ) NOME_GESTOR
                        FROM tb_crm_colaborador tc
                INNER JOIN tb_crm_grupo tg ON tg.ID_GRUPO = tc.ID_GRUPO 
                       WHERE tc.ID_MATRICULA ='{$ID_MATRICULA_CONSULTOR}'
                         AND tc.STATUS_COLABORADOR = 'ATIVO' ";

          $stmtValida = sqlsrv_prepare($conn, $sqlDadosConsultor);
          sqlsrv_execute($stmtValida);
          $resultadoSQL = sqlsrv_fetch_array($stmtValida);

          if ( $resultadoSQL == 0) {
              echo  '<script type="text/javascript">alert("Numero de Matricula não Existe");</script>';
              echo  '<script type="text/javascript"> window.location.href = "testeconhecimento.php" </script>';  //veerificar URL
              //header('location: PaginaIni.php');   não funciona
          }
$ID_COLABORADOR_CONSULTOR = $resultadoSQL['ID_COLABORADOR'];
$NOME_GESTOR =  $resultadoSQL['NOME_GESTOR'];
$NOME_CONSULTOR =  $resultadoSQL['NOME'];
$NOME_GRUPO =  $resultadoSQL['NOME_GRUPO'];
$ID_GRUPO = $resultadoSQL['ID_GRUPO'];



// SQL QUESTOES


?>

<html>
<head>
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

<?php


	//Creating random number s
	$rid = rand(1,3);
	echo $rid;
?>

<div id='wrapper'>

<center><font face='Andalus' size='5'>Teste <b>Conhecimento</b></font></center>
<br />
<br />
<br /><br />

<?php

if ($rid == 1){
	
	echo "
<form action='process.php?id=1' method='post' id='quizForm' id='1'>


	<ol>
    
   
    	<li>
        <h3>Pepe moreno ?</h3>
        
        <div>
        <input type='radio' name='answerOne' id='answerOne' value='A' />
        <label for='answerOneA'>A) SIM</label>
        </div>
        
        <div>
        <input type='radio' name='answerOne' id='answerOne' value='B' />
        <label for='answerOneB'>B) NÃO </label>
        </div>
        
        <div>
        <input type='radio' name='answerOne' id='answerOne' value='C' />
        <label for='answerOneC'>C) TALVEZ </label>
        </div>
        </li>
        
     
        <li>
        <h3>What is Youtube?</h3>
        
        <div>
        <input type='radio' name='answerTwo' id='answerTwo' value='A' />
        <label for='answerTwoA'>A) A Search Engine </label>
        </div>
        
        <div>
        <input type='radio' name='answerTwo' id='answerTwo' value='B' />
        <label for='answerTwoB'>B) Video sharing website</label>
        </div>
        
        <div>
        <input type='radio' name='answerTwo' id='answerTwo' value='C' />
        <label for='answerTwoC'>C) None of the above</label>
        </div>
        </li>
        
      
        
         <li>
        <h3>What is your favorite Tutor?</h3>
        
        <div>
        <input type='radio' name='answerThree' id='answerThree' value='A' />
        <label for='answerThreeA'>A)WebTuts </label>
        </div>
        
        <div>
        <input type='radio' name='answerThree' id='answerThree' value='B' />
        <label for='answerThreeB'>B) WebSpider</label>
        </div>
        
        <div>
        <input type='radio' name='answerThree' id='answerThree' value='C' />
        <label for='answerThreeC'>C) WebTutsHD (Choose this to Win $200)</label>
        </div>
        </li>
    </ol>
     <input type='submit' value='Concluir Teste' />
    
</form>";

}

if ($rid == 2){
	
	echo "


<form action='process.php?id=2' method='post' id='quizForm' id='2'>


	<ol>
    
    
    	<li>
        <h3>What Does CSS means</h3>
        
        <div>
        <input type='radio' name='answerOne' id='answerOne' value='A' />
        <label for='answerOneA'>A)Cascading Style Sheet</label>
        </div>
        
        <div>
        <input type='radio' name='answerOne' id='answerOne' value='B' />
        <label for='answerOneB'>B) Hyper turn mark lingo</label>
        </div>
        
        <div>
        <input type='radio' name='answerOne' id='answerOne' value='C' />
        <label for='answerOneC'>C) Happy tissue mahatma life</label>
        </div>
        </li>
        
       
        <li>
        <h3>What is Google?</h3>
        
        <div>
        <input type='radio' name='answerTwo' id='answerTwo' value='A' />
        <label for='answerTwoA'>A)Video sharing website </label>
        </div>
        
        <div>
        <input type='radio' name='answerTwo' id='answerTwo' value='B' />
        <label for='answerTwoB'>B)A Search Engine</label>
        </div>
        
        <div>
        <input type='radio' name='answerTwo' id='answerTwo' value='C' />
        <label for='answerTwoC'>C) None of the above</label>
        </div>
        </li>
        
       
         <li>
        <h3>Who is President of the United States?</h3>
        
        <div>
        <input type='radio' name='answerThree' id='answerThree' value='A' />
        <label for='answerThreeA'>A)No One </label>
        </div>
        
        <div>
        <input type='radio' name='answerThree' id='answerThree' value='B' />
        <label for='answerThreeB'>B)Akon</label>
        </div>
        
        <div>
        <input type='radio' name='answerThree' id='answerThree' value='C' />
        <label for='answerThreeC'>C)Barack Hussain Obama </label>
        </div>
        </li>
    </ol>
     <input type='submit' value='Concluir Teste' />
    
</form>
";
}

if ($rid == 3){
	
	echo "
<form action='process.php?id=3' method='post' id='quizForm' id='3'>


	<ol>
    
   
    	<li>
        <h3>What does PHP stand for  ?</h3>
        
        <div>
        <input type='radio' name='answerOne' id='answerOne' value='A' />
        <label for='answerOneA'>A)Hyper text preprocessor</label>
        </div>
        
        <div>
        <input type='radio' name='answerOne' id='answerOne' value='B' />
        <label for='answerOneB'>B) Hyper turn mark lingo</label>
        </div>
        
        <div>
        <input type='radio' name='answerOne' id='answerOne' value='C' />
        <label for='answerOneC'>C) Happy tissue mahatma life</label>
        </div>
        </li>
        
     
        <li>
        <h3>What will be a Bee in USA Called? </h3>
        
        <div>
        <input type='radio' name='answerTwo' id='answerTwo' value='A' />
        <label for='answerTwoA'>A)USA BSEE </label>
        </div>
        
        <div>
        <input type='radio' name='answerTwo' id='answerTwo' value='B' />
        <label for='answerTwoB'>B) US-B</label>
        </div>
        
        <div>
        <input type='radio' name='answerTwo' id='answerTwo' value='C' />
        <label for='answerTwoC'>C) None of the above</label>
        </div>
        </li>
        
      
        
         <li>
        <h3>Whom do people worship?</h3>
        
        <div>
        <input type='radio' name='answerThree' id='answerThree' value='A' />
        <label for='answerThreeA'>A)Sucka ! </label>
        </div>
        
        <div>
        <input type='radio' name='answerThree' id='answerThree' value='B' />
        <label for='answerThreeB'>B)Dog</label>
        </div>
        
        <div>
        <input type='radio' name='answerThree' id='answerThree' value='C' />
        <label for='answerThreeC'>C)God</label>
        </div>
        </li>
    </ol>
     <input type='submit' value='Concluir Teste' />
    
</form>";

}

?>
</div><!--- end of wrapper div --->

</body>
</html>