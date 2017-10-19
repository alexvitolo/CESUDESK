<?php
	// http://www.thiengo.com.br
	// Por: Vinícius Thiengo
	// Em: 25/11/2013
	// Versão: 1.0
	// cliente.php
	include('lib/nusoap.php');
	
	
	$cliente = new nusoap_client('http://localhost/cesudesk/TEST_WS/servidor.php?wsdl');
	
	
	$parametros = array('nome'=>'Teste',
						'idade'=>51);

	$parametros2 = array('nome'=>'');  // passar parametros obrigatorios ?
						
		
	$resultado = $cliente->call('exemplo', $parametros);
	$resultado2 = $cliente->call('GetNumChamados');
	
	echo ($resultado);
	echo ($resultado2);
	
?>