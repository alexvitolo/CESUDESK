<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; 
}


$COD_CHAMADO = $_POST['cd_tarefa'];


$squilVisu = "SELECT T.cd_tarefa
                    ,T.desc_tarefa
                    ,T.dh_cadastro
                    ,T.dh_entrega_prev
                    ,T.dh_fechamento
                    ,T.inf_complementar
                    ,T.prioridade
                    ,T.qt_horasgastastarefa
                    ,T.tem_anexo
                    ,T.titulo
                    ,T.tp_statustarefa
                    ,T.cd_modulo
                    ,T.projeto_cd_projeto
                    ,T.solicitante_cd_usuario
                    ,T.cd_tipotarefa
                    ,P.desc_projeto
                    ,M.desc_modulo
                    ,TP.desc_tipotarefa
               FROM DB_CRM_CESUDESK.dbo.tarefa T
         INNER JOIN DB_CRM_CESUDESK.dbo.projeto P ON P.cd_projeto = T.projeto_cd_projeto
         INNER JOIN DB_CRM_CESUDESK.dbo.modulo M ON M.cd_modulo = T.cd_modulo
         INNER JOIN DB_CRM_CESUDESK.dbo.tipotarefa TP ON TP.cd_tipotarefa = T.cd_tipotarefa
              WHERE T.cd_tarefa = {$COD_CHAMADO} ";

$result_squilaVisu = sqlsrv_prepare($conn, $squilVisu);
sqlsrv_execute($result_squilaVisu);

$vetorSQLVisu = sqlsrv_fetch_array($result_squilaVisu);

$dh_cadastro = date_format($vetorSQLVisu['dh_cadastro'], "Y-m-d");
$dh_entrega_prev = date_format($vetorSQLVisu['dh_entrega_prev'], "Y-m-d");

   if ($vetorSQLVisu['dh_fechamento'] <> '' ){
       $dh_fechamento = date_format($vetorSQLVisu['dh_fechamento'], "Y-m-d");
   }else{
   	   $dh_fechamento = '';
   }



$squilAnexo = "SELECT A.cd_tarefa
					 ,B.anexos_id
					 ,C.nm_anexo as NomeArq
                 FROM DB_CRM_CESUDESK.dbo.tarefa A 
           INNER JOIN DB_CRM_CESUDESK.dbo.tarefa_anexo B on B.tarefa_cd_tarefa= A.cd_tarefa
           INNER JOIN DB_CRM_CESUDESK.dbo.anexo C on C.id = B.anexos_id
                WHERE A.cd_tarefa = {$COD_CHAMADO} ";

$result_squilaAnexo = sqlsrv_prepare($conn, $squilAnexo);
sqlsrv_execute($result_squilaAnexo);



?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Lumino - Dashboard</title>
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
				<img src="http://placehold.it/50/30a5ff/fff" class="img-responsive" alt="">
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
			<li class="parent active"><a data-toggle="collapse" href="#sub-item-1">
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
			<li><a href="../planilhatrocas/index.php?USUARIO=<?php echo $_SESSION['USUARIO'] ;?>" target="_blank"><em class="fa fa-calendar">&nbsp;</em> Planilha troca</a></li>
			<li><a href="../AdmCrm/login.php?USUARIO=<?php echo $_SESSION['USUARIO'] ;?>" target="_blank"><em class="fa fa-bar-chart">&nbsp;</em> Schedule</a></li>
			<li><a href="ValidaLogout.php"><em class="fa fa-power-off">&nbsp;</em> Logout</a></li>
		</ul>
	</div><!--/.sidebar-->
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#">
					<em class="fa fa-home"></em>
				</a></li>
				<li class="active">Novo Chamado</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Novo Chamado</h1>
			</div>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h2>Gestão Chamado</h2>
			</div>
			<div class="col-md-10">
			 <form role="form" name="FormCha" method="post" id="formulario" action="ValidaCadastroChamado.php">
				<div class="panel panel-default">
					<div class="panel-body tabs">
						<ul class="nav nav-pills">
							<li class="active" id="litab1"><a href="#tab1" data-toggle="tab">Dados Iniciais</a></li>
							<li id="litab2"><a href="#tab2" data-toggle="tab">Dados da Solicitação</a></li>
							<li id="litab3"><a href="#tab3" data-toggle="tab">Anexos</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade in active" id="tab1">
								<h4>Dados Iniciais</h4>
								<div class="form-group">
									<label>Data de Cadastro</label>
									<input name="" class="form-control" type="date" placeholder="" value="<?php echo $dh_cadastro; ?>" readonly>
								</div>
								<div class="form-group">
									<label>Data de Entrega</label>
									<input name="dataEntrega" id="dataentrega" class="form-control" type="date" value="<?php echo $dh_entrega_prev; ?>" readonly>
								</div>
								<div class="form-group">
									<label>Data Fechamento</label>
									<input name="dataEntrega" id="datafechamento" class="form-control" type="date" value="<?php echo $dh_fechamento; ?>" readonly>
								</div>
									<div class="form-group">
										<label>Prioridade</label>
										<select name="" class="form-control">
											<option><?php echo $vetorSQLVisu['prioridade']; ?></option>
										</select>
									</div>
							</div>

							<div class="tab-pane fade" id="tab2">
								<h4>Dados da Solicitação</h4>
									<div class="form-group">
										<label>Projeto</label>
										<select name="" class="form-control">
											<option><?php echo $vetorSQLVisu['desc_projeto']; ?></option>
										</select>
									</div>
									<div class="form-group">
										<label>Módulo</label>
										<select name="" class="form-control">
											<option><?php echo $vetorSQLVisu['desc_modulo']; ?></option>
										</select>
									</div>
									<div class="form-group">
										<label>Tipo de Tarefa</label>
										<select name="" class="form-control">
											<option><?php echo $vetorSQLVisu['desc_tipotarefa']; ?></option>
										</select>
									</div>
									<div class="form-group">
									    <label>Título (Resumo da Solicitação)</label>
									    <input name="resumoSoli" class="form-control" placeholder="Digite o título do chamado">
								    </div>
								    <div class="form-group">
									    <label>Descrição</label>
									    <textarea name="descSoli" class="form-control" rows="3"></textarea>
								    </div>
							</div>
							<div class="tab-pane fade" id="tab3">
								<h4>Anexos</h4>
								<div class="form-group">
								   <?php while ($row = sqlsrv_fetch_array($result_squilaAnexo)){ ?>
								       <label>Carregar Anexo</label>
									    <a href="ChamadoDownload.php?COD_CHAMADO=<?php echo $row['cd_tarefa']; ?>&ANEXO_ID=<?php echo $row['anexos_id']; ?>"><input type="button" value="<?php echo $row['NomeArq']; ?>" ></input></a><br><br>
                                   <?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div><!--/.panel-->
			   <button type="submit" onclick="return validar()" class="btn btn-primary">Abrir Chamado</button>
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
           if (FormCha.dataEntrega.value == "") {
           	alert('Preencha o campo "Data de Entrega"');
           	document.getElementById("tab1").className = "tab-pane fade in active";
           	document.getElementById("litab1").className = "active";
   			document.getElementById("tab2").className = "tab-pane fade";
   			document.getElementById("litab2").className = "";
   			document.getElementById("tab3").className = "tab-pane fade";
   			document.getElementById("litab3").className = "";
           	FormCha.dataEntrega.focus();
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
        };

        
	</script>
		
</body>
</html>