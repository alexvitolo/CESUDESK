<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

$ID_USUARIO = $_SESSION['IDLOGIN'];

$squila = "SELECT B.USUARIO as username, A.msg , B.id as idLogin FROM [DB_CRM_CESUDESK].[dbo].[mensagem_logs] A INNER JOIN [DB_CRM_REPORT].[dbo].[tb_crm_login] B ON A.id_usuario = B.id ORDER BY A.id DESC";

$result1 = sqlsrv_prepare($conn, $squila);
sqlsrv_execute($result1);

while($extract = sqlsrv_fetch_array($result1)) {
	if ($ID_USUARIO == $extract['idLogin']) {
		echo "<div class='chat self'><div class='user-photo'></div><p class='chat-message'><span>" . $extract['username'] . "</span>: <span>" . $extract['msg'] . "</span> </p></div><br />";
	}else{
		echo "<div class='chat friend'><div class='user-photo'></div><p class='chat-message'><span>" . $extract['username'] . "</span>: <span>" . $extract['msg'] . "</span> </p></div><br />";
	}
}