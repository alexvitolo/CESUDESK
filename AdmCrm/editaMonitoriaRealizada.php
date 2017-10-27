<?php include '..\AdmCrm\connectionADM.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; 
}





if  (($_SESSION['ACESSO'] > 2) or ($_SESSION['ACESSO'] == null ))   {
 // Ação a ser executada: mata o script e manda uma mensagem
 echo  '<script type="text/javascript"> window.location.href = "index.php"  </script>';
}


$ID_PESQUISA = $_POST['ID_PESQUISA'];


$squilaPesquisaQuestoes = "SELECT tp.ID_PESQUISA
                                 ,tp.ID_COLABORADOR
                                 ,tc.NOME as NOME_CONSULTOR
                                 ,tc.ID_MATRICULA as MATRICULA_CONSULTOR
                                 ,tp.ID_COLABORADOR_APLICA
                                 ,(SELECT NOME FROM tb_crm_colaborador WHERE ID_COLABORADOR = tc.ID_COLABORADOR_GESTOR) as NOME_SUPERVISOR
                                ,(SELECT NOME FROM tb_crm_colaborador WHERE ID_COLABORADOR = tp.ID_COLABORADOR_APLICA) as NOME_QUEM_APLICA
                                 ,tp.ID_PROCESSO
                                 ,tpro.NOME  as NOME_PROCESSO
                                 ,tp.ID_GRUPO
                                 ,tg.DESCRICAO
                                 ,tp.ID_AVALIACAO
                                 ,tcro.NUMERO as NUMERO_AVALIACAO
                                 ,tp.ID_OBJETO_TALISMA
                                 ,ttal.DESCRICAO as NOME_OBJETOTALISMA
                                 ,ttal.ID_OBJETO_TALISMA
                                 ,tp.DESC_ID_TALISMA
                                 ,tp.CPF_MONITORIA
                                 ,tp.RAMAL_PA
                                 ,tp.ID_GRAVADOR
                                 ,tp.NOTA_FINAL
                                 ,tp.DT_ATENDIMENTO
                                 ,tp.DT_SISTEMA
                                 ,tp.ID_RESULT_LIG
                                 ,tp.OBSERVACAO_PESQUISA
                           FROM tb_qld_pesquisa tp
                     INNER JOIN tb_crm_processo tpro ON tpro.ID = tp.ID_PROCESSO 
                     INNER JOIN tb_crm_colaborador tc ON tc.ID_COLABORADOR = tp.ID_COLABORADOR
                     INNER JOIN tb_crm_grupo tg ON tg.ID_GRUPO = tp.ID_GRUPO 
                     INNER JOIN tb_qld_cronograma_avaliacao tcro ON tcro.ID_AVALIACAO = tp.ID_AVALIACAO
                     INNER JOIN tb_qld_objeto_talisma ttal ON ttal.ID_OBJETO_TALISMA = tp.ID_OBJETO_TALISMA
                          WHERE tp.ID_PESQUISA = {$ID_PESQUISA} " ;

$result_squilaPesquisaQuestoes = sqlsrv_prepare($conn, $squilaPesquisaQuestoes);
sqlsrv_execute($result_squilaPesquisaQuestoes);
$resultadoSQL = sqlsrv_fetch_array($result_squilaPesquisaQuestoes);


$DT_ATENDIMENTO = date_format($resultadoSQL['DT_ATENDIMENTO'], "Y-m-d");



$squilaItensQuestoes = "SELECT tq.ID_ITEM_PESQUISA
                                 ,tq.ID_QUESTAO
                                 ,tque.DESCRICAO
                                 ,tque.DESC_OBSERVACAO
                                 ,tq.RESPOSTA
                                 ,tq.NOTA_RESULTADO
                                 ,tq.ID_PESQUISA
                                 ,tque.PESO
                                 ,tque.BO_PARCIAL
                            FROM tb_qld_itens_questoes tq
                      INNER JOIN tb_qld_questoes tque ON tque.ID_QUESTAO = tq.ID_QUESTAO
                           WHERE tq.ID_PESQUISA = {$ID_PESQUISA}
                            AND tque.BO_QUESTAO_ATIVA = 'S'
                            AND tque.BO_FALHA_CRITICA = 'N'  " ;

$result_squilaItensQuestoes = sqlsrv_prepare($conn, $squilaItensQuestoes);
sqlsrv_execute($result_squilaItensQuestoes);




$squilaItensQuestoesCrit = "SELECT tq.ID_ITEM_PESQUISA
                                 ,tq.ID_QUESTAO
                                 ,tque.DESCRICAO
                                 ,tque.DESC_OBSERVACAO
                                 ,tq.RESPOSTA
                                 ,tq.NOTA_RESULTADO
                                 ,tq.ID_PESQUISA
                                 ,tque.PESO
                                 ,tque.BO_PARCIAL
                            FROM tb_qld_itens_questoes tq
                      INNER JOIN tb_qld_questoes tque ON tque.ID_QUESTAO = tq.ID_QUESTAO
                           WHERE tq.ID_PESQUISA = {$ID_PESQUISA}
                            AND tque.BO_QUESTAO_ATIVA = 'S'
                            AND tque.BO_FALHA_CRITICA = 'S'  " ;

$result_squilaItensQuestoesCrit = sqlsrv_prepare($conn, $squilaItensQuestoesCrit);
sqlsrv_execute($result_squilaItensQuestoesCrit);





$squilaResultLigacao = "SELECT tlig.ID_RESULT_LIG
                              ,tlig.ID_GRUPO
                              ,tlig.DESCRICAO
                          FROM tb_qld_resultado_ligacao tlig
                         WHERE tlig.ID_GRUPO = CASE 
                                               WHEN {$resultadoSQL['ID_GRUPO']} IN (1,2,3,4,5) THEN 1 
                                               ELSE {$resultadoSQL['ID_GRUPO']} END " ;

$result_squilaResultLigacao = sqlsrv_prepare($conn, $squilaResultLigacao);
sqlsrv_execute($result_squilaResultLigacao);



$squilaObjetoTalisma = "SELECT ID_OBJETO_TALISMA
                              ,DESCRICAO
                              ,TABELA_TALISMA
                          FROM tb_qld_objeto_talisma";

$result_squilaObjetoTalisma = sqlsrv_prepare($conn, $squilaObjetoTalisma);
sqlsrv_execute($result_squilaObjetoTalisma);


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
                          <span>Head Count</span>
                      </a>
                      <ul class ="sub">
                          <li class=""><a  href="index.php">Resumo</a></li>
                          <li class=""><a  href="DashboardQualidade.php">Dasboard Qualidade</a></li>
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
                          <li class=""><a  href="formularioAvaliacao.php"> Formulário de Avaliação </a></li>
                          
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
                          <li class="active"><a  href="monitoriaRealizada.php">Monitoria Realizadas</a></li>
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
            <h3><i class="fa fa-right"></i> Formulário de Avaliação </h3>

            <!-- criar formulario -->
              <div class="row mt">
                  <div class="col-md-12">
                      <div class="content-panel">
                         <form name="Form" method="post" id="formulario" action="ValidaEditaMonitoriaRealizada.php">


                         <fieldset>
                          <legend> Dados do Consultor  </legend> 
                          <table cellspacing="10" style="vertical-align: middle">
                           <tr>
                           <td style="width:110px";><br>
                             <label style="margin-left: 15px" for="nome">Nome Consultor: </label>
                            </td>
                            <td align="left"><br>
                            <?php echo $resultadoSQL['NOME_CONSULTOR'] ?></a>            
                            </td>
                             </tr>

                            <tr>
                            <td style="width:110px";><br>
                             <label style="margin-left: 15px" for="nome" > Supervisor: </label>
                            </td>
                            <td align="left"><br>
                            <?php echo $resultadoSQL['NOME_SUPERVISOR']  ?></a>    
                            </td>
                            </tr>

                              <tr>
                           <td style="width:110px";><br>
                             <label style="margin-left: 15px" for="nome">Grupo: </label>
                            </td>
                            <td align="left"><br>
                            <?php echo $resultadoSQL['DESCRICAO'] ?></a>            
                            </td>
                             </tr>

                          </table>
                         </fieldset><br><br>
                        <br>

<!-- DADOS PESSOAIS-->
                          <fieldset>
                          <legend> AVALIAÇÃO DA MONITORIA </legend> 
                          <table cellspacing="10" style="vertical-align: middle">
                           <tr>
                           <td style="width:110px";><br>
                             <label style="margin-left: 15px" for="nome">Avaliação: </label>
                            </td>
                            <td align="left"><br>
                            <?php echo $resultadoSQL['NUMERO_AVALIACAO'] ?></a> 
                            </td>
                             </tr>
                          </table>
                         </fieldset><br><br>
                        <br>

                         <fieldset>
                          <legend> Dados do Talisma </legend> 
                          <table cellspacing="10" style="vertical-align: middle">
                           <tr>
                           <td style="width:110px";><br>
                             <label style="margin-left: 15px" for="nome">OBJETO: </label> 
                            </td>
                            <td align="left"><br>
                             <select name="ID_OBJETO_TALISMA">
                                            <option value="null">Escolha uma Avaliacao</option>
                                           <?php while ($row = sqlsrv_fetch_array($result_squilaObjetoTalisma)){ ?>
                                            <option <?php if ($row['ID_OBJETO_TALISMA'] == $resultadoSQL['ID_OBJETO_TALISMA']) { echo 'selected'; } ?>  value=<?php echo $row['ID_OBJETO_TALISMA']?> > <?php echo $row['DESCRICAO'] ?> </option>
                                         <?php }
                                         ?>
                             </select>
                            </td>
                            </tr>
                            <tr>
                             <td style="width:110px";><br>
                             <label style="margin-left: 15px" for="nome">ID Objeto: </label>
                            </td>
                            <td align="left"><br>
                              <input style="width:155px" type="text" maxlength="10" value="<?php echo $resultadoSQL['DESC_ID_TALISMA'] ?>" id="CRTLV" name="DESC_ID_TALISMA" placeholder="Digite apenas NUMEROS" onkeypress='return event.charCode >= 48 && event.charCode <= 57'/> 
                            </td>
                             </tr>
                          </table>
                         </fieldset><br><br><br>

                         <fieldset>
                          <legend> Dados da Monitoria </legend> 
                          <table cellspacing="10" style="vertical-align: middle">
                           <tr>
                             <td style="width:110px";> <br>
                             <label style="margin-left: 15px" for="nome">CPF do Candidato: </label>
                            </td>
                            <td align="left"><br>
                            <input type="text" name="CPF_MONITORIA" id="CRTLV" maxlength="15" value="<?php echo $resultadoSQL['CPF_MONITORIA'] ?>">
                            </td>
                            </tr>
                            <tr>
                             <td style="width:110px";> <br>
                             <label style="margin-left: 15px" for="nome">Gravador: </label>
                            </td>
                            <td align="left"><br>
                            <input type="text" name="ID_GRAVADOR" id="CRTLV" maxlength="20" value="<?php echo $resultadoSQL['ID_GRAVADOR'] ?>"> 
                            </td>
                            </tr>
                            <tr>
                            <td style="width:110px";> <br>
                             <label style="margin-left: 15px" for="nome">Ramal PA: </label>
                            </td>
                            <td align="left"><br>
                           <input type="text" name="RAMAL_PA" id="CRTLV" maxlength="10" value="<?php echo $resultadoSQL['RAMAL_PA'] ?>"> 
                            </td>
                            </tr>
                            <tr>
                             <td style="width:110px";> <br>
                             <label style="margin-left: 15px" for="nome">Data Atendimento: </label>
                            </td>
                            <td align="left"><br>
                             <input type="date" id="CRTLV" name="DATA_ATENDIMENTO" value="<?php echo $DT_ATENDIMENTO; ?>"> 
                            </td>
                             </tr>
                          </table>
                         </fieldset><br><br><br>

                         <!--  variaveis php auxiliares    -->

                          <?php $numeroItem = 1 ;  $numeroItemCrit = 1 ; ?>

                        <fieldset>
                          <legend> Itens da Monitoria </legend> 
                          <table cellspacing="10" style="vertical-align: middle">
                           <tr>
                     <?php while ($row1 = sqlsrv_fetch_array($result_squilaItensQuestoes)) { ?>
                             <td style="width:420px";> <br><hr>
                             <label style="margin-left: 15px" for="nome">Item <?php echo $numeroItem ?> : <?php echo $row1['DESCRICAO'] ?> </label>
                            </td>
                            <td><br><hr>
                              <select name="vetorRespostas[<?php echo $row1['ID_QUESTAO']?>][<?php echo $row1['PESO']?>]""> 
                                 <option value="<?php echo $row1['RESPOSTA'] ?>"><?php if( $row1['RESPOSTA'] == "S" ){ echo "SIM" ;}ELSEIF( $row1['NOTA_RESULTADO'] == ($row1['PESO']/2) ){ echo "PARCIAL" ;} ELSE{ echo "NÃO" ;} ?></option> 
                                 <option value="S">SIM</option>
                                 <option value="N">NÃO</option> 
                          <?php if ( $row1['BO_PARCIAL'] == 'S') { ?>
                                 <option value="P">PARCIAL</option>
                          <?php } ?>
                            </select>
                            </td>
                             </tr>
                     <?php $numeroItem ++ ; } ?>
                          </table>
                         </fieldset><br><br><br>


                         <fieldset>
                          <legend> Falhas Críticas </legend> 
                          <table cellspacing="10" style="vertical-align: middle">
                           <tr>
                     <?php while ($row2 = sqlsrv_fetch_array($result_squilaItensQuestoesCrit)){ ?>
                             <td style="width:420px";><br><hr>
                             <label style="margin-left: 15px" for="nome">Item <?php echo $numeroItemCrit ?> : <?php echo $row2['DESCRICAO'] ?> </label>
                            </td>
                            <td><br><hr>
                              <select name="vetorRespostasCrit[<?php echo $row2['ID_QUESTAO']?>][<?php echo $row2['PESO']?>]"> 
                               <option value="<?php echo $row2['RESPOSTA'] ?>" ><?php if( $row2['RESPOSTA'] == "S" ){ echo "SIM" ;}ELSE{ echo "NÃO" ;}  ?></option>
                                 <option value="N" >NÃO</option> 
                                 <option value="S">SIM</option>
                                  <?php if ( $row2['BO_PARCIAL'] == 'S') { ?>
                                 <option value="P">PARCIAL</option>
                          <?php } ?>
                            </select>
                            </td>
                             </tr>
                     <?php $numeroItemCrit ++ ; } ?>
                          </table>
                         </fieldset><br><br><br>


                         <fieldset>
                          <legend> Resultado da Ligação </legend> 
                          <table cellspacing="10" style="vertical-align: middle">
                           <tr>
                             <td style="width:110px";> 
                              <label style="margin-left: 15px">  Grupo  </label>
                             </td>
                             <td>
                             <select name="ID_RESULT_LIG">
                                           <?php while ($row = sqlsrv_fetch_array($result_squilaResultLigacao)){ ?>
                                            <option <?php if ($resultadoSQL['ID_RESULT_LIG'] == $row['ID_RESULT_LIG']) { echo 'selected'; } ?> value=<?php echo $row['ID_RESULT_LIG']?> > <?php echo $row['DESCRICAO'] ?> </option>
                                         <?php }
                                         ?>
                             </select>
                             </td>
                              </tr>


                            <tr>
                             <td style="width:110px";><br>
                              <label style="margin-left: 15px" for="nome">Observação: </label>
                             </td>
                             <td align="left"><br>
                              <textarea name="OBSERVACAO_PESQUISA" cols="120" rows="10" > <?php echo $resultadoSQL['OBSERVACAO_PESQUISA'] ?> </textarea>
                             </td>
                            </tr>
                           </table>
                         </fieldset><br><br><br>
            
                        

                         <br/>
                          <input type="hidden" name="ID_PESQUISA" value="<?php echo $ID_PESQUISA ?>"> 
                          <td><button class="button" onclick=" return getConfirmation();" type="submit" value=""  name="">Confirmar</button> 
                         <a href="monitoriaRealizada.php"><input type="button" value="Cancelar"></a>
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




 
  <!--custom switch-->
  <script src="assets/js/bootstrap-switch.js"></script>
  
  <!--custom tagsinput-->
  <script src="assets/js/jquery.tagsinput.js"></script>
  

  <script src="assets/js/form-component.js"></script>   
    

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
        


    function getConfirmation(){
       // var retVal = confirm("Do you want to continue ?");
       if(  confirm(" Deseja confirmar a edição ? ") == true ){
          return true;
       }
       else{
          return false;
       }
    }
        

function formatar(mascara, documento){
  var i = documento.value.length;
  var saida = mascara.substring(0,1);
  var texto = mascara.substring(i)
  
  if (texto.substring(0,1) != saida){
            documento.value += texto.substring(0,1);
  }
  
}

$(document).ready(function() {

    $("#CRTLV").bind('paste', function(e) {
        e.preventDefault();
    });

});

</script>