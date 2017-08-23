<?php include '..\AdmCrm\connectionADM.php'; 

$squiladadosGestores = "SELECT tc.ID_MATRICULA,
                       tc.NOME,
                       tc.LOGIN_REDE,
                       tc.EMAIL,
                       tc.DT_ADMISSAO,
                       tc.DT_NASCIMENTO,
                       CONVERT(VARCHAR(8),th.ENTRADA, 24) as ENTRADA,
                       CONVERT(VARCHAR(8),th.SAIDA, 24) as SAIDA,
                       CONVERT(VARCHAR(8),th.CARGA_HORARIO, 24) as CARGA_HORARIO,
                       (SELECT NOME FROM tb_crm_colaborador WHERE ID_COLABORADOR = tc.ID_COLABORADOR_GESTOR) as NOMEGESTOR,
                       CONCAT(tcar.DESCRICAO,' ',tc.NIVEL_CARGO) as CARGO,
                       tg.DESCRICAO as GRUPO,
                       tr.DESCRICAO as REGIAO
                  FROM tb_crm_colaborador tc
            INNER JOIN tb_crm_grupo tg ON tg.ID_GRUPO = tc.ID_GRUPO
             LEFT JOIN tb_crm_regiao tr ON tr.ID_REGIAO = tg.ID_REGIAO
            INNER JOIN tb_crm_cargo tcar ON tcar.ID_CARGO = tc.ID_CARGO 
            INNER JOIN tb_crm_horario th ON th.ID_HORARIO = tc.ID_HORARIO
                 WHERE tcar.BO_GESTOR = 'S'
              ORDER BY STATUS_COLABORADOR, NOME";

$result_squilaGestores = sqlsrv_prepare($conn, $squiladadosGestores);
sqlsrv_execute($result_squilaGestores);


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>ADMINISTRATIVO CRM</title>

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
            <a href="index.html" class="logo"><b>CRM MASTER</b></a>
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
                    <li><a class="logout" href="login.php">Logout</a></li>
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
                  <h5 class="centered">CRM EAD</h5>
                    
                  <li class="sub-menu"">
                      <a class="" href="javascript:;" >
                          <i class="fa fa-dashboard"></i>
                          <span>Head Count</span>
                      </a>
                      <ul class ="sub">
                          <li class=""><a  href="index.html">Resumo</a></li>
                      </ul>
                  </li>

                 <li class="sub-menu">
                      <a class="active" href="javascript:;">
                          <i class="fa fa-th"></i>
                          <span>Schedule</span>
                      </a>
                      <ul class="sub">
                          <li class=""><a  href="listaColaboradores.php">Lista Colaboradores</a></li>
                          <li class=""><a  href="escalaPausa.php"> Escala de pausa </a></li>
                          <li class=""><a  href="escalaFinalSemana.php"> Escala Final de Semana </a></li>
                          <li class="active"><a  href="dadosGestores.php"> Dados Gestores </a></li>
                      </ul>
                  </li>
   

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
            <h3><i class="fa fa-right"></i> Dados Gestores </h3>

            <!-- criar formulario -->
              <div class="row mt">
                  <div class="col-md-12">
                      <div class="content-panel">
                        <form name="Form" method="post" id="formulario" action="">
                          <table class="table table-striped table-advance table-hover order-table table-wrapper">
                            <h4><i class="fa fa-right"></i> Lista Gestores  </h4>
                            <hr>
                            <input  style="margin-left: 15px;" type="search" class="light-table-filter" data-table="order-table table-wrapper table" placeholder="Search"></input>
                              <thead>
                              <tr>
                                  <th><i class=""></i> Matrícula </th>
                                  <th><i class=""></i> Nome </th>
                                  <th><i class=""></i> Login Rede </th>
                                  <th><i class=""></i> Data Admissão </th>
                                  <th><i class=""></i> Data Nascimento </th>
                                  <th><i class=""></i> Entrada </th>
                                  <th><i class=""></i> Saída </th>
                                  <th><i class=""></i> Nome Gestor </th>
                                  <th><i class=""></i> Cargo </th>
                                  <th><i class=""></i> Grupo </th>
                                  <th style="margin-right: 50px"><i class=""></i> Região </th>
 
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <?php  while($row = sqlsrv_fetch_array($result_squilaGestores)) { 
                                    ?>

                                  <td><?php echo $row['ID_MATRICULA']; ?></a></td>
                                  <td><?php echo $row['NOME']; ?></a></td>
                                  <td><?php echo $row['LOGIN_REDE']; ?></a></td>
                                  <td><?php echo date_format($row['DT_ADMISSAO'],"d/m/Y"); ?></a></td>
                                  <td><?php echo date_format($row['DT_NASCIMENTO'],"d/m/Y"); ?></a></td>
                                  <td><?php echo $row['ENTRADA']; ?></a></td>
                                  <td><?php echo $row['SAIDA']; ?></a></td>
                                  <td><?php echo $row['NOMEGESTOR']; ?></a></td>
                                  <td><?php echo $row['CARGO']; ?></a></td>
                                  <td><?php echo $row['GRUPO']; ?></a></td>
                                  <td style="margin-right: 50px"><?php echo $row['REGIAO']; ?></a></td>
                                  <td>
                                      
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
              2017 - CRM MASTER
              <a href="basic_table.html#" class="go-top">
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