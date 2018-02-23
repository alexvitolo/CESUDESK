<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; 
}

$COD_CHAMADO = $_GET['COD_CHAMADO'];


$UpdateTriagem = " UPDATE [DB_CRM_CESUDESK].[dbo].[triagem]
                      SET dh_fim_triagem = GETDATE(),
                          inf_resultadotriagem = 'Encerrada',
                          qt_horas_triagem = ,
                          tp_statustriagem = 'Encerrada',
                    WHERE idtriagem in (SELECT triagens_idtriagem 
                                          FROM [DB_CRM_CESUDESK].[dbo].[tarefa_triagem]
                                         WHERE tarefa_cd_tarefa = {$COD_CHAMADO })";

                   
 $result_UpdateTriagem = sqlsrv_query($conn, $UpdateTriagem);


      if (!($result_UpdateTriagem)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());exit;
      }  


 $UpdateTarefa = " UPDATE [DB_CRM_CESUDESK].[dbo].[tarefa]
                      SET dh_fechamento = GETDATE(),
                          qt_horasgastastarefa = 
                          tp_statustarefa = 'Fechada'
                    WHERE cd_tarefa {$COD_CHAMADO}";

                   
 $result_UpdateTarefa = sqlsrv_query($conn, $UpdateTarefa);



      if (!($result_UpdateTarefa)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());exit;
      }  




  echo  '<script type="text/javascript">alert("Chamado Encerrado com SUCESSO !");</script>';
  echo  '<script type="text/javascript"> window.location.href = "TratarChamados.php" </script>';

?>
