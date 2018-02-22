<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; 
}

$COD_CHAMADO = $_POST['COD_CHAMADO'];

if (! isset($_POST['CheckboxID'])) {
  echo  '<script type="text/javascript">alert("Nada a ser Atualizado !");</script>';
  echo  '<script type="text/javascript"> window.location.href = "DistribuirChamados.php" </script>';
}


foreach($_POST['CheckboxID'] as $key => $value ){

   $ID_USUARIO = $value; // atribui o id login do checkbox selecionado 

   $SelectValidaTriagem = "SELECT A.idtriagem
                             FROM [DB_CRM_CESUDESK].[dbo].[triagem] A 
                       INNER JOIN [DB_CRM_CESUDESK].[dbo].[tarefa_triagem] B ON A.idtriagem = B.triagens_idtriagem
                            WHERE B.tarefa_cd_tarefa = {$COD_CHAMADO}
                              AND A.cd_usuario = {$ID_USUARIO}";

   $ResultValidaTriagem= sqlsrv_query($conn, $SelectValidaTriagem);
   sqlsrv_execute($ResultValidaTriagem);

   $VetorValidaTriagem = sqlsrv_fetch_array($ResultValidaTriagem);;


   if ($VetorValidaTriagem == null) {
          
          $InsertTriagem = "INSERT INTO [DB_CRM_CESUDESK].[dbo].[triagem] (dh_inicio_triagem,tp_statustriagem,cd_usuario)
                                 VALUES (GETDATE(),'Andamento',{$ID_USUARIO})";

          $ResultInsertTriagem= sqlsrv_query($conn, $InsertTriagem);



          $InsertTarefaTriagem = "INSERT INTO [DB_CRM_CESUDESK].[dbo].[tarefa_triagem] (tarefa_cd_tarefa,triagens_idtriagem)
                                       VALUES ({$COD_CHAMADO}, (SELECT TOP 1 idtriagem FROM [DB_CRM_CESUDESK].[dbo].[triagem] ORDER BY 1 DESC)) ";
                                 
          $ResultTarefaTriagem = sqlsrv_query($conn, $InsertTarefaTriagem);


          $UpdateStatusTarefa = "UPDATE [DB_CRM_CESUDESK].[dbo].[tarefa] 
                                    SET tp_statustarefa = 'Andamento'
                                  WHERE cd_tarefa = {$COD_CHAMADO}";
                                 
          $ResultUpdateStatus = sqlsrv_query($conn, $UpdateStatusTarefa);
   }  


  echo  '<script type="text/javascript">alert("Triagem Realizada !");</script>';
  echo  '<script type="text/javascript"> window.location.href = "DistribuirChamados.php" </script>';


 }