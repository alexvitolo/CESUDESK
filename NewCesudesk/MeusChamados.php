<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://dd42150:8087/CESUDESK/NewCesudesk/index.php"  </script>'; 
}

$ID_COLABORADOR = $_SESSION['ID_COLABORADOR'];
$ID_LOGIN = $_SESSION['IDLOGIN'];


$squilaChamado = "SELECT TOP 120 cd_tarefa
                                ,dh_entrega_prev
                                ,prioridade
                                ,titulo
                                ,tp_statustarefa
                                ,cd_modulo
                                ,projeto_cd_projeto
                                ,solicitante_cd_usuario
                                ,cd_tipotarefa
                                ,(SELECT ISNULL(CONCAT(SUBSTRING(C.Nome,0,charindex(' ',C.Nome,1)),' '),'') FROM DB_CRM_CESUDESK.dbo.tarefa_triagem A INNER JOIN DB_CRM_CESUDESK.dbo.triagem B ON B.idtriagem = A.triagens_idtriagem AND A.tarefa_cd_tarefa = cd_tarefa INNER JOIN [DB_CRM_REPORT].[dbo].[tb_crm_login] C ON c.ID = B.cd_usuario FOR XML PATH('') )as responsavel
                          FROM DB_CRM_CESUDESK.dbo.tarefa
                         WHERE solicitante_cd_usuario = {$ID_LOGIN}
                      ORDER BY tp_statustarefa,dh_entrega_prev desc";

$result_squilaChamado = sqlsrv_prepare($conn, $squilaChamado);
sqlsrv_execute($result_squilaChamado);



$squilaResumo = "SELECT TOP 5 CASE 
		                WHEN M.id_usuario ={$ID_LOGIN} THEN 'S' 
		                ELSE 'N' 
		                END POSSUI_COMENT
		                ,T.cd_tarefa
                  FROM [DB_CRM_CESUDESK].[dbo].[tarefa] T
            INNER JOIN [DB_CRM_CESUDESK].[dbo].[mensagem_logs] M ON M.id_tarefa = T.cd_tarefa
            INNER JOIN (SELECT MAX(ML.id) ID_ZICA
								   ,ML.id_tarefa
					      FROM [DB_CRM_CESUDESK].[dbo].[mensagem_logs] ML
			          GROUP BY ML.id_tarefa) XCLEB ON XCLEB.ID_ZICA = M.id 
						                               AND XCLEB.id_tarefa =M.id_tarefa
                 WHERE T.solicitante_cd_usuario = {$ID_LOGIN}
                   AND T.tp_statustarefa in ('Aberta', 'Andamento')
			       AND M.dt_insert is not null
			       AND 'N' = CASE 
		                     WHEN M.id_usuario ={$ID_LOGIN} THEN 'S' 
		                     ELSE 'N' 
		                     END
			       ORDER BY M.dt_insert desc";

$result_squilaResumo= sqlsrv_prepare($conn, $squilaResumo);
sqlsrv_execute($result_squilaResumo);

$VetorResumo['cd_tarefa'] = '';
$VetorResumo['POSSUI_COMENT'] = 'S';

while($row = sqlsrv_fetch_array($result_squilaResumo)) {
	$VetorResumo['POSSUI_COMENT'] = 'N';
	$VetorResumo['cd_tarefa'] .= $row['cd_tarefa'];
	$VetorResumo['cd_tarefa'] .=' ,';

}

$VetorResumo['cd_tarefa'] =substr($VetorResumo['cd_tarefa'], 0, -1);



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
	<link rel="stylesheet" type="text/css" href="MeusChamados.css">
	
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
				<li class="active">Meus Chamados</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Meus Chamados</h1>
			</div>
		</div><!--/.row-->

       <?php if ($VetorResumo['POSSUI_COMENT'] == 'N'){ ?>
		    <div class="alert"><span class="fa-icon fa fa-exclamation-triangle"> </span>
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                Você possui Comentários Novos ! Chamado : <?php echo $VetorResumo['cd_tarefa']; ?>
            </div>
       <?php } ?>

		
		<div class="row">
			<div class="col-lg-12">
				<h2>Gestão Chamado</h2>
			</div>
			<div class="col-md-10">
			 <div class="row mt">
                  <div class="col-md-12">
                      <div class="content-panel">
                        <form name="Form" method="post" id="formulario" action="VisualizaChamado.php">
                          <table class="table table-striped table-advance table-hover order-table table-wrapper">
                            <h4><i class="fa fa-right"></i> Tabela Chamados Recentes </h4><br>
                            <a href="RelatorioGeralMeusChamados.php"><button type="button" class="btn btn-primary">Relatório Geral Chamados</button></a>
                            <hr>
                              <thead>
                              <tr>
                                  <th><i class=""></i> Código Chamado </th>
                                  <th><i class=""></i> Título </th>
                                  <th><i class=""></i> Prioridade </th>
                                  <th style="width:110px"><i class=""></i> Data Entrega </th>
                                  <th><i class=""></i> Status </th>
                                  <th style="width:150px"><i class=""></i> Responsável </th>
                                  <th><i class=""></i> Visualizar </th>

                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                              	<?php  while($row = sqlsrv_fetch_array($result_squilaChamado)) { 
                                    if ($row['tp_statustarefa'] == "Fechada") {
                                      $corStatus = "label label-danger label-mini";
                                    }elseif ($row['tp_statustarefa'] == "Andamento") {
                                      $corStatus = "label label-warning  label-mini";
                                    }else{
                                      $corStatus = "label label-success  label-mini";
                                    } 



                                    $COD_AUX ="/".$row['cd_tarefa']."/";

                                    if(preg_match($COD_AUX, $VetorResumo['cd_tarefa'])) {
                                        $CorStatus2 = 'bgcolor="#b3ffb3"';
                                    }else{
                                    	$CorStatus2 = '';
                                    }


                                    ?>                               
                                  <td <?php echo $CorStatus2 ; ?> ><?php echo $row['cd_tarefa']; ?></a></td>
                                  <td <?php echo $CorStatus2 ; ?> ><?php echo $row['titulo']; ?></a></td>
                                  <td <?php echo $CorStatus2 ; ?> ><?php echo $row['prioridade']; ?></a></td>
                                  <td <?php echo $CorStatus2 ; ?> ><?php echo date_format($row['dh_entrega_prev'],'d-m-Y'); ?></a></td>
                                  <td <?php echo $CorStatus2 ; ?> ><span class="<?php echo $corStatus ?>"><?php echo $row['tp_statustarefa']; ?></a></td></span>
                                  <td <?php echo $CorStatus2 ; ?> ><?php echo $row['responsavel']; ?></a></td>
                      
                                  <td <?php echo $CorStatus2 ; ?> >
                                      <!-- <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button> -->
                                      <button style="margin-left: 25px" class="btn btn-primary btn-xs" type="submit" value="<?php echo $row['cd_tarefa'] ?>"  name="cd_tarefa"><i class="fa fa-pencil"></i></button>
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


	</script>
		
</body>
</html>