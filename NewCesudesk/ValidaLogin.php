<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 


session_start();

$USERVALIDA = $_POST["USERVALIDA"];
$_SESSION['USUARIO'] = $USERVALIDA;

$_SESSION['TEMPOSESSION'] = date('H:i:s'); // session criada para validar o tempo de session de cada usuário


if ($_POST["SENHAVALIDA"] == "") {
                  echo  '<script type="text/javascript">alert("Senha com Campo Vazio");</script>';
                  echo  '<script type="text/javascript"> window.location.href = "index.php?USUARIO='.$USERVALIDA.'"  </script>';

 }else{

  $SENHA = $_POST["SENHAVALIDA"];
 }


$squilaUsuario = "SELECT 
                      tl.NOME
                     ,tl.ID
                     ,tl.USUARIO
                     ,tl.SENHA_USUARIO
                     ,tl.ACESSO_ADM
                     ,tc.ID_MATRICULA
                     ,tc.ID_COLABORADOR
                     ,tc.ID_GRUPO
                FROM [DB_CRM_REPORT].[dbo].[tb_crm_login] tl
           LEFT JOIN [DB_CRM_REPORT].[dbo].[tb_crm_colaborador] tc on tc.LOGIN_REDE = tl.USUARIO AND tc.STATUS_COLABORADOR = 'ATIVO' -- LEFT colaborador GCO nao obrigatorio
                WHERE USUARIO = '{$USERVALIDA}' 
                  AND tl.BO_ATIVO = 'S' " ;

$result_Usuario = sqlsrv_prepare($conn, $squilaUsuario);
sqlsrv_execute($result_Usuario);


 while ($row = sqlsrv_fetch_array($result_Usuario)){

      $senhaCorreta = $row["SENHA_USUARIO"];
      $_SESSION['ID_COLABORADOR'] = $row["ID_COLABORADOR"];
      $_SESSION['MATRICULA'] = $row["ID_MATRICULA"];
      $_SESSION['IDLOGIN'] = $row["ID"];
      $primeiraL = explode(" ", $row["NOME"]);
      $_SESSION['NOME'] = $primeiraL[0];
      $_SESSION['ID_GRUPO'] = $row["ID_GRUPO"];


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

                     $deleteLoggedUser = "DELETE tb_loggeduser
                                               WHERE USUARIO = '{$_SESSION['USUARIO']}' ";
   
                     $result_deleteLoggedUser = sqlsrv_query($conn, $deleteLoggedUser);
                     sqlsrv_free_stmt($result_deleteLoggedUser); 

               $insertLoggedUser = "INSERT INTO tb_loggeduser
                                    (USUARIO
                                    ,ACESSO
                                    ,ORIGEM
                                    )
                              VALUES
                                    ('{$_SESSION['USUARIO']}'
                                    ,{$_SESSION['ACESSO']}
                                    ,'GCO - Gestão de Controle Operacional'
                                    )  ";

                  $result_InsertLoggedUser = sqlsrv_query($conn, $insertLoggedUser); 
 


                  if (!($result_InsertLoggedUser)) {
                         // echo ("Falha na inclusão do registro");
                         print_r(sqlsrv_errors());
                         sqlsrv_close($conn);
                         echo  '<script type="text/javascript"> alert("Falha no Login"); window.location.href = "index.php" </script>';
                  }   
                  else {
                             sqlsrv_free_stmt($result_InsertLoggedUser);
                             echo  '<script type="text/javascript">alert("Bem-Vindo!");</script>';
                             echo  '<script type="text/javascript"> window.location.href = "main.php" </script>';
                    }
                  

        }else {
                  echo  '<script type="text/javascript">alert("Senha Incorreta");</script>';
                  echo  '<script type="text/javascript"> window.location.href = "index.php?USUARIO='.$USERVALIDA.' " </script>';
        }
        

       // echo  '<script type="text/javascript">alert("Impossível Realizar Troca no Sábado");</script>';
       // echo  '<script type="text/javascript"> window.location.href = "PaginaIni.php" </script>';

?>
