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



$squilaQuestoes = "SELECT tq.ID_QUESTAO
                                  ,tcon.ID_CONHECIMENTO
                                  ,tcon.DESCRICAO as DESC_CONHE
                                  ,tq.DESCRICAO as DESC_QUESTAO
                                  ,tq.BO_ATIVO
                                  ,tq.DIFICULDADE
                            FROM tb_ava_questoes_conhecimento tq
                      INNER JOIN tb_ava_conhecimento tcon ON tcon.ID_CONHECIMENTO = tq.ID_CONHECIMENTO
                           WHERE tcon.BO_STATUS = 'S'";

$result_squilaQuestoes = sqlsrv_prepare($conn, $squilaQuestoes);
sqlsrv_execute($result_squilaQuestoes);


$squilaSomaQuestoesEAD = "SELECT DISTINCT
                     tcon.DESCRICAO 
                    ,(SELECT COUNT(DIFICULDADE) FROM tb_ava_questoes_conhecimento WHERE ID_CONHECIMENTO = tq.ID_CONHECIMENTO AND DIFICULDADE = 1 AND BO_ATIVO ='S') as DIFICULDADE_EAZY
                    ,(SELECT COUNT(DIFICULDADE) FROM tb_ava_questoes_conhecimento WHERE ID_CONHECIMENTO = tq.ID_CONHECIMENTO AND DIFICULDADE = 2 AND BO_ATIVO ='S') as DIFICULDADE_MEDIUN
                    ,(SELECT COUNT(DIFICULDADE) FROM tb_ava_questoes_conhecimento WHERE ID_CONHECIMENTO = tq.ID_CONHECIMENTO AND DIFICULDADE = 3 AND BO_ATIVO ='S') as DIFICULDADE_HARD
                    ,(SELECT COUNT(ID_QUESTAO) FROM tb_ava_questoes_conhecimento WHERE ID_CONHECIMENTO = tq.ID_CONHECIMENTO AND BO_ATIVO ='S') as SOMA
            FROM tb_ava_questoes_conhecimento tq
      RIGHT JOIN tb_ava_conhecimento tcon ON tcon.ID_CONHECIMENTO = tq.ID_CONHECIMENTO
      INNER JOIN tb_crm_grupo tc ON tc.ID_GRUPO = tcon.ID_GRUPO
      INNER JOIN tb_crm_unidade tu ON tu.ID_UNIDADE = tc.ID_UNIDADE
           WHERE tcon.BO_STATUS = 'S'
             AND tu.ID_UNIDADE = 1";

$result_SomaQuestoesEAD = sqlsrv_prepare($conn, $squilaSomaQuestoesEAD);
sqlsrv_execute($result_SomaQuestoesEAD);



$squilaSomaQuestoesPRESEN = "SELECT DISTINCT
                     tcon.DESCRICAO 
                    ,(SELECT COUNT(DIFICULDADE) FROM tb_ava_questoes_conhecimento WHERE ID_CONHECIMENTO = tq.ID_CONHECIMENTO AND DIFICULDADE = 1 AND BO_ATIVO ='S') as DIFICULDADE_EAZY
                    ,(SELECT COUNT(DIFICULDADE) FROM tb_ava_questoes_conhecimento WHERE ID_CONHECIMENTO = tq.ID_CONHECIMENTO AND DIFICULDADE = 2 AND BO_ATIVO ='S') as DIFICULDADE_MEDIUN
                    ,(SELECT COUNT(DIFICULDADE) FROM tb_ava_questoes_conhecimento WHERE ID_CONHECIMENTO = tq.ID_CONHECIMENTO AND DIFICULDADE = 3 AND BO_ATIVO ='S') as DIFICULDADE_HARD
                    ,(SELECT COUNT(ID_QUESTAO) FROM tb_ava_questoes_conhecimento WHERE ID_CONHECIMENTO = tq.ID_CONHECIMENTO AND BO_ATIVO ='S') as SOMA
            FROM tb_ava_questoes_conhecimento tq
      RIGHT JOIN tb_ava_conhecimento tcon ON tcon.ID_CONHECIMENTO = tq.ID_CONHECIMENTO
      INNER JOIN tb_crm_grupo tc ON tc.ID_GRUPO = tcon.ID_GRUPO
      INNER JOIN tb_crm_unidade tu ON tu.ID_UNIDADE = tc.ID_UNIDADE
           WHERE tcon.BO_STATUS = 'S'
             AND tu.ID_UNIDADE = 2";

$result_SomaQuestoesPRESEN = sqlsrv_prepare($conn, $squilaSomaQuestoesPRESEN);
sqlsrv_execute($result_SomaQuestoesPRESEN);






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
                      <a class="active" href="javascript:;" >
                          <i class="fa fa-file-text"></i>
                          <span>Avaliações</span>
                      </a> <?php } ?>
                      <ul class="sub">
                          <li class=""><a  href="tipoTesteConhecimento.php">Tipo Conhecimento</a></li>
                          <li class="active"><a  href="questoesConhecimento.php">Questões Conhecimento</a></li>
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
            <h3><i class="fa fa-right"></i> Lista de Questões Teste Conhecimento </h3>

            <!-- criar formulario -->
              <div class="row mt">
                  <div class="col-md-12">
                      <div class="content-panel">

                        <legend>  Resumo de Questões - EAD </legend> 
                          <table cellspacing="10" style="vertical-align: middle">
                    <?php while ($row = sqlsrv_fetch_array($result_SomaQuestoesEAD)){ 

                         if ($row['DIFICULDADE_EAZY'] >= 3 & $row['DIFICULDADE_MEDIUN'] >= 3 & $row['DIFICULDADE_HARD'] >= 4 ) {
                                      $corStatusQues = "label label-success label-mini";
                                      $resStatusQues = "OK";
                                    }else{
                                      $corStatusQues = "label label-danger  label-mini";
                                      $resStatusQues = "INCOMPLETO";
                                    }

                      ?>
                           <tr>
                           <td style="width:300px";>
                             <label style="margin-left: 15px" for="nome"> <?php echo $row['DESCRICAO'] ?> </label>
                           <hr> </td>
                            <td style="width:140px";>
                            <label style="margin-left: 15px" > Questões Ativas = <?php echo $row['SOMA'] ?></label>
                            <hr></td>
                            <td style="width:140px";>
                            <label style="margin-left: 15px" > Fácil = <?php echo $row['DIFICULDADE_EAZY'] ?></label>
                            <hr></td>
                            <td style="width:140px";>
                            <label style="margin-left: 15px" > Médio = <?php echo $row['DIFICULDADE_MEDIUN'] ?></label>
                            <hr></td>
                            <td style="width:140px";>
                            <label style="margin-left: 15px" > Difícil = <?php echo $row['DIFICULDADE_HARD'] ?></label>
                            <hr></td>
                            <td style="width:140px";>
                            <label style="margin-left: 15px" ><span class="<?php echo $corStatusQues ?>"> Situação <?php echo $resStatusQues ?></label>
                            <hr></td>
                             </tr>
                     <?php } ?>
                          </table>
                         </fieldset><br><br>
                        <br><br>




                  <legend>  Resumo de Questões - PRESENCIAL  </legend> 
                          <table cellspacing="10" style="vertical-align: middle">
                    <?php while ($row2 = sqlsrv_fetch_array($result_SomaQuestoesPRESEN)){ 

                         if ($row2['DIFICULDADE_EAZY'] >= 3 & $row2['DIFICULDADE_MEDIUN'] >= 3 & $row2['DIFICULDADE_HARD'] >= 4 ) {
                                      $corStatusQues = "label label-success label-mini";
                                      $resStatusQues = "OK";
                                    }else{
                                      $corStatusQues = "label label-danger  label-mini";
                                      $resStatusQues = "INCOMPLETO";
                                    }

                      ?>
                           <tr>
                           <td style="width:300px";>
                             <label style="margin-left: 15px" for="nome"> <?php echo $row2['DESCRICAO'] ?> </label>
                           <hr> </td>
                            <td style="width:140px";>
                            <label style="margin-left: 15px" > Questões Ativas = <?php echo $row2['SOMA'] ?></label>
                            <hr></td>
                            <td style="width:140px";>
                            <label style="margin-left: 15px" > Fácil = <?php echo $row2['DIFICULDADE_EAZY'] ?></label>
                            <hr></td>
                            <td style="width:140px";>
                            <label style="margin-left: 15px" > Médio = <?php echo $row2['DIFICULDADE_MEDIUN'] ?></label>
                            <hr></td>
                            <td style="width:140px";>
                            <label style="margin-left: 15px" > Difícil = <?php echo $row2['DIFICULDADE_HARD'] ?></label>
                            <hr></td>
                            <td style="width:140px";>
                            <label style="margin-left: 15px" ><span class="<?php echo $corStatusQues ?>"> Situação <?php echo $resStatusQues ?></label>
                            <hr></td>
                             </tr>
                     <?php } ?>
                          </table>
                         </fieldset><br><br>
                        <br><br>



                        <form name="Form" method="post" id="formulario" action="editaQuestoesConhecimento.php">
                          <table class="table table-striped table-advance table-hover order-table table-wrapper">
                            <h4><i class="fa fa-right"></i> Geral Questões </h4>
                            <hr>
                            <input  style="margin-left: 15px;" type="search" class="light-table-filter" data-table="order-table table-wrapper table" placeholder="Search"></input>
                           <a href="#" id="myHref"><input style="float:right; margin-right: 50px" type="button" value="Nova Questão" ></input></a>
                           <a href="UserReport_Export_banco_questoes.php" ><input style="float:right; margin-right: 50px" type="button" value="Banco de Questões" ></input></a>
                              <thead>
                              <tr>
                                  <th><i class="fa fa"></i> ID Questão </th>
                                  <th><i class="fa fa"></i> Tipo Conhecimento </th>
                                  <th><i class="fa fa"></i> Questão </th>
                                  <th><i class="fa fa"></i> Dificuldade </th>
                                  <th><i class="fa fa"></i> Status </th>

                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <?php  while($row = sqlsrv_fetch_array($result_squilaQuestoes)) { 
                                    

                                    if ($row['BO_ATIVO'] == "S") {
                                      $corStatus = "label label-success label-mini";
                                    }elseif ($row['BO_ATIVO'] == "N"){
                                      $corStatus = "label label-danger  label-mini";
                                    }

                                     if ($row['DIFICULDADE'] == "1") {
                                      $corStatus2 = "label label-success label-mini";
                                    }elseif ($row['DIFICULDADE'] == "2"){
                                      $corStatus2 = "label label-warning  label-mini";
                                    }elseif ($row['DIFICULDADE'] == "3"){
                                      $corStatus2 = "label label-danger  label-mini";
                                    }


                                 ?>

                                  <td><?php echo $row['ID_QUESTAO']; ?></a></td>
                                  <td><?php echo $row['DESC_CONHE']; ?></td>
                                  <td><?php echo $row['DESC_QUESTAO']; ?></td>
                                  <td><span class="<?php echo $corStatus2 ?>"><?php if($row['DIFICULDADE'] == '1') { echo "Fácil" ;} elseif($row['DIFICULDADE'] == '2') { echo "Médio" ;} else { echo "Difícil" ;} ?></td>
                                  <td><span class="<?php echo $corStatus ?>"><?php if($row['BO_ATIVO'] == 'S') { echo "ATIVO" ;} else { echo "INATIVO" ;} ?></td>
                                  <td>
                                      <!-- <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button> -->
                                      <button class="btn btn-primary btn-xs" type="submit" value="<?php echo $row['ID_QUESTAO'] ?>"  name="ID_QUESTAO"><i class="fa fa-pencil"></i></button>
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

  $("#myHref").on('click', function() {
  var person = prompt("Insira o Número de Alternativas da Nova Quesstão", "10");
   if (person === null) {
        return; //break out of the function early
    }else{
         window.location = "cadastroQuestoesConhecimento.php?NUM_ALT=" + person;
     } 
});


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