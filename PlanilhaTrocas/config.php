<?php
// Dados do banco
$dbhost   = "W2K8R2-APP36\CRM_REPORTS";   #Nome do host
$db       = "DB_CRM_REPORT";   #Nome do banco de dados
$user     = "usr_cesudesk"; #Nome do usuário
$password = "ZRioYf68";   #Senha do usuário
// Dados da tabela
$tabela = "tb_crm_trocas";    #Nome da tabela
$campo1 = "ID";  #Nome do campo da tabela
$campo2 = "NAME";  #Nome de outro campo da tabela

mssql_connect()($dbhost,$user,$password) or die("Não foi possível a conexão com o servidor!");
 
$instrucaoSQL = "SELECT $campo1 FROM $tabela ORDER BY $campo1";
$consulta = mssql_query($instrucaoSQL);
echo"($instrucaoSQL)";
$numRegistros = sqlsrv_num_rows($consulta);
 
echo "Esta tabela contém $numRegistros registros!\n<hr>\n";
 
if ($numRegistros!=0) {
	while ($cadaLinha = mssql_fetch_array($consulta)) {
		echo "$cadaLinha[$campo1] - $cadaLinha[$campo2]\n<br>\n";
	}
}
?>