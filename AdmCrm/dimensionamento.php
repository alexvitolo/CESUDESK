<?php include '..\AdmCrm\connectionADM.php'; 

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
                      <a class="" href="javascript:;">
                          <i class="fa fa-th"></i>
                          <span>Schedule</span>
                      </a>
                      <ul class="sub">
                          <li class=""><a  href="listaColaboradores.php">Lista Colaboradores</a></li>
                          <li class=""><a  href="escalaPausa.php"> Escala de pausa </a></li>
                          <li class=""><a  href="escalaFinalSemana.php"> Escala Final de Semana </a></li>
                          <li class=""><a  href="dadosGestores.php"> Dados Gestores </a></li>
         
                      </ul>
                  </li>
   
                   <li class="sub-menu">
                      <a class="active" href="javascript:;" >
                          <i class="fa fa-desktop"></i>
                          <span>General</span>
                      </a>
                      <ul class="sub">
                         <li><a  href="listaHorarios.php">Lista Pausas</a></li>
                         <li class="active"><a  href="dimensionamento.php">Dimensionamento</a></li>
                          <li><a  href="colaboradores.php">Colaboradores</a></li>
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
            <!-- <h3><i class="fa fa-right"></i> Dimensionamento Colaboradores</h3>
            <hr> -->

            <!-- criar formulario -->
              <div class="row mt">
                      <iframe style="margin-left: 20px" width="1024" height="720" src="https://app.powerbi.com/view?r=eyJrIjoiMWJlMzkxNTMtMDJkNy00OGE2LWEzNmYtYzU4YWEzNjM0Y2E3IiwidCI6IjMxMWJmNTc5LTYzZjItNDI2YS04MGFhLWQzYTI2ZjFjMGFkMSIsImMiOjF9"  frameborder="0" allowFullScreen="true"></iframe>
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