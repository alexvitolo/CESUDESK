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
                      tl.NOME
                     ,tl.USUARIO
                     ,tl.SENHA_USUARIO
                     ,tl.ACESSO_ADM
                     ,tc.ID_MATRICULA
                     ,tc.ID_COLABORADOR
                FROM tb_crm_login tl
          INNER JOIN tb_crm_colaborador tc on tc.LOGIN_REDE = tl.USUARIO
                WHERE USUARIO = '{$USERVALIDA}' 
                  AND tc.STATUS_COLABORADOR = 'ATIVO' ";

$result_Usuario = sqlsrv_prepare($conn, $squilaUsuario);
sqlsrv_execute($result_Usuario);


 while ($row = sqlsrv_fetch_array($result_Usuario)){

      $senhaCorreta = $row["SENHA_USUARIO"];
      $_SESSION['ID_COLABORADOR'] = $row["ID_COLABORADOR"];
      $_SESSION['MATRICULA'] = $row["ID_MATRICULA"];


    if ($row['ACESSO_ADM'] == 1) 
  {
      $_SESSION['ACESSO'] = 1;
      $_SESSION['SUGESTAO_COLABORADOR'] = 1;

  }


     if ($row['ACESSO_ADM'] == 2) 
  {
      $_SESSION['ACESSO'] = 2;
      $_SESSION['SUGESTAO_COLABORADOR'] = 0;

  }


  elseif ($row['ACESSO_ADM'] <> 1) 
  {
      $_SESSION['ACESSO'] = 0;
      $_SESSION['SUGESTAO_COLABORADOR'] = 0;
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
