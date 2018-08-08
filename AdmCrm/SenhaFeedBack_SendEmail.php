<?php include '..\AdmCrm\connectionADM.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://dd42150:8087/CESUDESK/NewCesudesk/index.php"  </script>'; 
}

 
 $_SESSION['TEMPOSESSION'] = date('H:i:s');


if  (($_SESSION['ACESSO'] > 2) or ($_SESSION['ACESSO'] == null ))   {
 // Ação a ser executada: mata o script e manda uma mensagem
 echo  '<script type="text/javascript"> window.location.href = "index.php"  </script>';
}


 if ( ! isset ($_GET['ID_MATRICULA'])){
     echo ('Operação Invalida');
     echo  '<script type="text/javascript"> window.location.href = "index.php"  </script>';
 }else{
  $ID_MATRICULA = $_GET['ID_MATRICULA'];
 }

 $ID_MATRICULA_LOGIN = $_SESSION['MATRICULA'];



$squilaEmailDesti = "SELECT tc.EMAIL
                       FROM [DB_CRM_REPORT].[dbo].[tb_crm_colaborador] tc
                      WHERE tc.ID_MATRICULA = {$ID_MATRICULA}";

$result_squilaEmailDest = sqlsrv_prepare($conn, $squilaEmailDesti);
sqlsrv_execute($result_squilaEmailDest);

$vetorSQLEmailDesti = sqlsrv_fetch_array($result_squilaEmailDest);
$EmailDesti = $vetorSQLEmailDesti['EMAIL'];




$squilaEmailCopy= "SELECT tc.EMAIL
                     FROM [DB_CRM_REPORT].[dbo].[tb_crm_colaborador] tc
                    WHERE tc.ID_MATRICULA = {$ID_MATRICULA_LOGIN}";


$result_squilaEmailCopy = sqlsrv_prepare($conn, $squilaEmailCopy);
sqlsrv_execute($result_squilaEmailCopy);

$vetorSQLEmailCopy = sqlsrv_fetch_array($result_squilaEmailCopy);
$EmailCopy = $vetorSQLEmailCopy['EMAIL'];


$ExecFunSQL = "   USE [DB_CRM_REPORT]
                  EXEC  [dbo].[P_ENVIO_FEEDBACK_QUALIDADE]
                        @MATRICULA = {$ID_MATRICULA},
                        @EMAIL_DEST = '{$EmailDesti}',
                        @EMAIL_COPIA = '{$EmailCopy}'  ";

$result_ExecFunSQL = sqlsrv_prepare($conn, $ExecFunSQL);
sqlsrv_execute($result_ExecFunSQL);


  echo  '<script type="text/javascript">alert("E-mail Enviado !");</script>';
  echo  '<script type="text/javascript"> window.location.href = "ColaboradorSenhaFeedBack.php" </script>';