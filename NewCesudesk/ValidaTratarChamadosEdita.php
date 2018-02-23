<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; 
}

if ( ! isset( $_FILES['anexo'] )) {
    // Ação a ser executada: mata o script e manda uma mensagem
     echo  '<script type="text/javascript">alert("Nada a Ser Atualizado !");</script>';
     echo  '<script type="text/javascript"> window.location.href = "TratarChamados.php" </script>';
}



foreach( $_FILES['anexo'] as $key => $value ){ 
	// print_r($key);
	// print_r($value);
      
      foreach( $value as $key2 => $value2 ){ // 
	       
         if($key == 'name') { 
                $nomeArq[$key2] = $value2; // Vetor para armazenar nome correto do arquivo (por causa do foreack do tipo file)
          }
	         
         if($key == 'tmp_name' and $value2 <> null) {    // vetor com o conteudo do anexo
               //  print_r($key2);
	             // print_r($value2);


              $value2= file_get_contents($value2);
              $value2 = unpack('H*hex', $value2);
              $contents = '0x'.$value2['hex'];

	            $insertARQ = "INSERT INTO [DB_CRM_CESUDESK].[dbo].[anexo]
                                         (anexo
                                         ,dh_upload
                                         ,nm_anexo
                                         )
                               VALUES
                                      (CONVERT (varbinary(max),?,1) 
                                      ,GETDATE()
                                      ,'{$nomeArq[$key2]}') ";  

              $result_insertARQ = sqlsrv_query($conn, $insertARQ ,array($contents));
      


              if (!($result_insertARQ)) {
                     echo ("Falha na inclusão do registro");
                     print_r(sqlsrv_errors());exit;
              }


              $insertTarefaAnexo = "INSERT INTO [DB_CRM_CESUDESK].[dbo].[tarefa_anexo]
                                                (tarefa_cd_tarefa
                                                ,anexos_id
                                                )
                                         VALUES
                                                ((SELECT TOP 1 cd_tarefa FROM [DB_CRM_CESUDESK].[dbo].[tarefa] ORDER BY 1 DESC)
                                                ,(SELECT TOP 1 id FROM [DB_CRM_CESUDESK].[dbo].[anexo] ORDER BY 1 DESC)
                                                 ) ";  

              $result_TarefaAnexo = sqlsrv_query($conn, $insertTarefaAnexo);
      


              if (!($result_TarefaAnexo)) {
                     echo ("Falha na inclusão do registro");
                     print_r(sqlsrv_errors());exit;
              }   

          }
      }


}
  echo  '<script type="text/javascript">alert("Chamado Atualizado !");</script>';
  echo  '<script type="text/javascript"> window.location.href = "main.php" </script>';

?>
