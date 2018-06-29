
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
    



  

$query = "SELECT tq.ID_QUESTAO
                        ,tq.DESCRICAO
                        ,tq.DESC_OBSERVACAO
                        ,CONCAT(tc.DESCRICAO,' - ',tu.DESCRICAO) AS DESC_GRUPO
                        ,tq.PESO
                        ,tq.BO_FALHA_CRITICA
                        ,tq.BO_PARCIAL
                        ,CONVERT(VARCHAR(10),tq.DT_SISTEMA, 103) as DT_SISTEMA
                        ,tp.NOME
                        ,CASE WHEN tq.BO_QUESTAO_ATIVA = 'S' THEN 'ATIVO' ELSE 'DESATIVO' END AS BO_QUESTAO_ATIVA
                        FROM tb_qld_questoes tq
                  INNER JOIN tb_crm_grupo tc ON tc.ID_GRUPO = tq.ID_GRUPO
                  INNER JOIN tb_crm_processo tp ON tp.ID = tq.ID_PROCESSO
                  INNER JOIN tb_crm_unidade tu ON tu.ID_UNIDADE = tc.ID_UNIDADE
                  WHERE tq.BO_QUESTAO_ATIVA = 'S'
                  ORDER BY DESC_GRUPO";

$result = sqlsrv_prepare($conn, $query);
sqlsrv_execute($result);



  $output = '
                   <table class="table" bordered="1">  
                    <tr>  
                         <th>ID_QUESTAO</th>
                         <th>DESCRICAO</th>
                         <th>DESC_OBSERVACAO</th>
                         <th>DESC_GRUPO</th>
                         <th>PESO</th>
                         <th>BO_FALHA_CRITICA</th>
                         <th>BO_PARCIAL</th>
                         <th>DT_SISTEMA</th>
                         <th>NOME</th>
                         <th>BO_QUESTAO_ATIVA</th>
                    
                    </tr> ';

  while($row = sqlsrv_fetch_array($result))
  {


   $output .= '
    <tr>  
                         <td>'.$row["ID_QUESTAO"].'</td>
                         <td>'.$row["DESCRICAO"].'</td>
                         <td>'.$row["DESC_OBSERVACAO"].'</td>
                         <td>'.$row["DESC_GRUPO"].'</td>
                         <td>'.$row["PESO"].'</td>
                         <td>'.$row["BO_FALHA_CRITICA"].'</td>
                         <td>'.$row["BO_PARCIAL"].'</td>
                         <td>'.$row["DT_SISTEMA"].'</td>
                         <td>'.$row["NOME"].'</td>
                         <td>'.$row["BO_QUESTAO_ATIVA"].'</td>

                 
    </tr>
   ';
  }

  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=download.xls');
  echo $output;
 


?>  