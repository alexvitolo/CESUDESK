<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

$dataValida = date("Y-m-d" ,strtotime("now"));

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; 
}


if ($_SESSION['ACESSO'] <> 1 )  {
 // Ação a ser executada: mata o script e manda uma mensagem
 echo  '<script type="text/javascript"> window.location.href = "index.php"  </script>';
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
				<li class="active">Módulos</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Novo Módulo</h1>
			</div>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h2>Cadastro de um Novo Módulo</h2>
			</div>
			<div class="col-md-10">
			 <form role="form" name="FormCha" method="post" id="formulario" action="ValidaCadastroModulo.php" enctype="multipart/form-data">
				<div class="panel panel-default">
					<div class="panel-body tabs">
						<ul class="nav nav-pills">
							<li class="active" id="litab1"><a href="#tab1" data-toggle="tab">Dados Modulo</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade in active" id="tab1">
								<h4>Dados Iniciais</h4>
								<div class="form-group">
									<label>Descrição do Módulo</label>
									<input name="DESC_MODULO" class="form-control" placeholder=""  required>
								</div>
								<div class="form-group">
									<label>Informação Complementar</label>
									<input name="INF_COMPL" class="form-control" placeholder="Digite Aqui as Informações Complementares" required>
								</div>
							</div>
						</div>
				</div><!--/.panel-->
			   <button type="submit" onclick="return validar()" class="btn btn-primary">Cadastrar Módulo</button>
			   <button type="reset" class="btn btn-default">Limpar Cadastro</button>
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
		var aux =2 ; //Variavel indice para anexos
		window.onload = function () 
		{
	       var chart1 = document.getElementById("line-chart").getContext("2d");
	       window.myLine = new Chart(chart1).Line(lineChartData, {
	       responsive: true,
	       scaleLineColor: "rgba(0,0,0,.2)",
	       scaleGridLineColor: "rgba(0,0,0,.05)",
	       scaleFontColor: "#c5c7cc"
	       });
        };

       function validar() 
       {           
           if (FormCha.DATA_ENTREGA.value == "") {
           	alert('Preencha o campo "Data de Entrega"');
           	document.getElementById("tab1").className = "tab-pane fade in active";
           	document.getElementById("litab1").className = "active";
   			document.getElementById("tab2").className = "tab-pane fade";
   			document.getElementById("litab2").className = "";
   			document.getElementById("tab3").className = "tab-pane fade";
   			document.getElementById("litab3").className = "";
           	FormCha.DATA_ENTREGA.focus();
           	return false;
           }

           if (FormCha.resumoSoli.value == "") {
           	alert('Preencha o campo "Resumo Solicitação"');
           	document.getElementById("tab2").className = "tab-pane fade in active";
           	document.getElementById("litab2").className = "active";
   			document.getElementById("tab1").className = "tab-pane fade";
   			document.getElementById("litab1").className = "";
   			document.getElementById("tab3").className = "tab-pane fade";
   			document.getElementById("litab3").className = "";
           	FormCha.resumoSoli.focus();
           	return false;
           }

           if (FormCha.descSoli.value == "") {
           	alert('Preencha o campo "Descrição"');
           	document.getElementById("tab2").className = "tab-pane fade in active";
           	document.getElementById("litab2").className = "active";
   			document.getElementById("tab1").className = "tab-pane fade";
   			document.getElementById("litab1").className = "";
   			document.getElementById("tab3").className = "tab-pane fade";
   			document.getElementById("litab3").className = "";
           	FormCha.descSoli.focus();
           	return false;
            }
            return true;
        };

        $('#CriarAnexo').click(function(){
        	$('.addJS').append("<div id='JSid'> <input type='file' name='anexo["+aux+"]'> <p class='help-block'>Selecione um arquivo para anexar ao chamado.</p> </div>");
        	aux++;

        });

        $('#RemoverAnexo').click(function(){
        	$("#JSid").remove();

        });
	
	
	</script>
		
</body>
</html>