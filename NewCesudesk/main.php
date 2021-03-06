<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "index.php"  </script>'; 
}

// refresh automático pág

echo "<meta HTTP-EQUIV='refresh' CONTENT='320; URL=..\NewCesudesk\Main.php'>";


$USUARIO = $_SESSION['IDLOGIN'];

$squilaResumo = "SELECT TOP 5 CASE 
		                WHEN M.id_usuario ={$USUARIO} THEN 'S' 
		                ELSE 'N' 
		                END POSSUI_COMENT
		                ,T.cd_tarefa
		                ,(SELECT COUNT(1) 
                                   FROM [DB_CRM_CESUDESK].[dbo].[tarefa] 
                                  WHERE tp_statustarefa in ('Aberta', 'Andamento')
                                    AND solicitante_cd_usuario = {$USUARIO}) as COUNT_CHAMADO
                        ,(SELECT COUNT(1)
                          			FROM  [DB_CRM_CESUDESK].[dbo].[tarefa]
                          		   WHERE solicitante_cd_usuario = {$USUARIO}) as TOTAL_CHAMADO
                  FROM [DB_CRM_CESUDESK].[dbo].[tarefa] T
            INNER JOIN [DB_CRM_CESUDESK].[dbo].[mensagem_logs] M ON M.id_tarefa = T.cd_tarefa
            INNER JOIN (SELECT MAX(ML.id) ID_ZICA
								   ,ML.id_tarefa
					      FROM [DB_CRM_CESUDESK].[dbo].[mensagem_logs] ML
			          GROUP BY ML.id_tarefa) XCLEB ON XCLEB.ID_ZICA = M.id 
						                               AND XCLEB.id_tarefa =M.id_tarefa
                 WHERE T.solicitante_cd_usuario = {$USUARIO}
                   AND T.tp_statustarefa in ('Aberta', 'Andamento')
			       AND M.dt_insert is not null
			       AND 'N' = CASE 
		                     WHEN M.id_usuario ={$USUARIO} THEN 'S' 
		                     ELSE 'N' 
		                     END
			       ORDER BY M.dt_insert desc";

$result_squilaResumo= sqlsrv_prepare($conn, $squilaResumo);
sqlsrv_execute($result_squilaResumo);

$VetorResumo = sqlsrv_fetch_array($result_squilaResumo);


if ( ($_SESSION['ACESSO'] <> 1) or ($_SESSION['ACESSO'] =="" ) ) {  // Visão do supervisor chamados abertos

   $TituloGrafico = "Histórico Meus Chamados Abertos";

   $squilaDadosGrafico = "SELECT 
	                              (SELECT COUNT(1) FROM [DB_CRM_CESUDESK].[dbo].[tarefa] WHERE solicitante_cd_usuario = {$USUARIO} AND DATEPART(mm,dh_cadastro) = DATEPART(mm,DateAdd(month, -2, GetDate())) AND DATEPART(year,dh_cadastro) = DATEPART(year,GETDATE()) )as MES1
	                             ,(SELECT COUNT(1) FROM [DB_CRM_CESUDESK].[dbo].[tarefa] WHERE solicitante_cd_usuario = {$USUARIO} AND DATEPART(mm,dh_cadastro) = DATEPART(mm,DateAdd(month, -1, GetDate())) AND DATEPART(year,dh_cadastro) = DATEPART(year,GETDATE()) )as MES3
	                             ,(SELECT COUNT(1) FROM [DB_CRM_CESUDESK].[dbo].[tarefa] WHERE solicitante_cd_usuario = {$USUARIO} AND DATEPART(mm,dh_cadastro) = DATEPART(mm,DateAdd(month, 0, GetDate())) AND DATEPART(year,dh_cadastro) = DATEPART(year,GETDATE()) )as MES2
	                             ,(SELECT COUNT(1) FROM [DB_CRM_CESUDESK].[dbo].[tarefa] WHERE solicitante_cd_usuario = {$USUARIO} AND  DATEPART(mm,dh_fechamento) = DATEPART(mm,DateAdd(month, -2, GetDate())) AND DATEPART(year,dh_fechamento) = DATEPART(year,GETDATE()) )as FIM1
	                             ,(SELECT COUNT(1) FROM [DB_CRM_CESUDESK].[dbo].[tarefa] WHERE solicitante_cd_usuario = {$USUARIO} AND  DATEPART(mm,dh_fechamento) = DATEPART(mm,DateAdd(month, -1, GetDate())) AND DATEPART(year,dh_fechamento) = DATEPART(year,GETDATE()) )as FIM3
	                             ,(SELECT COUNT(1) FROM [DB_CRM_CESUDESK].[dbo].[tarefa] WHERE solicitante_cd_usuario = {$USUARIO} AND  DATEPART(mm,dh_fechamento) = DATEPART(mm,DateAdd(month, 0, GetDate())) AND DATEPART(year,dh_fechamento) = DATEPART(year,GETDATE()) )as FIM2
	                             ,CONCAT('@',DATENAME(mm,DateAdd(month, -2, GetDate())),'@',',@',DATENAME(mm,DateAdd(month, -1, GetDate())),'@',',@',DATENAME(mm,DateAdd(month, 0, GetDate())),'@') as NomeMes";
   
   $squilaDadosGrafico = str_replace("@", '"', $squilaDadosGrafico);
   $result_squilaGrafico= sqlsrv_prepare($conn, $squilaDadosGrafico);
   sqlsrv_execute($result_squilaGrafico);
   
   $VetorGrafico = sqlsrv_fetch_array($result_squilaGrafico);


   $squilaIndicador = "SELECT (SELECT COUNT(1) 
                                   FROM [DB_CRM_CESUDESK].[dbo].[tarefa] 
                                  WHERE tp_statustarefa in ('Aberta', 'Andamento')
                                    AND solicitante_cd_usuario = {$USUARIO}) as COUNT_CHAMADO
                        ,(SELECT COUNT(1)
                          			FROM  [DB_CRM_CESUDESK].[dbo].[tarefa]
                          		   WHERE solicitante_cd_usuario = {$USUARIO} ) as TOTAL_CHAMADO
                        ,(SELECT TOP 1 DT_SISTEMA
                                FROM [DB_CRM_REPORT].[dbo].[tb_loggeduser]
                               WHERE USUARIO = (SELECT USUARIO FROM [DB_CRM_REPORT].[dbo].[tb_crm_login] WHERE ID = {$USUARIO})
                            ORDER BY 1 DESC ) as ULTIMO_ACESSO
                        ,(SELECT avg(DATEDIFF(HOUR,T.dh_cadastro,T.dh_fechamento))
                           FROM (SELECT TOP 20 dh_cadastro,dh_fechamento 
                                       FROM [DB_CRM_CESUDESK].[dbo].[tarefa] 
                                      WHERE dh_fechamento is not null
                                        AND solicitante_cd_usuario = {$USUARIO}
                                   ORDER BY 1 desc) as T ) as TEMPO_MEDIO ";

   $result_squilaIndicador= sqlsrv_prepare($conn, $squilaIndicador);
   sqlsrv_execute($result_squilaIndicador);
   
   $VetorIndicador = sqlsrv_fetch_array($result_squilaIndicador);

}

if ( $_SESSION['ACESSO'] == 1) { // visão ADM, serumo total de chamados abertos

   $TituloGrafico = "Resumo Número de Chamados";

   $squilaDadosGrafico = "SELECT (SELECT COUNT(1) FROM [DB_CRM_CESUDESK].[dbo].[tarefa] WHERE DATEPART(mm,dh_cadastro) =                              DATEPART(mm,DateAdd(month, -2, GetDate())) AND DATEPART(year,dh_cadastro) = DATEPART(year,GETDATE()) )as MES1
	                             ,(SELECT COUNT(1) FROM [DB_CRM_CESUDESK].[dbo].[tarefa] WHERE DATEPART(mm,dh_cadastro) = DATEPART(mm,DateAdd(month, -1, GetDate())) AND DATEPART(year,dh_cadastro) = DATEPART(year,GETDATE()) )as MES3
	                             ,(SELECT COUNT(1) FROM [DB_CRM_CESUDESK].[dbo].[tarefa] WHERE DATEPART(mm,dh_cadastro) = DATEPART(mm,DateAdd(month, 0, GetDate())) AND DATEPART(year,dh_cadastro) = DATEPART(year,GETDATE()) )as MES2
	                             ,(SELECT COUNT(1) FROM [DB_CRM_CESUDESK].[dbo].[tarefa] WHERE DATEPART(mm,dh_fechamento) = DATEPART(mm,DateAdd(month, -2, GetDate())) AND DATEPART(year,dh_fechamento) = DATEPART(year,GETDATE()) )as FIM1
	                             ,(SELECT COUNT(1) FROM [DB_CRM_CESUDESK].[dbo].[tarefa] WHERE DATEPART(mm,dh_fechamento) = DATEPART(mm,DateAdd(month, -1, GetDate())) AND DATEPART(year,dh_fechamento) = DATEPART(year,GETDATE()) )as FIM3
	                             ,(SELECT COUNT(1) FROM [DB_CRM_CESUDESK].[dbo].[tarefa] WHERE DATEPART(mm,dh_fechamento) = DATEPART(mm,DateAdd(month, 0, GetDate())) AND DATEPART(year,dh_fechamento) = DATEPART(year,GETDATE()) )as FIM2
	                             ,CONCAT('@',DATENAME(mm,DateAdd(month, -2, GetDate())),'@',',@',DATENAME(mm,DateAdd(month, -1, GetDate())),'@',',@',DATENAME(mm,DateAdd(month, 0, GetDate())),'@') as NomeMes";
   
   $squilaDadosGrafico = str_replace("@", '"', $squilaDadosGrafico);
   $result_squilaGrafico= sqlsrv_prepare($conn, $squilaDadosGrafico);
   sqlsrv_execute($result_squilaGrafico);
   
   $VetorGrafico = sqlsrv_fetch_array($result_squilaGrafico);


   $squilaIndicador = "SELECT (SELECT COUNT(1) 
                                   FROM [DB_CRM_CESUDESK].[dbo].[tarefa] 
                                  WHERE tp_statustarefa in ('Aberta', 'Andamento')
                                    ) as COUNT_CHAMADO
                        ,(SELECT COUNT(1)
                          			FROM  [DB_CRM_CESUDESK].[dbo].[tarefa]
                          		    ) as TOTAL_CHAMADO
                        ,(SELECT TOP 1 DT_SISTEMA
                                FROM [DB_CRM_REPORT].[dbo].[tb_loggeduser]
                               WHERE USUARIO = (SELECT USUARIO FROM [DB_CRM_REPORT].[dbo].[tb_crm_login] WHERE ID = {$USUARIO})
                            ORDER BY 1 DESC ) as ULTIMO_ACESSO
                        ,(SELECT avg(DATEDIFF(HOUR,T.dh_cadastro,T.dh_fechamento))
                           FROM (SELECT TOP 20 dh_cadastro,dh_fechamento 
                                       FROM [DB_CRM_CESUDESK].[dbo].[tarefa] 
                                      WHERE dh_fechamento is not null order by 1 desc) as T ) as TEMPO_MEDIO";

   $result_squilaIndicador= sqlsrv_prepare($conn, $squilaIndicador);
   sqlsrv_execute($result_squilaIndicador);
   
   $VetorIndicador = sqlsrv_fetch_array($result_squilaIndicador);


}

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
			<li class="active"><a href="main.php"><em class="fa fa-dashboard">&nbsp;</em>Resumo</a></li>

			<?php  if ($_SESSION['ACESSO'] == 1){ ?>

				<li class=""><a href="indicadoresCRM.php"><em class="fa fa-bar-chart">&nbsp;</em>Indicadores</a></li>

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
					<li><a class="" href="TreinamentoCRM.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Documentos CRM
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
				<h1 class="page-header">Dashboard</h1>
			</div>
		</div><!--/.row-->

    	 <?php if ($VetorResumo['POSSUI_COMENT'] == 'N'){ ?>
		    <div class="alert"><span class="fa-icon fa fa-exclamation-triangle"> </span>
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                Você possui Comentários Novos ! Chamado : <?php echo $VetorResumo['cd_tarefa']; ?>
            </div>
         <?php } ?>
		
		<div class="panel panel-container">
			<div class="row">
				<div class="col-xs-6 col-md-3 col-lg-3 no-padding">
					<div class="panel panel-teal panel-widget border-right">
						<div class="row no-padding"><em class="fa fa-xl fa-spin fa-refresh color-blue"></em>
							<div class="large"><?php if ($VetorIndicador['COUNT_CHAMADO'] == ""){ echo "0"; } else { echo $VetorIndicador['COUNT_CHAMADO'];} ?></div>
							<div class="text-muted">Chamados Abertos</div>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-md-3 col-lg-3 no-padding">
					<div class="panel panel-blue panel-widget border-right">
						<div class="row no-padding"><em class="fa fa-xl fa-comments color-orange"></em>
							<div class="large"><?php echo $VetorIndicador['TEMPO_MEDIO']; ?></div>
							<div class="text-muted">Tempo Médio Chamado (Horas)</div>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-md-3 col-lg-3 no-padding">
					<div class="panel panel-orange panel-widget border-right">
						<div class="row no-padding"><em class="fa fa-xl fa-users color-teal"></em>
							<div style="font-size: 38px"><?php echo date_format($VetorIndicador['ULTIMO_ACESSO'], "d-m-Y H:i"); ?></div>
							<div class="text-muted">Último Acesso</div>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-md-3 col-lg-3 no-padding">
					<div class="panel panel-red panel-widget ">
						<div class="row no-padding"><em class="fa fa-xl fa-search color-red"></em>
							<div class="large"><?php if ($VetorIndicador['TOTAL_CHAMADO'] == ""){ echo "0"; }else {echo $VetorIndicador['TOTAL_CHAMADO'];} ?></div>
							<div class="text-muted">Total de Chamados</div>
						</div>
					</div>
				</div>
			</div><!--/.row-->
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<?php echo $TituloGrafico; ?> 
						<ul class="pull-right panel-settings panel-button-tab-right">
							<li class="dropdown"><a class="pull-right dropdown-toggle" data-toggle="dropdown" href="#">
								<em class="fa fa-cogs"></em>
							</a>
							</li>
						</ul>
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
					<div class="panel-body">
						<div class="canvas-wrapper">
							<canvas class="main-chart" id="line-chart" height="200" width="600"></canvas>
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
	<script>
      window.onload = function () {
	      var chart1 = document.getElementById("line-chart").getContext("2d");
	      window.myLine = new Chart(chart1).Line(lineChartData, {
	      responsive: true,
	      scaleLineColor: "rgba(0,0,0,.2)",
	      scaleGridLineColor: "rgba(0,0,0,.05)",
	      scaleFontColor: "#c5c7cc"
	      });
      };

    // Get all elements with class="closebtn"
    var close = document.getElementsByClassName("closebtn");
    var i;

// Loop through all close buttons
for (i = 0; i < close.length; i++) {
    // When someone clicks on a close button
    close[i].onclick = function(){

        // Get the parent of <span class="closebtn"> (<div class="alert">)
        var div = this.parentElement;

        // Set the opacity of div to 0 (transparent)
        div.style.opacity = "0";

        // Hide the div after 600ms (the same amount of milliseconds it takes to fade out)
        setTimeout(function(){ div.style.display = "none"; }, 600);
    }
}


var lineChartData = {
		labels : [<?php echo $VetorGrafico['NomeMes'] ?>],
		datasets : [
			{
				label: "My First dataset",
				fillColor : "rgba(220,220,220,0.2)",
				strokeColor : "rgba(220,220,220,1)",
				pointColor : "rgba(220,220,220,1)",
				pointStrokeColor : "#fff",
				pointHighlightFill : "#fff",
				pointHighlightStroke : "rgba(220,220,220,1)",
				data : [<?php echo $VetorGrafico['MES1'].','.$VetorGrafico['MES3'].','.$VetorGrafico['MES2']; ?>]
			},
			{
				label: "My Second dataset",
				fillColor : "rgba(48, 164, 255, 0.2)",
				strokeColor : "rgba(48, 164, 255, 1)",
				pointColor : "rgba(48, 164, 255, 1)",
				pointStrokeColor : "#fff",
				pointHighlightFill : "#fff",
				pointHighlightStroke : "rgba(48, 164, 255, 1)",
				data : [<?php echo $VetorGrafico['FIM1'].','.$VetorGrafico['FIM3'].','.$VetorGrafico['FIM2']; ?>]
			}
		]

    }


	</script>
		
</body>
</html>