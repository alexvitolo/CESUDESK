s<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

$dataValida = date("Y-m-d" ,strtotime("now"));

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://dd42150:8087/CESUDESK/NewCesudesk/index.php"  </script>'; 
}


if ($_SESSION['ACESSO'] <> 1 )  {
 // Ação a ser executada: mata o script e manda uma mensagem
 echo  '<script type="text/javascript"> window.location.href = "index.php"  </script>';
}


$cd_projeto = $_POST['cd_projeto'];

$squilaEdProjeto= "SELECT cd_projeto
                        ,desc_projeto
                        ,dh_fechamento
                        ,dt_inicio
                        ,inf_complementar
                        ,tp_statusprojeto
  				   FROM [DB_CRM_CESUDESK].[dbo].[projeto]
  				  WHERE cd_projeto = {$cd_projeto}
                  ORDER BY 1 desc";

$result_squilaEdProjeto = sqlsrv_prepare($conn, $squilaEdProjeto);
sqlsrv_execute($result_squilaEdProjeto);

$VetorEditaProjeto = sqlsrv_fetch_array($result_squilaEdProjeto)


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
			<li class="parent active"><a data-toggle="collapse" href="#sub-item-3">
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
				<li class="active">Projetos</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Novo Projeto</h1>
			</div>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h2>Cadastro de um Novo Projeto</h2>
			</div>
			<div class="col-md-10">
			 <form role="form" name="FormCha" method="post" id="formulario" action="ValidaEditaProjeto.php" enctype="multipart/form-data">
				<div class="panel panel-default">
					<div class="panel-body tabs">
						<ul class="nav nav-pills">
							<li class="active" id="litab1"><a href="#tab1" data-toggle="tab">Dados Projeto</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade in active" id="tab1">
								<h4>Dados Iniciais</h4>
								<div class="form-group">
									<label>Descrição do Projeto</label>
									<input name="DESC_PROJETO" class="form-control" placeholder="" value="<?php echo $VetorEditaProjeto['desc_projeto']; ?>" required>
								</div>
								<div class="form-group">
									<label>Data Início</label>
									<input name="DT_INICIO" class="form-control" value="<?php echo date_format($VetorEditaProjeto['dt_inicio'],'Y-m-d'); ?>" type="date" placeholder=""  required>
								</div>
								<div class="form-group">
									<label>Data Fechamento</label>
									<input name="DT_FECHAMENTO" class="form-control" value="<?php echo date_format($VetorEditaProjeto['dh_fechamento'],'Y-m-d'); ?>" type="date" placeholder=""  required>
								</div>
								<div class="form-group">
									<label>Informação Complementar</label>
									<input name="INF_COMPL" class="form-control" value="<?php echo $VetorEditaProjeto['inf_complementar']; ?>" placeholder="Digite Aqui as Informações Complementares" required>
								</div>
								<div class="form-group">
									<label>Status Projeto</label>
									<select name="ANDAMENTO" class="form-control"> 
                                         <option value="<?php echo $VetorEditaProjeto['tp_statusprojeto']; ?>" ><?php echo $VetorEditaProjeto['tp_statusprojeto']; ?></option> 
                                         <option value="Fechado">Fechado</option>
                                         <option value="Andamento">Andamento</option>   
                                   </select>
								</div>
								<input type="hidden" name="COD_PROJETO" value="<?php echo $cd_projeto; ?>">
							</div>
						</div>
				</div><!--/.panel-->
			   <button type="submit" onclick="return validar()" class="btn btn-primary">Atualizar Projeto</button>
			   </form><br><br>
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

	<script language="javascript" type="text/javascript">
	</script>
		
</body>
</html>