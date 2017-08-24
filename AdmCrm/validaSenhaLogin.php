<?php include '..\AdmCrm\connectionADM.php'; 


session_start();


$USERVALIDA = $_POST["USERVALIDA"];
$_SESSION['USUARIO'] = $USERVALIDA;


if ($_POST["SENHAVALIDA"] == "") {
                  echo  '<script type="text/javascript">alert("Senha com Campo Vazio");</script>';
                  echo  '<script type="text/javascript"> window.location.href = "login.php?USUARIO='.$USERVALIDA.'"  </script>';

 }else{

  $SENHA = $_POST["SENHAVALIDA"];
 }


$squilaUsuario = "SELECT 
                      NOME
                     ,USUARIO
                     ,SENHA_USUARIO
                     ,ACESSO_ADM
                FROM tb_crm_login
                WHERE USUARIO = '{$USERVALIDA}' ";

$result_Usuario = sqlsrv_prepare($conn, $squilaUsuario);
sqlsrv_execute($result_Usuario);


 while ($row = sqlsrv_fetch_array($result_Usuario)){

      $senhaCorreta = $row["SENHA_USUARIO"];

    if ($row['ACESSO_ADM'] == 1) 
  {
      $_SESSION['ACESSO'] = 1;

  }

  elseif ($row['ACESSO_ADM'] <> 1) 
  {
      $_SESSION['ACESSO'] = 0;
  }

 }

        if ($SENHA == $senhaCorreta) {
                  echo  '<script type="text/javascript">alert("Bem-Vindo!");</script>';
                  echo  '<script type="text/javascript"> window.location.href = "index.php" </script>';

        } else {
                  echo  '<script type="text/javascript">alert("Senha Incorreta");</script>';
                  echo  '<script type="text/javascript"> window.location.href = "login.php?USUARIO='.$USERVALIDA.' " </script>';
        }
        

       // echo  '<script type="text/javascript">alert("Impossível Realizar Troca no Sábado");</script>';
       // echo  '<script type="text/javascript"> window.location.href = "PaginaIni.php" </script>';

?>
