<?php include '..\AdmCrm\connectionADM.php'; 
session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://dd42150:8087/CESUDESK/NewCesudesk/index.php"  </script>'; 
}

if ( (date('H:i:s')) >=  (date('H:i:s', strtotime('+55 minute', strtotime($_SESSION['TEMPOSESSION'])))) & ($_SESSION['ACESSO'] <> 1 ) ){
     // Ação a ser executada: encerra a session depois de 15 min
   echo  '<script type="text/javascript"> alert("Tempo de Sessão Expirada"); window.location.href = "http://dd42150:8087/CESUDESK/NewCesudesk/main.php"  </script>'; 
   session_destroy();
 }
 
 $_SESSION['TEMPOSESSION'] = date('H:i:s');




 $squilaUltimoProcesso = "SELECT ID
                          FROM tb_crm_processo 
                          WHERE MODALIDADE = 'Graduação' AND ATIVO = 1";

$result_squilaUltimoProcesso = sqlsrv_prepare($conn, $squilaUltimoProcesso);
sqlsrv_execute($result_squilaUltimoProcesso);
$ID_ULTIMO_PROCESSO = sqlsrv_fetch_array($result_squilaUltimoProcesso);


 if ( ! isset ($_GET['PROCESSO'])){
    $sqlCondicao = "WHERE tp.ID_PROCESSO =".$ID_ULTIMO_PROCESSO['ID'];
    $_GET['PROCESSO'] = $ID_ULTIMO_PROCESSO['ID'];
 }else{
  $sqlCondicao = "WHERE tp.ID_PROCESSO =".$_GET['PROCESSO'];
 }


 $squilaProcessoSelect = "SELECT TOP 6 ID
                       ,NOME
                       ,MODALIDADE
                       ,ATIVO
                FROM tb_crm_processo WHERE NOME not like '4%' ORDER BY DATA_INICIO DESC";

$result_squilaProcessoSelect = sqlsrv_prepare($conn, $squilaProcessoSelect);
sqlsrv_execute($result_squilaProcessoSelect);







$ID_COLABORADOR= $_SESSION['ID_COLABORADOR'];



if (($_SESSION['ACESSO'] == 1) or ($_SESSION['ACESSO'] == 2) ) { 

      $sqlValidaAnexo ="SELECT tp.ID_PESQUISA
                                    ,tc.nome as NOME_COLABORADOR
                                    ,(SELECT NOME FROM [DB_CRM_REPORT].[dbo].[tb_crm_colaborador] WHERE ID_COLABORADOR = tp.ID_COLABORADOR_APLICA) as QUEM_APLICOU
                                    , tpro.NOME as PROCESSO
                                    ,(SELECT NUMERO FROM [DB_CRM_REPORT].[dbo].[tb_qld_cronograma_avaliacao] WHERE ID_AVALIACAO = tp.ID_AVALIACAO) as AVALIACAO
                                    ,tc.ID_COLABORADOR_GESTOR
                                    ,tp.NOTA_FINAL
                              FROM [DB_CRM_REPORT].[dbo].[tb_qld_pesquisa] tp
                        INNER JOIN [DB_CRM_REPORT].[dbo].[tb_crm_colaborador] tc ON tc.ID_COLABORADOR = tp.ID_COLABORADOR
                        INNER JOIN [DB_CRM_REPORT].[dbo].[tb_qld_pesquisa_anexo] tpa ON tpa.ID_PESQUISA = tp.ID_PESQUISA
                        INNER JOIN [DB_CRM_REPORT].[dbo].[tb_crm_processo] tpro ON tpro.ID = tp.ID_PROCESSO
                        ".$sqlCondicao."
                        ";

          $result_squilaAnexo = sqlsrv_prepare($conn, $sqlValidaAnexo);
           sqlsrv_execute($result_squilaAnexo);


}else{


      $sqlValidaAnexo ="SELECT tp.ID_PESQUISA
                                    ,tc.nome as NOME_COLABORADOR
                                    ,(SELECT NOME FROM [DB_CRM_REPORT].[dbo].[tb_crm_colaborador] WHERE ID_COLABORADOR = tp.ID_COLABORADOR_APLICA) as QUEM_APLICOU
                                    , tpro.NOME as PROCESSO
                                    ,(SELECT NUMERO FROM [DB_CRM_REPORT].[dbo].[tb_qld_cronograma_avaliacao] WHERE ID_AVALIACAO = tp.ID_AVALIACAO) as AVALIACAO
                                    ,tc.ID_COLABORADOR_GESTOR
                                    ,tp.NOTA_FINAL
                              FROM [DB_CRM_REPORT].[dbo].[tb_qld_pesquisa] tp
                        INNER JOIN [DB_CRM_REPORT].[dbo].[tb_crm_colaborador] tc ON tc.ID_COLABORADOR = tp.ID_COLABORADOR
                        INNER JOIN [DB_CRM_REPORT].[dbo].[tb_qld_pesquisa_anexo] tpa ON tpa.ID_PESQUISA = tp.ID_PESQUISA
                        INNER JOIN [DB_CRM_REPORT].[dbo].[tb_crm_processo] tpro ON tpro.ID = tp.ID_PROCESSO
                            ".$sqlCondicao." AND tc.ID_COLABORADOR_GESTOR = {$ID_COLABORADOR} ";

          $result_squilaAnexo = sqlsrv_prepare($conn, $sqlValidaAnexo);
           sqlsrv_execute($result_squilaAnexo);


}


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Analytics EAD</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link rel="shortcut icon" href="icone.ico" >
    <link rel="stylesheet" href="..\AdmCrm\general.css">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        
    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

  <section id="container" >
      <!-- **********************************************************************************************************************************************************
      TOP BAR CONTENT & NOTIFICATIONS
      *********************************************************************************************************************************************************** -->
      <!--header start-->
      <header class="header black-bg">
              <div class="sidebar-toggle-box">
                  <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
              </div>
            <!--logo start-->
            <a href="index.php" class="logo"><b> GCO – Gestão de Controle Operacional </b></a>
            <!--logo end-->
            <div class="nav notify-row" id="top_menu">
                <!--  notification start -->
                <ul class="nav top-menu">
                    <!-- settings start -->
                    <!-- settings end -->
                    <!-- inbox dropdown start-->
                    <!-- inbox dropdown end -->
                </ul>
                <!--  notification end -->
            </div>
            <div class="top-menu">
              <ul class="nav pull-right top-menu">
                    <li><a class="logout" href="validaLogout.php">Logout</a></li>
              </ul>
            </div>
        </header>
      <!--header end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN SIDEBAR MENU
      *********************************************************************************************************************************************************** -->
      <!--sidebar start-->
      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu" id="nav-accordion">
              
                  <p class="centered"><a href=""><img src="assets/img/ui-sam.gif" class="img-circle" width="60"></a></p>
                  <h5 class="centered">Analytics EAD</h5>
                    
                  <li class="sub-menu"">
                      <a class="" href="javascript:;" >
                          <i class="fa fa-dashboard"></i>
                          <span>Home</span>
                      </a>
                      <ul class ="sub">
                          <li class=""><a  href="index.php">Resumo</a></li>
                          <li class=""><a  href="DashboardQualidade.php">Dasboard Qualidade</a></li>
                          <li class=""><a  href="DashboardDiscador.php">Dasboard Discador</a></li>
                          <li class=""><a  href="DashboardMicroGestao.php">Dasboard Micro Gestão</a></li>
                          <li class=""><a  href="DashboardTrocaHorario.php">Dasboard Troca Horário</a></li>
                      </ul>
                  </li>

                  <li class="sub-menu">
                      <a class="" href="javascript:;">
                          <i class="fa fa-th"></i>
                          <span>Schedule</span>
                      </a>
                      <ul class="sub">
                          <li class=""><a  href="listaColaboradores.php">Lista Colaboradores</a></li>
                          <li class=""><a  href="escalaPausa.php"> Escala de pausa </a></li>
                          <li class=""><a  href="escalaFinalSemana.php"> Escala Final de Semana </a></li>
                           <li class=""><a  href="dadosGestores.php"> Dados Gestores </a></li>
                          <li class=""><a  href="cadastroColaborador.php"> Sugestão Novo Colaborador </a></li>
                          
                      </ul>
                  </li>

                   <li class="sub-menu">
                      <a class="active" href="javascript:;">
                          <i class="fa fa-book"></i>
                          <span>Monitoria</span>
                      </a>
                      <ul class="sub">
                          <li class=""><a  href="formularioAvaliacao.php"> Formulário de Avaliação </a></li>
                          <li class="active"><a  href="AnexosMonitorias.php"> Anexos Monitorias </a></li>
                          
                      </ul>
                  </li>



                  <?php if (($_SESSION['ACESSO'] == 1) or ($_SESSION['ACESSO'] == 2) ) { ?>
                      <li class="sub-menu">
                      <a class="" href="javascript:;" >
                          <i class="fa fa-signal"></i>
                          <span>Qualidade</span> 
                      </a> <?php } ?>
                      <ul class="sub">
                          <li class=""><a  href="questoesMonitoria.php">Questões Monitoria</a></li>
                          <li class=""><a  href="monitoriaRealizada.php">Monitoria Realizadas</a></li>
                          <li class=""><a  href="cronogramaAvaliacao.php">Cronograma Avaliação Monitoria</a></li>
                           <li class=""><a  href="prazoAvaliacao.php">Prazo Avaliação Monitoria</a></li>
                          <li class=""><a  href="ColaboradorSenhaFeedBack.php">Col. Senha FeedBack</a></li>
                      </ul>
                  </li>

               <?php if (($_SESSION['ACESSO'] == 1) or ($_SESSION['ACESSO'] == 2) ) { ?>
                  <li class="sub-menu">
                      <a class="" href="javascript:;" >
                          <i class="fa fa-file-text"></i>
                          <span>Avaliações</span>
                      </a> <?php } ?>
                      <ul class="sub">
                          <li class=""><a  href="tipoTesteConhecimento.php">Tipo Conhecimento</a></li>
                          <li class=""><a  href="questoesConhecimento.php">Questões Conhecimento</a></li>
                          <li class=""><a  href="testeconhecimento.php">Teste Conhecimento</a></li>
                          <li class=""><a  href="testeConhecimentoCadastrado.php">Conhecimento Realizados</a></li>
                      </ul>
                  </li>


                  
                   
                   <?php if ($_SESSION['ACESSO'] == 1){ ?>
                      <li class="sub-menu">
                      <a class="" href="javascript:;" >
                          <i class="fa fa-desktop"></i>
                          <span>General</span> 
                      </a> <?php } ?>
                      <ul class="sub">
                          <li><a  href="usuarioLogin.php">Usuários GCO</a></li>
                          <li><a  href="listaHorarios.php">Lista Pausas</a></li>
                         <li class=""><a  href="dimensionamento.php">Dimensionamento</a></li>
                          <li class=""><a  href="colaboradores.php">Colaboradores</a></li>
                          <li class=""><a  href="cargo.php">Cargo</a></li>
                          <li class=""><a  href="grupo.php">Grupo</a></li>
                          <li class=""><a  href="regiao.php">Região</a></li>
                          <li class=""><a  href="processo.php">Processo</a></li>
                          <li class=""><a  href="motivo.php">Motivo</a></li>
                          <li class=""><a  href="submotivo.php">Sub-Motivo</a></li>
                      </ul>
                  </li>

                   <?php if ($_SESSION['ACESSO'] == 1){ ?>
                      <li class="sub-menu">
                      <a class="" href="../MOBIRISE/INDEX.html" >
                          <i class="fa fa-cog fa-spin"></i>
                          <span>BETA DEV</span> 
                      </a> <?php } ?>

              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>
      <!--sidebar end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">
            <h3><i class="fa fa-right"></i> Lista de Anexos Monitorias </h3>

            <!-- criar formulario -->
              <div class="row mt">
                  <div class="col-md-12">
                      <div class="content-panel">
                          
                          <div class="panel-heading">
                                   <div class="pull-right">
                                    <form name="Form" method="get" id="id" action="AnexosMonitorias.php">
                                      <a style="float:left; margin-right: 10px; margin-top: 20px"> PROCESSO </a>
                                        <select onchange="this.form.submit()" name="PROCESSO" style="float:left; margin-right: 20px; margin-top: 20px" >
                                           <?php while ($row = sqlsrv_fetch_array($result_squilaProcessoSelect)){ ?>
                                             <option <?php if($row['ID'] == $_GET['PROCESSO'] ) { echo 'selected' ;} ?> value=<?php echo $row['ID']?> > <?php echo $row['NOME'] ?> </option>
                                         <?php }
                                         ?>
                                        </select>
                                    </form>
                                     <!-- <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filtro </button> -->
                                    </div>
                              </div>

                        <form name="Form" method="get" id="formulario" action="MonitoriaAnexoDownload.php">
                          <table class="table table-striped table-advance table-hover order-table table-wrapper">
                            <h4><i class="fa fa-right"></i> Monitorias </h4>
                            <hr>
                            <input  style="margin-left: 15px;" type="search" class="light-table-filter" data-table="order-table table-wrapper table" placeholder="Search"></input><br><br>
                              <thead>
                              <tr>
                                  <th style="text-align: center"><i class="fa fa-magic"></i> ID Pesquisa </th>
                                  <th style="text-align: center"><i class="fa fa-male"></i> Nome Colaborador </th>
                                  <th style="text-align: center"><i class="fa fa-comment"></i> Número Avaliação </th>
                                  <th style="text-align: center"><i class="fa fa-flag"></i> Quem Aplicou </th>
                                  <th style="text-align: center"><i class="fa fa-bookmark"></i> Processo </th>
                                  <th style="text-align: center"><i class="fa fa-star"></i> Nota Final </th>
                                  <th style="text-align: center"><i class="fa fa-check-square"></i> Download </th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <?php  while($row = sqlsrv_fetch_array($result_squilaAnexo)) { 

                                    ?>

                                  <td style="text-align: center"><?php echo $row['ID_PESQUISA'] ?></a></td>
                                  <td style="text-align: center"><?php echo $row['NOME_COLABORADOR'] ?></td>
                                  <td style="text-align: center"><?php echo $row['AVALIACAO']?></span></td>
                                  <td style="text-align: center"><?php echo $row['QUEM_APLICOU']?></span></td>
                                  <td style="text-align: center"><?php echo $row['PROCESSO']?></span></td>
                                  <td style="text-align: center"><?php echo $row['NOTA_FINAL']?></span></td>
                                  <td>
                                      <!-- <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button> -->
                                      <button style="margin-left: 55px" class="btn btn-primary btn-xs" type="submit" value="<?php echo $row['ID_PESQUISA'] ?>"  name="ID_PESQUISA"><i class="fa fa-cloud-download"></i></button>
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

    </section>
      </section><!-- /MAIN CONTENT -->

      <!--main content end-->
      <!--footer start-->
      <footer class="site-footer">
          <div class="text-center">
              2017 - ANALYTICS EAD
              <a href="" class="go-top">
                  <i class="fa fa-angle-up"></i>
              </a>
          </div>
      </footer>
      <!--footer end-->
  </section>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>


    <!--common script for all pages-->
    <script src="assets/js/common-scripts.js"></script>

    <!--script for this page-->

  </body>
</html>



<script type="text/javascript">
(function(document) {
  'use strict';

  var LightTableFilter = (function(Arr) {

    var _input;

    function _onInputEvent(e) {
      _input = e.target;
      var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
      Arr.forEach.call(tables, function(table) {
        Arr.forEach.call(table.tBodies, function(tbody) {
          Arr.forEach.call(tbody.rows, _filter);
        });
      });
    }

    function _filter(row) {
      var text = row.textContent.toLowerCase(), val = _input.value.toLowerCase();
      row.style.display = text.indexOf(val) === -1 ? 'none' : 'table-row';
    }

    return {
      init: function() {
        var inputs = document.getElementsByClassName('light-table-filter');
        Arr.forEach.call(inputs, function(input) {
          input.oninput = _onInputEvent;
        });
      }
    };
  })(Array.prototype);

  document.addEventListener('readystatechange', function() {
    if (document.readyState === 'complete') {
      LightTableFilter.init();
    }
  });

   })(document);
        

</script>