
<?php

//conection forçada


$serverName = "W2K8R2-APP36\CRM_REPORTS"; //Hostname/IP,...
$connectionOptions = array(
    "Database" => "DB_CRM_CESUDESK",
    "Uid" => "usr_cesudesk",
    "PWD" => "ZRioYf68"
);

//Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

if( $conn === false ) {
    die( print_r(sqlsrv_errors(), true)); //See why it fails
}


if (($_POST['DATA_INI'] == '') || ($_POST['DATA_FIM'] == '') ) {
 $_POST['DATA_INI'] = '2015-01-01';
 $_POST['DATA_FIM'] = date("Y-m-d");
}

    
$query = "SELECT T.cd_tarefa
                ,T.desc_tarefa
                ,CONVERT(VARCHAR(10),T.dh_cadastro, 103) as dh_cadastro
                ,CONVERT(VARCHAR(10),T.dh_entrega_prev, 103) as dh_entrega_prev
                ,CONVERT(VARCHAR(10),T.dh_fechamento, 103) as dh_fechamento
                ,T.inf_complementar
                ,T.prioridade
                ,T.qt_horasgastastarefa
                ,T.tem_anexo
                ,T.titulo
                ,T.tp_statustarefa
                ,(SELECT desc_modulo FROM DB_CRM_CESUDESK.DBO.modulo WHERE cd_modulo =T.cd_modulo) as nome_modulo
                ,(SELECT desc_projeto FROM DB_CRM_CESUDESK.DBO.projeto WHERE cd_projeto = T.projeto_cd_projeto) as nome_projeto
                ,(SELECT NOME FROM DB_CRM_REPORT.DBO.tb_crm_login WHERE ID = T.solicitante_cd_usuario) as nome_usuario
                ,(SELECT desc_tipotarefa FROM DB_CRM_CESUDESK.DBO.tipotarefa WHERE cd_tipotarefa = T.cd_tipotarefa) as nome_tipoTarefa
                ,(SELECT NOME FROM DB_CRM_REPORT.DBO.tb_crm_login WHERE ID = TT.cd_usuario) as nome_chamado
           FROM DB_CRM_CESUDESK.dbo.tarefa T
     INNER JOIN [DB_CRM_CESUDESK].[dbo].[tarefa_triagem] TRI ON TRI.tarefa_cd_tarefa = T.cd_tarefa
     INNER JOIN [DB_CRM_CESUDESK].[dbo].[triagem] TT ON TT.idtriagem = TRI.triagens_idtriagem
          WHERE T.dh_cadastro >='{$_POST['DATA_INI']}'
            AND (T.dh_fechamento <='{$_POST['DATA_FIM']}' OR T.dh_fechamento is null )";

$result = sqlsrv_prepare($conn, $query);
sqlsrv_execute($result);



  $output = '
                   <table class="table" bordered="1">  
                    <tr>  
                         <th>cd_tarefa</th>
                         <th>desc_tarefa</th>
                         <th>dh_cadastro</th>
                         <th>dh_entrega_prev</th>
                         <th>dh_fechamento</th>
                         <th>inf_complementar</th>
                         <th>prioridade</th>
                         <th>qt_horasgastastarefa</th>
                         <th>tem_anexo</th>
                         <th>titulo</th>
                         <th>tp_statustarefa</th>
                         <th>nome_modulo</th>
                         <th>processo_seletivo</th>
                         <th>nome_usuario</th>
                         <th>nome_tipoTarefa</th>
                         <th>nome_chamado</th>
                    </tr> ';

  while($row = sqlsrv_fetch_array($result))
  {


   $output .= '
    <tr>  
                         <td>'.$row["cd_tarefa"].'</td>  
                         <td>'.$row["desc_tarefa"].'</td>
                         <td>'.$row["dh_cadastro"].'</td>  
                         <td>'.$row["dh_entrega_prev"].'</td>  
                         <td>'.$row["dh_fechamento"].'</td>  
                         <td>'.$row["inf_complementar"].'</td>  
                         <td>'.$row["prioridade"].'</td>  
                         <td>'.$row["qt_horasgastastarefa"].'</td>  
                         <td>'.$row["tem_anexo"].'</td>  
                         <td>'.$row["titulo"].'</td>  
                         <td>'.$row["tp_statustarefa"].'</td>  
                         <td>'.$row["nome_modulo"].'</td>  
                         <td>'.$row["nome_projeto"].'</td>  
                         <td>'.$row["nome_usuario"].'</td>
                         <td>'.$row["nome_tipoTarefa"].'</td>
                         <td>'.$row["nome_chamado"].'</td> 
    </tr>
   ';
  }

  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=download.xls');
  echo $output;
 


?>  