<?php include '..\CESUDESK\AdmCrm\connectionADM.php'; 




$SENHA_FEEDBACK = $_POST["SENHA_FEEDBACK"];
$ID_PESQUISA = $_POST["ID_PESQUISA"];


if (! isset( $SENHA_FEEDBACK ) ) {
    $SENHA_FEEDBACK = '';
}


$squiladicaSENHA = "SELECT TC.ID_COLABORADOR,
                      TC.NOME,
                      TC.LOGIN_REDE,
                      TC.PASS_FEEDBACK,
                      tp.FEEDBACK
                 FROM tb_crm_colaborador TC
           INNER JOIN tb_qld_pesquisa tp ON tp.ID_COLABORADOR = TC.ID_COLABORADOR
                WHERE tp.ID_PESQUISA = {$ID_PESQUISA} ";

$result_squilaSENHA = sqlsrv_prepare($conn, $squiladicaSENHA);
sqlsrv_execute($result_squilaSENHA);

$vetorSQL = sqlsrv_fetch_array($result_squilaSENHA);

if ($SENHA_FEEDBACK <> $vetorSQL['PASS_FEEDBACK']) {

	 echo  '<script type="text/javascript">alert("Senha inválida");</script>';
	 exit;
}

if ($vetorSQL['FEEDBACK'] == 'SIM') {

	 echo  '<script type="text/javascript">alert("Consultor já recebeu FeedBack");</script>';
	 exit;
}

else{

		$updateSquila = " UPDATE tb_qld_pesquisa
		                              SET 
		                                 FEEDBACK = 'SIM'
		                                 
		                            WHERE  ID_PESQUISA = {$ID_PESQUISA} ";

		  $result_update = sqlsrv_query($conn, $updateSquila);
		   sqlsrv_free_stmt($result_update);




		 echo  '<script type="text/javascript">alert("Validação FeedBack Concluída");</script>';
		 //echo  '<script type="text/javascript"> window.location.href = "http://d42150:8087/CESUDESK/NewCesudesk/index.php"</script>';
    }

?>
