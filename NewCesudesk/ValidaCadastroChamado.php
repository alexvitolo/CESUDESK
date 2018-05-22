<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://dd42150:8087/CESUDESK/NewCesudesk/index.php"  </script>'; 
}

$ID_LOGIN       = $_SESSION['IDLOGIN'];
$DATA_CADASTRO  = $_POST ["DATA_CADASTRO"]; // getdate();
$DATA_ENTREGA   = $_POST ["DATA_ENTREGA"];
$PRIORIDADE     = $_POST ["PRIORIDADE"];
$PROJETO        = $_POST ["PROJETO"];
$MODULO         = $_POST ["MODULO"];
$TIPO_TAREFA    = $_POST ["TIPO_TAREFA"];

$resumoSoli     = $_POST["resumoSoli"];
$descSoli       = $_POST["descSoli"];

$resumoSoli = str_replace("'", '"', $resumoSoli);
$descSoli   = str_replace("'", '"', $descSoli);

$DATA_ENTREGA = date("Y-m-d H:i:s" , strtotime($DATA_ENTREGA));


$insertTarefa = " INSERT INTO [DB_CRM_CESUDESK].[dbo].[tarefa]
                              (desc_tarefa
                              ,dh_cadastro
                              ,dh_entrega_prev
                              ,prioridade
                              ,titulo
                              ,tp_statustarefa
                              ,cd_modulo
                              ,projeto_cd_projeto
                              ,solicitante_cd_usuario
                              ,cd_tipotarefa)
                      VALUES
                             ('{$descSoli}'
                             ,GETDATE()
                             ,'{$DATA_ENTREGA}'
                             ,{$PRIORIDADE}
                             ,'{$resumoSoli}'
                             ,'Aberta'
                             ,{$MODULO}
                             ,{$PROJETO}
                             ,{$ID_LOGIN}
                             ,{$TIPO_TAREFA})";

                   
 $result_insert = sqlsrv_query($conn, $insertTarefa);

      if (!($result_insert)) {
             echo ("Falha na inclusão do registro");
             print_r(sqlsrv_errors());exit;
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
  echo  '<script type="text/javascript">alert("Chamado Cadastrado !");</script>';
  echo  '<script type="text/javascript"> window.location.href = "main.php" </script>';

?>
