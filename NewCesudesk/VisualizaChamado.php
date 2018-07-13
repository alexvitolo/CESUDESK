<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://dd42150:8087/CESUDESK/NewCesudesk/index.php"  </script>'; 
}


$COD_CHAMADO = $_POST['cd_tarefa'];
$_SESSION['chamadoChat'] = $_POST['cd_tarefa'];
$ID_USUARIO = $_SESSION['IDLOGIN'];

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
	<title>NewCesudesk - CRM</title>
	<link rel="shortcut icon" href="icone.ico" >
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="VisualizaChamado.css">
	
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
			 <form role="form" name="FormCha" method="post" id="formulario" action="ValidaTratarChamadosEdita.php" enctype="multipart/form-data">
				<div class="panel panel-default">
					<div class="panel-body tabs">
						<ul class="nav nav-pills">
							<li class="active" id="litab1"><a href="#tab1" data-toggle="tab">Dados Iniciais</a></li>
							<li id="litab2"><a href="#tab2" data-toggle="tab">Dados da Solicitação</a></li>
							<li id="litab3"><a href="#tab3" data-toggle="tab">Anexos</a></li>
							<li id="litab4"><a href="#tab4" data-toggle="tab">Comentários</a></li>
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
										<select name="" class="form-control" readonly>
											<option><?php echo $vetorSQLVisu['prioridade']; ?></option>
										</select>
									</div>
							</div>

							<div class="tab-pane fade" id="tab2">
								<h4>Dados da Solicitação</h4>
									<div class="form-group">
										<label>Projeto</label>
										<select name="" class="form-control" readonly>
											<option><?php echo $vetorSQLVisu['desc_projeto']; ?></option>
										</select>
									</div>
									<div class="form-group">
										<label>Solicitação</label>
										<select name="" class="form-control" readonly>
											<option><?php echo $vetorSQLVisu['desc_modulo']; ?></option>
										</select>
									</div>
									<div class="form-group">
										<label>Tipo de Tarefa</label>
										<select name="" class="form-control" readonly>
											<option><?php echo $vetorSQLVisu['desc_tipotarefa']; ?></option>
										</select>
									</div>
									<div class="form-group">
									    <label>Título (Resumo da Solicitação)</label>
									    <input name="resumoSoli" class="form-control" value="<?php echo $vetorSQLVisu['titulo']; ?>" readonly>
								    </div>
								    <div class="form-group">
									    <label>Descrição</label>
									    <textarea name="descSoli" class="form-control" value="" readonly><?php echo $vetorSQLVisu['desc_tarefa']; ?></textarea>
								    </div>
							</div>
							<div class="tab-pane fade" id="tab3">
								<h4>Anexos</h4>
								<div class="form-group">
								   <?php while ($row = sqlsrv_fetch_array($result_squilaAnexo)){ ?>
								       <label>Carregar Anexo</label>
									    <a href="ChamadoDownload.php?COD_CHAMADO=<?php echo $row['cd_tarefa']; ?>&ANEXO_ID=<?php echo $row['anexos_id']; ?>"><input type="button" value="<?php echo $row['NomeArq']; ?>" ></input></a><br><br>
                                   <?php } ?>

                                   <h4>Adicionar Novo Anexo</h4>
								        <div class="form-group">
								        	<label>Carregar Anexo</label>
								        	<input type="file" name="anexo[1]">
								        	<p class="help-block">Selecione um arquivo para anexar ao chamado.</p><br>
								        	<div class="addJS"></div>
								        </div>
								        <input type="hidden" name="COD_CHAMADO" value="<?php echo $COD_CHAMADO; ?>">

								</div>
							</div>
		    </form>
							<div class="tab-pane fade" id="tab4">
								<h4>Comentários</h4>
								<div class="form-group">
									<form name="form1" id="form1">
                                        <br />
                                        Mensagem: <br />
                                        <textarea name="msg" cols="100" rows="3" id="textareaMSG"></textarea><br />
                                        <a href="#" id="SubChat" onclick="submitChat();">Send</a><br /><br />
                                        </form>
                                        <div class="chatbox">
                                        	<div class="chatlogs">
                                                 <div id="chatlogs">
                                                  LOADING CHATLOG...
                                                 </div>
                                            </div>
                                        </div>
								</div>
							</div>
						</div>
					</div>
					<button type="submit" class="btn btn-primary">Atualizar ANEXO</button>
				</div><!--/.panel-->
			   <br><br>
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


 <script
  src="http://code.jquery.com/jquery-2.2.4.min.js"
  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
  crossorigin="anonymous"></script>

<script>

function submitChat() {
	if(form1.msg.value == '') {
		alert("Campo Mensagem Obrigatório");
		return;
	}
	var idLogin = <?php echo $ID_USUARIO ;?> ;
	var msg = form1.msg.value; 
	var codChamado = <?php echo $COD_CHAMADO ;?> ;
	var xmlhttp = new XMLHttpRequest();
	
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById('chatlogs').innerHTML = xmlhttp.responseText;
		}
	}
	
	xmlhttp.open('GET','ChamadoChatInsert.php?idLogin='+idLogin+'&msg='+msg+'&codChamado='+codChamado,true);
	xmlhttp.send();

}

$(document).ready(function(e){
	$.ajaxSetup({
		cache: false
	});
	setInterval( function(){ $('#chatlogs').load('ChamadoChatLogs.php'); }, 2000 );
});

$("#textareaMSG").keyup(function(event) {
    if (event.keyCode === 13) {
        $("#SubChat").click();
        document.getElementById("textareaMSG").value = "";
    }
});


</script>


</body>
</html>