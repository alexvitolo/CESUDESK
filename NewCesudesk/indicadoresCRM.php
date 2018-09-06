<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "index.php"  </script>'; 
}

// refresh automático pág

echo "<meta HTTP-EQUIV='refresh' CONTENT='520; URL=..\NewCesudesk\indicadoresCRM.php'>";


$USUARIO = $_SESSION['IDLOGIN'];
$aux = 1;

$squilaEMAILSocket = " SELECT TOP 3 *
                         FROM (
                                 SELECT [ID_SOCKETLABS]
                                     ,[object]
                                     ,[SERVER]
                                     ,[ACCOUNT]
                                     ,[DT_INICIO_PLANO]
                                     ,[DT_FIM_PLANO]
                                     ,[BANDWIDTH_USED]
                                     ,[API_COUNT_USED]
                                     ,[API_COUNT_MAX]
                                     ,[MESSAGE_COUNT]
                                     ,[MESSAGE_COUNT_MAX]
                                     ,[DT_SISTEMA]
                                     ,ROW_NUMBER() OVER(PARTITION BY [SERVER] ORDER BY [ID_SOCKETLABS] DESC) rn
                                 FROM [tlMain].[dbo].[UNICESUMAR_SOCKETLABS] ) a
                      WHERE rn = 1
                   ORDER BY SERVER ASC ";

$result_squilaEMAIL= sqlsrv_prepare($conn2, $squilaEMAILSocket);
sqlsrv_execute($result_squilaEMAIL);

 

while ($row = sqlsrv_fetch_array($result_squilaEMAIL)){ 

	$Server_EMAL_USED[$aux]    = $row['MESSAGE_COUNT'];
	$Server_EMAL_LIMIT[$aux]   = $row['MESSAGE_COUNT_MAX'];
	$Server_EMAIL_NAME[$aux]   = $row['SERVER'];
	$Server_EMAIL_INDICA[$aux] = ($row['MESSAGE_COUNT']/$row['MESSAGE_COUNT_MAX'])*100;
	$Server_EMAIL_INDICA[$aux] = str_replace(",", ".", $Server_EMAIL_INDICA[$aux]);
	$aux++;
}  



// SQL DADOS SMS POR HORA (dividido em 2 sql)


  $data_points_4 = array();

   $sql4 = " SELECT CONCAT('new Date ( Date.UTC (',CONVERT(VARCHAR(10),DATEPART(YEAR, dtCreatedDate)),',', 
                    CONVERT(VARCHAR(10),DATEPART(MONTH, dtCreatedDate)-1),',',
                    CONVERT(VARCHAR(10),DATEPART(DAY, dtCreatedDate)),',',
                    CONVERT(VARCHAR(10),DATEPART(HOUR, dtCreatedDate)+2),
                     '))'
                     ) AS HORA_LEAD
         ,CASE WHEN ((select top 1 count(1) as soma_SMS from tblSMSDetails SMS_ENVIADOS WITH (NOLOCK)
                         where (SMS_ENVIADOS.dtCreatedDate >= CONVERT(date, GETDATE()))
                      group by SUBSTRING(CONVERT(varchar,dtCreatedDate,114),1,2)
                      order by count(1) desc) = count(1)) THEN CONVERT(varchar(MAX),CONCAT(count(1),', indexLabel: @highest@,markerColor: @red@, markerType: @triangle@')) 
               WHEN ((select top 1 count(1) as soma_SMS from tblSMSDetails SMS_ENVIADOS WITH (NOLOCK)
                         where (SMS_ENVIADOS.dtCreatedDate >= CONVERT(date, GETDATE()))
                      group by SUBSTRING(CONVERT(varchar,dtCreatedDate,114),1,2)
                      order by count(1) asc) = count(1)) THEN CONVERT(varchar(MAX),CONCAT(count(1),', indexLabel: @lowest@,markerColor: @DarkSlateGrey@, markerType: @cross@')) 
                ELSE CONVERT(varchar,count(1)) 
              END AS soma_SMS
                    ,CONVERT(VARCHAR(10),DATEPART(HOUR, dtCreatedDate)) as HORA
 
                          from tblSMSDetails SMS_ENVIADOS WITH (NOLOCK)
                         where (SMS_ENVIADOS.dtCreatedDate >= CONVERT(date, GETDATE()))
                      group by CONVERT(VARCHAR(10),DATEPART(YEAR, dtCreatedDate)),
                               CONVERT(VARCHAR(10),DATEPART(MONTH, dtCreatedDate)-1),
                               CONVERT(VARCHAR(10),DATEPART(DAY, dtCreatedDate)),
                               CONVERT(VARCHAR(10),DATEPART(HOUR, dtCreatedDate)+2),
                               DATEPART(HOUR, dtCreatedDate)
                     order by DATEPART(HOUR, dtCreatedDate) asc";
   
   $result_4 = sqlsrv_prepare($conn2, $sql4);
   sqlsrv_execute($result_4);
   
         if (!($result_4)) {
                echo ("Falha na inclusão do registro");
                print_r(sqlsrv_errors());
         }   
   ;

$aux2 = 1;
$mediaSMS2 = 0;
$somaSMS2 = 0;

while($row4 = sqlsrv_fetch_array($result_4))
{
      $aux2 ++;
      $point_4 = array("x" => $row4['HORA_LEAD'], "y" => $row4['soma_SMS']);
      array_push($data_points_4, $point_4);
      $mediaSMS2 = $mediaSMS2 + $row4['soma_SMS'];
      $somaSMS2 = $somaSMS2 + $row4['soma_SMS'];
}

$Data_SMS2 = json_encode($data_points_4, JSON_NUMERIC_CHECK);   // gabiara hehehe

$Data_SMS2 = str_replace('"new', 'new', $Data_SMS2);
$Data_SMS2 = str_replace('y":"', 'y":', $Data_SMS2);
$Data_SMS2 = str_replace('))"', '))', $Data_SMS2); 
$Data_SMS2 = str_replace('@', '"', $Data_SMS2);
$Data_SMS2 = str_replace('""}', '"}', $Data_SMS2);
// print_r($Data_SMS2);exit;
$mediaSMS2 = $mediaSMS2/$aux2;
$mediaSMS2 = round($mediaSMS2,0);




$data_points_3 = array();

   $sql3 = " SELECT CONCAT('new Date ( Date.UTC (',CONVERT(VARCHAR(10),DATEPART(YEAR, dtStatusUpdatedDate)),',', 
                    CONVERT(VARCHAR(10),DATEPART(MONTH, dtStatusUpdatedDate)-1),',',
                    CONVERT(VARCHAR(10),DATEPART(DAY, dtStatusUpdatedDate)),',',
                    CONVERT(VARCHAR(10),DATEPART(HOUR, dtStatusUpdatedDate)+2),
                     '))'
                     ) AS HORA_LEAD
         ,CASE WHEN ((select top 1 count(1) as soma_SMS from tblSMSDetails SMS_ENVIADOS WITH (NOLOCK)
                         where (SMS_ENVIADOS.dtStatusUpdatedDate >= CONVERT(date, GETDATE()))
                      group by SUBSTRING(CONVERT(varchar,dtStatusUpdatedDate,114),1,2)
                      order by count(1) desc) = count(1)) THEN CONVERT(varchar(MAX),CONCAT(count(1),', indexLabel: @highest@,markerColor: @red@, markerType: @triangle@')) 
               WHEN ((select top 1 count(1) as soma_SMS from tblSMSDetails SMS_ENVIADOS WITH (NOLOCK)
                         where (SMS_ENVIADOS.dtStatusUpdatedDate >= CONVERT(date, GETDATE()))
                      group by SUBSTRING(CONVERT(varchar,dtStatusUpdatedDate,114),1,2)
                      order by count(1) asc) = count(1)) THEN CONVERT(varchar(MAX),CONCAT(count(1),', indexLabel: @lowest@,markerColor: @DarkSlateGrey@, markerType: @cross@')) 
                ELSE CONVERT(varchar,count(1)) 
              END AS soma_SMS
                    ,CONVERT(VARCHAR(10),DATEPART(HOUR, dtStatusUpdatedDate)) as HORA
 
                          from tblSMSDetails SMS_ENVIADOS WITH (NOLOCK)
                         where (SMS_ENVIADOS.dtStatusUpdatedDate >= CONVERT(date, GETDATE()))
                      group by CONVERT(VARCHAR(10),DATEPART(YEAR, dtStatusUpdatedDate)),
                               CONVERT(VARCHAR(10),DATEPART(MONTH, dtStatusUpdatedDate)-1),
                               CONVERT(VARCHAR(10),DATEPART(DAY, dtStatusUpdatedDate)),
                               CONVERT(VARCHAR(10),DATEPART(HOUR, dtStatusUpdatedDate)+2),
                               DATEPART(HOUR, dtStatusUpdatedDate)
                     order by DATEPART(HOUR, dtStatusUpdatedDate) asc
 ";
   
   $result_3 = sqlsrv_prepare($conn2, $sql3);
   sqlsrv_execute($result_3);
   
         if (!($result_3)) {
                echo ("Falha na inclusão do registro");
                print_r(sqlsrv_errors());
         }   
   ;
$aux1 = 0;
$mediaSMS = 0;
$somaSMS = 0;

while($row3 = sqlsrv_fetch_array($result_3))
{
      $aux1 ++;
      $point_3 = array("x" => $row3['HORA_LEAD'], "y" => $row3['soma_SMS']);
      array_push($data_points_3, $point_3);
      $mediaSMS = $mediaSMS + $row3['soma_SMS'];
      $somaSMS = $somaSMS + $row3['soma_SMS'];
}

$Data_SMS = json_encode($data_points_3, JSON_NUMERIC_CHECK);   // gabiara hehehe

$Data_SMS = str_replace('"new', 'new', $Data_SMS);
$Data_SMS = str_replace('y":"', 'y":', $Data_SMS);
$Data_SMS = str_replace('))"', '))', $Data_SMS); 
$Data_SMS = str_replace('@', '"', $Data_SMS);
$Data_SMS = str_replace('""}', '"}', $Data_SMS);
// print_r($Data_SMS);exit;
$mediaSMS = $mediaSMS/$aux1;
$mediaSMS = round($mediaSMS,0);
//print_r($media);
// exit;


// alerta email media horas

$squilaEMAILMediaHoras = "SELECT ISNULL(cast(cast(avg(cast(CAST(getdate()-dtDateOfInsertion as datetime) as float)) as datetime) as time),'00:00:00') AvgTime,
							     count(1) as COUNT_EMAIL
                            FROM tblOutgoingOBMs  
                           WHERE bCrash = 0 and nErrorCode = 0 ";

$result_squilaEMAILMediaHoras = sqlsrv_prepare($conn2, $squilaEMAILMediaHoras);
sqlsrv_execute($result_squilaEMAILMediaHoras);

$EMAILMediaHoras = sqlsrv_fetch_array($result_squilaEMAILMediaHoras);

foreach ($EMAILMediaHoras[0] as $key => $value) {
		if ($key == 'date') {
			$horaEmailAlert = $value;
	}
}

$horaEmailAlert = strtotime($horaEmailAlert);
$horaEmailAlert = date('G.i', $horaEmailAlert);
$SomaEmailAlert = $EMAILMediaHoras[1];








$squilaGrafCamp= " SELECT CONCAT('{ y:',COUNT(1),', label:%', B.tCampaignName,'%},') as DATAPOINTGRAF
                     FROM tblOutgoingOBMs A
               INNER JOIN tblCampaignMain B ON B.aCampaignID = A.nCampaignID 
                    WHERE A.bCrash = 0 and A.nErrorCode = 0  
                 GROUP BY nCampaignID,tCampaignName ORDER BY 1 DESC

 ";

$result_squilaGrafCamp = sqlsrv_prepare($conn2, $squilaGrafCamp);
sqlsrv_execute($result_squilaGrafCamp);


$DataGrafCamp = '';

while($row4 = sqlsrv_fetch_array($result_squilaGrafCamp))
{
     $DataGrafCamp .= $row4[0];
}

$DataGrafCamp = str_replace('%', '"', $DataGrafCamp);
$DataGrafCamp = rtrim($DataGrafCamp, ',');





$squilaGrafHistEmail= " SELECT STUFF((  SELECT ',' + CONVERT(varchar,COUNT(*))
                          FROM 
                             (
	                           SELECT CONVERT(date,dtDateOfAction,108) as dtDateOfAction
	                           	  ,VisitHour = DATEPART(HH, dtDateOfAction)
	                             FROM tblOBMReportMailer 
	                             WHERE dtDateOfAction >= CONVERT(date,GETDATE(),103)  
                               ) AS Visits
                         WHERE CONVERT(nchar(10), CAST(CAST(VisitHour AS nchar) + ':00' AS datetime), 108) BETWEEN '06:00' and '20:00'
                      GROUP BY dtDateOfAction
                          	  ,VisitHour
                      ORDER BY dtDateOfAction
                               FOR XML PATH('')  ), 1, 1, '' )
 
 ";

$result_squilaGrafHistEmail = sqlsrv_prepare($conn2, $squilaGrafHistEmail);
sqlsrv_execute($result_squilaGrafHistEmail);


$VetorGrafHistEmail = sqlsrv_fetch_array($result_squilaGrafHistEmail);








?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>NewCesudesk - CRM</title>
	<link rel="shortcut icon" href="icone.ico" >
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="main.css">
	
	<!--Custom Font-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse"><span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span></button>
				<a class="navbar-brand" href="#"><span>New</span>Cesudesk</a>
			</div>
		</div><!-- /.container-fluid -->
	</nav>
	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<div class="profile-sidebar">
			<div class="profile-userpic">
				<img src="imag\people_512.png" class="img-responsive" alt="">
			</div>
			<div class="profile-usertitle">
				<div class="profile-usertitle-name"><?php echo $_SESSION['NOME']; ?></div>
				<div class="profile-usertitle-status"><span class="indicator label-success"></span>Online</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="divider"></div>
		<form role="search">
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Search">
			</div>
		</form>
		<ul class="nav menu">
			<li class=""><a href="main.php"><em class="fa fa-dashboard">&nbsp;</em>Resumo</a></li>
			
			<?php  if ($_SESSION['ACESSO'] == 1){ ?>

				<li class="active"><a href="indicadoresCRM.php"><em class="fa fa-bar-chart">&nbsp;</em>Indicadores</a></li>

			<?php } ?>

			<li class="parent"><a data-toggle="collapse" href="#sub-item-1">
				<em class="fa fa-navicon">&nbsp;</em> Chamados <span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><em class="fa fa-plus"></em></span>
				</a>
				<ul class="children collapse" id="sub-item-1">
					<li><a class="" href="NovoChamado.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Novo Chamado
					</a></li>
					<li><a class="" href="MeusChamados.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Meus Chamados
					</a></li>
					<li><a class="" href="EquipeChamados.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Chamados Equipe
					</a></li>
				</ul>
			</li>
			<?php  if ($_SESSION['ACESSO'] == 1){ ?>
			<li class="parent"><a data-toggle="collapse" href="#sub-item-2">
				<em class="fa fa-bug">&nbsp;</em> CRM <span data-toggle="collapse" href="#sub-item-2" class="icon pull-right"><em class="fa fa-plus"></em></span>
				</a>
				<ul class="children collapse" id="sub-item-2">
					<li><a class="" href="DistribuirChamados.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Distribuir Chamado
					</a></li>
					<li><a class="" href="TratarChamados.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Tratar Chamados
					</a></li>
					<li><a class="" href="ChamadosEncerrados.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Encerrados
					</a></li>
				</ul>
			</li>
			<li class="parent"><a data-toggle="collapse" href="#sub-item-3">
				<em class="fa fa-wrench">&nbsp;</em> Gestão Cesudesk <span data-toggle="collapse" href="#sub-item-2" class="icon pull-right"><em class="fa fa-plus"></em></span>
				</a>
				<ul class="children collapse" id="sub-item-3">
					<li><a class="" href="RelatoriosCesudesk.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Relatórios
					</a></li>
					<li><a class="" href="Modulos.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Módulos
					</a></li>
					<li><a class="" href="Projetos.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Projetos
					</a></li>
					<li><a class="" href="TipoTarefa.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Tipos de Tarefas
					</a></li>
				</ul>
			</li>
		    <?php }; ?>
		    <?php  if ($_SESSION['ACESSO'] == 2){ ?>
			<li class="parent"><a data-toggle="collapse" href="#sub-item-2">
				<em class="fa fa-bookmark">&nbsp;</em> Qualidade <span data-toggle="collapse" href="#sub-item-2" class="icon pull-right"><em class="fa fa-plus"></em></span>
				</a>
				<ul class="children collapse" id="sub-item-2">
					<li><a class="" href="TratarChamados.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Tratar Chamados
					</a></li>
					<li><a class="" href="ChamadosEncerrados.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Encerrados
					</a></li>
                         <li><a class="" href="TodosChamadosQualidade.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Chamados Equipe
					</a></li>
				</ul>
			</li>
			<?php }; ?>
			<li><a href="../planilhatrocas/index.php?USUARIO=<?php echo $_SESSION['USUARIO'] ;?>" target="_blank"><em class="fa fa-calendar">&nbsp;</em> Planilha troca</a></li>
			<li><a href="../AdmCrm/validaSenhaLogin.php" target="_blank"><em class="fa fa-bar-chart">&nbsp;</em> Schedule</a></li>
			<li><a href="ValidaLogout.php"><em class="fa fa-power-off">&nbsp;</em> Logout</a></li>
		</ul>
	</div><!--/.sidebar-->
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#">
					<em class="fa fa-home"></em>
				</a></li>
				<li class="active">Dashboard</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Relatórios - CRM</h1>
			</div>
		</div><!--/.row-->


		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						Emails SocketLabs 
						<ul class="pull-right panel-settings panel-button-tab-right">
							<li class="dropdown"><a class="pull-right dropdown-toggle" data-toggle="dropdown" href="#">
								<em class="fa fa-cogs"></em>
							</a>
							</li>
						</ul>
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
					<div class="panel-body">
						<div class="canvas-wrapper">
							<div id="container-server1" style="width: 300px; height: 200px; float: left; margin: 20px"></div>
                            <div id="container-server2" style="width: 300px; height: 200px; float: left; margin: 20px"></div>
                            <div id="container-server3" style="width: 300px; height: 200px; float: left; margin: 20px"></div>
						</div>
					</div>
				</div>
			</div>
		</div><!--/.row-->


		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						Email - Tempo Médio Espera Outgoing
						<ul class="pull-right panel-settings panel-button-tab-right">
							<li class="dropdown"><a class="pull-right dropdown-toggle" data-toggle="dropdown" href="#">
								<em class="fa fa-cogs"></em>
							</a>
							</li>
						</ul>
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
					<div class="panel-body">
						<div class="canvas-wrapper">
							<div id="container" style="min-width: 310px; max-width: 400px; height: 300px; margin: 0 auto"></div>
							<div id="chartContainer" style="height: 300px; width: 85%;margin-left:150px"></div>
							<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
						</div>
					</div>
				</div>
			</div>
		</div><!--/.row-->



		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						Histórico Envio Email's
						<ul class="pull-right panel-settings panel-button-tab-right">
							<li class="dropdown"><a class="pull-right dropdown-toggle" data-toggle="dropdown" href="#">
								<em class="fa fa-cogs"></em>
							</a>
							</li>
						</ul>
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
					<div class="panel-body">
						<div class="canvas-wrapper">
							<canvas id="bar-chart-grouped" width="800" height="450"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div><!--/.row-->





		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						SMS Enviados 
						<ul class="pull-right panel-settings panel-button-tab-right">
							<li class="dropdown"><a class="pull-right dropdown-toggle" data-toggle="dropdown" href="#">
								<em class="fa fa-cogs"></em>
							</a>
							</li>
						</ul>
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
					<div class="panel-body">
						<div class="canvas-wrapper">
							<div id="chartContainer3" style="height: 300px; width: 100%; margin-top: 5px"></div>
						</div>
					</div>
				</div>
			</div>
		</div><!--/.row-->

		

		
		<div class="row">
			<div class="col-xs-6 col-md-3">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>Conclusão</h4>
						<div class="easypiechart" id="easypiechart-blue" data-percent="92" ><span class="percent">92%</span></div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>Comentários</h4>
						<div class="easypiechart" id="easypiechart-orange" data-percent="65" ><span class="percent">65%</span></div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>Utilização</h4>
						<div class="easypiechart" id="easypiechart-teal" data-percent="56" ><span class="percent">56%</span></div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>Total Acessos</h4>
						<div class="easypiechart" id="easypiechart-red" data-percent="27" ><span class="percent">27%</span></div>
					</div>
				</div>
			</div>
		</div><!--/.row-->
	</div>	<!--/.main-->
	
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/chart.min.js"></script>
	<!-- <script src="js/chart-data.js"></script> -->
	<script src="js/easypiechart.js"></script>
	<script src="js/easypiechart-data.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script src="js/custom.js"></script>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	<script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

<script>
window.onload = function () {

//GRAFICOS DISPAROS SMS

var jsonSMS  = <?php echo $Data_SMS ; ?>;
var jsonSMS2 = <?php echo $Data_SMS2 ; ?>;
var mediaSMS = <?php echo $mediaSMS ; ?>;
var somaSMS  = <?php echo $somaSMS ; ?>;
var somaSMS2 = <?php echo $somaSMS2 ; ?>;
var chart3   = new CanvasJS.Chart("chartContainer3", {
  animationEnabled: true,  
  title:{
    text: "SMS por HORA"
  },
  axisY: {
    title: "Quantidade Enviada",
    suffix: "Un",
    stripLines: [{
      value: 20,
      label: "Média do Dia: "+mediaSMS
    }]
  },
  toolTip: {
    shared: true
  },
  legend: {
    cursor: "pointer",
    verticalAlign: "top",
    horizontalAlign: "center",
    dockInsidePlotArea: true,
    itemclick: toogleDataSeries
  },
  axisX:{
        title: "Horas",
        interval:1, 
        intervalType: "hour",        
        valueFormatString: "hh TT", 
        labelAngle: -75
      },
  data: [{
    type:"line",
    axisYType: "secondary",
    name: "DATA UPDATE: " +somaSMS,
    showInLegend: true,
    type: "spline",
    dataPoints: jsonSMS
  },
  {
    type:"line",
    axisYType: "secondary",
    name: "DATA CRIAÇÃO: " +somaSMS2,
    showInLegend: true,
    type: "spline",
    dataPoints: jsonSMS2
  }]
});
chart3.render();

function toogleDataSeries(e){
  if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
    e.dataSeries.visible = false;
  } else{
    e.dataSeries.visible = true;
  }
  chart3.render();
}




//GRAFICOS EMAIL


var gaugeOptions = {

    chart: {
        type: 'solidgauge'
    },

    title: null,

    pane: {
        center: ['50%', '85%'],
        size: '140%',
        startAngle: -90,
        endAngle: 90,
        background: {
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
            innerRadius: '60%',
            outerRadius: '100%',
            shape: 'arc'
        }
    },

    tooltip: {
        enabled: false
    },

    // the value axis
    yAxis: {
        stops: [
            [0.1, '#00ff33'], // green
            [0.5, '#DDDF0D'], // yellow
            [0.7, '#DF5353'], // red 
            [0.9, '#050303'] // black
        ],
        lineWidth: 0,
        minorTickInterval: null,
        tickAmount: 2,
        title: {
            y: -70
        },
        labels: {
            y: 16
        }
    },

    plotOptions: {
        solidgauge: {
            dataLabels: {
                y: 5,
                borderWidth: 0,
                useHTML: true
            }
        }
    }
};

// The Server1 gauge
var USED  = <?php echo $Server_EMAL_USED[1] ; ?>;
var LIMIT = <?php echo $Server_EMAL_LIMIT[1] ; ?>;
var NAME  = <?php echo $Server_EMAIL_NAME[1] ; ?>;
var INDC  = <?php echo $Server_EMAIL_INDICA[1] ; ?>;

var chartServer1 = Highcharts.chart('container-server1', Highcharts.merge(gaugeOptions, {
    yAxis: {
        min: 0,
        max: LIMIT,
        title: {
            text: 'Servidor '+NAME+ ' ('+INDC+'%)'
        }
    },

    credits: {
        enabled: false
    },

    series: [{
        name: 'Email',
        data: [USED],
        dataLabels: {
            format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' +
                   '<span style="font-size:12px;color:silver">Envios</span></div>'
        },
        tooltip: {
            valueSuffix: ' Envios'
        }
    }]

}));

// The Server2 gauge
var USED2  = <?php echo $Server_EMAL_USED[2] ; ?>;
var LIMIT2 = <?php echo $Server_EMAL_LIMIT[2] ; ?>;
var NAME2  = <?php echo $Server_EMAIL_NAME[2] ; ?>;
var INDC2  = <?php echo $Server_EMAIL_INDICA[2] ; ?>;

var chartServer2 = Highcharts.chart('container-server2', Highcharts.merge(gaugeOptions, {
    yAxis: {
        min: 0,
        max: LIMIT2,
        title: {
            text: 'Servidor '+NAME2+ ' ('+INDC2+'%)'
        }
    },

    credits: {
        enabled: false
    },

    series: [{
        name: 'Email',
        data: [USED2],
        dataLabels: {
            format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' +
                   '<span style="font-size:12px;color:silver">Envios</span></div>'
        },
        tooltip: {
            valueSuffix: ' Envios'
        }
    }]

}));


// The Server3 gauge
var USED3  = <?php echo $Server_EMAL_USED[3] ; ?>;
var LIMIT3 = <?php echo $Server_EMAL_LIMIT[3] ; ?>;
var NAME3  = <?php echo $Server_EMAIL_NAME[3] ; ?>;
var INDC3  = <?php echo $Server_EMAIL_INDICA[3] ; ?>;

var chartServer3 = Highcharts.chart('container-server3', Highcharts.merge(gaugeOptions, {
    yAxis: {
        min: 0,
        max: LIMIT3,
        title: {
            text: 'Servidor '+NAME3+ ' ('+INDC3+'%)'
        }
    },

    credits: {
        enabled: false
    },

    series: [{
        name: 'Email',
        data: [USED3],
        dataLabels: {
            format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' +
                   '<span style="font-size:12px;color:silver">Envios</span></div>'
        },
        tooltip: {
            valueSuffix: ' Envios'
        }
    }]

}));



var MEDIA  = <?php echo $horaEmailAlert ; ?>;
var SOMA_EMAIL = <?php echo $SomaEmailAlert ; ?>;

Highcharts.chart('container', {

    chart: {
        type: 'gauge',
        plotBackgroundColor: null,
        plotBackgroundImage: null,
        plotBorderWidth: 0,
        plotShadow: false
    },

    title: {
        text: 'Tempo Médio Horas - Ultimos 5 dias ('+SOMA_EMAIL+' Emails) '
    },

    pane: {
        startAngle: -150,
        endAngle: 150,
        background: [{
            backgroundColor: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [
                    [0, '#FFF'],
                    [1, '#333']
                ]
            },
            borderWidth: 0,
            outerRadius: '109%'
        }, {
            backgroundColor: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [
                    [0, '#333'],
                    [1, '#FFF']
                ]
            },
            borderWidth: 1,
            outerRadius: '107%'
        }, {
            // default background
        }, {
            backgroundColor: '#DDD',
            borderWidth: 0,
            outerRadius: '105%',
            innerRadius: '103%'
        }]
    },

    // the value axis
    yAxis: {
        min: 0,
        max: 8,

        minorTickInterval: 'auto',
        minorTickWidth: 1,
        minorTickLength: 10,
        minorTickPosition: 'inside',
        minorTickColor: '#666',

        tickPixelInterval: 22,
        tickWidth: 2,
        tickPosition: 'inside',
        tickLength: 1,
        tickColor: '#666',
        labels: {
            step: 2,
            rotation: 'auto'
        },
        title: {
            text: 'HORAS'
        },
        plotBands: [{
            from: 0,
            to: 2,
            color: '#55BF3B' // green
        }, {
            from: 2,
            to: 5,
            color: '#DDDF0D' // yellow
        }, {
            from: 5,
            to: 6,
            color: '#DF5353' // red
        }, {
            from: 6,
            to: 8,
            color: '#000000 ' // black
        }]
    },

    series: [{
        name: 'Horas',
        data: [MEDIA],
        tooltip: {
            valueSuffix: ' h'
        }
    }]

},
);



//var DATAGRAFCAMP  = <?php echo $DataGrafCamp ; ?>;
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	title:{
		text: ''
	},
	data: [{
		type: "funnel",
		indexLabel: "{label} - {y}",
		toolTipContent: "<b>{label}</b>: {y} <b>({percentage}%)</b>",
		neckWidth: 20,
		neckHeight: 0,
		valueRepresents: "area",
		dataPoints: [
			<?php echo $DataGrafCamp ; ?>
		]
	}]
});
calculatePercentage();
chart.render();

function calculatePercentage() {
	var dataPoint = chart.options.data[0].dataPoints;
	var total = SOMA_EMAIL;
	for(var i = 0; i < dataPoint.length; i++) {
		
		chart.options.data[0].dataPoints[i].percentage = ((dataPoint[i].y / total) * 100).toFixed(2);
		
	}
}





new Chart(document.getElementById("bar-chart-grouped"), {
    type: 'bar',
    data: {
      labels: ["06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00"],
      datasets: [
        {
          label: "Média Mensal",
          backgroundColor: "#3e95cd",
          data: [133,221,783,2478]
        }, {
          label: "Hoje",
          backgroundColor: "#8e5ea2",
          data: [<?php echo $VetorGrafHistEmail[0] ; ?>]
        }
      ]
    },
    options: {
      title: {
        display: true,
        text: ' '
      }
    }
});







}
</script>

		
</body>
</html>