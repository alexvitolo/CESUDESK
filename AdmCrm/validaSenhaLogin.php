<?php include '..\AdmCrm\connectionADM.php'; 


session_start();


$USERVALIDA = $_SESSION['USUARIO'];
$_SESSION['TEMPOSESSION'] = date('H:i:s'); // session criada para validar o tempo de session de cada usuário


$squilaUsuario = "SELECT 
                      tl.NOME
                     ,tl.USUARIO
                     ,tl.SENHA_USUARIO
                     ,tl.ACESSO_ADM
                     ,tc.ID_MATRICULA
                     ,tc.ID_COLABORADOR
                     ,tl.ACESSO_GCO
                FROM tb_crm_login tl
          INNER JOIN tb_crm_colaborador tc on tc.LOGIN_REDE = tl.USUARIO
                WHERE USUARIO = '{$USERVALIDA}' 
                  AND tc.STATUS_COLABORADOR = 'ATIVO' 
                  AND tl.BO_ATIVO = 'S' 
                  AND tl.ACESSO_GCO <> 'N' " ;

$result_Usuario = sqlsrv_prepare($conn, $squilaUsuario);
sqlsrv_execute($result_Usuario);


 $row = sqlsrv_fetch_array($result_Usuario);

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

 

       if ($row > 0) {

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
                         echo  '<script type="text/javascript"> alert("Falha no Login"); window.location.href = "login.php" </script>';
                  }   
                  else {
                             sqlsrv_free_stmt($result_InsertLoggedUser);
                             echo  '<script type="text/javascript">alert("Bem-Vindo!");</script>';
                             echo  '<script type="text/javascript"> window.location.href = "index.php" </script>';
                    }
                  

        }else {
                  echo  '<script type="text/javascript">alert("Acesso Negado");</script>';
                  echo  '<script type="text/javascript"> window.location.href = "../NewCesudesk/main.php?"; close(); </script>';
        }
        

       // echo  '<script type="text/javascript">alert("Impossível Realizar Troca no Sábado");</script>';
       // echo  '<script type="text/javascript"> window.location.href = "PaginaIni.php" </script>';

?>
