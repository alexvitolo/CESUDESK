<?php include 'connectchat.php'; 

$squila = "SELECT username,msg FROM [DB_CRM_CESUDESK].[dbo].[mensagem_logs] ORDER BY id DESC";

$result1 = sqlsrv_prepare($conn, $squila);
sqlsrv_execute($result1);

while($extract = sqlsrv_fetch_array($result1)) {
	echo "<span>" . $extract['username'] . "</span>: <span>" . $extract['msg'] . "</span><br />";
}