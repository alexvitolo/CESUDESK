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





$squilaGrupos = "SELECT TOP 1 ID_GRUPO
                             ,DESCRICAO
                   FROM DB_CRM_REPORT.dbo.tb_crm_grupo";

$result_squilaGrupos = sqlsrv_prepare($conn, $squilaGrupos);
sqlsrv_execute($result_squilaGrupos);
$ID_GRUPO = sqlsrv_fetch_array($result_squilaGrupos);



 if ( ! isset ($_GET['ID_GRUPO'])){
    $sqlCondicaoTeste = "WHERE tc.ID_GRUPO =".$ID_GRUPO['ID_GRUPO'];
    $_GET['ID_GRUPO'] = $ID_GRUPO['ID_GRUPO'];
 }else{
  $sqlCondicaoTeste = "WHERE tc.ID_GRUPO =".$_GET['ID_GRUPO'];
 }





$squilaTodosGrupos = "SELECT DISTINCT CASE WHEN tc.ID_GRUPO in (1,2,3,4,5) THEN 1
                                              WHEN tc.ID_GRUPO in (24,26,27,28,29,30) THEN 24 
                                              ELSE tc.ID_GRUPO END  as ID_GRUPO
                                         ,tc.DESCRICAO
                           FROM DB_CRM_REPORT.dbo.tb_crm_grupo tc
                     INNER JOIN DB_CRM_REPORT.dbo.tb_qld_questoes tq ON tc.ID_GRUPO = tq.ID_GRUPO";

$result_squilaTodosGrupos = sqlsrv_prepare($conn, $squilaTodosGrupos);
sqlsrv_execute($result_squilaTodosGrupos);







$squilaDicas = "SELECT tq.ID_QUESTAO
                        ,tq.DESCRICAO
                        ,tq.DESC_OBSERVACAO
                        ,CONCAT(tc.DESCRICAO,' - ',tu.DESCRICAO,' ',tq.TIPO_LIGACAO) AS DESC_GRUPO
                        ,tq.PESO
                        ,tq.BO_FALHA_CRITICA
                        ,tq.BO_PARCIAL
                        ,tq.DT_SISTEMA
                        ,tp.NOME
                        ,CASE WHEN tq.BO_QUESTAO_ATIVA = 'S' THEN 'ATIVO' ELSE 'DESATIVO' END AS BO_QUESTAO_ATIVA
                        FROM tb_qld_questoes tq
                  INNER JOIN tb_crm_grupo tc ON tc.ID_GRUPO = tq.ID_GRUPO
                  INNER JOIN tb_crm_processo tp ON tp.ID = tq.ID_PROCESSO
                  INNER JOIN tb_crm_unidade tu ON tu.ID_UNIDADE = tc.ID_UNIDADE
                 ".$sqlCondicaoTeste."
                  ORDER BY BO_QUESTAO_ATIVA,DESC_GRUPO";

$result_squila = sqlsrv_prepare($conn, $squilaDicas);
sqlsrv_execute($result_squila);


//EAD

$squilaSomaPesos = "SELECT SUM(tq.peso) as SOMA
                              ,tc.DESCRICAO
                      FROM tb_qld_questoes tq
                INNER JOIN tb_crm_grupo tc ON tc.ID_GRUPO = tq.ID_GRUPO
                INNER JOIN tb_crm_unidade tu ON tu.ID_UNIDADE = tc.ID_UNIDADE AND tu.ID_UNIDADE = 1
                     WHERE BO_QUESTAO_ATIVA ='S'
                  GROUP BY tc.DESCRICAO";

$result_SomaPesos = sqlsrv_prepare($conn, $squilaSomaPesos);
sqlsrv_execute($result_SomaPesos);



//PRESENCIAL

$squilaSomaPesos2 = "SELECT SUM(tq.peso) as SOMA
                              ,CONCAT(tc.DESCRICAO,' - ',tq.TIPO_LIGACAO) as DESCRICAO
                      FROM tb_qld_questoes tq
                INNER JOIN tb_crm_grupo tc ON tc.ID_GRUPO = tq.ID_GRUPO
                INNER JOIN tb_crm_unidade tu ON tu.ID_UNIDADE = tc.ID_UNIDADE AND tu.ID_UNIDADE = 2
                     WHERE BO_QUESTAO_ATIVA ='S'
                  GROUP BY tc.DESCRICAO
                          ,tq.TIPO_LIGACAO
                ORDER BY DESCRICAO ";

$result_SomaPesos2 = sqlsrv_prepare($conn, $squilaSomaPesos2);
sqlsrv_execute($result_SomaPesos2);




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
                      <a class="active" href="javascript:;" >
                          <i class="fa fa-signal"></i>
                          <span>Qualidade</span> 
                      </a> <?php } ?>
                      <ul class="sub">
                          <li class="active"><a  href="questoesMonitoria.php">Questões Monitoria</a></li>
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
            <h3><i class="fa fa-right"></i> Lista de Questões</h3>

            <!-- criar formulario -->
              <div class="row mt">
                  <div class="col-md-12">
                      <div class="content-panel">

                      <fieldset>
                          <legend>  Somatória de Pesos - EAD </legend> 
                          <table cellspacing="10" style="vertical-align: middle">
                    <?php while ($row = sqlsrv_fetch_array($result_SomaPesos)){ ?>
                           <tr>
                           <td style="width:250px";>
                             <label style="margin-left: 15px" for="nome"> <?php echo $row['DESCRICAO'] ?> </label>
                           <hr> </td>
                            <td style="width:140px";>
                            <label style="margin-left: 15px" > <?php echo $row['SOMA'] ?></label>
                            <hr></td>
                             </tr>
                     <?php } ?>
                          </table>
                         </fieldset><br><br>
                        <br>

                        <fieldset>
                          <legend>  Somatória de Pesos - PRESENCIAL </legend> 
                          <table cellspacing="10" style="vertical-align: middle">
                    <?php while ($row2 = sqlsrv_fetch_array($result_SomaPesos2)){ ?>
                           <tr>
                           <td style="width:250px";>
                             <label style="margin-left: 15px" for="nome"> <?php echo $row2['DESCRICAO'] ?> </label>
                           <hr> </td>
                            <td style="width:140px";>
                            <label style="margin-left: 15px" > <?php echo $row2['SOMA'] ?></label>
                            <hr></td>
                             </tr>
                     <?php } ?>
                          </table>
                         </fieldset><br><br>
                        <br>


                         <form name="Form" method="get" id="id" action="questoesMonitoria.php">
                                        <select onchange="this.form.submit()" name="ID_GRUPO" style="float:right; margin-right: 20px; margin-top: 20px" >
                                           <?php while ($row = sqlsrv_fetch_array($result_squilaTodosGrupos)){ ?>
                                             <option <?php if($row['ID_GRUPO'] == $_GET['ID_GRUPO'] ) { echo 'selected' ;} ?> value=<?php echo $row['ID_GRUPO']?> > <?php echo $row['DESCRICAO'] ?> </option>
                                         <?php }
                                         ?>
                                        </select>
                                         <a style="float:right; margin-right: 10px; margin-top: 22px"> FILTRO GRUPO DE QUESTOES </a>
                        </form>


                        <form name="Form" method="post" id="formulario" action="editaquestoesMonitoria.php">
                          <table class="table table-striped table-advance table-hover order-table table-wrapper">
                            <h4><i class="fa fa-right"></i> Tabela de Questôes </h4>
                            <hr>
                            <input  style="margin-left: 15px;" type="search" class="light-table-filter" data-table="order-table table-wrapper table" placeholder="Search"></input>
                               <a href="UserReport_Export_questoes_monitoria.php"><input style="float:right; margin-right: 50px ;margin-top: 17px" type="button" value="Relatório Geral de Questões" ></input></a><br><br>
                              <thead><br>
                              <tr>
                                  <th><i class=""></i> Descrição </th>
                                  <th><i class=""></i> Observação </th>
                                  <th><i class=""></i> Grupo </th>
                                  <th><i class=""></i> Peso </th>
                                  <th><i class=""></i> Falha Crítica</th>
                                  <th><i class=""></i> Possui Parcial  </th>
                                  <th><i class=""></i> Ativa </th>
                                  <th><i class=""></i> Processo </th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <?php  while($row = sqlsrv_fetch_array($result_squila)) { 
                                    if ($row['BO_FALHA_CRITICA'] == "S") {
                                      $corStatus = "label label-success label-mini";
                                    }elseif ($row['BO_FALHA_CRITICA'] == "N")  {
                                      $corStatus = "label label-danger  label-mini";
                                    }

                                    if ($row['BO_PARCIAL'] == "S") {
                                      $corStatus2 = "label label-success label-mini";
                                    }elseif ($row['BO_PARCIAL'] == "N")  {
                                      $corStatus2 = "label label-danger  label-mini";
                                    }

                                    if ($row['BO_QUESTAO_ATIVA'] == "ATIVO") {
                                      $corStatus3 = "label label-success label-mini";
                                    }elseif ($row['BO_QUESTAO_ATIVA'] == "DESATIVO")  {
                                      $corStatus3 = "label label-danger  label-mini";
                                    }

                                    ?>
                                  <td style="width: 100px"><?php echo $row['DESCRICAO'] ?></a></td>
                                  <td style="width: 500px"><?php echo $row['DESC_OBSERVACAO'] ?></td>
                                  <td><a><?php echo $row['DESC_GRUPO'] ?></a></td>
                                  <td><?php echo $row['PESO'] ?></a></td>
                                  <td style="text-align: center"><span class="<?php echo $corStatus ?>"><?php echo $row['BO_FALHA_CRITICA'] ?></span></td>
                                  <td style="text-align: center"><span class="<?php echo $corStatus2 ?>"><?php echo $row['BO_PARCIAL'] ?></span></td>
                                  <td><span class="<?php echo $corStatus3 ?>"><?php echo $row['BO_QUESTAO_ATIVA']?></span></td>
                                  <td><?php echo $row['NOME'] ?></a></td>
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