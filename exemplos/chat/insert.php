<?php include 'connectchat.php'; 

$uname = $_REQUEST['uname'];
$msg   = $_REQUEST['msg'];

$ChatInsert = "INSERT INTO [DB_CRM_CESUDESK].[dbo].[mensagem_logs] (username, msg, id_usuario) 
                    VALUES ('{$uname}','{$msg}',null) ";

$result_insert = sqlsrv_query($conn, $ChatInsert);

if (!($result_insert)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
      }   
      else{
            sqlsrv_free_stmt($result_insert);
            echo  '<script type="text/javascript">alert("Colaborador cadastrado !");</script>';
        }


$ChatSelect = "SELECT username,msg FROM [DB_CRM_CESUDESK].[dbo].[mensagem_logs] ORDER BY id DESC";

$result1 = sqlsrv_prepare($conn, $ChatSelect);
sqlsrv_execute($result1);

while($extract = sqlsrv_fetch_array($result1)) {
	echo "<span>" . $extract['username'] . "</span>: <span>" . $extract['msg'] . "</span><br />";
}