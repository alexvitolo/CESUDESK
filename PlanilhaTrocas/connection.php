<?php
namespace Unicesumar\TalismaSync;

use \PDO;

$serverName = "W2K8R2-APP36\CRM_REPORTS"; //Hostname/IP,...
$connectionOptions = array(
    "Database" => "DB_CRM_REPORT",
    "Uid" => "usr_cesudesk",
    "PWD" => "ZRioYf68"
);

//Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

if( $conn === false ) {
    die( print_r( sqlsrv_errors(), true)); //See why it fails
}
    
$sql = "SELECT ID, NAME, GRUPO, QTD_TROCA FROM tb_crm_trocas";
 $stmt = sqlsrv_prepare($conn, $sql);
 $result = sqlsrv_execute($stmt);
//$row = sqlsrv_fetch_array($stmt);

// foreach ($row as $key => $value) {
// 	echo($key);
// 	echo($value);
// }

 // $connPDO = new PDO ("dblib:host=172.16.1.164:58411;dbname=DB_CRM_REPORT","usr_cesudesk","ZRioYf68");
 $connPDO = new PDO ("sqlsrv:Server=172.16.1.164,58411;Database=DB_CRM_REPORT","usr_cesudesk","ZRioYf68");

 $statement = $connPDO->prepare($sql);

 $exec = $statement->execute();
 $resultPDO = array();
 if ($exec) {
      $resultPDO = $statement->fetchAll(PDO::FETCH_ASSOC);
 } else {
  error_log("Erro na chamada do metodo: " . __METHOD__ . ", arquivo: " . __FILE__ . ", linha: " . __LINE__);
 }
