<?php

try {
   $dbh = new PDO('pgsql:host=10.32.14.58;port=5433;dbname=postgres;user=###;password=postgres');
   echo "PDO connection object created";
}
catch(PDOException $e)
{
      echo $e->getMessage();
}


//pg_Connect("host=10.32.14.58 port=5433 dbname=workbase user=postgres password=postgres");
?>

