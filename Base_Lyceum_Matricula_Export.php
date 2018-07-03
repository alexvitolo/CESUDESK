
<?php

//conection forçada
ini_set('max_execution_time', 180);
ini_set('memory_limit', '512M'); 

$serverName = "172.16.1.145\lyceum"; //Hostname/IP,...
$connectionOptions = array(
    "Database" => "LYCEUM",
    "Uid" => "gid",
    "PWD" => "Fuyt2DZkKV"
);

//Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

if( $conn === false ) {
    die( print_r( sqlsrv_errors(), true)); //See why it fails
}
    
  
  $DT_INI = $_POST["DT_INI"];
  $DT_FIM = $_POST["DT_FIM"];


$query = "SELECT P.IDACOMPANHAMENTO, 
    CC.CANDIDATO,
    CC.CONCURSO,
    cc.CPF,
    D.ALUNO,
                         G.CURSO AS CURSO_VEST
                        ,CC.NOME_COMPL
                        ,CC.E_MAIL
                        ,CC.SEXO
                        ,CC.ESTADO_CIVIL
                        --,CC.CEP
                        --,CC.DDD_FONE_COMERCIAL
                        --,CC.FONE_COMERCIAL
                        --,CC.DDD_FONE_RECADO
                        --,CC.FONE_RECADO
                        ,CC.DDD_FONE_CELULAR
                        ,CC.FONE_CELULAR
                        --,CC.ENDERECO
                        --,CC.END_NUM
                        --,CC.END_COMPL
                        --,CC.BAIRRO
                        --,CC.DDD_FONE
                        --,CC.FONE
                        --,CC.UNIDADE_FISICA
                        --,CC.NOME_PAI
                        --,CC.NOME_MAE
                        ,ISNULL(CC.DT_NASC,'') as DT_NASC
                        --,CC.NACIONALIDADE
                        --,CC.PAIS_NASC
                        --,CC.RG_NUM
                        --,CC.RG_UF
                        ,CC.CURSO_SUPERIOR
                        ,CC.ANOCONCL_2G
                        
                        ,CC.TIPO_INGRESSO
                        --,CC.OBSERVACAO
                        --,CC.NECESSIDADE_ESPECIAL
                        --,CC.REGISTRO_ENEM
                        
                        --,CC.END_PAIS
                        ,ISNULL(CC.DT_INSCRICAO,'') as DT_INSCRICAO
                        ,CP.PROUNI
                        ,CP.INSCRICAO_USUARIO
                        ,ANT_INSTITUICAO
                        ,ANT_CURSO
                        ,ISNULL(D.DT_INGRESSO,'') AS DATA_RA
                        ,PS.TIPO AS TIPO_BOLETO
                        ,ISNULL(CAST(PS.VENCIMENTO AS DATE),'') AS DT_VCTO_BOLETO_PS
                        ,ISNULL(PS.DATA_PAGAMENTO,'') AS DT_PGTO_BOLETO_PS
                        --,ANT_SERIE
                        --,ANT_REGIME
                        ,CP.VOUCHER
                        ,CA.LOCAL AS CAMPANHA
                        ,CONVERT(VARCHAR(255),CA.INFORMACAO_CANDIDATO) AS INFO_CAMPANHA
                        ,PC.ALUNO AS INDICADO_POR
                        ,ISNULL(CP.DT_PROVA,'') as DT_PROVA
                        --,NL.NO_QUESTOES_DISSERT
                        ,CP.COMO_CONHECEU
                        ,ISNULL(PVE.DT_FINALIZACAO,'') AS REALIZADO_VEST
                        --,CC.OBSERVACAO AS CARGA_HORARIA
                        --,CC.MUNICIPIO
                        --,CC.MUNICIPIO_NASC
                        --,P.IDSTATUS            
            ,ISNULL(PLS2.DATA_EMISSAO_BOLETO,'') as DATA_EMISSAO_BOLETO
                        
            ,CC.UNIDADE_FISICA COD_POLO
            ,PQ.NOME NOME_POLO
                    FROM EAD.DBO.PORTAL_ACOMP_CANDIDATO P 
             --CROSS APPLY (SELECT MAX(Z.CANDIDATO) AS CANDIDATO
             --               FROM EAD.DBO.PORTAL_ACOMP_CANDIDATO Z 
             --              WHERE Z.IDACOMPANHAMENTO = P.IDACOMPANHAMENTO) PZ
         OUTER APPLY (SELECT TOP 1 PLS.DATAALTERACAO AS DATA_EMISSAO_BOLETO 
                FROM EAD.DBO.PORTAL_LOG_BOLETO_PROCESSO_SELETIVO PLS 
                WHERE PLS.CANDIDATO = P.CANDIDATO
                AND PLS.CONCURSO = P.CONCURSO
                ORDER BY PLS.DATAALTERACAO DESC) PLS2
              INNER JOIN LYCEUM.DBO.LY_CANDIDATO CC ON CC.CONCURSO = P.CONCURSO AND CC.CANDIDATO = P.CANDIDATO--PZ.CANDIDATO 
              INNER JOIN EAD.DBO.CANDIDATOS_PARCEIRO CP ON CP.CONCURSO = CC.CONCURSO AND CP.INSCRICAO = CC.CANDIDATO 
               LEFT JOIN EAD.DBO.PORTAL_CUPONAGEM_CUPOM PC ON PC.CUPOM = CP.VOUCHER
               LEFT JOIN EAD.DBO.PORTAL_CUPONAGEM_ACAO CA ON PC.CODIGO_ACAO = CA.CODIGO 
               LEFT JOIN EAD.DBO.[PORTAL_CANDIDATOS_NOTAS_LANCADAS] NL ON NL.CONCURSO = CC.CONCURSO AND NL.CANDIDATO = CC.CANDIDATO 
         LEFT OUTER JOIN LYCEUM.DBO.LY_ALUNO D (NOLOCK) ON P.CANDIDATO = D.CANDIDATO 
              INNER JOIN LYCEUM.DBO.LY_CONCURSO E (NOLOCK) ON CC.CONCURSO = E.CONCURSO 
              INNER JOIN LYCEUM.DBO.LY_OPCOES_VEST F (NOLOCK) ON CC.CONCURSO = F.CONCURSO AND P.CANDIDATO = F.CANDIDATO AND F.ORDEM = 1 
              INNER JOIN LYCEUM.DBO.LY_CURSO_VEST G (NOLOCK) ON G.CURSO_VEST = F.CURSO_VEST AND G.CONCURSO = F.CONCURSO AND G.CONCURSO = F.CONCURSO 
               LEFT JOIN EAD.DBO.PORTAL_FINANCEIRO_PS PS ON PS.CANDIDATO = P.CANDIDATO AND PS.CONCURSO = P.CONCURSO 
               LEFT JOIN EAD.DBO.PROVA_ELETRONICA PVE ON PVE.CANDIDATO = P.CANDIDATO
               LEFT JOIN EAD.DBO.PORTAL_PARCEIRO PQ ON PQ.COD_PARCEIRO = CC.UNIDADE_FISICA
                   WHERE P.CONCURSO LIKE '%2018%'
                     AND CC.DT_INSCRICAO BETWEEN '{$DT_INI}' and '{$DT_FIM}'
  ORDER BY 1,2
  --WHERE EXISTS (SELECT 1 FROM @IDS S WHERE S.IDACOMPANHAMENTO = P.idAcompanhamento )   
--88673";

$result = sqlsrv_prepare($conn, $query);
sqlsrv_execute($result);



  $output = '
                   <table class="table" bordered="1">  
                    <tr>  
                         <th>IDACOMPANHAMENTO</th>
                         <th>CANDIDATO</th>
                         <th>CONCURSO</th>
                         <th>CPF</th>
                         <th>ALUNO</th>
                         <th>CURSO_VEST</th>
                         <th>NOME_COMPL</th>
                         <th>E_MAIL</th>
                         <th>SEXO</th>
                         <th>ESTADO_CIVIL</th>
                         <th>DDD_FONE_CELULAR</th>
                         <th>FONE_CELULAR</th>
                         <th>DT_NASC</th>
                         <th>CURSO_SUPERIOR</th>
                         <th>ANOCONCL_2G</th>
                         <th>DT_INSCRICAO</th>
                         <th>PROUNI</th>
                         <th>INSCRICAO_USUARIO</th>
                         <th>ANT_INSTITUICAO</th>
                         <th>ANT_CURSO</th>
                         <th>DATA_RA</th>
                         <th>TIPO_BOLETO</th>
                         <th>DT_VCTO_BOLETO_PS</th>
                         <th>DT_PGTO_BOLETO_PS</th>
                         <th>VOUCHER</th>
                         <th>CAMPANHA</th>
                         <th>INFO_CAMPANHA</th>
                         <th>INDICADO_POR</th>
                         <th>DT_PROVA</th>
                         <th>COMO_CONHECEU</th>
                         <th>REALIZADO_VEST</th>
                         <th>DATA_EMISSAO_BOLETO</th>
                         <th>COD_POLO</th>
                         <th>NOME_POLO</th>
                    </tr> ';

  while($row = sqlsrv_fetch_array($result))
  {


   $output .= '
    <tr>   
                         <td>'.$row["IDACOMPANHAMENTO"].'</td>
                         <td>'.$row["CANDIDATO"].'</td>
                         <td>'.$row["CONCURSO"].'</td>
                         <td>'.$row["CPF"].'</td>
                         <td>'.$row["ALUNO"].'</td>
                         <td>'.$row["CURSO_VEST"].'</td>
                         <td>'.$row["NOME_COMPL"].'</td>
                         <td>'.$row["E_MAIL"].'</td>
                         <td>'.$row["SEXO"].'</td>
                         <td>'.$row["ESTADO_CIVIL"].'</td>
                         <td>'.$row["DDD_FONE_CELULAR"].'</td>
                         <td>'.$row["FONE_CELULAR"].'</td>
                         <td>'.date_format($row["DT_NASC"], "d/m/Y").'</td>
                         <td>'.$row["CURSO_SUPERIOR"].'</td>
                         <td>'.$row["ANOCONCL_2G"].'</td>
                         <td>'.date_format($row["DT_INSCRICAO"], "d/m/Y").'</td>
                         <td>'.$row["PROUNI"].'</td>
                         <td>'.$row["INSCRICAO_USUARIO"].'</td>
                         <td>'.$row["ANT_INSTITUICAO"].'</td>
                         <td>'.$row["ANT_CURSO"].'</td>
                         <td>'.date_format($row["DATA_RA"], "d/m/Y").'</td>
                         <td>'.$row["TIPO_BOLETO"].'</td>
                         <td>'.date_format($row["DT_VCTO_BOLETO_PS"], "d/m/Y").'</td>
                         <td>'.date_format($row["DT_PGTO_BOLETO_PS"], "d/m/Y").'</td>
                         <td>'.$row["VOUCHER"].'</td>
                         <td>'.$row["CAMPANHA"].'</td>
                         <td>'.$row["INFO_CAMPANHA"].'</td>
                         <td>'.$row["INDICADO_POR"].'</td>
                         <td>'.date_format($row["DT_PROVA"], "d/m/Y").'</td>
                         <td>'.$row["COMO_CONHECEU"].'</td>
                         <td>'.date_format($row["REALIZADO_VEST"], "d/m/Y").'</td>
                         <td>'.date_format($row["DATA_EMISSAO_BOLETO"], "d/m/Y").'</td>
                         <td>'.$row["COD_POLO"].'</td>
                         <td>'.$row["NOME_POLO"].'</td> 
                 
    </tr>
   ';
  }

  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=download.xls');
  echo $output;
 


?>  