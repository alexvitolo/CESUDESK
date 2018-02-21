<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; 
}


$COD_CHAMADO = $_POST['cd_tarefa'];
$ID_USUARIO = $_SESSION['IDLOGIN'];

$squilaUsuario= "SELECT ID
                       ,USUARIO
                       ,BO_ATIVO
                       ,NOME
                       ,RECEBE_TRIAGEM
                  FROM [DB_CRM_REPORT].[dbo].[tb_crm_login] 
                 WHERE RECEBE_TRIAGEM = 'S' 
                   AND BO_ATIVO = 'S' ";

$result_squilaUsuario = sqlsrv_prepare($conn, $squilaUsuario);
sqlsrv_execute($result_squilaUsuario);


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
					<?php  if ($_SESSION['ACESSO'] == 1){ ?>
					<li><a class="" href="DistribuirChamados.php">
						<span class="fa fa-arrow-right">&nbsp;</span> Distribuir Chamado
					</a></li>
					<?php }; ?>
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
				<h1 class="page-header">Gerenciador de Chamados</h1>
			</div>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h2>Escolha os colaboradores para receber a triagem</h2>
			</div>
			<div class="col-md-10">
			 <form role="form" name="FormCha" method="post" id="formulario" action="">
				<div class="panel panel-default">
					<div class="panel-body tabs">
						<ul class="nav nav-pills">
							<li class="active" id="litab1"><a href="#tab1" data-toggle="tab">Distribuir Chamados</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade in active" id="tab1">
								<h4>Usuários</h4>                                                                                                                                                                                                                                
								<div class="form-group">
								   <select name="USUARIO_TRIAGEM[]" class="form-control">
											 <?php while ($row = sqlsrv_fetch_array($result_squilaUsuario)){ ?>
											   <option value="<?php echo $row['ID']; ?>"><?php echo $row['NOME'] ;?></option>
											<?php } ?>
								   </select><br>
								   <div class="addJSUsuario"></div>
								</div>
								<button type="button" id='CriarUsuario'>Adicionar um novo Usuário</button>
								<button type="button" id='RemoverUsuario'>Limpar</button>
							</div>
		                </form>
							
						</div>
					</div>
				</div><!--/.panel-->
			   <button type="submit" onclick="return validar()" class="btn btn-primary">Realizar Triagem</button>
			   <button type="" class="btn btn-default">Cancelar</button>
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
		var aux =2 ; //Variavel indice para anexos

		$('#CriarUsuario').click(function(){
        	$('.addJSUsuario').append("<div id='JSidUsuario'> <input type='file' name='anexo["+aux+"]'> <p class='help-block'>Selecione um arquivo para anexar ao chamado.</p> </div>");
        	aux++;

        });

        $('#RemoverUsuario').click(function(){
        	$("#JSidUsuario").remove();

        });
	
	
	</script>

</body>
</html>