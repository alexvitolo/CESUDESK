<?php include '..\AdmCrm\connectionADM.php'; 
session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
 // Ação a ser executada: mata o script e manda uma mensagem
echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; }

if  (($_SESSION['ACESSO'] > 2) or ($_SESSION['ACESSO'] == null ))   {
 // Ação a ser executada: mata o script e manda uma mensagem
 echo  '<script type="text/javascript"> window.location.href = "index.php"  </script>';
}


$NUM_ALT = $_GET["NUM_ALT"];


if   ( ereg('[^0-6]',$NUM_ALT )) {
 echo  '<script type="text/javascript">alert("Valor Inválido !");</script>';
 echo  '<script type="text/javascript"> window.location.href = "questoesConhecimento.php"  </script>';
 exit;
}

$aux = 0;


$squilaConhecimento = "SELECT tcon.ID_CONHECIMENTO
                         ,tcon.BO_STATUS
                         ,tcon.DESCRICAO
                    FROM tb_ava_conhecimento tcon
                   WHERE tcon.BO_STATUS = 'S' ";

$result_squilaConhecimento = sqlsrv_prepare($conn, $squilaConhecimento);
sqlsrv_execute($result_squilaConhecimento);




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
              
                  <p class="centered"><a href="profile.html"><img src="assets/img/ui-sam.gif" class="img-circle" width="60"></a></p>
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
                          <li class=""><a  href="questoesMonitoria.php">Questões</a></li>
                          <li class=""><a  href="monitoriaRealizada.php">Monitoria Realizadas</a></li>
                          <li class=""><a  href="cronogramaAvaliacao.php">Cronograma Avaliação</a></li>
                           <li class=""><a  href="prazoAvaliacao.php">Prazo Avaliação</a></li>
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
                          <li class=""><a  href="testeConhecimentoCadastrado.php">Testes Cadastrados</a></li>
                      </ul>
                  </li>


                  
                   
                   <?php if ($_SESSION['ACESSO'] == 1){ ?>
                      <li class="sub-menu">
                      <a class="" href="javascript:;" >
                          <i class="fa fa-desktop"></i>
                          <span>General</span> 
                      </a> <?php } ?>
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
            <h3><i class="fa fa-right"></i> Cadastro Nova Questão</h3>

            <!-- criar formulario -->
              <div class="row mt">
                  <div class="col-md-12">
                      <div class="content-panel">
                         <form name="Form" method="post" id="formulario" action="ValidaCadastroQuestoesConheci.php" onSubmit="return enviardados();" >
<!-- DADOS PESSOAIS-->
                         <fieldset>
                          <legend> Dados Questão </legend>
                          <table cellspacing="10" style="vertical-align: middle">
                           <tr>
                            <td style="width:110px";>
                             <label style="margin-left: 15px" >Tipo Conhecimento: </label>
                            </td>
                            <td align="left">
                             <select name="ID_CONHECIMENTO">
                                            <option value="null">Escolha um Tipo</option>
                                           <?php while ($row = sqlsrv_fetch_array($result_squilaConhecimento)){ ?>
                                            <option value=<?php echo $row['ID_CONHECIMENTO']?> > <?php echo $row['DESCRICAO'] ?> </option>
                                         <?php }
                                         ?>
                             </select>
                            </td>
                          </tr>

                          <tr>
                            <td style="width:110px";><br>
                             <label style="margin-left: 15px" >Status: </label>
                            </td>
                            <td align="left"><br>
                             <select name="BO_STATUS"> 
                                 <option value="S">ATIVO</option>
                                 <option value="N">INATIVO</option> 
                            </select>
                            </td>
                           </tr>

                           <tr>
                            <td style="width:110px";><br>
                             <label style="margin-left: 15px" >Dificuldade: </label>
                            </td>
                            <td align="left"><br>
                             <select name="DIFICULDADE"> 
                                 <option value="1">Fácil</option>
                                 <option value="2">Médio</option>
                                 <option value="3">Difícil</option>  
                            </select>
                            </td>
                           </tr>

                           <tr>
                            <td style="width:110px";><br><br>
                             <label style="margin-left: 15px" >Descriçao da Questão: </label>
                            </td>
                             <td align="left"><br><br>
                              <textarea name="DESC_QUESTAO" value="" cols="120" rows="8" > TEXTO </textarea>
                             </td>
                           </tr>

                          </table>
                         </fieldset>

                          <br><br>
                          <fieldset>
                          <legend> Alternativas Questão </legend>
                            <table cellspacing="10" style="vertical-align: middle">

                        <?php for ($aux = 1; $aux <= $NUM_ALT; $aux++ ) {  ?>
                               <tr>
                            <td style="width:110px";><br><br>
                             <label style="margin-left: 15px" >Alternativa <?php echo $aux ;?> </label>
                            </td>
                             <td align="left"><br><br>
                              <textarea name="<?php echo ('ALTERNATIVA'. $aux) ?>" value="" cols="120" rows="8" > TEXTO </textarea>
                             </td>
                              <td style="width:110px";><br><br>
                             <label style="margin-left: 15px" >VERDADEIRO ? </label>
                            </td>
                             <td align="left"><br><br>
                               <input type="checkbox" name="<?php echo ('RESP_ALTERNATIVA'. $aux) ?>" unchecked data-toggle="switch"  value="on">
                             </td>
                           </tr>
                        <?php  } ?>

                            </table>
                         </fieldset>
                         
                         <br>

                          <td><button class="button" onclick=" return getConfirmation();" type="submit" value=""  name="" >Confirmar</button> 
                           <input type="hidden" name="NUM_ALT" value="<?php echo $NUM_ALT ?>"> 
                         <a href="tipoTesteConhecimento.php"><input type="button" value="Cancelar"></a>
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

      <!--custom switch checkbox-->
  <script src="assets/js/bootstrap-switch-new-on-off.js"></script>
  
  <!--custom tagsinput-->
  <script src="assets/js/jquery.tagsinput.js"></script>
  

  <script src="assets/js/form-component.js"></script>   



  </body>
</html>



<script type="text/javascript">

function enviardados(){
 

if (document.Form.ID_CONHECIMENTO.value == 'null')
{
alert( "Preencha o campo CONHECIMENTO!" );
document.Form.ID_CONHECIMENTO.focus();
return false;
}

return true;
}


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
       if(  confirm(" Deseja Confirmar? ") == true ){
          return true;
       }
       else{
          return false;
       }
    }
        



</script>