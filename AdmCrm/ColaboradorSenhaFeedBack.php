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





if  (($_SESSION['ACESSO'] > 2) or ($_SESSION['ACESSO'] == null ))   {
 // Ação a ser executada: mata o script e manda uma mensagem
 echo  '<script type="text/javascript"> window.location.href = "index.php"  </script>';
}



$squilaDicas = "SELECT tc.ID_MATRICULA,
                       tc.NOME,
                       tc.LOGIN_REDE,
                       (SELECT NOME FROM tb_crm_colaborador WHERE ID_COLABORADOR = tc.ID_COLABORADOR_GESTOR) as NOMEGESTOR
                  FROM tb_crm_colaborador tc
      INNER JOIN tb_crm_grupo tg ON tg.ID_GRUPO = tc.ID_GRUPO
       LEFT JOIN tb_crm_regiao tr ON tr.ID_REGIAO = tg.ID_REGIAO
      INNER JOIN tb_crm_cargo tcar ON tcar.ID_CARGO = tc.ID_CARGO 
      INNER JOIN tb_crm_horario th ON th.ID_HORARIO = tc.ID_HORARIO
           WHERE tcar.BO_GESTOR = 'N'
             AND tcar.ID_CARGO NOT IN ('1','2','3','4','5')
             AND tc.STATUS_COLABORADOR = 'ATIVO'
             AND tc.PASS_FEEDBACK IS NULL
        ORDER BY STATUS_COLABORADOR, NOME";

$result_squila = sqlsrv_prepare($conn, $squilaDicas);
sqlsrv_execute($result_squila);



$squilaDicas2 = "SELECT tc.ID_MATRICULA,
                       tc.NOME,
                       tc.LOGIN_REDE,
                       (SELECT NOME FROM tb_crm_colaborador WHERE ID_COLABORADOR = tc.ID_COLABORADOR_GESTOR) as NOMEGESTOR,
                       tc.PASS_FEEDBACK
                  FROM tb_crm_colaborador tc
      INNER JOIN tb_crm_grupo tg ON tg.ID_GRUPO = tc.ID_GRUPO
       LEFT JOIN tb_crm_regiao tr ON tr.ID_REGIAO = tg.ID_REGIAO
      INNER JOIN tb_crm_cargo tcar ON tcar.ID_CARGO = tc.ID_CARGO 
      INNER JOIN tb_crm_horario th ON th.ID_HORARIO = tc.ID_HORARIO
           WHERE tcar.BO_GESTOR = 'N'
             AND tcar.ID_CARGO NOT IN ('1','2','3','4','5')
             AND tc.STATUS_COLABORADOR = 'ATIVO'
             AND tc.PASS_FEEDBACK IS NOT NULL 
        ORDER BY STATUS_COLABORADOR, NOME";

$result_squila2 = sqlsrv_prepare($conn, $squilaDicas2);
sqlsrv_execute($result_squila2);






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
                          
                      </ul>
                  </li>

                   <li class="sub-menu">
                      <a class="" href="javascript:;">
                          <i class="fa fa-book"></i>
                          <span>Monitoria</span>
                      </a>
                      <ul class="sub">
                          <li class=""><a  href="formularioAvaliacao.php"> Formulário de Avaliação </a></li>
                          <li class=""><a  href="AnexosMonitorias.php"> Anexos Monitorias </a></li>
                          
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
                          <li class=""><a  href="cronogramaAvaliacao.php">Cronograma Avaliação Monitoria</a></li>
                          <li class=""><a  href="prazoAvaliacao.php">Prazo Avaliação Monitoria</a></li>
                          <li class="active"><a  href="ColaboradorSenhaFeedBack.php">Col. Senha FeedBack</a></li>
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
            <h3><i class="fa fa-right"></i> Colaborador Senha FeedBack </h3>

            <!-- criar formulario EAD-->
              <div class="row mt">
                  <div class="col-md-12">
                      <div class="content-panel">


                        <form name="Form" method="get" id="formulario" action="../../CESUDESK/Altera_Senha_FeedBack.php">
                          <table class="table table-striped table-advance table-hover order-table table-wrapper">
                            <h4><i class="fa fa-right"></i> Colaboradores Sem Senha Cadastrada </h4>
                            <hr>
                                <input  style="margin-left: 15px;" type="search" class="light-table-filter" data-table="order-table table-wrapper table" placeholder="Search"></input></input>
                              <thead>
                              <tr>
                                  <th><i class=""></i> ID Matrícula </th>
                                  <th style="text-align: center"><i class=""></i> Nome </th>
                                  <th><i class=""></i> Login Rede </th>
                                  <th><i class=""></i> Nome Gestor </th>
                                  <th><i class=""></i> Senha FeedBack </th>
                                  <th><i class=""></i> Gerar Senha </th>
                                  <th><i class=""></i> Enviar Email </th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <?php  while($row = sqlsrv_fetch_array($result_squila)) { 
              
                                    ?>
                                  <td style="width: 100px"><?php echo $row['ID_MATRICULA'] ?></a></td>
                                  <td style="text-align: center"><?php echo $row['NOME'] ?></td>
                                  <td><?php echo $row['LOGIN_REDE'] ?></a></td>
                                  <td><?php echo $row['NOMEGESTOR'] ?></a></td>
                                  <td><?php echo 'NÃO POSSUI' ?></a></td>
                                  <td>
                                      <!-- <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button> -->
                                      <button style="margin-left: 25px" class="btn btn-primary btn-xs" type="submit" value="<?php echo base64_encode($row['ID_MATRICULA']); ?>"  name="ID_MATRICULA"><i class="fa fa-pencil"></i></button>
                                  </td>
                                  <td>
                                      <a style="margin-left: 25px" href="SenhaFeedBack_SendEmail.php?ID_MATRICULA=<?php echo $row['ID_MATRICULA']; ?>"><i class="fa fa-play"></i></button>
                                      </a>
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


      <!-- criar formulario PRESENCIAL-->
              <div class="row mt">
                  <div class="col-md-12">
                      <div class="content-panel">


                        <form name="Form" method="get" id="formulario" action="../../CESUDESK/Altera_Senha_FeedBack.php">
                          <table class="table table-striped table-advance table-hover order-table table-wrapper">
                            <h4><i class="fa fa-right"></i> Colaborador com senha Cadastrada </h4>
                            <hr>
                               <input  style="margin-left: 15px;" type="search" class="light-table-filter" data-table="order-table table-wrapper table" placeholder="Search"></input></input>
                              <thead>
                              <tr>
                                  <th><i class=""></i> ID Matrícula </th>
                                  <th style="text-align: center"><i class=""></i> Nome </th>
                                  <th><i class=""></i> Login Rede </th>
                                  <th><i class=""></i> Nome Gestor </th>
                                  <th><i class=""></i> Senha FeedBack </th>
                                  <th><i class=""></i> Link Senha </th>
                                   <th><i class=""></i> Enviar Email </th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <?php  while($row2 = sqlsrv_fetch_array($result_squila2)) { 
              
                                    ?>
                                  <td style="width: 100px"><?php echo $row2['ID_MATRICULA'] ?></a></td>
                                  <td style="text-align: center"><?php echo $row2['NOME'] ?></td>
                                  <td><?php echo $row2['LOGIN_REDE'] ?></a></td>
                                  <td><?php echo $row2['NOMEGESTOR'] ?></a></td>
                                  <td style="text-align: center"><?php echo $row2['PASS_FEEDBACK'] ?></a></td>
                                  <td>
                                      <!-- <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button> -->
                                      <button style="margin-left: 20px" class="btn btn-primary btn-xs" type="submit" value="<?php echo base64_encode($row2['ID_MATRICULA']); ?>"  name="ID_MATRICULA"><i class="fa fa-pencil"></i></button>
                                  </td>
                                   <td>
                                      <a style="margin-left: 25px" href="SenhaFeedBack_SendEmail.php?ID_MATRICULA=<?php echo $row2['ID_MATRICULA']; ?>"><i class="fa fa-play"></i></button>
                                      </a>
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