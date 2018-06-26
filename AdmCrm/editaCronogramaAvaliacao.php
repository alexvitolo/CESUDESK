<?php include '..\AdmCrm\connectionADM.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://d42150:8087/CESUDESK/NewCesudesk/index.php"  </script>'; 
}

if ( (date('H:i:s')) >=  (date('H:i:s', strtotime('+55 minute', strtotime($_SESSION['TEMPOSESSION'])))) & ($_SESSION['ACESSO'] <> 1 ) ){
     // Ação a ser executada: encerra a session depois de 15 min
   echo  '<script type="text/javascript"> alert("Tempo de Sessão Expirada"); window.location.href = "http://d42150:8087/CESUDESK/NewCesudesk/main.php"  </script>'; 
   session_destroy();
 }
 
 $_SESSION['TEMPOSESSION'] = date('H:i:s');





if  (($_SESSION['ACESSO'] > 2) or ($_SESSION['ACESSO'] == null ))   {
 // Ação a ser executada: mata o script e manda uma mensagem
 echo  '<script type="text/javascript"> window.location.href = "index.php"  </script>';
}


$ID_AVALIACAO = $_POST['ID_AVALIACAO'];


$squilaAvaliacao = "SELECT tcron.ID_AVALIACAO
                      ,tcron.NUMERO
                      ,tcron.ID_CARGO
                      ,tcar.DESCRICAO
                      ,tcron.BO_STATUS
                FROM tb_qld_cronograma_avaliacao tcron
          INNER JOIN tb_crm_cargo tcar ON tcar.ID_CARGO = tcron.ID_CARGO
               WHERE ID_AVALIACAO = {$ID_AVALIACAO} ";

$result_squilaAvaliacao = sqlsrv_prepare($conn, $squilaAvaliacao);
sqlsrv_execute($result_squilaAvaliacao);
$resultadoSQL = sqlsrv_fetch_array($result_squilaAvaliacao);


$squilaNumeroAvaliacao = "SELECT 
                                tcron.NUMERO
                FROM tb_qld_cronograma_avaliacao tcron ";

$result_squilaNumeroAvaliacao = sqlsrv_prepare($conn, $squilaNumeroAvaliacao);
sqlsrv_execute($result_squilaNumeroAvaliacao);



$squilaCargoAvaliacao = "SELECT 
                              tcron.ID_CARGO
                             ,tcar.DESCRICAO
                FROM tb_qld_cronograma_avaliacao tcron
                LEFT JOIN tb_crm_cargo tcar ON tcar.ID_CARGO = tcron.ID_CARGO
                GROUP BY  tcar.DESCRICAO, tcron.ID_CARGO";

$result_squilaCargoAvaliacao = sqlsrv_prepare($conn, $squilaCargoAvaliacao);
sqlsrv_execute($result_squilaCargoAvaliacao);


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
                          <li class=""><a  href="questoesMonitoria.php">Questões Monitoria</a></li>
                          <li class=""><a  href="monitoriaRealizada.php">Monitoria Realizadas</a></li>
                          <li class="active"><a  href="cronogramaAvaliacao.php">Cronograma Avaliação Monitoria</a></li>
                      </ul>
                  </li>
                   <?php if ($_SESSION['ACESSO'] == 1){ ?>
                      <li class="sub-menu">
                      <a class="" href="javascript:;" >
                          <i class="fa fa-desktop"></i>
                          <span>General</span> 
                      </a> 
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
            <h3><i class="fa fa-right"></i> Tela de Edição de Cronograma </h3>

            <!-- criar formulario -->
              <div class="row mt">
                  <div class="col-md-12">
                      <div class="content-panel">

                        <form name="Form" method="post" id="formulario" action="ValidaEditaCronogramaAvaliacao.php">
                          <table class="table table-striped table-advance table-hover order-table table-wrapper">
                            <h4><i class="fa fa-right"></i></h4>
                            <fieldset>
                          <legend> Cronograma Avaliação </legend> 
                          <table cellspacing="10" style="vertical-align: middle">
                           <tr>
                            <td style="width:110px";>
                             <label style="margin-left: 15px" >ID Pesquisa: </label>
                            </td>
                            <td align="left">
                             <a><?php echo $resultadoSQL['ID_AVALIACAO']; ?> </a>
                            </td>
                            </tr>
                            <tr>
                            <td><br>
                             <label style="margin-left: 15px"> Numero: </label>
                            </td>
                            <td align="left"><br>
                             <select name="NUMERO">
                                            <option value="<?php echo $resultadoSQL['NUMERO']; ?>"> <?php echo $resultadoSQL['NUMERO']; ?> </option>
                                           <?php while ($row = sqlsrv_fetch_array($result_squilaNumeroAvaliacao)){ ?>
                                            <option value=<?php echo $row['NUMERO']?> > <?php echo $row['NUMERO'] ?> </option>
                                         <?php }
                                         ?>
                             </select>
                            </td>
                           </tr>

                           <tr>
                            <td style="width:120px";><br>
                             <label style="margin-left: 15px">Cargo Funcionário:</label>
                            </td>
                            <td align="left"><br>
                             <select name="ID_CARGO">
                                            <option value="<?php echo $resultadoSQL['ID_CARGO']; ?>"> <?php echo $resultadoSQL['DESCRICAO']; ?> </option>
                                           <?php while ($row = sqlsrv_fetch_array($result_squilaCargoAvaliacao)){ ?>
                                            <option value=<?php echo $row['ID_CARGO']?> > <?php echo $row['DESCRICAO'] ?> </option>
                                         <?php }
                                         ?>
                             </select>
                            </td>
                            </td>
                           </tr>
                           <tr>
                           <td style="width:120px";><br>
                            <label style="margin-left: 15px"> Ativo: </label>
                            </td>
                            <td align="left"><br>
                             <select name="BO_STATUS">
                                    <option value="<?php echo $resultadoSQL['BO_STATUS']; ?>"><?php if ($resultadoSQL['BO_STATUS'] == 'S') { echo "SIM" ;} else {echo "NÃO" ;} ?></option> 
                                    <option value="S">SIM</option>
                                    <option value="N">NÃO</option> 
                            </select>
                            </td>
                            </tr>

                         </table><br><br>


                         <br/>

                          <td><button class="button" onclick=" return getConfirmation();" type="submit" value="<?php echo $resultadoSQL['ID_AVALIACAO'] ?>"  name="ID_AVALIACAO">Confirmar</button> 
                         <a href="cronogramaAvaliacao.php"><input type="button" value="Cancelar"></a>
                      </form>
                      </div><!-- /content-panel -->
                  </div><!-- /col-md-12 -->
              </div><!-- /row -->

    </section>
      </section><!-- /MAIN CONTENT -->

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