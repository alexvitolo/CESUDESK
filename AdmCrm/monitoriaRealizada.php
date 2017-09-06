<?php include '..\AdmCrm\connectionADM.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
 // Ação a ser executada: mata o script e manda uma mensagem
echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; }


if  (($_SESSION['ACESSO'] > 2) or ($_SESSION['ACESSO'] == null ))   {
 // Ação a ser executada: mata o script e manda uma mensagem
 echo  '<script type="text/javascript"> window.location.href = "index.php"  </script>';
}



$squilaDicas = "SELECT tp.ID_PESQUISA
                      ,tp.ID_COLABORADOR
                      ,tc.NOME as NOME_CONSULTOR
                      ,tc.ID_MATRICULA as MATRICULA_CONSULTOR
                      ,tp.ID_COLABORADOR_APLICA
                      ,(SELECT NOME FROM tb_crm_colaborador WHERE ID_COLABORADOR = tp.ID_COLABORADOR_APLICA) as NOME_QUEM_APLICOU
                      ,tp.ID_PROCESSO
                      ,tpro.NOME as NOME_PROCESSO
                      ,tp.ID_GRUPO
                      ,tcron.NUMERO as NUMERO_AVALIACAO
                      ,tg.DESCRICAO
                      ,tp.CPF_MONITORIA
                      ,tp.RAMAL_PA
                      ,tp.DT_ATENDIMENTO
                      ,tp.NOTA_FINAL
                FROM tb_qld_pesquisa tp
          INNER JOIN tb_crm_processo tpro ON tpro.ID = tp.ID_PROCESSO AND tpro.ATIVO = 1
          INNER JOIN tb_crm_colaborador tc ON tc.ID_COLABORADOR = tp.ID_COLABORADOR
          INNER JOIN tb_crm_grupo tg ON tg.ID_GRUPO = tp.ID_GRUPO
          INNER JOIN tb_qld_cronograma_avaliacao tcron ON tcron.ID_AVALIACAO = tp.ID_AVALIACAO
            ORDER BY tp.DT_ATENDIMENTO desc";

$result_squila = sqlsrv_prepare($conn, $squilaDicas);
sqlsrv_execute($result_squila);




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
              
                  <p class="centered"><a href="profile.html"><img src="assets/img/ui-sam.gif" class="img-circle" width="60"></a></p>
                  <h5 class="centered">Analytics EAD</h5>
                    
                  <li class="sub-menu"">
                      <a class="" href="javascript:;" >
                          <i class="fa fa-dashboard"></i>
                          <span>Head Count</span>
                      </a>
                      <ul class ="sub">
                          <li class=""><a  href="index.php">Resumo</a></li>
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
                          <li class=""><a  href="formularioAvaliacao.php"> Formulário Monitoria </a>
                          
                      </ul>
                  </li>

                  <?php if (($_SESSION['ACESSO'] == 1) or ($_SESSION['ACESSO'] == 2) ) { ?>
                      <li class="sub-menu">
                      <a class="active" href="javascript:;" >
                          <i class="fa fa-signal"></i>
                          <span>Qualidade</span> 
                      </a> <?php } ?>
                      <ul class="sub">
                          <li class=""><a  href="questoesMonitoria.php">Questões</a></li>
                          <li class="active"><a  href="monitoriaRealizada.php">Monitoria Realizadas</a></li>
                            <li class=""><a  href="cronogramaAvaliacao.php">Cronograma Avaliação</a></li>
                      </ul>
                  </li>
                   <?php if ($_SESSION['ACESSO'] == 1){ ?>
                      <li class="sub-menu">
                      <a class="" href="javascript:;" >
                          <i class="fa fa-desktop"></i>
                          <span>General</span> 
                      </a> 
                      <ul class="sub">
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
               <?php } ?>

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
            <h3><i class="fa fa-right"></i> Lista de Formulários </h3>

            <!-- criar formulario -->
              <div class="row mt">
                  <div class="col-md-12">
                      <div class="content-panel">


                        <form name="Form" method="post" id="formulario" action="editaMonitoriaRealizada.php">
                          <table class="table table-striped table-advance table-hover order-table table-wrapper">
                            <h4><i class="fa fa-right"></i> Avaliações </h4>
                            <hr>
                            <input  style="margin-left: 15px;" type="search" class="light-table-filter" data-table="order-table table-wrapper table" placeholder="Search"></input>
                              <thead>
                              <tr>
                                  <th><i class=""></i> ID Pesquisa </th>
                                  <th><i class=""></i> Consultor </th>
                                  <th><i class=""></i> Avaliação </th>
                                  <th><i class=""></i> Grupo </th>
                                  <th><i class=""></i> Matrícula Consultor </th>
                                  <th><i class=""></i> Quem Aplicou </th>
                                  <th><i class=""></i> Ramal </th>
                                  <th><i class=""></i> CPF do Aluno  </th>
                                  <th><i class=""></i> Data Atendimento Cliente </th>
                                  <th><i class=""></i> Nota Final </th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <?php  while($row = sqlsrv_fetch_array($result_squila)) { 
              
                                    ?>
                                  <td style="width: 100px"><?php echo $row['ID_PESQUISA'] ?></a></td>
                                  <td style="width: 250px"><?php echo $row['NOME_CONSULTOR'] ?></td>
                                  <td style="text-align: center;"><?php echo $row['NUMERO_AVALIACAO'] ?></a></td>
                                  <td><?php echo $row['DESCRICAO'] ?></a></td>
                                  <td><a><?php echo $row['MATRICULA_CONSULTOR'] ?></a></td>
                                  <td><?php echo $row['NOME_QUEM_APLICOU'] ?></a></td>
                                  <td><?php echo $row['RAMAL_PA'] ?></td>
                                  <td><?php echo $row['CPF_MONITORIA'] ?></td>
                                  <td><?php echo date_format($row['DT_ATENDIMENTO'],"d/m/Y");?></td>
                                  <td><?php echo $row['NOTA_FINAL'] ?></td>
                                  <td>
                                      <!-- <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button> -->
                                      <button class="btn btn-primary btn-xs" type="submit" value="<?php echo $row['ID_PESQUISA'] ?>"  name="ID_PESQUISA"><i class="fa fa-pencil"></i></button>
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