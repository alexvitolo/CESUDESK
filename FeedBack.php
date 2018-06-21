<?php include '..\CESUDESK\AdmCrm\connectionADM.php'; 


if (! isset( $_GET["ID_PESQUISA"] ) ) {
    $_GET["ID_PESQUISA"] = '';
}

$ID_PESQUISA = $_GET["ID_PESQUISA"];

if ($ID_PESQUISA == 1162) {
   echo  '<script type="text/javascript">alert("Filtro Inválido");</script>';
   exit;
}



$squilaPesquisa = "SELECT tc.ID_MATRICULA,
                       tc.ID_COLABORADOR,
                       (SELECT NOME from tb_crm_colaborador WHERE ID_COLABORADOR = tc.ID_COLABORADOR_GESTOR) as NOME_SUP,
                       tc.NOME,
                       tc.LOGIN_REDE,
                       tc.PASS_FEEDBACK,
                       (SELECT DESCRICAO FROM tb_crm_grupo WHERE ID_GRUPO = tp.ID_GRUPO) as NOME_GRUPO,
                       (SELECT NOME FROM tb_crm_processo WHERE ID = tp.ID_PROCESSO) as NOME_PROCESSO
                  FROM tb_crm_colaborador tc
             LEFT JOIN tb_qld_pesquisa tp ON tp.ID_COLABORADOR = tc.ID_COLABORADOR
                 WHERE tp.ID_PESQUISA = {$ID_PESQUISA} ";

$result_squilaPesquisa = sqlsrv_prepare($conn, $squilaPesquisa);
sqlsrv_execute($result_squilaPesquisa);


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Qualidade - EAD</title>

    <!-- Bootstrap core CSS -->
    <link href="../CESUDESK/AdmCrm/assets/css/bootstrap.css" rel="stylesheet">
    <link rel="shortcut icon" href="icone.ico" >
     <link rel="stylesheet" href="..\CESUDESK\AdmCrm\general.css">
    <!--external css-->
    <link href="../CESUDESK/AdmCrm/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        
    <!-- Custom styles for this template -->
    <link href="../CESUDESK/AdmCrm/assets/css/style.css" rel="stylesheet">
    <link href="../CESUDESK/AdmCrm/assets/css/style-responsive.css" rel="stylesheet">
    <link href="..\CESUDESK\AdmCrm\colaboradores.css" rel="stylesheet">

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
                  <div class="" data-placement="right" data-original-title="Toggle Navigation"></div>
              </div>
            <!--logo start-->
            <a href="" class="logo"><b> FeedBack – Validação  </b></a>
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

              </ul>
            </div>
        </header>
      <!--header end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN SIDEBAR MENU
      *********************************************************************************************************************************************************** -->
      <!--sidebar start-->
      <!--sidebar end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">

            <!-- criar formulario -->
              <div class="row mt">
                  <div class="col-md-8">
                      <div class="content-panel">
                        <form name="Form" method="post" id="formulario" action="Valida_FeedBack.php">
                          <table class="table table-striped table-advance table-hover order-table table-wrapper">
                            <h4><i class="fa fa-right"></i> Validação Recebimento FeedBack </h4>
                            <hr>
                   
                              <thead>
                              <tr>
                                  <th><i class=""></i> Matrícula </th>
                                  <th><i class=""></i> Nome </th>
                                  <th><i class=""></i> Login Rede </th>
                                  <th><i class=""></i> Processo </th>
                                  <th><i class=""></i> Grupo </th>
                                  <th><i class=""></i> Nome Supervisor </th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <?php  while($row = sqlsrv_fetch_array($result_squilaPesquisa)) { 
                                    ?>
                                  <td><?php echo $row['ID_MATRICULA'] ?></a></td>
                                  <td><?php echo $row['NOME'] ?></td>
                                  <td><?php echo $row['LOGIN_REDE'] ?></a></td>
                                  <td><?php echo $row['NOME_PROCESSO'] ?></a></td>
                                  <td><?php echo $row['NOME_GRUPO'] ?></a></td>
                                  <td><?php echo $row['NOME_SUP'] ?></a></td>
                                  <td>
                                      <!-- <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button> -->
                                  </td>
                              </tr>

                              <?php 
                                    }
                              ?>
                              
                              </tbody>
                          </table>
                              <br>

                              <div class="form-group">
                                <label style="margin-left: 15px">Senha Digital</label>
                                <input name="SENHA_FEEDBACK"  type="password" placeholder=""  required>
                              </div>
                              <br>

                               <button style="margin-left: 15px" type="submit" class="btn btn-primary">Validar FeedBack</button>
                               <input type="hidden" name="ID_PESQUISA" value="<?php echo $ID_PESQUISA ;?>">
                               <br>
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
              2018 - CALL CENTER
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