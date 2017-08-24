
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
    



  

$query = "SELECT	   tc.ID_COLABORADOR,
					             tc.ID_MATRICULA,
                       tc.NOME,
                       tc.LOGIN_REDE,
                       CONVERT(VARCHAR(8),th.ENTRADA, 24) as ENTRADA,
                       CONVERT(VARCHAR(8),th.SAIDA, 24) as SAIDA,
                       CONVERT(VARCHAR(8),th.CARGA_HORARIO, 24) as CARGA_HORARIO,
                       tc.STATUS_COLABORADOR,
                       (SELECT NOME FROM tb_crm_colaborador WHERE ID_COLABORADOR = tc.ID_COLABORADOR_GESTOR) as NOMEGESTOR,
                       tc.CODIGO_PORTAL,
                       tc.LOGIN_TELEFONIA,
                       CONCAT(tcar.DESCRICAO,' ',tc.NIVEL_CARGO) as CARGO,
                       tg.DESCRICAO as GRUPO,
                       tr.DESCRICAO as REGIAO,
                       tc.EMAIL,
                       tc.TELEFONE,
                       CONVERT(VARCHAR(10),tc.DT_ADMISSAO, 103) as DT_ADMISSAO,
                       CONVERT(VARCHAR(10),tc.DT_NASCIMENTO, 103) as DT_NASCIMENTO,

					   (SELECT convert(varchar,ttp.HORARIO_PAUSA,108) FROM tb_crm_escala_pausa ttp WHERE ttp.ID_COLABORADOR = tc.ID_COLABORADOR AND ttp.ID_TIPO_PAUSA = 1 AND ttp.DT_VIGENCIA_FINAL is null) AS PAUSA1,
					   (SELECT convert(varchar,ttp.HORARIO_PAUSA,108) FROM tb_crm_escala_pausa ttp WHERE ttp.ID_COLABORADOR = tc.ID_COLABORADOR AND ttp.ID_TIPO_PAUSA = 5 AND ttp.DT_VIGENCIA_FINAL is null) AS LANCHE,
					   (SELECT convert(varchar,ttp.HORARIO_PAUSA,108) FROM tb_crm_escala_pausa ttp WHERE ttp.ID_COLABORADOR = tc.ID_COLABORADOR AND ttp.ID_TIPO_PAUSA = 2 AND ttp.DT_VIGENCIA_FINAL is null) AS PAUSA2
					   
                  FROM tb_crm_colaborador tc
      INNER JOIN tb_crm_grupo tg ON tg.ID_GRUPO = tc.ID_GRUPO
       LEFT JOIN tb_crm_regiao tr ON tr.ID_REGIAO = tg.ID_REGIAO
      INNER JOIN tb_crm_cargo tcar ON tcar.ID_CARGO = tc.ID_CARGO 
      INNER JOIN tb_crm_horario th ON th.ID_HORARIO = tc.ID_HORARIO
           WHERE tcar.BO_GESTOR = 'N'
             AND tc.STATUS_COLABORADOR <> 'DESLIGADO'
                 AND tcar.ID_CARGO NOT IN ('1','2','3','4','5')
        ORDER BY STATUS_COLABORADOR, NOME";

$result = sqlsrv_prepare($conn, $query);
sqlsrv_execute($result);



  $output = '
                   <table class="table" bordered="1">  
                    <tr>  
                         <th>ID_COLABORADOR</th>
                         <th>ID_MATRICULA</th>
                         <th>NOME</th>
                         <th>LOGIN_REDE</th>
                         <th>ENTRADA</th>
                         <th>SAIDA</th>
                         <th>CARGA_HORARIO</th>
                         <th>STATUS_COLABORADOR</th>
                         <th>NOME_GESTOR</th>
                         <th>CODIGO_PORTAL</th>
                         <th>LOGIN_TELEFONIA</th>
                         <th>CARGO</th>
                         <th>GRUPO</th>
                         <th>REGIAO</th>
                         <th>EMAIL</th>
                         <th>TELEFONE</th>
                         <th>DT_ADMISSAO</th>
                         <th>DT_NASCIMENTO</th>
                         <th>PAUSA1</th>
                         <th>LANCHE</th>
                         <th>PAUSA2</th>
                    </tr> ';

  while($row = sqlsrv_fetch_array($result))
  {


   $output .= '
    <tr>  
                         <td>'.$row["ID_COLABORADOR"].'</td>  
                         <td>'.$row["ID_MATRICULA"].'</td>
                         <td>'.$row["NOME"].'</td>  
                         <td>'.$row["LOGIN_REDE"].'</td>  
                         <td>'.$row["ENTRADA"].'</td>  
                         <td>'.$row["SAIDA"].'</td>  
                         <td>'.$row["CARGA_HORARIO"].'</td>  
                         <td>'.$row["STATUS_COLABORADOR"].'</td>  
                         <td>'.$row["NOMEGESTOR"].'</td>  
                         <td>'.$row["CODIGO_PORTAL"].'</td>  
                         <td>'.$row["LOGIN_TELEFONIA"].'</td>  
                         <td>'.$row["CARGO"].'</td>  
                         <td>'.$row["GRUPO"].'</td>  
                         <td>'.$row["REGIAO"].'</td>
                         <td>'.$row["EMAIL"].'</td> 
                         <td>'.$row["TELEFONE"].'</td> 
                         <td>'.$row["DT_ADMISSAO"].'</td> 
                         <td>'.$row["DT_NASCIMENTO"].'</td>   
                         <td>'.$row["PAUSA1"].'</td>  
                         <td>'.$row["LANCHE"].'</td>  
                         <td>'.$row["PAUSA2"].'</td>  
                 
    </tr>
   ';
  }

  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=download.xls');
  echo $output;
 


?>  