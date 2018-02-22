<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 



$idLogin = $_REQUEST['idLogin'];
$msg   = str_replace("'", '"', $_REQUEST['msg']);
$codChamado = $_REQUEST['codChamado'];

$ChatInsert = "INSERT INTO [DB_CRM_CESUDESK].[dbo].[mensagem_logs] (id_usuario, msg, id_tarefa) 
                    VALUES ({$idLogin},'{$msg}',{$codChamado}) ";

$result_insert = sqlsrv_query($conn, $ChatInsert);

if (!($result_insert)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
      }   
      else{
            sqlsrv_free_stmt($result_insert);
        }


$ChatSelect = "SELECT B.USUARIO as username, A.msg, B.id as idLogin 
                 FROM [DB_CRM_CESUDESK].[dbo].[mensagem_logs] A 
           INNER JOIN [DB_CRM_REPORT].[dbo].[tb_crm_login] B ON A.id_usuario = B.id 
                WHERE A.id_tarefa = {$codChamado}
             ORDER BY A.id DESC";

$result1 = sqlsrv_prepare($conn, $ChatSelect);
sqlsrv_execute($result1);

while($extract = sqlsrv_fetch_array($result1)) {
	if ($idLogin == $extract['idLogin']) {
		echo "<div class='chat self'><div class='user-photo'></div><p class='chat-message'><span>" . $extract['username'] . "</span>: <span>" . $extract['msg'] . "</span> </p></div><br />";
	}else{
		echo "<div class='chat friend'><div class='user-photo'></div><p class='chat-message'><span>" . $extract['username'] . "</span>: <span>" . $extract['msg'] . "</span> </p></div><br />";
	}
}