<?php include '..\CESUDESK\AdmCrm\connectionADM.php'; 




$SENHA_NOVA = $_POST["SENHA_NOVA"];
$ID_MATRICULA = $_POST["ID_MATRICULA"];


if (! isset( $SENHA_NOVA ) ) {
    $SENHA_NOVA = '';
}


$updateSquila = " UPDATE tb_crm_colaborador
                              SET 
                                 PASS_FEEDBACK = '{$SENHA_NOVA}'
                                 
                            WHERE  ID_MATRICULA = {$ID_MATRICULA} ";

  $result_update = sqlsrv_query($conn, $updateSquila);
   sqlsrv_free_stmt($result_update);


 $ID_MATRICULA = base64_encode($ID_MATRICULA);

 echo  '<script type="text/javascript">alert("Senha Atualizada");</script>';
 echo  '<script type="text/javascript"> window.location.href = "Altera_Senha_FeedBack.php?ID_MATRICULA='.$ID_MATRICULA.'" </script>';
?>
