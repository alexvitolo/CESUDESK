<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; 
}

if ($_SESSION['ACESSO'] <> 1 )  {
 // Ação a ser executada: mata o script e manda uma mensagem
 echo  '<script type="text/javascript"> window.location.href = "index.php"  </script>';
}


$ID_COLABORADOR = $_SESSION['ID_COLABORADOR'];
$ID_LOGIN = $_SESSION['IDLOGIN'];


$squilaChamado = "SELECT T.cd_tarefa
                        ,T.dh_entrega_prev
                        ,T.prioridade
                        ,T.titulo
                        ,T.tp_statustarefa
                        ,T.cd_modulo
                        ,T.projeto_cd_projeto
                        ,T.solicitante_cd_usuario
                        ,T.cd_tipotarefa
                        ,(SELECT USUARIO FROM [DB_CRM_REPORT].[dbo].[tb_crm_login] WHERE ID = T.solicitante_cd_usuario) as NM_SOLICITA
                  FROM DB_CRM_CESUDESK.dbo.tarefa T
                 WHERE T.tp_statustarefa in ('Andamento','Aberta')
              ORDER BY T.tp_statustarefa,T.dh_entrega_prev asc";

$result_squilaChamado = sqlsrv_prepare($conn, $squilaChamado);
sqlsrv_execute($result_squilaChamado);




?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>NewCesudesk - CRM</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	
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
		<!-- <form role="search"> -->
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Search">
			</div>
		<!-- </form> -->
		<ul class="nav menu">
			<li class=""><a href="main.php"><em class="fa fa-dashboard">&nbsp;</em>Resumo</a></li>
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
				</ul>
			</li>
            <?php  if ($_SESSION['ACESSO'] == 1){ ?>
			<li class="parent active"><a data-toggle="collapse" href="#sub-item-2">
				<em class="fa fa-bug">&nbsp;</em> CRM <span data-toggle="collapse" href="#sub-item-2" class="icon pull-right"><em class="fa fa-plus"></em></span>
				</a>
				<ul class="children collapse" id="sub-item-2">
					<li><a class="" href="DistribuirChamados.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Distribuir Chamado
					</a></li>
					<li><a class="" href="TratarChamados.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Tratar Chamados
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
				<li class="active">Meus Chamados</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Triagem</h1>
			</div>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h2>Distribuir Chamado para Equipe CRM</h2>
			</div>
			<div class="col-md-10">
			 <div class="row mt">
                  <div class="col-md-12" style="width: 1050px">
                      <div class="content-panel">
                        <form name="Form" method="post" id="formulario" action="RealizaTriagem.php">
                          <table class="table table-striped table-advance table-hover order-table table-wrapper">
                            <h4><i class="fa fa-right"></i> Tabela de Chamados Abertos e Andamento </h4>
                            <hr>
                              <thead>
                              <tr>
                                  <th><i class=""></i> Código Chamado </th>
                                  <th><i class=""></i> Solicitante </th>
                                  <th><i class=""></i> Título </th>
                                  <th><i class=""></i> Prioridade </th>
                                  <th style="width:110px"><i class=""></i> Data Entrega </th>
                                  <th><i class=""></i> Status </th>
                                  <th><i class=""></i> Direcionar Triagem </th>

                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                              	<?php  while($row = sqlsrv_fetch_array($result_squilaChamado)) { 
                                    if ($row['tp_statustarefa'] == "Fechada") {
                                      $corStatus = "label label-danger label-mini";
                                    }elseif ($row['tp_statustarefa'] == "Aberta") {
                                      $corStatus = "label label-success  label-mini";
                                    }else{
                                      $corStatus = "label label-warning  label-mini";
                                    }

                                    if ( (date_format($row['dh_entrega_prev'],'Y-m-d')) < date('Y-m-d') ) {
                                    	 $CorStatus = 'bgcolor="#ffe6e6"';
                                    }elseif ( (date_format($row['dh_entrega_prev'],'Y-m-d')) == date('Y-m-d') ) {
                                    	$CorStatus = 'bgcolor="#ffffb3"';
                                    }else{
                                    	$CorStatus = '';
                                    }

                                    ?>                               
                                  <td <?php echo $CorStatus ; ?> ><?php echo $row['cd_tarefa']; ?></a></td>
                                  <td <?php echo $CorStatus ; ?> ><?php echo $row['NM_SOLICITA']; ?></a></td>
                                  <td <?php echo $CorStatus ; ?> ><?php echo $row['titulo']; ?></a></td>
                                  <td <?php echo $CorStatus ; ?>  style="text-align:center"><?php echo $row['prioridade']; ?></a></td>
                                  <td <?php echo $CorStatus ; ?> ><?php echo date_format($row['dh_entrega_prev'],'d-m-Y'); ?></a></td>
                                  <td <?php echo $CorStatus ; ?> ><span class="<?php echo $corStatus ?>"><?php echo $row['tp_statustarefa']; ?></a></td></span>
                      
                                  <td <?php echo $CorStatus ; ?> >
                                      <!-- <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button> -->
                                      <button style="margin-left: 50px" class="btn btn-primary btn-xs" type="submit" value="<?php echo $row['cd_tarefa'] ?>"  name="cd_tarefa"><i class="fa fa-pencil"></i></button>
                                  </td>
                              </tr>

                              <?php 
                                     }
                              ?>
                              
                              </tbody>
                          </table>
                        </form>
                      </div><!-- /content-panel -->
                  </div><!-- /col-md-12 -->
              </div><!-- /row -->
			</div><!--/.col-->
		</div><!--/.row-->
	</div>	<!--/.main-->
	
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/chart.min.js"></script>
	<script src="js/chart-data.js"></script>
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

	</script>
		
</body>
</html>