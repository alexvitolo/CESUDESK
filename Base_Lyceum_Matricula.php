

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>BASE Lyceum</title>

    <!-- Bootstrap core CSS -->
    <link href="../CESUDESK/AdmCrm/assets/css/bootstrap.css" rel="stylesheet">
    <link rel="shortcut icon" href="icone.ico" >
     <link rel="stylesheet" href="..\CESUDESK\AdmCrm\general.css">
    <!--external css-->
    <link href="../CESUDESK/AdmCrm/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        
    <!-- Custom styles for this template -->
    <link href="../CESUDESK/AdmCrm/assets/css/style.css" rel="stylesheet">
    <link href="../CESUDESK/AdmCrm/assets/css/style-responsive.css" rel="stylesheet">
    <link href="..\CESUDESK\AdmCrm\colaboradores.css" rel="stylesheet">

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
                  <div class="" data-placement="right" data-original-title="Toggle Navigation"></div>
              </div>
            <!--logo start-->
            <a href="" class="logo"><b> Base do Piá – Lyceum </b></a>
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

              </ul>
            </div>
        </header>
      <!--header end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN SIDEBAR MENU
      *********************************************************************************************************************************************************** -->
      <!--sidebar start-->
      <!--sidebar end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">

            <!-- criar formulario -->
              <div class="row mt">
                  <div class="col-md-8">
                      <div class="content-panel">
                        <form name="Form" method="post" id="formulario" action="Base_Lyceum_Matricula_Export.php">
                          <table class="table table-striped table-advance table-hover order-table table-wrapper">
                            <h4><i class="fa fa-right"></i> Gerar Base Matriculados Lyceum</h4><br>
                               <button style="margin-left: 15px" type="submit" class="btn btn-primary">Gerar Base</button>
                               

                                <table cellspacing="10" style="vertical-align: middle">

                               <tr>
                               <td style="width:110px";>
                               <br>
                                 <label style="margin-left: 15px" >Data Início Inscrição: </label>
                                </td>
                                <td align="left">
                                <br>
                                 <input type="date" name="DT_INI" required>
                                </td>
                                <td style="width:110px";>
                                <br>
                                <label style="margin-left: 15px" >Data Fim Inscrição: </label>
                               </td>
                               <td align="left">
                               <br>
                                <input type="date" name="DT_FIM" required >
                            </td>
                           </tr>

                         </table><br><br>



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
              2018 - PIÁ
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
