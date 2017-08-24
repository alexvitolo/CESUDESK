<?php include '..\AdmCrm\connectionADM.php'; 
session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
 // Ação a ser executada: mata o script e manda uma mensagem
echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; }


$ID_GRUPO = $_GET["ID_GRUPO"]; 
$ID_HORARIO = $_GET["ID_HORARIO"]; 



$sqlPausaOK = " SELECT DISTINCT CONVERT(VARCHAR,DM1.HORARIO_PAUSA,108) As HORARIO_PAUSA 
                      ,(ISNULL(DM1.DIMENSIONAMENTO,0) - ISNULL(DM2.QT_COL_PAUSAS,0)) AS DIMEN_VS_PAUSA
                      ,DM1.ID_GRUPO
                      ,CASE
                      WHEN (DM1.HORARIO_PAUSA BETWEEN tn.ENTRADA+'01:00:00' AND tn.ENTRADA+'02:30:00' ) THEN 'PS1'
                      WHEN (DM1.HORARIO_PAUSA BETWEEN tn.ENTRADA+'02:30:01' AND tn.ENTRADA+'04:00:00' ) THEN 'PSL'
                      WHEN (DM1.HORARIO_PAUSA BETWEEN tn.ENTRADA+'04:00:01' AND tn.ENTRADA+'04:50:00' ) THEN 'PS2'
                       ELSE 'LOCURA'
                       END AS TIPO_PAUSA
                    FROM (SELECT COUNT(tc.ID_COLABORADOR) AS GERAL_LOGADOS
                                ,ROUND(COUNT(tc.ID_COLABORADOR)*0.2,0) AS DIMENSIONAMENTO
                            ,tc.ID_GRUPO
                            ,tp.HORARIO_PAUSA
                            FROM tb_crm_colaborador tc 
                           INNER JOIN tb_crm_horario th ON th.ID_HORARIO = tc.ID_HORARIO AND th.BO_ESCALA_FDS = 'N' AND TH.CARGA_HORARIO = '06:00:00'
                           INNER JOIN tb_crm_cargo tl on tl.ID_CARGO = tc.ID_CARGO AND tl.BO_GESTOR = 'N'
                           left JOIN tb_crm_horario_pausa tp ON tp.HORARIO_PAUSA BETWEEN th.ENTRADA AND th.SAIDA
                           WHERE TC.STATUS_COLABORADOR = 'ATIVO'
                        GROUP BY tc.ID_GRUPO
                                ,tp.HORARIO_PAUSA) DM1
                  LEFT JOIN (SELECT SUM(RK.QT_COL_PAUSAS) AS QT_COL_PAUSAS
                           ,RK.HORARIO_PAUSA
                           ,RK.ID_GRUPO
                           ,RK.ID_TIPO_PAUSA
                               FROM (SELECT COUNT(tp.ID_COLABORADOR) AS QT_COL_PAUSAS
                                 ,tp.HORARIO_PAUSA
                                 ,tp.ID_TIPO_PAUSA
                                 ,tc.ID_GRUPO
                              FROM tb_crm_escala_pausa tp
                          INNER JOIN tb_crm_colaborador tc ON tp.ID_COLABORADOR = tc.ID_COLABORADOR AND tc.STATUS_COLABORADOR = 'ATIVO'
                          INNER JOIN tb_crm_cargo ta ON ta.ID_CARGO = tc.ID_CARGO AND ta.BO_GESTOR = 'N' 
                             WHERE tp.DT_VIGENCIA_FINAL IS NULL
                               AND tp.HORARIO_PAUSA IS NOT NULL
                          GROUP BY tp.HORARIO_PAUSA
                              ,tp.ID_TIPO_PAUSA
                              ,tc.ID_GRUPO
                             UNION 
                             SELECT COUNT(tp.ID_COLABORADOR) AS QT_COL_PAUSAS
                                 ,tp.HORARIO_PAUSA+'00:10:00'
                                 ,tp.ID_TIPO_PAUSA
                                 ,tc.ID_GRUPO
                               FROM tb_crm_escala_pausa tp
                           INNER JOIN tb_crm_colaborador tc ON tp.ID_COLABORADOR = tc.ID_COLABORADOR AND tc.STATUS_COLABORADOR = 'ATIVO'
                           INNER JOIN tb_crm_cargo ta ON ta.ID_CARGO = tc.ID_CARGO AND ta.BO_GESTOR = 'N' 
                            WHERE tp.DT_VIGENCIA_FINAL IS NULL
                              AND tp.HORARIO_PAUSA IS NOT NULL
                              AND tp.ID_TIPO_PAUSA = 5
                           GROUP BY tp.HORARIO_PAUSA
                               ,tp.ID_TIPO_PAUSA
                               ,tc.ID_GRUPO) AS RK
                        GROUP BY RK.HORARIO_PAUSA
                            ,RK.ID_GRUPO
                            ,RK.ID_TIPO_PAUSA) DM2 ON DM1.ID_GRUPO = DM2.ID_GRUPO AND DM1.HORARIO_PAUSA = DM2.HORARIO_PAUSA
                      INNER JOIN tb_crm_colaborador nc ON nc.ID_GRUPO = DM1.ID_GRUPO AND nc.STATUS_COLABORADOR = 'ATIVO'
                      INNER JOIN tb_crm_horario tn ON nc.ID_HORARIO = tn.ID_HORARIO AND tn.BO_ESCALA_FDS = 'N' AND tn.CARGA_HORARIO = '06:00:00'
                  WHERE 1=1
                    AND DM1.ID_GRUPO = {$ID_GRUPO}
                    AND tn.ID_HORARIO = {$ID_HORARIO}
                    AND (ISNULL(DM1.DIMENSIONAMENTO,0) - ISNULL(DM2.QT_COL_PAUSAS,0)) >= 1
                    AND DM1.HORARIO_PAUSA BETWEEN tn.ENTRADA+'01:00:00' AND tn.SAIDA-'01:10:00'
                  ORDER BY CONVERT(VARCHAR,DM1.HORARIO_PAUSA,108), DM1.ID_GRUPO";

$result_PausaOK = sqlsrv_prepare($conn, $sqlPausaOK);
sqlsrv_execute($result_PausaOK);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

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


          <section class="wrapper">
            <h3><i class="fa fa-right"></i> Lista de Colaboradores</h3>

            <!-- criar formulario -->
              <div class="row mt">
                  <div class="col-md-12">
                      <div class="content-panel">
                        <form name="Form" method="post" id="formulario" action="editaColaborador.php">
                          <table class="table table-striped table-advance table-hover order-table table-wrapper">
                            <h4><i class="fa fa-right"></i> Colaboradores Call-Center </h4>
                            <hr>
                            <input  style="margin-left: 15px;" type="search" class="light-table-filter" data-table="order-table table-wrapper table" placeholder="Search"></input>
                              <thead>
                              <tr>
                                  <th><i class=""></i> Horário Pausa </th>
                                  <th><i class=""></i> Dimensão VS Pausa </th>
                                  <th><i class=""></i> ID Grupo </th>
                                  <th><i class=""></i> Tipo Pausa</th>

                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <?php  while($row = sqlsrv_fetch_array($result_PausaOK)) { 
                                    ?>
                                  <td><?php echo $row['HORARIO_PAUSA'] ?></a></td>
                                  <td><?php echo $row['DIMEN_VS_PAUSA'] ?></td>
                                  <td><?php echo $row['ID_GRUPO'] ?></a></td>
                                  <td><?php echo $row['TIPO_PAUSA'] ?></a></td>
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