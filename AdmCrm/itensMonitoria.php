<?php include '..\AdmCrm\connectionADM.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
 // Ação a ser executada: mata o script e manda uma mensagem
echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; }

if ( ($_SESSION['ACESSO'] == 1) or ($_SESSION['ACESSO'] == 2 ) )  {
 // Ação a ser executada: mata o script e manda uma mensagem
 echo  '<script type="text/javascript"> window.location.href = "index.php"  </script>';
}


$squilaDicas = "SELECT tq.ID_QUESTAO
                        ,tq.DESCRICAO
                        ,tq.DESC_OBSERVACAO
                        ,tc.DESCRICAO AS DESC_GRUPO
                        ,tq.PESO
                        ,tq.BO_FALHA_CRITICA
                        ,tq.BO_PARCIAL
                        ,tq.DT_SISTEMA
                        ,tq.BO_QUESTAO_ATIVA
                    FROM tb_qld_questoes tq
                  INNER JOIN tb_crm_grupo tc ON tc.ID_GRUPO = TQ.ID_GRUPO
                  ORDER BY DESC_GRUPO";

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
                      <a class="" href="javascript:;" >
                          <i class="fa fa-signal"></i>
                          <span>Qualidade</span> 
                      </a> <?php } ?>
                      <ul class="sub">
                          <li class=""><a  href="itensMonitoria.php">Itens Monitoria</a></li>
                      </ul>
                  </li>
                   <?php if ($_SESSION['ACESSO'] == 1){ ?>
                      <li class="sub-menu">
                      <a class="active" href="javascript:;" >
                          <i class="fa fa-desktop"></i>
                          <span>General</span> 
                      </a> <?php } ?>
                      <ul class="sub">
                          <li><a  href="listaHorarios.php">Lista Pausas</a></li>
                         <li class=""><a  href="dimensionamento.php">Dimensionamento</a></li>
                          <li class="active"><a  href="colaboradores.php">Colaboradores</a></li>
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
            <h3><i class="fa fa-right"></i> Lista de Colaboradores</h3>

            <!-- criar formulario -->
              <div class="row mt">
                  <div class="col-md-12">
                      <div class="content-panel">
                        <form name="Form" method="post" id="formulario" action="editaColaborador.php">
                          <table class="table table-striped table-advance table-hover order-table table-wrapper">
                            <h4><i class="fa fa-right"></i> Tabela Colaboradores </h4>
                            <hr>
                            <input  style="margin-left: 15px;" type="search" class="light-table-filter" data-table="order-table table-wrapper table" placeholder="Search"></input>
                            <a href="cadastroColaborador.php"><input style="margin-left: 800px" type="button" value="Novo Colaborador" ></input></a>
                              <thead>
                              <tr>
                                  <th><i class=""></i> ID Questão </th>
                                  <th><i class=""></i> Descrição </th>
                                  <th><i class=""></i> Observação </th>
                                  <th><i class=""></i> Grupo </th>
                                  <th><i class=""></i> Peso </th>
                                  <th><i class=""></i> Falha Crítica</th>
                                  <th><i class=""></i> Status </th>
                                  <th><i class=""></i> Supervisor </th>
                                  <th><i class=""></i> Cargo </th>
                                  <th><i class=""></i> Grupo </th>
                                  <th><i class=""></i> Região </th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <?php  while($row = sqlsrv_fetch_array($result_squila)) { 
                                    if ($row['STATUS_COLABORADOR'] == "ATIVO") {
                                      $corStatus = "label label-success label-mini";
                                    }elseif (($row['STATUS_COLABORADOR'] == "DESLIGADO") or ($row['STATUS_COLABORADOR'] == "Desligado")) {
                                      $corStatus = "label label-danger  label-mini";
                                    }else{
                                      $corStatus = "label label-warning  label-mini";
                                    }
                                    ?>
                                  <td><?php echo $row['ID_MATRICULA'] ?></a></td>
                                  <td><?php echo $row['NOME'] ?></td>
                                  <td><?php echo $row['LOGIN_REDE'] ?></a></td>
                                  <td><?php echo $row['ENTRADA'] ?></a></td>
                                  <td><?php echo $row['SAIDA'] ?></a></td>
                                  <td><?php echo $row['CARGA_HORARIO'] ?></a></td>
                                  <td><span class="<?php echo $corStatus ?>"><?php echo $row['STATUS_COLABORADOR']?></span></td>
                                  <td><?php echo $row['NOMEGESTOR']?></td>
                                  <td><?php echo $row['CARGO']?></td>
                                  <td><?php echo $row['GRUPO']?></td>
                                  <td><?php echo $row['REGIAO']?></td>
                                  <td>
                                      <!-- <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button> -->
                                      <button class="btn btn-primary btn-xs" type="submit" value="<?php echo $row['ID_COLABORADOR'] ?>"  name="ID_COLABORADOR"><i class="fa fa-pencil"></i></button>
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