<?php include '..\AdmCrm\connectionADM.php'; 
session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; 
}

if ( (date('H:i:s')) >=  (date('H:i:s', strtotime('+55 minute', strtotime($_SESSION['TEMPOSESSION'])))) & ($_SESSION['ACESSO'] <> 1 ) ){
     // Ação a ser executada: encerra a session depois de 15 min
   echo  '<script type="text/javascript"> alert("Tempo de Sessão Expirada"); window.location.href = "http://d42150:8087/cesudesk/AdmCrm/login.php"  </script>'; 
   session_destroy();
 }
 
 $_SESSION['TEMPOSESSION'] = date('H:i:s');




date_default_timezone_set('America/Sao_Paulo');


$MATRICULA = $_POST["MATRICULA"]; 
$NOME = $_POST["NOME"]; 
$dtNascimento = $_POST["dtNascimento"]; 
$email = $_POST["email"]; 
$codPortal = $_POST["codPortal"]; 
$loginRede = $_POST["loginRede"]; 
$loginTelefonia = $_POST["loginTelefonia"];
$telefone = $_POST["telefone"];
$STATUS = $_POST["STATUS"]; 
$supervisor = $_POST["supervisor"]; 
$cargo = $_POST["cargo"];
$nivelCargo = $_POST["nivelCargo"];
$dtAdmissao = $_POST["dtAdmissao"];
$horario = $_POST["horario"];
$grupo = $_POST["grupo"]; 

$MATRICULA_OLD = $_POST["MATRICULA_OLD"];


$ID_COLABORADOR = $_POST["ID_COLABORADOR"];
$pausa1 = $_POST["pausa1"]; 
$pausa2 = $_POST["pausa2"]; 
$lanche = $_POST["lanche"]; 




$dtDesligamento = $_POST["dtDesligamento"]; 
$motivoDesligamento = $_POST["motivoDesligamento"]; 
$subMotivoDesligamento = $_POST["subMotivoDesligamento"];

$dtFeriasIni = $_POST["dtFeriasIni"]; 
$dtFeriasFim = $_POST["dtFeriasFim"]; 

$today = date("Y-m-d");


// realizar verificação para ver se é consultor
if(isset($_POST['validaEscalaPausa']))
{

//updates

  $updateSquilaPausa1 = " UPDATE tb_crm_escala_pausa
                            SET DT_VIGENCIA_INICIAL = DT_VIGENCIA_INICIAL
                               ,DT_VIGENCIA_FINAL = '{$today}'

                          WHERE ID_COLABORADOR = '{$ID_COLABORADOR}' AND DT_VIGENCIA_FINAL IS NULL";

  $result_update1 = sqlsrv_query($conn, $updateSquilaPausa1);
   sqlsrv_free_stmt($result_update1);



 //inserts




  $insertSquilaPausa1 = " INSERT INTO tb_crm_escala_pausa
                                    (ID_COLABORADOR
                                    ,ID_HORARIO
                                    ,ID_TIPO_PAUSA
                                    ,HORARIO_PAUSA
                                    ,DT_VIGENCIA_INICIAL
                                    ,DT_VIGENCIA_FINAL
                                     )
                              VALUES
                                    ('{$ID_COLABORADOR}'
                                    ,'{$horario}'
                                    ,'1'
                                    ,'{$pausa1}'
                                    ,'{$today}'
                                    ,null
                                    )  ";

$result_insert1 = sqlsrv_query($conn, $insertSquilaPausa1);
   sqlsrv_free_stmt($result_insert1);



  $insertSquilaPausa2 = " INSERT INTO tb_crm_escala_pausa
                                    (ID_COLABORADOR
                                    ,ID_HORARIO
                                    ,ID_TIPO_PAUSA
                                    ,HORARIO_PAUSA
                                    ,DT_VIGENCIA_INICIAL
                                    ,DT_VIGENCIA_FINAL
                                     )
                              VALUES
                                    ('{$ID_COLABORADOR}'
                                    ,'{$horario}'
                                    ,'2'
                                    ,'{$pausa2}'
                                    ,'{$today}'
                                    ,null
                                    )  ";

$result_insert2 = sqlsrv_query($conn, $insertSquilaPausa2);
   sqlsrv_free_stmt($result_insert2);



  $insertSquilaPausa5 = " INSERT INTO tb_crm_escala_pausa
                                    (ID_COLABORADOR
                                    ,ID_HORARIO
                                    ,ID_TIPO_PAUSA
                                    ,HORARIO_PAUSA
                                    ,DT_VIGENCIA_INICIAL
                                    ,DT_VIGENCIA_FINAL
                                     )
                              VALUES
                                    ('{$ID_COLABORADOR}'
                                    ,'{$horario}'
                                    ,'5'
                                    ,'{$lanche}'
                                    ,'{$today}'
                                    ,null
                                    )  ";

$result_insert5 = sqlsrv_query($conn, $insertSquilaPausa5);
   sqlsrv_free_stmt($result_insert5);


}



if(isset($_POST['validaDadosColaborador']))
{
  $sqldicas2 = '';

  if(isset($_POST['validaMotivoDesliga']) AND ($STATUS == "DESLIGADO"))
   {$sqldicas2 = ",DT_DESLIGAMENTO = '{$dtDesligamento}'      
                  ,ID_MOTIVO = '{$motivoDesligamento}'      
                  ,ID_SUB_MOTIVO = '{$subMotivoDesligamento}' ";}

    elseif (isset($_POST['validaMotivoDesliga']) AND ($STATUS <> "DESLIGADO")) {
       echo  '<script type="text/javascript">alert("Status Invalido para a operação!");</script>';
       echo  '<script type="text/javascript"> window.location.href = "colaboradores.php" </script>';exit;
    }


      if ($nivelCargo == 'null') {
        $validaNivelCargo = ",NIVEL_CARGO = null ";
      }else{
        $validaNivelCargo = ",NIVEL_CARGO = '{$nivelCargo}' ";
      }


      if ($dtFeriasIni == 'null' || $STATUS =='ATIVO') {
        $dtFeriasIni = ",DT_FERIAS_INI = null ";
      }else{
        $dtFeriasIni = ",DT_FERIAS_INI = '{$dtFeriasIni}' ";
      }

      if ($dtFeriasFim == 'null' || $STATUS =='ATIVO') {
        $dtFeriasFim = ",DT_FERIAS_FIM = null ";
      }else{
        $dtFeriasFim = ",DT_FERIAS_FIM = '{$dtFeriasFim}' ";
      }



$updateSquila = " UPDATE tb_crm_colaborador
                     SET ID_MATRICULA = '{$MATRICULA}'
                        ,LOGIN_REDE = '{$loginRede}'
                        ,NOME = '{$NOME}'
                        ,STATUS_COLABORADOR = '{$STATUS}'
                        ,ID_COLABORADOR_GESTOR = {$supervisor}
                        ,ID_CARGO = '{$cargo}'
                        ".$validaNivelCargo."
                        ,ID_GRUPO = '{$grupo}'
                        ,EMAIL = '{$email}'
                        ,TELEFONE = '{$telefone}'
                        ,DT_ADMISSAO = '{$dtAdmissao}'
                        ,DT_NASCIMENTO = '{$dtNascimento}'
                        ".$dtFeriasIni."
                        ".$dtFeriasFim."
                        ,LOGIN_TELEFONIA = '{$loginTelefonia}'
                        ,CODIGO_PORTAL = '{$codPortal}'
                        ,ID_HORARIO = '{$horario}'
                        ".$sqldicas2."
                  WHERE  ID_MATRICULA = '{$MATRICULA_OLD}'";

 $result_update = sqlsrv_query($conn, $updateSquila);

      if (!($result_update)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());
      }   
      else {
            sqlsrv_free_stmt($result_update);
            sqlsrv_close($conn);
            echo  '<script type="text/javascript">alert("Colaborador Atualizado !");</script>';
            echo  '<script type="text/javascript"> window.location.href = "Colaboradores.php" </script>';
        }

}

else{
  echo  '<script type="text/javascript">alert("Nenhuma das Opções Marcadas !");</script>';
  echo  '<script type="text/javascript"> window.location.href = "colaboradores.php" </script>';
}

  echo  '<script type="text/javascript">alert("Colaborador Atualizado !");</script>';
  echo  '<script type="text/javascript"> window.location.href = "colaboradores.php" </script>';