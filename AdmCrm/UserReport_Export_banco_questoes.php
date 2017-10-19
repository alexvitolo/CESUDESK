
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
    



  

$query = "SELECT tcon.ID_QUESTAO
                ,tc.DESCRICAO AS TIPO_TESTE
                ,tp.NOME AS PROCESSO_TESTE
                ,tcon.DESCRICAO AS DESCRICAO_PERGUNTA
                ,CASE tcon.BO_ATIVO    WHEN 'N' THEN 'DESATIVADA'
                                       WHEN 'S' THEN 'ATIVADA'
                                       END AS STATUS_ATUAL
               ,CASE tcon.DIFICULDADE WHEN 1 THEN 'FACIL'
                                      WHEN 2 THEN 'MEDIO'
                                      WHEN 3 THEN 'DIFICIL'
                                       END AS DIFICULDADE
               ,talt.DESC_RESPOSTA AS ALTERNATIVAS
               ,CASE talt.BO_VERDADEIRO WHEN 'N' THEN 'FALSO'
                                        WHEN 'S' THEN 'VERDADEIRO'
                                      END AS RESPOSTA_CORRETA 
           FROM tb_ava_questoes_conhecimento tcon
      LEFT JOIN tb_ava_conhecimento tc ON tc.ID_CONHECIMENTO = tcon.ID_CONHECIMENTO
      LEFT JOIN tb_crm_processo tp ON tp.ID = tc.ID_PROCESSO
      LEFT JOIN tb_ava_questoes_conhecimento_alt talt ON talt.ID_QUESTAO = tcon.ID_QUESTAO
      
       ORDER BY tc.ID_CONHECIMENTO DESC";

$result = sqlsrv_prepare($conn, $query);
sqlsrv_execute($result);



  $output = '
                   <table class="table" bordered="1">  
                    <tr>  
                         <th>ID_QUESTAO</th>
                         <th>TIPO_TESTE</th>
                         <th>PROCESSO_TESTE</th>
                         <th>DESCRICAO_PERGUNTA</th>
                         <th>STATUS_ATUAL</th>
                         <th>DIFICULDADE</th>
                         <th>ALTERNATIVAS</th>
                         <th>RESPOSTA_CORRETA</th>
                    </tr> ';

  while($row = sqlsrv_fetch_array($result))
  {


   $output .= '
    <tr>  
                         <td>'.$row["ID_QUESTAO"].'</td>  
                         <td>'.$row["TIPO_TESTE"].'</td>
                         <td>'.$row["PROCESSO_TESTE"].'</td>  
                         <td>'.$row["DESCRICAO_PERGUNTA"].'</td>  
                         <td>'.$row["STATUS_ATUAL"].'</td>  
                         <td>'.$row["DIFICULDADE"].'</td> 
                         <td>'.$row["ALTERNATIVAS"].'</td>   
                         <td>'.$row["RESPOSTA_CORRETA"].'</td>             
    </tr>
   ';
  }

  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=download.xls');
  echo $output;
 


?>  