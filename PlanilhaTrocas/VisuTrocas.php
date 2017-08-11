<?php include '..\PlanilhaTrocas\connection.php';
session_start();

$sql_trocas = "SELECT tc.ID_MATRICULA
                    ,tc.NOME
                    ,tg.DESCRICAO AS DESCRICAO_GRUPO
                    ,tr.DESCRICAO AS DESCRIVAO_REGIAO
                    ,(SELECT COUNT(1) FROM tb_crm_trocas_new WHERE tc.ID_COLABORADOR in (ID_COLABORADOR1,ID_COLABORADOR2)) as QT_TROCA_GERAL
                    ,(SELECT COUNT(1) 
                      FROM tb_crm_trocas_new 
                     WHERE (tc.ID_COLABORADOR IN (ID_COLABORADOR1,ID_COLABORADOR2))
                       AND DT_TROCA BETWEEN DATEADD(month, DATEDIFF(month, -1, getdate()) - 1, 0) -- PRIMEIRO DIA DO MÊS ATUAL
                                          AND DATEADD(ss, -1, DATEADD(month, DATEDIFF(month, 0, getdate())+1, 0)) -- ULTIMO DIA DO MÊS ATUAL
                       AND TP_STATUS IN ('AGUARDANDO_ENVIO' , 'CONCLUIDO')
                       ) as QT_TROCA_MES_ATUAL
                  ,CAST(DATEADD(month, DATEDIFF(month, -1, getdate()) - 1, 0) AS DATE) AS FIRST_DAY
                  ,CAST(DATEADD(ss, -1, DATEADD(month, DATEDIFF(month, 0, getdate())+1, 0)) AS DATE) AS LAST_DAY
                        FROM tb_crm_colaborador tc
                  INNER JOIN tb_crm_grupo tg on tg.ID_GRUPO = tc.ID_GRUPO
                  INNER JOIN tb_crm_regiao tr on tr.ID_REGIAO = tg.ID_REGIAO
                  INNER JOIN tb_crm_cargo ta on ta.ID_CARGO = tc.ID_CARGO AND ta.BO_TROCA_HORARIO = 'S'
               WHERE tc.STATUS_COLABORADOR = 'ATIVO'   
                  ORDER BY tc.NOME";
   $result_trocas = sqlsrv_prepare($conn, $sql_trocas);
   sqlsrv_execute($result_trocas);


?>
<!DOCTYPE html>
<html>
<title>CESUDESK - Planilha de Trocas</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="..\PlanilhaTrocas\VisuTrocas.css">


<style>
body, h1,h2,h3,h4,h5,h6 {font-family: "Montserrat", sans-serif}
.w3-row-padding img {margin-bottom: 12px}
/* Set the width of the sidebar to 120px */
.w3-sidebar {width: 120px;background: #01579B;}
/* Add a left margin to the "page content" that matches the width of the sidebar (120px) */
#main {margin-left: 100px}
/* Remove margins from "page content" on small screens */
@media only screen and (max-width: 100px) {#main {margin-left: 0px}}
</style>
<body style="background-color: #EEEEEE;"">

<!-- Icon Bar (Sidebar - hidden on small screens) -->
<nav class="w3-sidebar w3-bar-block w3-small w3-hide-small w3-center">
  <!-- Avatar image in top left corner -->
  <img src="..\PlanilhaTrocas\imagens\Avatar.jpg" style="width:100%">
  <a href="PaginaIni.php" style="color: white;" class="w3-bar-item w3-button w3-padding-large w3-hover-blue">
    <i class="fa fa-home w3-xxlarge"></i>
    <p>HOME</p>
  </a>
  <a href="VisuTrocas.php" class="w3-bar-item w3-button w3-padding-large w3-blue">
    <i class="fa fa-user w3-xxlarge"></i>
    <p>Visualizar Trocas</p>
  </a>
</nav>

<!-- Navbar on small screens (Hidden on medium and large screens) -->
<div class="w3-top w3-hide-large w3-hide-medium" id="myNavbar">
  <div class="w3-bar w3-black w3-opacity w3-hover-opacity-off w3-center w3-small">
    <a href="#" class="w3-bar-item w3-button" style="width:25% !important">HOME</a>
    <a href="#about" class="w3-bar-item w3-button" style="width:25% !important">Visualizar Trocas</a>
  </div>
</div>

<!-- Page Content -->
<div class="w3-padding-large" id="main">
  <!-- Header/Home -->
  <header style="background-color: #EEEEEE;" class="w3-container w3-padding-32 w3-center " id="home">
    <h1 class="w3-jumbo"><span class="w3-hide-small">Visualizar Trocas</span> </h1>
    <p>Call Center</p>
  </header>

  <!-- About Section -->
  <div class="w3-content w3-justify w3-text-grey w3-padding-64" id="about">
    <h2 class="w3-text-black">Trocas Relizadas</h2>
    <hr class="w3-text-black" align="left" style="width:250px">
    <p style="color: black"; >Consulte as trocas do consultor
    </p>
     <!-- Dentro desta DIV, inserir a Tabela para Visualizar -->

<input type="search" class="light-table-filter" data-table="order-table table-wrapper table" placeholder="Search"></input> <a style="padding-left: 460px"> Período Atual <?php echo date("01/m/Y") ?> Até <?php echo date("t/m/Y") ?>  </a>
<div class="w3-responsive" id="table-scroll">
<section class="container">

  <table style="color: black" class="order-table table-wrapper table">
    <thead>
      <tr>
        <th>Matrícula</th>
        <th>Name</th>
        <th>Grupo</th>
        <th ALIGN=MIDDLE>Quantidade Mês Atual</th>
        <th ALIGN=MIDDLE>Quantidade Trocas Geral</th>
      </tr>
    </thead>
    <tbody>
       <?php  while($row = sqlsrv_fetch_array($result_trocas)) {
         ?>
            <tr>
                <td width='100'><?php echo $row['ID_MATRICULA']?></td>
                <td><?php echo utf8_encode($row['NOME'])?></td>
                <td><?php echo utf8_encode($row['DESCRICAO_GRUPO'])?></td>
                <td width='140' ALIGN=MIDDLE><?php echo $row['QT_TROCA_MES_ATUAL']?></td>
                <td width='140' ALIGN=MIDDLE><?php echo $row['QT_TROCA_GERAL']?></td>
            </tr>
        <?php

        }
        ?>
    </tbody>
  </table>
 </section>
</div>
    <!-- Footer -->
  <footer class="w3-content w3-padding-64 w3-text-grey w3-xlarge">
    <p class="w3-medium">Powered by <a href="https://pbs.twimg.com/profile_images/378800000055685514/ea9a7b6ee16b6d8c237b249f2f59bee0.jpeg" target="_blank" class="w3-hover-text-green">CRM - UNICESUMAR</a></p>
  <!-- End footer -->
  </footer>

<!-- END PAGE CONTENT -->
</div>
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