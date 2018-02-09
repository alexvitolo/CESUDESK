<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

$USUARIO_SESSION = $_SESSION['USUARIO'];

 session_destroy();

echo  '<script type="text/javascript"> window.location.href = "index.php?USUARIO='.$USUARIO_SESSION.'"  </script>';

?>
