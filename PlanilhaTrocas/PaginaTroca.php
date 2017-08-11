<?php include '..\PlanilhaTrocas\connection.php';
session_start(); 
setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");
date_default_timezone_set('America/Sao_Paulo');
    
          $numero_PaginaIni = $_POST["numero_PaginaIni"]; //atribuição do campo name "numero_PaginaIni" vindo do formulário para variavel  
          $dateTroca_PaginaIni = $_POST["dateTroca_PaginaIni"];
          //Verifica se o campo nome não está em branco.
          //echo !empty($numero_PaginaIni) ? "Nome: {$numero_PaginaIni}<br/>" : "Favor digitar o Número do Consultor<br/>";
          //Verifica se o campo sobrenome não está em branco.
         // echo !empty($dateTroca_PaginaIni) ? "Sobrenome: {$dateTroca_PaginaIni}<br/>" : "Favor digitar a data corretamente<br/>";

//processo de validação trocas dias
// domingo =0 sabado =6
$diasemana_numero = date('w', time());
$diasemana_dataTroca = date('w', strtotime($dateTroca_PaginaIni));
$hora_servidor = date("H:i:s");
$horaLimite = '17:00:00';

  if (($diasemana_numero == 6) or ($diasemana_numero == 0)){

     echo  '<script type="text/javascript">alert("Impossível Realizar Troca no Sábado");</script>';
     echo  '<script type="text/javascript"> window.location.href = "PaginaIni.php" </script>';
  }
  elseif(($diasemana_numero == 5) and ( (strtotime($hora_servidor) >= strtotime($horaLimite)) ) ) {
    echo  '<script type="text/javascript">alert("Impossível Realizar Troca, Horário Excedido  ");</script>';
    echo  '<script type="text/javascript"> window.location.href = "PaginaIni.php" </script>';
  }
  elseif($diasemana_dataTroca == 0){
    echo  '<script type="text/javascript">alert("Impossível Realizar Troca para Data Escolhida ");</script>';
    echo  '<script type="text/javascript"> window.location.href = "PaginaIni.php" </script>';
  }






          $sqlValida ="SELECT tc.ID_MATRICULA
                                ,tc.NOME
                  ,CASE when (SELECT COUNT(1) 
                                    FROM tb_crm_trocas_new 
                                   WHERE tc.ID_COLABORADOR in (ID_COLABORADOR1,ID_COLABORADOR2)
                                     AND DT_TROCA BETWEEN DATEADD(month, DATEDIFF(month, -1, getdate()) - 1, 0) -- PRIMEIRO DIA DO MÊS ATUAL
                                                        AND DATEADD(ss, -1, DATEADD(month, DATEDIFF(month, 0, getdate())+1, 0)) -- ULTIMO DIA DO MÊS ATUAL        
                                     AND TP_STATUS <> 'CANCELADO' ) >=2 then 'INVALIDO' 
                               ELSE 'VALIDO'
                  END CONDICAO

                        FROM tb_crm_colaborador tc
                  INNER JOIN tb_crm_grupo tg on tg.ID_GRUPO = tc.ID_GRUPO
                  INNER JOIN tb_crm_regiao tr on tr.ID_REGIAO = tg.ID_REGIAO
                  INNER JOIN tb_crm_cargo ta on ta.ID_CARGO = tc.ID_CARGO AND ta.BO_TROCA_HORARIO = 'S' 
                       WHERE ID_MATRICULA ='{$numero_PaginaIni}'
                         AND tc.STATUS_COLABORADOR = 'ATIVO'
                    ORDER BY tr.id_regiao
                            ,tc.NOME";

          $stmtValida = sqlsrv_prepare($conn, $sqlValida);
          $resultValida = sqlsrv_execute($stmtValida);
          $resultadoSQL = sqlsrv_fetch_array($stmtValida);

          if ( $resultadoSQL == 0) {
              echo  '<script type="text/javascript">alert("Numero de Matricula não Existe");</script>';
              echo  '<script type="text/javascript"> window.location.href = "PaginaIni.php" </script>';
              //header('location: PaginaIni.php');   não funciona
          }
          else{
            if ($resultadoSQL['CONDICAO'] == "INVALIDO") {
              echo  '<script type="text/javascript">alert("Consultor já possui 2 ou mais trocas!");</script>';
              echo  '<script type="text/javascript"> window.location.href = "PaginaIni.php" </script>';
              //header('location: PaginaIni.php');   não funciona
            }

          }
        
         

  $sqlPaginaTroca = " SELECT tc.ID_MATRICULA
                           ,tc.NOME
                           ,tg.DESCRICAO AS DESCRICAO_GRUPO
                           ,tr.DESCRICAO AS DESCRICAO_REGIAO
                           ,(SELECT COUNT(1) 
                               FROM tb_crm_trocas 
                              WHERE MATRICULA = tc.ID_MATRICULA 
                                AND DATA_TROCA BETWEEN DATEADD(month, DATEDIFF(month, -1, getdate()) - 1, 0) -- PRIMEIRO DIA DO MÊS ATUAL
                                                   AND DATEADD(ss, -1, DATEADD(month, DATEDIFF(month, 0, getdate())+1, 0)) -- ULTIMO DIA DO MÊS ATUAL
                                                      ) as QT_TROCA_MES_ATUAL
                      FROM tb_crm_colaborador tc
                INNER JOIN tb_crm_grupo tg on tg.ID_GRUPO = tc.ID_GRUPO
                INNER JOIN tb_crm_regiao tr on tr.ID_REGIAO = tg.ID_REGIAO
                INNER JOIN tb_crm_cargo ta on ta.ID_CARGO = tc.ID_CARGO AND ta.BO_TROCA_HORARIO = 'S' 
                     WHERE (SELECT COUNT(1) 
                              FROM TB_CRM_TROCAS_NEW 
                             WHERE TC.ID_COLABORADOR IN (ID_COLABORADOR1,ID_COLABORADOR2)
                               AND DT_TROCA BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, -1, GETDATE()) - 1, 0) 
                                                AND DATEADD(SS, -1, DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())+1, 0))
                               AND TP_STATUS <> 'CANCELADO') <= 2
                       AND (SELECT COUNT(1)
                              FROM TB_CRM_TROCAS_NEW 
                        INNER JOIN (SELECT ID_COLABORADOR 
                                      FROM tb_crm_colaborador 
                                     WHERE ID_MATRICULA = TC.ID_MATRICULA) T ON ID_COLABORADOR1 = T.ID_COLABORADOR 
                                                                             OR ID_COLABORADOR2 = T.ID_COLABORADOR
                             WHERE DT_TROCA = '{$dateTroca_PaginaIni}' 
                               AND TP_STATUS <> 'CANCELADO') = 0
                       AND tc.ID_HORARIO NOT IN (SELECT ID_HORARIO
                                                   FROM tb_crm_colaborador
                                                  WHERE ID_MATRICULA = '{$numero_PaginaIni}'
                          )
                       AND tc.ID_MATRICULA <> '{$numero_PaginaIni}'
                       AND UPPER(tc.STATUS_COLABORADOR) = 'ATIVO'
                       AND EXISTS (SELECT top 1 1
                                     FROM tb_crm_colaborador c
                               INNER JOIN tb_crm_grupo g ON g.ID_GRUPO = c.ID_GRUPO
                                    WHERE g.ID_REGIAO = tg.ID_REGIAO
                                      AND c.ID_GRUPO = tc.ID_GRUPO
                                      AND ID_MATRICULA = '{$numero_PaginaIni}'
                    )
                  ORDER BY tr.id_regiao, tc.NOME";
 
  $stmtPaginaTroca = sqlsrv_prepare($conn, $sqlPaginaTroca);
  $resultPaginaTroca = sqlsrv_execute($stmtPaginaTroca);


?>
<!DOCTYPE html>
<html>
<title>CESUDESK - Planilha de Trocas</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="..\PlanilhaTrocas\PaginaTroca.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<style>
body, h1,h2,h3,h4,h5,h6 {font-family: "Montserrat", sans-serif}
.w3-row-padding img {margin-bottom: 12px}
/* Set the width of the sidebar to 120px */
.w3-sidebar {width: 120px;background: #01579B;}
/* Add a left margin to the "page content" that matches the width of the sidebar (120px) */
#main {margin-left: 100px}
/* Remove margins from "page content" on small screens */
@media only screen and (max-width: 100px) {#main {margin-left: 0}}
</style>
<body style="background-color: #EEEEEE;">

<!-- Icon Bar (Sidebar - hidden on small screens) -->
<nav class="w3-sidebar w3-bar-block w3-small w3-hide-small w3-center">
  <!-- Avatar image in top left corner -->
  <img src="..\PlanilhaTrocas\imagens\Avatar.jpg" style="width:100%">
  <a class="w3-bar-item w3-button w3-padding-large w3-blue">
    <i class="fa fa-home w3-xxlarge"></i>
    <p>HOME</p>
  </a>
</nav>

<!-- Navbar on small screens (Hidden on medium and large screens) -->
<div class="w3-top w3-hide-large w3-hide-medium" id="myNavbar">
  <div class="w3-bar w3-blue w3-opacity w3-hover-opacity-off w3-center w3-small">
    <a href="#" class="w3-bar-item w3-button" style="width:25% !important">HOME</a>
  </div>
</div>

<!-- Page Content -->
<div class="w3-padding-large" id="main">
  <!-- Header/Home -->
  <header style="background-color: #EEEEEE;" class="w3-container w3-padding-32 w3-center" id="home">
    <h1 class="w3-jumbo"><span class="w3-hide-small">Planilha de Trocas</span> </h1>
    <p>Call Center</p>
  </header>

  <!-- About Section -->
  <div class="w3-content w3-justify w3-text-grey w3-padding-64" id="about">
    <h2 class="w3-text-black">Colaboradores Disponíveis</h2>
    <hr align="left" style="width:395px" class="w3-opacity">
    <p style="color:black;" >Selecione na tabela o colaborador disponível para realização da troca
    </p>

    <!-- Dentro desta DIV, inserir a Tabela para Visualizar -->
<input type="search" class="light-table-filter" data-table="order-table table-wrapper table" placeholder="Search"></input> <a style="padding-left: 460px"> Período Atual <?php echo date("01/m/Y") ?> Até <?php echo date("t/m/Y") ?>  </a>
<div style="color:black;" class="w3-responsive" id="table-scroll">
<section class="container">

 <form name="Form" method="post" id="formulario" action="PaginaTrocaValida.php">
   <table id="consultor" class="order-table table-wrapper table">
    <thead>

        <tr>
            <th>Matrícula</th>
            <th>Nome Consultor</th>
            <th>Grupo</th> 
            <th>Região</th>          
            <th ALIGN=MIDDLE>Trocas no mês</th>                   
        </tr>
    </thead>
    <tbody>
       <?php  while($row= sqlsrv_fetch_array($stmtPaginaTroca)) {
         ?>
            <tr>
                <td><?php echo $row['ID_MATRICULA']?></td>
                <td><?php echo utf8_encode($row['NOME'])?></td>
                <td><?php echo utf8_encode($row['DESCRICAO_GRUPO'])?></td>
                <td><?php echo utf8_encode($row['DESCRICAO_REGIAO'])?></td>
                <td ALIGN=MIDDLE><?php echo $row['QT_TROCA_MES_ATUAL']?></td>
                <td><button class="btn btn-primary btn-block" onclick=" return getConfirmation();" type="submit" value="<?php echo $row['ID_MATRICULA']?>"  name="ID_MATRICULA">Trocar</button> </a>
            </tr>
                  <!--Tabela criada para guardar valores para a proxima pagina-->
              <tr> 
                <td> <input type="hidden" class="input_text" name="numero_PaginaIni" id="numero" value="<?php echo $numero_PaginaIni ?>" /></td>
                <td> <input type="hidden" class="input_text" name="dateTroca_PaginaIni" id="date" value="<?php echo $dateTroca_PaginaIni ?>" /></td>
            </tr>
        <?php
            }
        ?>
           
    </tbody>
   </table>        
</form>

</div>

    <p> </p>  <!-- Pular Linha -->
    <form method="post" action="..\PlanilhaTrocas\PaginaIni.php"> 
      <input type="submit" class="button" value="Cancelar Operação" ></input>
    </form>
  
    <!-- Footer -->
  <footer class="w3-content w3-padding-64 w3-text-grey w3-xlarge">
    <p class="w3-medium">Powered by <a href="http://d42150:8080/main" target="_blank" class="w3-hover-text-green">CRM - UNICESUMAR</a></p>
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


      // função criada para retornar falso ou verdadeiro no botão formulário
      //caso for falso o formulário não dispara ação de entrar em outra pag

    function getConfirmation(){
       // var retVal = confirm("Do you want to continue ?");
       if(  confirm(" Deseja realizar a troca ?") == true ){
          return true;
       }
       else{
          return false;
       }
    }
        

</script>