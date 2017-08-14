<?php include '..\PlanilhaTrocas\connection.php'; 

// $ID_MATRICULA = $_POST["ID_MATRICULA"];

// fazer skila dicas 

$sqlSupervisores = "SELECT tc.ID_COLABORADOR AS ID_SUP
                      ,tc.NOME AS NOME_SUP
                  FROM tb_crm_colaborador tc
            INNER JOIN tb_crm_cargo ta ON ta.ID_CARGO = tc.ID_CARGO AND ta.BO_GESTOR = 'S'
              ORDER BY tc.NOME";

$result_supervisores = sqlsrv_prepare($conn, $sqlSupervisores);
sqlsrv_execute($result_supervisores);


$sqlHorarios = "SELECT th.ID_HORARIO
                      ,CONVERT(varchar,th.ENTRADA,108) AS ENTRADA
                      ,CONVERT(varchar,th.SAIDA,108) AS SAIDA
                      ,CONVERT(varchar,th.CARGA_HORARIO,108) AS CARGA_HORARIO
                      FROM tb_crm_horario th
                    WHERE th.BO_ESCALA_FDS = 'N'
                   ORDER BY th.CARGA_HORARIO
                       ,th.ENTRADA";

$result_Horario = sqlsrv_prepare($conn, $sqlHorarios);
sqlsrv_execute($result_Horario);

$sqlCargos = "SELECT tc.ID_CARGO
                    ,tc.DESCRICAO AS DESC_CARGO
                  FROM tb_crm_cargo tc
              ORDER BY tc.DESCRICAO";

$result_Cargo = sqlsrv_prepare($conn, $sqlCargos);
sqlsrv_execute($result_Cargo);


?>

<!DOCTYPE html>
<html lang="pt">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>ADMINISTRATIVO CRM</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="/vendor/bootstrap-combobox/css/bootstrap-combobox.css">
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
                    <li><a class="logout" href="login.html">Logout</a></li>
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
              
                  <p class="centered"><a href="profile.html"><img src="assets/img/ui-sam.png" class="img-circle" width="60"></a></p>
                  <h5 class="centered">CRM EAD</h5>
                    
                  <li class="mt">
                      <a class="" href="index.html">
                          <i class="fa fa-dashboard"></i>
                          <span>Dashboard</span>
                      </a>
                  </li>

                  <li class="sub-menu">
                      <a class="active" href="colaboradores.php">
                          <i class="fa fa-dashboard"></i>
                          <span>Colaboradores</span>
                      </a>
                  </li>
   
                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="fa fa-desktop"></i>
                          <span>UI Elements</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="general.html">General</a></li>
                          <li><a  href="buttons.html">Buttons</a></li>
                          <li><a  href="panels.html">Panels</a></li>
                      </ul>
                  </li>

                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="fa fa-cogs"></i>
                          <span>Components</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="calendar.html">Calendar</a></li>
                          <li><a  href="gallery.html">Gallery</a></li>
                          <li><a  href="todo_list.html">Todo List</a></li>
                      </ul>
                  </li>
                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="fa fa-book"></i>
                          <span>Extra Pages</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="blank.html">Blank Page</a></li>
                          <li><a  href="login.html">Login</a></li>
                          <li><a  href="lock_screen.html">Lock Screen</a></li>
                      </ul>
                  </li>
                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="fa fa-tasks"></i>
                          <span>Forms</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="form_component.html">Form Components</a></li>
                      </ul>
                  </li>
                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="fa fa-th"></i>
                          <span>Data Tables</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="basic_table.html">Basic Table</a></li>
                          <li><a  href="responsive_table.html">Responsive Table</a></li>
                      </ul>
                  </li>
                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class=" fa fa-bar-chart-o"></i>
                          <span>Charts</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="morris.html">Morris</a></li>
                          <li><a  href="chartjs.html">Chartjs</a></li>
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
            <h3><i class="fa fa-right"></i> Edição de colaboradores</h3>

            <!-- criar formulario -->
              <div class="row mt">
                  <div class="col-md-12">
                      <div class="content-panel">
                         <form name="Form" method="post" id="formulario" action="@paginaphp.php">
<!-- DADOS PESSOAIS-->
                         <fieldset>
                          <legend> Dados do colaborador </legend>
                          <table cellspacing="10" style="vertical-align: middle">
                           <tr>
                            <td style="width:110px";>
                             <label style="margin-left: 15px" for="nome">Matricula: </label>
                            </td>
                            <td align="left">
                             <input type="text" name="ID_MATRICULA">
                            </td>
                            <td>
                             <label style="margin-left: 15px" for="sobrenome">Nome: </label>
                            </td>
                            <td align="left">
                             <input type="text" name="nomr">
                            </td>
                             <td>
                             <label style="margin-left: 15px" for="sobrenome">Data Nascimento: </label>
                            </td>
                            <td align="left">
                             <input type="date" name="dtNascimento">
                            </td>
                             <td>
                             <label style="margin-left: 15px" for="sobrenome">E-mail: </label>
                            </td>
                            <td align="left">
                             <input type="text" name="email" size="40">
                            </td>
                           </tr>

                           <tr>
                            <td>
                            <br/>
                             <label style="margin-left: 15px" for="rg">Login Rede: </label>
                            </td>
                            <td align="left">
                             <input type="text" name="loginRede" size="20" > 
                            </td>
                             <td>
                             <label style="margin-left: 15px" >Login :</label>
                            </td>
                            <td align="left">
                             <input type="text" name="login" size="30" > 
                            </td>
                            <td>
                             <label style="margin-left: 15px" for="status">Status :</label>
                            </td>
                            <td align="left">
                             <select name="status"> 
                             <option value="ATIVO">ATIVO</option>
                             <option value="FERIAS">FERIAS</option> 
                             <option value="DESLIGADO">DESLIGADO</option>
                             <option value="INSS">INSS</option>  
                            </select>
                            </td>
                             <td>
                             <label style="margin-left: 15px" for="sobrenome">Código Portal: </label>
                            </td>
                            <td align="left">
                             <input type="text" name="codPortal" size="10">
                            </td>
                           </tr>

                           <tr>
                            <td>
                             <label style="margin-left: 15px">Supervisor: </label>
                             <!-- <label class="col-xs-3 control-label">Supervisor</label> -->
                            </td>
                            <td align="left">
                             <!-- <input type="text" name="idSupervisor" size="9" >  -->
                              <div class="form-group">
                                 <!-- <label class="col-xs-3 control-label">Supervisor</label> -->
                                 <div class="col-xs-12 selectContainer" >
                                     <select name="supervisor">
                                         <option value="">Escolha um supervisor</option>
                                         <?php while ($row = sqlsrv_fetch_array($result_supervisores)){ ?>
                                            <option value=<?php echo $row['ID_SUP']?> > <?php echo utf8_encode($row['NOME_SUP']) ?> </option>
                                         <?php }
                                         ?>
                                     </select>
                                 </div>
                              </div>
                            </td>
                            </tr> 

                            <tr>
                            <td>
                             <label style="margin-left: 15px">Cargo: </label>
                             <!-- <label class="col-xs-3 control-label">Supervisor</label> -->
                            </td>
                            <td align="left">
                             <!-- <input type="text" name="idSupervisor" size="9" >  -->
                              <div class="form-group">
                                 <!-- <label class="col-xs-3 control-label">Supervisor</label> -->
                                 <div class="col-xs-12 selectContainer" >
                                     <select name="supervisor">
                                         <option value="">Escolha um Cargo</option>
                                         <?php while ($row = sqlsrv_fetch_array($result_Cargo)){ ?>
                                            <option value=<?php echo $row['ID_CARGO']?> > <?php echo utf8_encode($row['DESC_CARGO']) ?> </option>
                                         <?php }
                                         ?>
                                     </select>
                                 </div>
                              </div>
                            </td>

                            <td align="left">
                             <input type="text" name="login" size="30" > 
                            </td>
                            <td>
                             <label style="margin-left: 15px" for="status">Nível Cargo :</label>
                            </td>
                            <td align="left">
                             <select name="status"> 
                             <option value="I">I</option>
                             <option value="II">II</option> 
                             <option value="III">III</option>
                             <option value="IV">IV</option>  
                            </select>
                            </td>
                             <td>
                             <label style="margin-left: 15px" >Data Admissão :</label>
                            </td>
                            <td align="left">
                             <input type="date" name="dtAdmissao"> 
                            </td>
                           </tr>

                            <tr>
                            <td>
                             <label style="margin-left: 15px">Horário: </label>
                             <!-- <label class="col-xs-3 control-label">Horário</label> -->
                            </td>
                            <td align="left">
                             <!-- <input type="text" name="idSupervisor" size="9" >  -->
                              <div class="form-group">
                                 <!-- <label class="col-xs-3 control-label">Supervisor</label> -->
                                 <div class="col-xs-12 selectContainer" >
                                     <select  name="horario">
                                         <option value="">Escolha um horário</option>
                                         <?php while ($row = sqlsrv_fetch_array($result_Horario)){ ?>
                                            <option value=<?php echo $row['ID_HORARIO']?> > <?php echo 'ENTRADA: '.$row['ENTRADA'].'  |  Saída: '.$row['SAIDA'].'  |  Carga Horária: '.$row['CARGA_HORARIO']?> </option>
                                         <?php }
                                         ?>
                                     </select>
                                 </div>
                              </div>
                            </td>

                          </table>
                         </fieldset>
                         
                         <br/>

                          <td><button class="button" onclick=" return getConfirmation();" type="submit" value="<?php echo $row['ID_MATRICULA']?>"  name="ID_MATRICULA">Confirmar</button> 
                         <input type="reset" value="Limpar">
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
              2014 - Alvarez.is
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
        


    function getConfirmation(){
       // var retVal = confirm("Do you want to continue ?");
       if(  confirm(" Deseja Finalizar a Troca ? ") == true ){
          return true;
       }
       else{
          return false;
       }
    }
        



</script>