<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://dd42150:8087/CESUDESK/NewCesudesk/index.php"  </script>'; 
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
                                         ,bo_documento
                                         )
                               VALUES
                                      (CONVERT (varbinary(max),?,1) 
                                      ,GETDATE()
                                      ,'{$nomeArq[$key2]}' 
                                      ,'S')
                                      ";  

              $result_insertARQ = sqlsrv_query($conn, $insertARQ ,array($contents));
      


              if (!($result_insertARQ)) {
                     echo ("Falha na inclusão do registro");
                     print_r(sqlsrv_errors());exit;
              }




          }
      }


}
  echo  '<script type="text/javascript">alert("Anexo Cadastrado !");</script>';
  echo  '<script type="text/javascript"> window.location.href = "TreinamentoCRM.php" </script>';

?>
