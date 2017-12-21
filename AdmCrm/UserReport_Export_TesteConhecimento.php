
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
    



  

$query_test = "SELECT TCON.ID_TESTE
                ,TCON.ID_CONHECIMENTO
                ,tava.DESCRICAO
                ,(SELECT DESCRICAO FROM tb_crm_grupo tg INNER JOIN tb_crm_colaborador tc ON tc.ID_GRUPO = tg.ID_GRUPO and tc.ID_COLABORADOR=TCON.ID_COLABORADOR) as GRUPO 
                ,(SELECT NOME FROM tb_crm_processo WHERE ID = tava.ID_PROCESSO) as PROCESSO
                --,TCON.ID_COLABORADOR
                ,tc.NOME
                ,tc.ID_MATRICULA
                ,TCON.NOTA_FINAL
                ,TCON.QUEM_REALIZOU
                ,tque.DESCRICAO
                ,tque.DIFICULDADE
                ,tqalt.BO_VERDADEIRO as RESPOSTA_CORRETA
                ,CONVERT(VARCHAR(10),TCON.DT_SISTEMA, 103) as DATA_REALIZOU_PROVA
          FROM tb_ava_teste_conhecimento TCON
    INNER JOIN tb_crm_colaborador tc ON tc.ID_COLABORADOR = TCON.ID_COLABORADOR
    INNER JOIN tb_ava_conhecimento tava ON tava.ID_CONHECIMENTO = TCON.ID_CONHECIMENTO
    INNER JOIN tb_ava_teste_conhecimento_resp tresp ON tresp.ID_TESTE = TCON.ID_TESTE
    INNER JOIN tb_ava_questoes_conhecimento tque ON tque.ID_QUESTAO = tresp.ID_QUESTAO
    INNER JOIN tb_ava_questoes_conhecimento_alt tqalt ON tqalt.ID_RESPOSTA = tresp.ID_RESPOSTA";

$result_test = sqlsrv_prepare($conn, $query_test);
sqlsrv_execute($result_test);



  $output = '
                   <table class="table" bordered="1">  
                    <tr>  
                         <th>ID_TESTE</th>
                         <th>ID_CONHECIMENTO</th>
                         <th>DESCRICAO</th>
                         <th>GRUPO</th>
                         <th>PROCESSO</th>
                         <th>NOME</th>
                         <th>ID_MATRICULA</th>
                         <th>NOTA_FINAL</th>
                         <th>QUEM_REALIZOU</th>
                         <th>DESCRICAO</th>
                         <th>DIFICULDADE</th>
                         <th>RESPOSTA_CORRETA</th>
                         <th>DATA_REALIZOU_PROVA</th>
                    </tr> ';

  while($row = sqlsrv_fetch_array($result_test))
  {


   $output .= '
    <tr>  
                         <td>'.$row["ID_TESTE"].'</td>
                         <td>'.$row["ID_CONHECIMENTO"].'</td>  
                         <td>'.$row["DESCRICAO"].'</td>  
                         <td>'.$row["GRUPO"].'</td>  
                         <td>'.$row["PROCESSO"].'</td>  
                         <td>'.$row["NOME"].'</td>
                         <td>'.$row["ID_MATRICULA"].'</td>   
                         <td>'.$row["NOTA_FINAL"].'</td>  
                         <td>'.$row["QUEM_REALIZOU"].'</td>  
                         <td>'.$row["DESCRICAO"].'</td>  
                         <td>'.$row["DIFICULDADE"].'</td>  
                         <td>'.$row["RESPOSTA_CORRETA"].'</td>  
                         <td>'.$row["DATA_REALIZOU_PROVA"].'</td>  
                 
    </tr>
   ';
  }

  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=download.xls');
  echo $output;
 


?>  