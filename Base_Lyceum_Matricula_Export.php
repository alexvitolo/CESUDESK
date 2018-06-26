
<?php

//conection forçada


$serverName = "172.16.1.145:49241"; //Hostname/IP,...
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
                        ,CC.DT_NASC
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
                        ,CC.DT_INSCRICAO
                        ,CP.PROUNI
                        ,CP.INSCRICAO_USUARIO
                        ,ANT_INSTITUICAO
                        ,ANT_CURSO
                        ,D.DT_INGRESSO AS DATA_RA
                        ,PS.TIPO AS TIPO_BOLETO
                        ,CAST(PS.VENCIMENTO AS DATE) AS DT_VCTO_BOLETO_PS
                        ,PS.DATA_PAGAMENTO AS DT_PGTO_BOLETO_PS
                        --,ANT_SERIE
                        --,ANT_REGIME
                        ,CP.VOUCHER
                        ,CA.LOCAL AS CAMPANHA
                        ,CONVERT(VARCHAR(255),CA.INFORMACAO_CANDIDATO) AS INFO_CAMPANHA
                        ,PC.ALUNO AS INDICADO_POR
                        ,CP.DT_PROVA
                        --,NL.NO_QUESTOES_DISSERT
                        ,CP.COMO_CONHECEU
                        ,PVE.DT_FINALIZACAO AS REALIZADO_VEST
                        --,CC.OBSERVACAO AS CARGA_HORARIA
                        --,CC.MUNICIPIO
                        --,CC.MUNICIPIO_NASC
                        --,P.IDSTATUS            
            ,PLS2.DATA_EMISSAO_BOLETO 
                        
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
  ORDER BY 1,2
  --WHERE EXISTS (SELECT 1 FROM @IDS S WHERE S.IDACOMPANHAMENTO = P.idAcompanhamento )   
--88673";

$result = sqlsrv_prepare($conn, $query);
sqlsrv_execute($result);



  $output = '
                   <table class="table" bordered="1">  
                    <tr>  
                         <th>IDACOMPANHAMENTO</tr>
                         <th>CANDIDATO</tr>
                         <th>CONCURSO</tr>
                         <th>CPF</tr>
                         <th>ALUNO</tr>
                         <th>CURSO_VEST</tr>
                         <th>NOME_COMPL</tr>
                         <th>E_MAIL</tr>
                         <th>SEXO</tr>
                         <th>ESTADO_CIVIL</tr>
                         <th>DDD_FONE_CELULAR</tr>
                         <th>FONE_CELULAR</tr>
                         <th>DT_NASC</tr>
                         <th>CURSO_SUPERIOR</tr>
                         <th>ANOCONCL_2G</tr>
                         <th>DT_INSCRICAO</tr>
                         <th>PROUNI</tr>
                         <th>INSCRICAO_USUARIO</tr>
                         <th>ANT_INSTITUICAO</tr>
                         <th>ANT_CURSO</tr>
                         <th>DATA_RA</tr>
                         <th>TIPO_BOLETO</tr>
                         <th>DT_VCTO_BOLETO_PS</tr>
                         <th>DT_PGTO_BOLETO_PS</tr>
                         <th>VOUCHER</tr>
                         <th>CAMPANHA</tr>
                         <th>INFO_CAMPANHA</tr>
                         <th>INDICADO_POR</tr>
                         <th>DT_PROVA</tr>
                         <th>COMO_CONHECEU</tr>
                         <th>REALIZADO_VEST</tr>
                         <th>DATA_EMISSAO_BOLETO</tr>
                         <th>COD_POLO</tr>
                         <th>NOME_POLO</tr>
                    </tr> ';

  while($row = sqlsrv_fetch_array($result))
  {


   $output .= '
    <tr>   
                         <td>'.$row["IDACOMPANHAMENTO"].'</tr>
                         <td>'.$row["CANDIDATO"].'</tr>
                         <td>'.$row["CONCURSO"].'</tr>
                         <td>'.$row["CPF"].'</tr>
                         <td>'.$row["ALUNO"].'</tr>
                         <td>'.$row["CURSO_VEST"].'</tr>
                         <td>'.$row["NOME_COMPL"].'</tr>
                         <td>'.$row["E_MAIL"].'</tr>
                         <td>'.$row["SEXO"].'</tr>
                         <td>'.$row["ESTADO_CIVIL"].'</tr>
                         <td>'.$row["DDD_FONE_CELULAR"].'</tr>
                         <td>'.$row["FONE_CELULAR"].'</tr>
                         <td>'.$row["DT_NASC"].'</tr>
                         <td>'.$row["CURSO_SUPERIOR"].'</tr>
                         <td>'.$row["ANOCONCL_2G"].'</tr>
                         <td>'.$row["DT_INSCRICAO"].'</tr>
                         <td>'.$row["PROUNI"].'</tr>
                         <td>'.$row["INSCRICAO_USUARIO"].'</tr>
                         <td>'.$row["ANT_INSTITUICAO"].'</tr>
                         <td>'.$row["ANT_CURSO"].'</tr>
                         <td>'.$row["DATA_RA"].'</tr>
                         <td>'.$row["TIPO_BOLETO"].'</tr>
                         <td>'.$row["DT_VCTO_BOLETO_PS"].'</tr>
                         <td>'.$row["DT_PGTO_BOLETO_PS"].'</tr>
                         <td>'.$row["VOUCHER"].'</tr>
                         <td>'.$row["CAMPANHA"].'</tr>
                         <td>'.$row["INFO_CAMPANHA"].'</tr>
                         <td>'.$row["INDICADO_POR"].'</tr>
                         <td>'.$row["DT_PROVA"].'</tr>
                         <td>'.$row["COMO_CONHECEU"].'</tr>
                         <td>'.$row["REALIZADO_VEST"].'</tr>
                         <td>'.$row["DATA_EMISSAO_BOLETO"].'</tr>
                         <td>'.$row["COD_POLO"].'</tr>
                         <td>'.$row["NOME_POLO"].'</tr> 
                 
    </tr>
   ';
  }

  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=download.xls');
  echo $output;
 


?>  