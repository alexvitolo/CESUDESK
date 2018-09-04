<?php include '..\NewCesudesk\connectionNEWCESUDESK.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://dd42150:8087/CESUDESK/NewCesudesk/index.php"  </script>'; 
}

if ($_SESSION['ACESSO'] <> 1 )  {
 // Ação a ser executada: mata o script e manda uma mensagem
 echo  '<script type="text/javascript"> window.location.href = "index.php"  </script>';
}


 
 $_SESSION['TEMPOSESSION'] = date('H:i:s');


$NOME_COLAB_COD = strtoupper($_GET['NOME_COLAB_COD']);

$sqlListaCodCol = " SELECT TOP 50 [id] as CODIGO_PORTAL
                              ,nome
                              ,email
                              ,usuario
                              ,telefone
                              ,celular
                              ,cpf
                          FROM [EAD_PORTAL].[dbo].[usuarios] 
                          WHERE nome like '%{$NOME_COLAB_COD}%' order by 2 asc
                       ";

$result_CodCol = sqlsrv_prepare($conn3, $sqlListaCodCol);
sqlsrv_execute($result_CodCol);

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>NewCesudesk - CRM</title>
  <link rel="shortcut icon" href="icone.ico" >
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/font-awesome.min.css" rel="stylesheet">
  <link href="css/datepicker3.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="TratarChamadosEdita.css">
  
  <!--Custom Font-->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <script src="js/respond.min.js"></script>
  <![endif]-->
</head>

  <body>


          <section class="wrapper">
            <h3><i class="fa fa-right"></i> Lista de Colaboradores Portal</h3>

            <!-- criar formulario -->
              <div class="row mt">
                  <div class="col-md-12">
                      <div class="content-panel">
                        <form name="Form" method="post" id="formulario" action="editaColaborador.php">
                          <table class="table table-striped table-advance table-hover order-table table-wrapper">
                            <input  style="margin-left: 15px;" type="search" class="light-table-filter" data-table="order-table table-wrapper table" placeholder="Search"></input>
                              <thead>
                              <tr>
                                  <th><i class=""></i>CODIGO_PORTAL </th>
                                  <th><i class=""></i>Nome </th>
                                  <th><i class=""></i>Email </th>
                                  <th><i class=""></i>Usuario </th>
                                  <th><i class=""></i>Telefone </th>
                                  <th><i class=""></i>Celular </th>
                                  <th><i class=""></i>Cpf </th>

                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <?php  while($row = sqlsrv_fetch_array($result_CodCol)) { 
                                    ?>
                                  <td><?php echo $row['CODIGO_PORTAL'] ?></a></td>  
                                  <td><?php echo $row['nome'] ?></a></td>
                                  <td><?php echo $row['email'] ?></td>
                                  <td><?php echo $row['usuario'] ?></td>
                                  <td><?php echo $row['telefone'] ?></a></td>
                                  <td><a><?php echo $row['celular'] ?></a></td>
                                  <td><a><?php echo $row['cpf'] ?></a></td>
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