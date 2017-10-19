<?php
/*
 *
 *	Service: SOAP endpoint
 *	Payload: rpc/encoded
 *	Transport: http
 *	Authentication: none
 */


$db_connection = pg_connect("host=120.0.0.1:5432 dbname=workbase user=postgres password=postgres");

$result = pg_query($db_connection, "SELECT cd_tarefa, desc_tarefa, dh_cadastro, dh_entrega_prev, dh_fechamento, 
                                           inf_complementar, prioridade, qt_horasgastastarefa, tem_anexo, 
                                           titulo, tp_statustarefa, cd_modulo, projeto_cd_projeto, solicitante_cd_usuario, 
                                           cd_tipotarefa
                                      FROM tarefa");

print_r($result);exit;

?>
