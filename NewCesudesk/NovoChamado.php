<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

$dataValida = date("Y-m-d" ,strtotime("now"));

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://dd42150:8087/CESUDESK/NewCesudesk/index.php"  </script>'; 
}

 if ( ! isset ($_GET['COD_MODULO'])){
    $sqlCondicao = "";

 }else{
  $sqlCondicao = $_GET['COD_MODULO'];
 }



$squilaProjeto = "SELECT cd_projeto
                        ,desc_projeto
                    FROM DB_CRM_CESUDESK.dbo.projeto
                   WHERE tp_statusprojeto ='Andamento' 
                   ORDER by 1 DESC";

$result_Projeto = sqlsrv_prepare($conn, $squilaProjeto);
sqlsrv_execute($result_Projeto);


$squilaModulo = "SELECT cd_modulo
                        ,desc_modulo
                    FROM DB_CRM_CESUDESK.dbo.modulo
                   WHERE bo_ativo = 0
                ORDER by desc_modulo";

$result_Modulo = sqlsrv_prepare($conn, $squilaModulo);
sqlsrv_execute($result_Modulo);


$squilaTipoTarefa = "SELECT TT.cd_tipotarefa
                           ,TT.desc_tipotarefa
                           ,M.cd_modulo
                           ,M.desc_modulo
                   FROM DB_CRM_CESUDESK.dbo.tipotarefa TT
             INNER JOIN DB_CRM_CESUDESK.dbo.modulo M ON M.cd_modulo = TT.cd_modulo
                  WHERE TT.bo_ativo = 0
                    AND M.cd_modulo = {$sqlCondicao}
               ORDER by desc_tipotarefa";

$result_TipoTarefa = sqlsrv_prepare($conn, $squilaTipoTarefa);
sqlsrv_execute($result_TipoTarefa);

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
			 <form role="form" name="FormCha" method="post" id="formulario" action="ValidaCadastroChamado.php" enctype="multipart/form-data">
				<div class="panel panel-default">
					<div class="panel-body tabs">
						<ul class="nav nav-pills">
							<li class="active" id="litab1"><a href="#tab1" data-toggle="tab">Dados da Solicitação</a></li>
							<li id="litab2"><a href="#tab2" data-toggle="tab">Dados Iniciais</a></li>
							<li id="litab3"><a href="#tab3" data-toggle="tab">Anexos</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade in active" id="tab1">

									<h4>Dados da Solicitação</h4>
									<div class="form-group">
										<label>Projeto</label>
										<select name="PROJETO" class="form-control">
											 <?php while ($row = sqlsrv_fetch_array($result_Projeto)){ ?>
											   <option value="<?php echo $row['cd_projeto']; ?>"><?php echo $row['desc_projeto'] ;?></option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group">
										<label>Solicitação</label>
										<select required name="MODULO" class="form-control" id="MODULO">
											<option>Escolha um Módulo</option>
											<?php while ($row = sqlsrv_fetch_array($result_Modulo)){ ?>
											   <option <?php if ($row['cd_modulo'] == $sqlCondicao){ echo 'selected';} ; ?> value="<?php echo $row['cd_modulo']; ?>"><?php echo $row['desc_modulo'] ;?></option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group">
										<label>Tipo de Tarefa</label>
										<select required <?php if($sqlCondicao <> ""){ echo "autofocus";}; ?> name="TIPO_TAREFA" class="form-control" id="TIPO_TAREFA">
											<?php while ($row = sqlsrv_fetch_array($result_TipoTarefa)){ ?>
											   <option <?php if ($row['cd_tipotarefa'] == $sqlCondicao){ echo 'selected';} ; ?> value="<?php echo $row['cd_tipotarefa']; ?>"><?php echo $row['desc_tipotarefa'] ;?></option>
											<?php } ?>
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

							<div class="tab-pane fade" id="tab2">

								<h4>Dados Iniciais</h4>
								<div class="form-group">
									<label>Data de Cadastro</label>
									<input name="DATA_CADASTRO" class="form-control" type="date" placeholder="" value="<?php echo date('Y-m-d'); ?>" readonly>
								</div>
								<div class="form-group">
									<label>Data de Entrega</label>
									<input name="DATA_ENTREGA" id="dataentrega" min="<?php echo $dataValida ?>" class="form-control" type="date" placeholder="Data da Entrega">
								</div>
									<div class="form-group">
										<label>Prioridade</label>
										<select name="PRIORIDADE" class="form-control">
											<option>0</option>
											<option>1</option>
											<option>2</option>
											<option>3</option>
										</select>
									</div>
		
							</div>
							<div class="tab-pane fade" id="tab3">
								<h4>Anexos</h4>
								<div class="form-group">
									<label>Carregar Anexo</label>
									<input type="file" name="anexo[1]">
									<p class="help-block">Selecione um arquivo para anexar ao chamado.</p>
									<div class="addJS"></div>
								</div>
								<button type="button" id='CriarAnexo'>Adicionar um novo anexo</button>
								<button type="button" id='RemoverAnexo'>Limpar</button>
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

	<script src="js/bootstrap-datepicker.js"></script>
	<script src="js/custom.js"></script>
    <script type="text/javascript" src="js/jquery-1.10.1.js"></script>

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
           	document.getElementById("tab2").className = "tab-pane fade in active";
           	document.getElementById("litab2").className = "active";
   			document.getElementById("tab1").className = "tab-pane fade";
   			document.getElementById("litab1").className = "";
   			document.getElementById("tab3").className = "tab-pane fade";
   			document.getElementById("litab3").className = "";
           	FormCha.DATA_ENTREGA.focus();
           	return false;
           }

           if (FormCha.resumoSoli.value == "") {
           	alert('Preencha o campo "Resumo Solicitação"');
           	document.getElementById("tab1").className = "tab-pane fade in active";
           	document.getElementById("litab1").className = "active";
   			document.getElementById("tab2").className = "tab-pane fade";
   			document.getElementById("litab2").className = "";
   			document.getElementById("tab3").className = "tab-pane fade";
   			document.getElementById("litab3").className = "";
           	FormCha.resumoSoli.focus();
           	return false;
           }

           if (FormCha.descSoli.value == "") {
           	alert('Preencha o campo "Descrição"');
           	document.getElementById("tab1").className = "tab-pane fade in active";
           	document.getElementById("litab1").className = "active";
   			document.getElementById("tab2").className = "tab-pane fade";
   			document.getElementById("litab2").className = "";
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

         

		    $('#MODULO').change(function () {
		        var MODULO = $(this).val();
                window.location.href = "NovoChamado.php?COD_MODULO="+MODULO;

             });
         

	
	
	</script>
		
</body>
</html>