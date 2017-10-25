<?php include '..\AdmCrm\connectionADM.php'; 
session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; 
}

if ( (date('h:i:s')) >=  (date('h:i:s', strtotime('+15 minute', strtotime($_SESSION['TEMPOSESSION'])))) & ($_SESSION['ACESSO'] <> 1 ) ){
     // Ação a ser executada: encerra a session depois de 15 min
   echo  '<script type="text/javascript"> alert("Tempo de Sessão Expirada"); window.location.href = "http://d42150:8087/cesudesk/AdmCrm/login.php"  </script>'; 
   session_destroy();
 }
 
 $_SESSION['TEMPOSESSION'] = date('h:i:s');





$ID_QUEM_APLICOU = $_SESSION['ID_COLABORADOR'];



$sqlProcessoAtual = "  SELECT ID
                             ,NOME
                             ,MODALIDADE
                             ,ATIVO
                             ,DATA_INICIO
                             ,DATA_FIM
                             
                        FROM tb_crm_processo
                       WHERE MODALIDADE = 'Graduação'
                         AND ATIVO = 1
                 ";

$result_ProcessoAtual = sqlsrv_prepare($conn, $sqlProcessoAtual);
sqlsrv_execute($result_ProcessoAtual);
$vetorSQL = sqlsrv_fetch_array($result_ProcessoAtual);


$sqlListaMonitorias = "  SELECT 
                      tc.NOME as NOME_CONSULTOR
                      ,tp.ID_COLABORADOR_APLICA
                      ,tc.ID_MATRICULA as MATRICULA_CONSULTOR
                      ,(SELECT NOME FROM tb_crm_colaborador WHERE ID_COLABORADOR = tp.ID_COLABORADOR_APLICA) as NOME_QUEM_APLICOU
                      ,tpro.NOME as NOME_PROCESSO
                      ,tp.ID_GRUPO
                      ,tcron.NUMERO as NUMERO_DA_AVALIACAO
                      ,tg.DESCRICAO
                      ,tp.NOTA_FINAL
                      ,tp.RAMAL_PA
                      ,tp.DT_ATENDIMENTO
                      ,tp.DT_SISTEMA
                FROM tb_qld_pesquisa tp
          INNER JOIN tb_crm_processo tpro ON tpro.ID = tp.ID_PROCESSO AND tpro.ATIVO = 1
          INNER JOIN tb_crm_colaborador tc ON tc.ID_COLABORADOR = tp.ID_COLABORADOR
          INNER JOIN tb_crm_grupo tg ON tg.ID_GRUPO = tp.ID_GRUPO
          INNER JOIN tb_qld_cronograma_avaliacao tcron ON tcron.ID_AVALIACAO = tp.ID_AVALIACAO
            WHERE ID_COLABORADOR_APLICA = {$ID_QUEM_APLICOU}
              AND tpro.ATIVO = 1 
            ORDER BY tp.DT_SISTEMA desc
                                         ";

$result_ListaMonitoria = sqlsrv_prepare($conn, $sqlListaMonitorias);
sqlsrv_execute($result_ListaMonitoria);

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


          <section class="wrapper">
            <h3><i class="fa fa-right"></i> Lista de Monitorias já Realizadas</h3>

            <!-- criar formulario -->
              <div class="row mt">
                  <div class="col-md-12">
                      <div class="content-panel">
                        <form name="Form" method="post" id="formulario" action="editaColaborador.php">
                          <table class="table table-striped table-advance table-hover order-table table-wrapper">
                            <h4><i class="fa fa-right"></i>Processo Atual: <?php echo $vetorSQL['NOME']  ?> <br><br> Data Início:  <?php echo date_format($vetorSQL['DATA_INICIO'],"d-m-Y")  ?> <br><br> Data Fim:  <?php echo date_format($vetorSQL['DATA_FIM'],"d-m-Y")  ?> </h4>
                            <hr>
                            <input  style="margin-left: 15px;" type="search" class="light-table-filter" data-table="order-table table-wrapper table" placeholder="Search"></input>
                              <thead>
                              <tr>
                                  <th><i class=""></i> Nome Avaliador </th>
                                  <th><i class=""></i> Nome Consultor </th>
                                  <th><i class=""></i> Matrícula Consultor </th>
                                  <th><i class=""></i> Numero Avaliação </th>
                                  <th><i class=""></i> Grupo </th>
                                  <th><i class=""></i> Nota Final</th>
                                  <th><i class=""></i> Data Atendimento </th>
                                  <th><i class=""></i> Data Cadastro Monitoria </th>

                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <?php  while($row = sqlsrv_fetch_array($result_ListaMonitoria)) { 
                                    ?>
                                  <td><?php echo $row['NOME_QUEM_APLICOU'] ?></a></td>  
                                  <td><?php echo $row['NOME_CONSULTOR'] ?></a></td>
                                  <td><?php echo $row['MATRICULA_CONSULTOR'] ?></td>
                                  <td><?php echo $row['NUMERO_DA_AVALIACAO'] ?></td>
                                  <td><?php echo $row['DESCRICAO'] ?></a></td>
                                  <td><a><?php echo $row['NOTA_FINAL'] ?></a></td>
                                  <td><?php echo date_format($row['DT_ATENDIMENTO'],"d-m-Y") ?></a></td>
                                  <td><?php echo date_format($row['DT_SISTEMA'],"d-m-Y") ?></a></td>

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