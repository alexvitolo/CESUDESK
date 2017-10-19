<?php
	// http://www.thiengo.com.br
	// Por: Vinícius Thiengo
	// Em: 25/11/2013
	// Versão: 1.0
	// servidor.php
	include('lib/nusoap.php');
	
	

	
	$servidor = new nusoap_server();
	
	
	$servidor->configureWSDL('urn:Servidor');
	$servidor->wsdl->schemaTargetNamespace = 'urn:Servidor';
	
	
	function exemplo($nome, $idade){
		return($nome.' -> '.$idade);
	}
	
    function GetNumChamados(){
     include '..\TEST_WS\connect_postgres.php'; 
     $result = pg_query($db_connection, "SELECT COUNT(cd_usuario)
	                                       FROM usuario 
	                                      WHERE ativo = true;");
     $vetorSQL = pg_fetch_array($result);

		return(' Numero total de Usuários Ativos '.$vetorSQL['count']);
	}


	
	$servidor->register(
		'exemplo',
		array('nome'=>'xsd:string',
				'idade'=>'xsd:int'),
		array('retorno'=>'xsd:string'),
		'urn:Servidor.exemplo',
		'urn:Servidor.exmeplo',
		'rpc',
		'encoded',
		'Apenas um exemplo utilizando o NuSOAP PHP.'
	);

		$servidor->register(
		'GetNumChamados',
		array('nome'=>'xsd:string'),
		array('retorno'=>'xsd:string'),
		'urn:Servidor.GetNumChamados',
		'urn:Servidor.GetNumChamados',
		'rpc',
		'encoded',
		'Apenas um GetNumChamados utilizando o NuSOAP PHP.'
	);
	
	
	$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
	$servidor->service($HTTP_RAW_POST_DATA);

	$f = fopen('ARQUIVOXML.txt', 'w');
	fwrite($f, $HTTP_RAW_POST_DATA);
	fclose($f);
?>