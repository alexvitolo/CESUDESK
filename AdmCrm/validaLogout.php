<?php include '..\AdmCrm\connectionADM.php'; 

session_start();

$USUARIO_SESSION = $_SESSION['USUARIO'];

 session_destroy();

echo  '<script type="text/javascript"> window.location.href = "login.php?USUARIO='.$USUARIO_SESSION.'"  </script>';

?>
