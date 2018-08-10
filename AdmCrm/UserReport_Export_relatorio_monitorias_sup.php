
<?php

//conection forçada
session_start();

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
    

$ID_COLABORADOR_AVALIADOR = $_SESSION['MATRICULA'];

  



// visão dos ADM e QUALIDADE

if (($_SESSION['ACESSO'] == 1) or ($_SESSION['ACESSO'] == 2) ) { 


$query = "     SELECT tp.ID_PESQUISA
                      ,tp.ID_COLABORADOR
                      ,tc.NOME as NOME_CONSULTOR
                      ,tc.ID_MATRICULA as MATRICULA_CONSULTOR
                      ,tp.ID_COLABORADOR_APLICA
                      ,(SELECT NOME FROM tb_crm_colaborador WHERE ID_COLABORADOR = tp.ID_COLABORADOR_APLICA) as NOME_QUEM_APLICOU
                      ,(SELECT DESCRICAO FROM tb_crm_cargo tcar2 INNER JOIN tb_crm_colaborador tco2 ON tco2.ID_CARGO = tcar2.ID_CARGO WHERE tco2.ID_COLABORADOR = tp.ID_COLABORADOR_APLICA) as CARGO_QUEM_APLICOU
                      ,(SELECT NOME FROM tb_crm_colaborador WHERE ID_COLABORADOR = tc.ID_COLABORADOR_GESTOR) as NOME_SUP
                      ,tp.ID_PROCESSO
                      ,tpro.NOME as NOME_PROCESSO
                      ,tcron.NUMERO as NUMERO_AVALIACAO
                      ,tp.NOTA_FINAL
                      ,tg.DESCRICAO as NOME_GRUPO
                      ,ISNULL((SELECT CONCAT(tq2.DESCRICAO,' ;')
                            FROM DB_CRM_REPORT.dbo.tb_qld_questoes tq2
                      INNER JOIN DB_CRM_REPORT.dbo.tb_qld_itens_questoes tiq2 ON tiq2.ID_QUESTAO = tq2.ID_QUESTAO 
                           WHERE tiq2.ID_PESQUISA = tp.ID_PESQUISA 
                             AND tq2.PESO <> tiq2.NOTA_RESULTADO 
                             AND tq2.BO_FALHA_CRITICA = 'N'  FOR XML PATH('') ),'NAO POSSUI') as ITENS_PONTUADOS

                      ,ISNULL((SELECT CONCAT(tq2.DESCRICAO,' ;')
                            FROM DB_CRM_REPORT.dbo.tb_qld_questoes tq2
                      INNER JOIN DB_CRM_REPORT.dbo.tb_qld_itens_questoes tiq2 ON tiq2.ID_QUESTAO = tq2.ID_QUESTAO 
                           WHERE tiq2.ID_PESQUISA = tp.ID_PESQUISA 
                             AND (tq2.PESO = 0 and tq2.BO_FALHA_CRITICA = 'S' AND tiq2.RESPOSTA = 'S')  FOR XML PATH('') ),'NAO POSSUI') as ITENS_PONTUADOS_CRITICO
                FROM tb_qld_pesquisa tp
          INNER JOIN tb_crm_processo tpro ON tpro.ID = tp.ID_PROCESSO 
          INNER JOIN tb_crm_colaborador tc ON tc.ID_COLABORADOR = tp.ID_COLABORADOR
          INNER JOIN tb_crm_grupo tg ON tg.ID_GRUPO = tp.ID_GRUPO
          INNER JOIN tb_qld_cronograma_avaliacao tcron ON tcron.ID_AVALIACAO = tp.ID_AVALIACAO
            ORDER BY tp.DT_SISTEMA desc  ";

$result = sqlsrv_prepare($conn, $query);
sqlsrv_execute($result);




$TITULO['TITULO'] = 'VISAO GERAL DAS MONITORIAS REALIZADAS - QUALIDADE - PROCESSO ATUAL';




}else{     // visao dos SUP



$query = "     SELECT tp.ID_PESQUISA
                      ,tp.ID_COLABORADOR
                      ,tc.NOME as NOME_CONSULTOR
                      ,tc.ID_MATRICULA as MATRICULA_CONSULTOR
                      ,tp.ID_COLABORADOR_APLICA
                      ,(SELECT NOME FROM tb_crm_colaborador WHERE ID_COLABORADOR = tp.ID_COLABORADOR_APLICA) as NOME_QUEM_APLICOU
                      ,(SELECT DESCRICAO FROM tb_crm_cargo tcar2 INNER JOIN tb_crm_colaborador tco2 ON tco2.ID_CARGO = tcar2.ID_CARGO WHERE tco2.ID_COLABORADOR = tp.ID_COLABORADOR_APLICA) as CARGO_QUEM_APLICOU
                      ,(SELECT NOME FROM tb_crm_colaborador WHERE ID_COLABORADOR = tc.ID_COLABORADOR_GESTOR) as NOME_SUP
                      ,tp.ID_PROCESSO
                      ,tpro.NOME as NOME_PROCESSO
                      ,tcron.NUMERO as NUMERO_AVALIACAO
                      ,tp.NOTA_FINAL
                      ,tg.DESCRICAO as NOME_GRUPO
                      ,ISNULL((SELECT CONCAT(tq2.DESCRICAO,' ;')
                            FROM DB_CRM_REPORT.dbo.tb_qld_questoes tq2
                      INNER JOIN DB_CRM_REPORT.dbo.tb_qld_itens_questoes tiq2 ON tiq2.ID_QUESTAO = tq2.ID_QUESTAO 
                           WHERE tiq2.ID_PESQUISA = tp.ID_PESQUISA 
                             AND tq2.PESO <> tiq2.NOTA_RESULTADO 
                             AND tq2.BO_FALHA_CRITICA = 'N'  FOR XML PATH('') ),'NAO POSSUI') as ITENS_PONTUADOS

                      ,ISNULL((SELECT CONCAT(tq2.DESCRICAO,' ;')
                            FROM DB_CRM_REPORT.dbo.tb_qld_questoes tq2
                      INNER JOIN DB_CRM_REPORT.dbo.tb_qld_itens_questoes tiq2 ON tiq2.ID_QUESTAO = tq2.ID_QUESTAO 
                           WHERE tiq2.ID_PESQUISA = tp.ID_PESQUISA 
                             AND (tq2.PESO = 0 and tq2.BO_FALHA_CRITICA = 'S' AND tiq2.RESPOSTA = 'S')  FOR XML PATH('') ),'NAO POSSUI') as ITENS_PONTUADOS_CRITICO
                FROM tb_qld_pesquisa tp
          INNER JOIN tb_crm_processo tpro ON tpro.ID = tp.ID_PROCESSO 
          INNER JOIN tb_crm_colaborador tc ON tc.ID_COLABORADOR = tp.ID_COLABORADOR
          INNER JOIN tb_crm_grupo tg ON tg.ID_GRUPO = tp.ID_GRUPO
          INNER JOIN tb_qld_cronograma_avaliacao tcron ON tcron.ID_AVALIACAO = tp.ID_AVALIACAO
               WHERE tpro.ATIVO = 1
                 AND tc.ID_COLABORADOR_GESTOR = (SELECT ID_COLABORADOR FROM tb_crm_colaborador WHERE ID_MATRICULA = {$ID_COLABORADOR_AVALIADOR} )
            ORDER BY tp.DT_SISTEMA desc  ";

$result = sqlsrv_prepare($conn, $query);
sqlsrv_execute($result);







$query2 = "     SELECT DISTINCT CONCAT('SUPERVISOR: ',(SELECT NOME FROM tb_crm_colaborador WHERE ID_COLABORADOR = tc.ID_COLABORADOR_GESTOR),'   PROCESSO: ',tpro.NOME,' ') as TITULO
                          FROM tb_qld_pesquisa tp
                    INNER JOIN tb_crm_processo tpro ON tpro.ID = tp.ID_PROCESSO 
                    INNER JOIN tb_crm_colaborador tc ON tc.ID_COLABORADOR = tp.ID_COLABORADOR
                    INNER JOIN tb_qld_cronograma_avaliacao tcron ON tcron.ID_AVALIACAO = tp.ID_AVALIACAO
                         WHERE tpro.ATIVO = 1  
                           AND tc.ID_COLABORADOR_GESTOR = (SELECT ID_COLABORADOR FROM tb_crm_colaborador WHERE ID_MATRICULA = {$ID_COLABORADOR_AVALIADOR} ";

$result2 = sqlsrv_prepare($conn, $query2);
sqlsrv_execute($result2);


$TITULO = sqlsrv_fetch_array($result2);



}





  $output = '
                   <table class="table" bordered="1">  
                    <tr>
                         <th> </th>
                         <th colspan="3">'.$TITULO['TITULO'].'</th>
                         <th> </th>
                    </tr>
                    <tr>  
                         <th>Nome Consultor</th>
                         <th>Matricula Consultor</th>
                         <th>Nome Supervisor</th>
                         <th>Nome do Grupo</th>
                         <th>Numero da Avaliacao</th>
                         <th>Quem Aplicou</th>
                         <th>Itens Pontuados</th>
                         <th>Itens Criticos Contuados</th>
                         <th>Nota Final</th>
                         <th>STATUS</th>
                    </tr> ';

  while($row = sqlsrv_fetch_array($result))
  {

    if ($row['NOTA_FINAL'] < 79 ){
      $COR_STATUS = '#ff6666';

    }elseif( $row['NOTA_FINAL'] >= 79 AND $row['NOTA_FINAL'] < 85 ){
      $COR_STATUS = '#ffff66';

    }else{
      $COR_STATUS = '#85e085';
    }


   $output .= '
    <tr>  
                         <td>'.$row["NOME_CONSULTOR"].'</td>
                         <td>'.$row["MATRICULA_CONSULTOR"].'</td> 
                         <td>'.$row["NOME_SUP"].'</td> 
                         <td>'.$row["NOME_GRUPO"].'</td> 
                         <td>'.$row["NUMERO_AVALIACAO"].'</td> 
                         <td>'.$row["CARGO_QUEM_APLICOU"].'</td> 
                         <td>'.$row["ITENS_PONTUADOS"].'</td>
                         <td>'.$row["ITENS_PONTUADOS_CRITICO"].'</td>
                         <td style="background-color:'.$COR_STATUS.';">'.$row["NOTA_FINAL"].'</td>

                 
    </tr>
   ';
  }

  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=download.xls');
  echo $output;
 


?>  