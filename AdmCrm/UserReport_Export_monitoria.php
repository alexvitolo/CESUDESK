
<?php

//conection forçada


$serverName = "W2K8R2-APP36\CRM_REPORTS"; //Hostname/IP,...
$connectionOptions = array(
    "Database" => "DB_CRM_REPORT",
    "Uid" => "usr_cesudesk",
    "PWD" => "ZRioYf68"
);

//Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

if( $conn === false ) {
    die( print_r( sqlsrv_errors(), true)); //See why it fails
}
    



  

$query = "SELECT tpes.ID_PESQUISA
                   ,tpes.ID_COLABORADOR
                   ,tc.NOME
                   ,tpes.ID_COLABORADOR_APLICA
                   ,(SELECT NOME FROM tb_crm_colaborador WHERE ID_COLABORADOR = tpes.ID_COLABORADOR_APLICA) as COLABORADOR_APLICA
                   ,tpes.ID_PROCESSO
                   ,tp.NOME as NOME_PROCESSO
                   ,tpes.ID_GRUPO
                   ,tg.DESCRICAO as DESC_GRUPO
                   ,tpes.ID_OBJETO_TALISMA
                   ,tbo.DESCRICAO as DESC_OBJ_TALISMA
                   ,tpes.CPF_MONITORIA
                   ,tpes.RAMAL_PA
                   ,tpes.ID_GRAVADOR
                   ,tpes.NOTA_FINAL
                   ,CONVERT(VARCHAR(10),tpes.DT_ATENDIMENTO, 103) as DT_ATENDIMENTO
                   ,CONVERT(VARCHAR(10),tpes.DT_SISTEMA, 103) as DT_SISTEMA
                   ,tpes.ID_RESULT_LIG
                   ,tlig.DESCRICAO as DESC_RESULTADO_LIG
                   ,tpes.OBSERVACAO_PESQUISA
                   ,tpes.DESC_ID_TALISMA
                   ,tcron.NUMERO as NUMERO_AVALIACAO
           FROM tb_qld_pesquisa tpes
     INNER JOIN tb_crm_colaborador tc ON tc.ID_COLABORADOR = tpes.ID_COLABORADOR
     INNER JOIN tb_crm_processo tp ON tp.ID = tpes.ID_PROCESSO
     INNER JOIN tb_crm_grupo tg ON tg.ID_GRUPO = tpes.ID_GRUPO
     INNER JOIN tb_qld_objeto_talisma tbo ON tbo.ID_OBJETO_TALISMA = tpes.ID_OBJETO_TALISMA
     INNER JOIN tb_qld_resultado_ligacao tlig ON tlig.ID_RESULT_LIG = tpes.ID_RESULT_LIG
     INNER JOIN tb_qld_cronograma_avaliacao tcron ON tcron.ID_AVALIACAO = tpes.ID_AVALIACAO";

$result = sqlsrv_prepare($conn, $query);
sqlsrv_execute($result);



  $output = '
                   <table class="table" bordered="1">  
                    <tr>  
                         <th>ID_PESQUISA</th>
                         <th>ID_COLABORADOR</th>
                         <th>NOME</th>
                         <th>ID_COLABORADOR_APLICA</th>
                         <th>COLABORADOR_APLICA</th>
                         <th>ID_PROCESSO</th>
                         <th>NOME_PROCESSO</th>
                         <th>ID_GRUPO</th>
                         <th>DESC_GRUPO</th>
                         <th>ID_OBJETO_TALISMA</th>
                         <th>DESC_OBJ_TALISMA</th>
                         <th>CPF_MONITORIA</th>
                         <th>RAMAL_PA</th>
                         <th>ID_GRAVADOR</th>
                         <th>NOTA_FINAL</th>
                         <th>DT_ATENDIMENTO</th>
                         <th>DT_SISTEMA</th>
                         <th>ID_RESULT_LIG</th> 
                         <th>DESC_RESULTADO_LIG</th>
                         <th>OBSERVACAO_PESQUISA</th>
                         <th>DESC_ID_TALISMA</th>
                         <th>NUMERO_AVALIACAO</th>
                    
                    </tr> ';

  while($row = sqlsrv_fetch_array($result))
  {


   $output .= '
    <tr>  
                         <td>'.$row["ID_PESQUISA"].'</td>  
                         <td>'.$row["ID_COLABORADOR"].'</td>
                         <td>'.$row["NOME"].'</td>  
                         <td>'.$row["ID_COLABORADOR_APLICA"].'</td>  
                         <td>'.$row["COLABORADOR_APLICA"].'</td>  
                         <td>'.$row["ID_PROCESSO"].'</td>  
                         <td>'.$row["NOME_PROCESSO"].'</td>  
                         <td>'.$row["ID_GRUPO"].'</td>  
                         <td>'.$row["DESC_GRUPO"].'</td>  
                         <td>'.$row["ID_OBJETO_TALISMA"].'</td>  
                         <td>'.$row["DESC_OBJ_TALISMA"].'</td>  
                         <td>'.$row["CPF_MONITORIA"].'</td>  
                         <td>'.$row["RAMAL_PA"].'</td>  
                         <td>'.$row["ID_GRAVADOR"].'</td>
                         <td>'.$row["NOTA_FINAL"].'</td> 
                         <td>'.$row["DT_ATENDIMENTO"].'</td> 
                         <td>'.$row["DT_SISTEMA"].'</td> 
                         <td>'.$row["ID_RESULT_LIG"].'</td>   
                         <td>'.$row["DESC_RESULTADO_LIG"].'</td>  
                         <td>'.$row["OBSERVACAO_PESQUISA"].'</td>  
                         <td>'.$row["DESC_ID_TALISMA"].'</td> 
                         <td>'.$row["NUMERO_AVALIACAO"].'</td> 
                 
    </tr>
   ';
  }

  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=download.xls');
  echo $output;
 


?>  