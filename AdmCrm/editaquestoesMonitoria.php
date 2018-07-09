<?php include '..\AdmCrm\connectionADM.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://dd42150:8087/CESUDESK/NewCesudesk/index.php"  </script>'; 
}


if  (($_SESSION['ACESSO'] > 2) or ($_SESSION['ACESSO'] == null ))   {
 // Ação a ser executada: mata o script e manda uma mensagem
 echo  '<script type="text/javascript"> window.location.href = "index.php"  </script>';
}


$ID_QUESTAO = $_POST['ID_QUESTAO'];


$squilaQuestoes = "SELECT tq.ID_QUESTAO
                        ,tq.DESCRICAO
                        ,tq.DESC_OBSERVACAO
                        ,tc.DESCRICAO AS DESC_GRUPO
                        ,tc.ID_GRUPO
                        ,tq.PESO
                        ,tq.BO_FALHA_CRITICA
                        ,tq.BO_PARCIAL
                        ,tq.BO_QUESTAO_ATIVA
                    FROM tb_qld_questoes tq
                  INNER JOIN tb_crm_grupo tc ON tc.ID_GRUPO = TQ.ID_GRUPO
                   WHERE tq.ID_QUESTAO = {$ID_QUESTAO}
                  ORDER BY DESC_GRUPO";

$result_squilaQuestoes = sqlsrv_prepare($conn, $squilaQuestoes);
sqlsrv_execute($result_squilaQuestoes);
$resultadoSQL = sqlsrv_fetch_array($result_squilaQuestoes);



$squilaGrupo = "SELECT DISTINCT 
                            CASE WHEN ID_GRUPO in (1,2,3,4,5,17) THEN 1 ELSE ID_GRUPO END ID_GRUPO_COLABORADOR
                            ,DESCRICAO
                       FROM tb_crm_grupo";

$result_squilaGrupo= sqlsrv_prepare($conn, $squilaGrupo);
sqlsrv_execute($result_squilaGrupo);




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
                          <li class="active"><a  href="questoesMonitoria.php">Questões Monitoria</a></li>
                          <li class=""><a  href="monitoriaRealizada.php">Monitoria Realizadas</a></li>
                          <li class=""><a  href="cronogramaAvaliacao.php">Cronograma Avaliação Monitoria</a></li>
                           <li class=""><a  href="prazoAvaliacao.php">Prazo Avaliação Monitoria</a></li>
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
            <h3><i class="fa fa-right"></i> Questões </h3>

            <!-- criar formulario -->
              <div class="row mt">
                  <div class="col-md-12">
                      <div class="content-panel">

                        <form name="Form" method="post" id="formulario" action="ValidaEditaquestoesMonitoria.php">
                          <table class="table table-striped table-advance table-hover order-table table-wrapper">
                            <h4><i class="fa fa-right"></i> Tela de Edição de Ítens </h4>
                            <fieldset>
                          <legend> Editar </legend> 
                          <table cellspacing="10" style="vertical-align: middle">
                           <tr>
                            <td style="width:110px";>
                             <label style="margin-left: 15px" >Descrição: </label>
                            </td>
                            <td align="left">
                             <input type="text" size="80" name="DESCRICAO" value="<?php echo $resultadoSQL['DESCRICAO']; ?>">
                            </td>
                            </tr>
                            <tr>
                            <td>
                             <label style="margin-left: 15px" for="nome">Oberservação: </label>
                            </td>
                            <td align="left"><br><br>
                             <textarea name="DESC_OBSERVACAO" cols="120" rows="10" > <?php echo $resultadoSQL['DESC_OBSERVACAO']; ?> </textarea>
                            </td>
                           </tr>

                           <tr>
                            <td style="width:120px";><br>
                             <label style="margin-left: 15px">Grupo:</label>
                            </td>
                            <td align="left"><br>
                             <select name="ID_GRUPO">
                                            <option value="<?php echo $resultadoSQL['ID_GRUPO']; ?>"> <?php echo $resultadoSQL['DESC_GRUPO']; ?> </option>
                                           <?php while ($row = sqlsrv_fetch_array($result_squilaGrupo)){ ?>
                                            <option value=<?php echo $row['ID_GRUPO_COLABORADOR']?> > <?php echo $row['DESCRICAO'] ?> </option>
                                         <?php }
                                         ?>
                             </select>
                            </td>
                            </td>
                           </tr>

                            <tr>
                            <td><br>
                             <label style="margin-left: 15px"> Peso: </label>
                            </td>
                            <td align="left"><br>
                             <input type="text" name="PESO" value="<?php echo $resultadoSQL['PESO']; ?>">
                            </td>
                            </tr>
                            <tr>
                            <td><br>
                             <label style="margin-left: 15px" for="status">Falha Crítica:</label>
                            </td>
                            <td align="left"><br>
                             <select name="BO_FALHA_CRITICA">
                                    <option value="<?php echo $resultadoSQL['BO_FALHA_CRITICA']; ?>"><?php if ($resultadoSQL['BO_FALHA_CRITICA'] == 'S') { echo "SIM" ;} else {echo "NÃO" ;} ?></option> 
                                    <option value="S">SIM</option>
                                    <option value="N">NÃO</option> 
                            </select>
                            </td>
                            </tr>
                            <tr>
                              <td><br>
                             <label style="margin-left: 15px" for="status">Questão Parcial: </label>
                            </td>
                            <td align="left"><br>
                             <select name="BO_PARCIAL">
                                    <option value="<?php echo $resultadoSQL['BO_PARCIAL']; ?>"><?php if ($resultadoSQL['BO_PARCIAL'] == 'S') { echo "SIM" ;} else {echo "NÃO" ;} ?></option> 
                                    <option value="S">SIM</option>
                                    <option value="N">NÃO</option> 
                            </select>
                            </td>
                           </tr>

                           <tr>
                              <td><br>
                             <label style="margin-left: 15px" for="status">Status: </label>
                            </td>
                            <td align="left"><br>
                             <select name="BO_QUESTAO_ATIVA">
                                    <option value="<?php echo $resultadoSQL['BO_QUESTAO_ATIVA']; ?>"><?php if ($resultadoSQL['BO_QUESTAO_ATIVA'] == 'S') { echo "ATIVO" ;} else {echo "DESATIVO" ;} ?></option> 
                                    <option value="S">ATIVO</option>
                                    <option value="N">DESATIVO</option> 
                            </select>
                            </td>
                           </tr>

                         </table><br><br>


                         <br/>

                          <td><button class="button" onclick=" return getConfirmation();" type="submit" value="<?php echo $resultadoSQL['ID_QUESTAO'] ?>"  name="ID_QUESTAO">Confirmar</button> 
                         <a href="questoesMonitoria.php"><input type="button" value="Cancelar"></a>
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