<?php
namespace Unicesumar;

use \PDO;

setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");
date_default_timezone_set('America/Sao_Paulo');

$serverName = "W2K8R2-APP36\CRM_REPORTS"; //Hostname/IP,...
$connectionOptions = array(
    "Database" => "DB_CRM_REPORT",
    "Uid" => "usr_cesudesk",
    "PWD" => "ZRioYf68",
    "CharacterSet"  => 'UTF-8'
);

//Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

if( $conn === false ) {
    die( print_r( sqlsrv_errors(), true)); //See why it fails
}




$serverName2 = "W2K12R2-SQL23\TALISMA"; //Hostname/IP,...
$connectionOptions2 = array(
    "Database" => "tlMain",
    "Uid" => "talismaadmin",
    "PWD" => "talisma",
    "CharacterSet"  => 'UTF-8'
);

//Establishes the connection
$conn2 = sqlsrv_connect($serverName2, $connectionOptions2);

if( $conn2 === false ) {
    die( print_r( sqlsrv_errors(), true)); //See why it fails
}



$serverName3 = "172.16.1.145\LYCEUM"; //Hostname/IP,...
$connectionOptions3 = array(
    "Database" => "LYCEUM",
    "Uid" => "gid",
    "PWD" => "Fuyt2DZkKV",
    "CharacterSet"  => 'UTF-8'
);

//Establishes the connection
$conn3 = sqlsrv_connect($serverName3, $connectionOptions3);

if( $conn3 === false ) {
    die( print_r( sqlsrv_errors(), true)); //See why it fails
}



    
// $sql = "SELECT ID, NAME, GRUPO, QTD_TROCA FROM tb_crm_trocas";
//  $stmt = sqlsrv_prepare($conn, $sql);
//  $result = sqlsrv_execute($stmt);
//$row = sqlsrv_fetch_array($stmt);

// foreach ($row as $key => $value) {
// 	echo($key);
// 	echo($value);
// }

 // $connPDO = new PDO ("dblib:host=172.16.1.164:58411;dbname=DB_CRM_REPORT","usr_cesudesk","ZRioYf68");
 // $connPDO = new PDO ("sqlsrv:Server=172.16.1.164,58411;Database=DB_CRM_REPORT","usr_cesudesk","ZRioYf68");

 // $exec = $statement->execute();
 // $resultPDO = array();
 // if ($exec) {
 //      $resultPDO = $statement->fetchAll(PDO::FETCH_ASSOC);
 // } else {
 //  error_log("Erro na chamada do metodo: " . __METHOD__ . ", arquivo: " . __FILE__ . ", linha: " . __LINE__);
 // }
//Dsn=PostgreSQL35W;Driver={PostgreSQL ODBC Driver(UNICODE)};server=10.32.14.58;port=5433;database=workbase;uid=postgres;pwd=postgres
// COLLATE Latin1_General_CS_AS
?>