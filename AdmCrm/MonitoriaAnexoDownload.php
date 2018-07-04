<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://dd42150:8087/CESUDESK/NewCesudesk/index.php"  </script>'; 
}


$ID_PESQUISA = $_GET['ID_PESQUISA'];



$squilAnexo = "SELECT C.anexo
          					 ,C.nm_anexo
          					 ,right(C.nm_anexo, charindex('.', reverse(C.nm_anexo) + '.') - 1) as ExtArq
                 FROM DB_CRM_REPORT.dbo.tb_qld_pesquisa_anexo C
                WHERE C.ID_PESQUISA = {$ID_PESQUISA} ";

$result_squilaAnexo = sqlsrv_prepare($conn, $squilAnexo);
sqlsrv_execute($result_squilaAnexo);

$vetorSQLAnexo = sqlsrv_fetch_array($result_squilaAnexo);

  $file = ($vetorSQLAnexo['anexo']);
          header("Cache-Control: no-cache private");
          header("Content-Description: File Transfer");
          header('Content-disposition: attachment; filename='.$vetorSQLAnexo['nm_anexo']);
          header('Content-Type:'.$vetorSQLAnexo['ExtArq']);
          header("Content-Transfer-Encoding: binary");
          header('Content-Length: '. strlen($file));
          ob_clean();
          flush();
          echo $file;


?>

