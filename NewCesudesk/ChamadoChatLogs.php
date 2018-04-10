<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

$ID_USUARIO = $_SESSION['IDLOGIN'];
$codChamado = $_SESSION['chamadoChat'];

$squila = "SET LANGUAGE 'Brazilian' 
           SELECT B.USUARIO as username, 
                      A.msg, 
                        B.id as idLogin, 
                        SUBSTRING(CONVERT(VARCHAR(24), A.dt_insert, 113),0,18) as DATAHORA
			 FROM [DB_CRM_CESUDESK].[dbo].[mensagem_logs] A 
	   INNER JOIN [DB_CRM_REPORT].[dbo].[tb_crm_login] B ON A.id_usuario = B.id 
	   		WHERE A.id_tarefa = {$codChamado}
	     ORDER BY A.id DESC";

$result1 = sqlsrv_prepare($conn, $squila);
sqlsrv_execute($result1);

while($extract = sqlsrv_fetch_array($result1)) {
	if ($ID_USUARIO == $extract['idLogin']) {
		echo "<div class='chat self'><div class='user-photo'></div><p class='chat-message'><span>" . $extract['username'] . "</span>: <span>" . $extract['msg'] . "</span><br> <a style='color:#000000 ; float: right; height:10px; font-size: 12px;'>" . $extract['DATAHORA'] . "</a></p></div><br />";
	}else{
		echo "<div class='chat friend'><div class='user-photo'></div><p class='chat-message'><span>" . $extract['username'] . "</span>: <span>" . $extract['msg'] . "</span> <br> <a style='color:#000000 ; float: right; height:10px; font-size: 12px;'>" . $extract['DATAHORA'] . "</a></p></div><br />";
	}
}