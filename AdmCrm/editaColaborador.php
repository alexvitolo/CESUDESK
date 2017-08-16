<?php include '..\PlanilhaTrocas\connection.php'; 

$ID_COLABORADOR = $_POST["ID_COLABORADOR"]; // id colaborador

$squiladica = "SELECT TC.ID_COLABORADOR,
                      TC.NOME,
                      TC.LOGIN_REDE,
                      TC.LOGIN_TELEFONIA,
                      TC.TELEFONE,
                      TC.DT_NASCIMENTO,
                      TC.DT_ADMISSAO,
                      TC.EMAIL,
                      TC.STATUS_COLABORADOR,
                      TC.ID_MATRICULA,
                      TC.ID_COLABORADOR_GESTOR,
                      TC.CODIGO_PORTAL,
                      TC.NIVEL_CARGO,
                      TC.ID_CARGO,
                      TC.ID_GRUPO,
                      TC.ID_HORARIO

                 FROM tb_crm_colaborador TC
                WHERE ID_COLABORADOR = '{$ID_COLABORADOR}' ";

$result_squila = sqlsrv_prepare($conn, $squiladica);
sqlsrv_execute($result_squila);

$vetorSQL = sqlsrv_fetch_array($result_squila);

$DT_NASCIMENTO = date_format($vetorSQL['DT_NASCIMENTO'], "Y-m-d");
$DT_ADMISSAO = date_format($vetorSQL['DT_ADMISSAO'], "Y-m-d");




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

$sqlGrupo = "SELECT tg.ID_GRUPO
                   ,tg.DESCRICAO AS DESC_GRUPO
                   ,CASE
                    WHEN tr.DESCRICAO = 'Sem região' THEN '' ELSE tr.DESCRICAO
                     END AS DESC_REGIAO
              FROM tb_crm_grupo tg
        INNER JOIN tb_crm_regiao tr on tg.ID_REGIAO = tr.ID_REGIAO";

$result_Grupo = sqlsrv_prepare($conn, $sqlGrupo);
sqlsrv_execute($result_Grupo);


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>ADMINISTRATIVO CRM</title>

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
                         <form name="Form" method="post" id="formulario" action="ValidaEditaColaborador.php">
<!-- DADOS PESSOAIS-->
                         <fieldset>
                          <legend> Dados do colaborador </legend>
                          <table cellspacing="10" style="vertical-align: middle">
                           <tr>
                            <td style="width:110px";>
                             <label style="margin-left: 15px" for="nome">Matricula: </label>
                            </td>
                            <td align="left">
                             <input type="text" name="MATRICULA" value="<?php echo $vetorSQL['ID_MATRICULA']; ?>">
                            </td>
                            <td>
                             <label style="margin-left: 15px" for="nome">Nome: </label>
                            </td>
                            <td align="left">
                             <input type="text" name="NOME" size="35" value="<?php echo $vetorSQL['NOME']; ?>">
                            </td>
                             <td>
                             <label style="margin-left: 15px" for="sobrenome">Data Nascimento: </label>
                            </td>
                            <td align="left">
                             <input type="date" name="dtNascimento" value="<?php echo $DT_NASCIMENTO ?>">
                            </td> 
                           </tr>

                           <tr>
                            <td>
                             <label style="margin-left: 15px" >E-mail: </label>
                            </td>
                            <td align="left">
                             <input type="text" name="email" size="40" value="<?php echo $vetorSQL['EMAIL']; ?>">
                            </td>
                            <td>
                             <label style="margin-left: 15px" >Código Portal: </label>
                            </td>
                            <td align="left">
                             <input type="text" name="codPortal" size="10" value="<?php echo $vetorSQL['CODIGO_PORTAL']; ?>">
                            </td>
                           </tr>

                           <tr>
                           <td>
                             <label style="margin-left: 15px" >Telefone: </label>
                            </td>
                            <td align="left">
                             <input type="text" name="telefone" size="25" value="<?php echo $vetorSQL['TELEFONE']; ?>">
                            </td>
                           </tr>

                           <tr>
                            <td>
                            <br/>
                             <label style="margin-left: 15px" for="rg">Login Rede: </label>
                            </td>
                            <td align="left">
                             <input type="text" name="loginRede" size="25" value="<?php echo $vetorSQL['LOGIN_REDE']; ?>"> 
                            </td>
                             <td>
                             <label style="margin-left: 15px" >Login Telefonia:</label>
                            </td>
                            <td align="left">
                             <input type="text" name="loginTelefonia" size="30" value="<?php echo $vetorSQL['LOGIN_TELEFONIA']; ?>"> 
                            </td>
                            <td>
                             <label style="margin-left: 15px" for="status">Status :</label>
                            </td>
                            <td align="left">
                             <select name="STATUS" value="<?php echo $vetorSQL['STATUS_COLABORADOR']; ?>"> 
                             <option value="ATIVO">ATIVO</option>
                             <option value="FERIAS">FERIAS</option> 
                             <option value="DESLIGADO">DESLIGADO</option>
                             <option value="INSS">INSS</option>  
                            </select>
                            </td>
                           </tr>

                           <tr>
                            <td style="width:120px";>
                             <label style="margin-left: 15px">Nome Supervisor:</label>
                            </td>
                            <td align="left">
                             <select name="supervisor">
                                         <option value="">Escolha um supervisor</option>
                                         <?php while ($row = sqlsrv_fetch_array($result_supervisores)){ ?>
                                            <option value=<?php echo $row['ID_SUP']?> > <?php echo utf8_encode($row['NOME_SUP']) ?> </option>
                                         <?php }
                                         ?>
                             </select>
                            </td>
                            </td>
                           </tr>

                            <tr>
                            <td>
                            <br/>
                             <label style="margin-left: 15px" for="rg">Cargo: </label>
                            </td>
                            <td align="left">
                             <select name="cargo">
                                         <option value="">Escolha um Cargo</option>
                                         <?php while ($row = sqlsrv_fetch_array($result_Cargo)){ ?>
                                            <option value=<?php echo $row['ID_CARGO']?> > <?php echo utf8_encode($row['DESC_CARGO']) ?> </option>
                                         <?php }
                                         ?>
                             </select>
                            </td>
                            <td>
                             <label style="margin-left: 15px" for="status">Nível Cargo :</label>
                            </td>
                            <td align="left">
                             <select name="nivelCargo" value="<?php echo $vetorSQL['NIVEL_CARGO']; ?>"> 
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
                             <input type="date" name="dtAdmissao" value="<?php echo $DT_ADMISSAO ; ?>"> 
                            </td>
                           </tr>

                            <tr>
                            <td>
                            <br/>
                             <label style="margin-left: 15px" >Horário: </label>
                            </td>
                            <td align="left">
                             <select  name="horario">
                                         <option value="">Escolha um horário</option>
                                         <?php while ($row = sqlsrv_fetch_array($result_Horario)){ ?>
                                            <option value=<?php echo $row['ID_HORARIO']?> > <?php echo 'ENTRADA: '.$row['ENTRADA'].'  |  Saída: '.$row['SAIDA'].'  |  Carga Horária: '.$row['CARGA_HORARIO']?> </option>
                                         <?php }
                                         ?>
                             </select>
                            </td>
                            <td>
                            <br/>
                             <label style="margin-left: 15px" >Grupo: </label>
                            </td>
                            <td align="left">
                             <select  name="grupo">
                                         <option value="">Escolha um Grupo</option>
                                         <?php while ($row = sqlsrv_fetch_array($result_Grupo)){ ?>
                                           <option value=<?php echo $row['ID_GRUPO']?> > <?php echo 'Grupo: '. utf8_encode($row['DESC_GRUPO']).'  |   '. utf8_encode($row['DESC_REGIAO']) ?> </option>
                                         <?php }
                                         ?>
                             </select>
                            </td>
                           </tr>


                          </table>
                         </fieldset>
                         
                         <br/>

                          <td><button class="button" onclick=" return getConfirmation();" type="submit" value="<?php echo $row['ID_MATRICULA']?>"  name="ID_MATRICULA">Confirmar</button> 
                         <a href="colaboradores.php"><input type="button" value="Cancelar"></a>
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
              2017 - CRM MASTER
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