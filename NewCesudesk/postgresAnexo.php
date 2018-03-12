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


$SYNC = '"SYNC"';





$dbconn = pg_connect("host=10.32.14.58 port=5433 dbname=workbase user=postgres password=postgres");
//connect to a database named "postgres" on the host "host" with a username and password
 
if (!$dbconn){
echo "<center><h1>Doesn't work =(</h1></center>";
}else
 echo "<center><h1>Good connection</h1></center>";

$result = pg_query($dbconn, "SELECT id,'0x'|| encode(anexo, 'hex') as anexo,nm_anexo,dh_upload FROM anexo WHERE nm_anexo is not null and anexo is not null and dh_upload >= '2017-06-01 00:00:00' and $SYNC is null limit 100");
if (!$result) {
 echo "An error occured.\n";
 exit;
}
 
while ($row = pg_fetch_row($result)) {

 $insertSquila = " SET IDENTITY_INSERT [DB_CRM_CESUDESK].[dbo].[anexo] ON

                   INSERT INTO [DB_CRM_CESUDESK].[dbo].[anexo]
                              ([id]
                               ,[anexo]
                               ,[dh_upload]
                               ,[nm_anexo])
                        
                       VALUES
                        ({$row[0]}
                        ,{$row[1]}
                        ,'{$row[3]}'  
                        ,'{$row[2]}')";

 $result_insert = sqlsrv_query($conn, $insertSquila);

      if (!($result_insert)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
      }   

      else {
            sqlsrv_free_stmt($result_insert);

            print_r("ok");
        }

      pg_query($dbconn, "UPDATE public.anexo set $SYNC ='OK' WHERE id=$row[0]");
      

}


 
?>