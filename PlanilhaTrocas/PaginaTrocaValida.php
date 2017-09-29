<?php include '..\PlanilhaTrocas\connection.php';
session_start(); 
 date_default_timezone_set('America/Sao_Paulo');
$_SESSION['BO_TROCA']='S';

          $numero_PaginaTroca  = $_POST["ID_MATRICULA"]; //atribuição do campo name "numero_PaginaIni" vindo do formulário para variavel  
          $numero_PaginaIni    = $_POST["numero_PaginaIni"]; //atribuição do campo name "numero_PaginaIni" vindo do formulário para variavel  
          $dateTroca_PaginaIni = $_POST["dateTroca_PaginaIni"];
           // sql para identificar conflito de jornadas

          $sqlValidaOperac =" DECLARE @DATE DATE 
                              SET @DATE = '{$dateTroca_PaginaIni}' --@param
                        
                        SELECT C.ID_COLABORADOR,
                               c.ID_MATRICULA,
                               F.ID_HORARIO,
                               c.NOME AS CONSULTOR,
                               convert(varchar,H1.ENTRADA,108) AS ENTRADA,
                               (SELECT NOME FROM tb_crm_colaborador WHERE ID_COLABORADOR = c.ID_COLABORADOR_GESTOR) AS NOME_SUP,
                               c.ID_COLABORADOR_GESTOR as ID_SUP,
                               H1.SAIDA,
                               tg.DESCRICAO AS DESC_REGIAO,
                               CASE 
                                 WHEN c.ID_MATRICULA = '{$numero_PaginaIni}' THEN 1 ELSE 2 END RK_QS_ORDER
                          FROM tb_crm_colaborador c
                    INNER JOIN tb_crm_grupo tg ON tg.ID_GRUPO = c.ID_GRUPO
                    INNER JOIN tb_crm_escala_fds F ON f.ID_COLABORADOR = C.ID_COLABORADOR
                    INNER JOIN tb_crm_horario H1 ON H1.ID_HORARIO = F.ID_HORARIO
                         WHERE c.ID_MATRICULA IN ('{$numero_PaginaIni}','{$numero_PaginaTroca}')
                           AND F.DT_FDS = @DATE 
                        
                        UNION
                        SELECT C.ID_COLABORADOR,
                               c.ID_MATRICULA,
                               C.ID_HORARIO,
                               c.NOME AS CONSULTOR,
                               convert(varchar,h.ENTRADA,108) AS ENTRADA,
                               (SELECT NOME FROM tb_crm_colaborador WHERE ID_COLABORADOR = c.ID_COLABORADOR_GESTOR) AS NOME_SUP,
                               c.ID_COLABORADOR_GESTOR as ID_SUP,
                               H.SAIDA,
                               tg.DESCRICAO AS DESC_REGIAO,
                               CASE 
                                 WHEN c.ID_MATRICULA = '{$numero_PaginaIni}' THEN 1 ELSE 2 END RK_QS_ORDER
                          FROM tb_crm_colaborador c
                    INNER JOIN tb_crm_grupo tg ON tg.ID_GRUPO = c.ID_GRUPO
                    INNER JOIN tb_crm_horario H ON h.ID_HORARIO = c.ID_HORARIO
                         WHERE c.ID_MATRICULA IN ('{$numero_PaginaIni}','{$numero_PaginaTroca}')
                           AND NOT EXISTS (SELECT 1 FROM tb_crm_escala_fds WHERE DT_FDS = @DATE )
                        ORDER BY RK_QS_ORDER
                          --(13,1) => (4,12)";


         // $sqlValidaOperac ="SELECT tc.ID_COLABORADOR
         //                           ,tc.ID_COLABORADOR_GESTOR
         //                           ,(SELECT NOME FROM tb_crm_colaborador WHERE ID_COLABORADOR = tc.ID_COLABORADOR_GESTOR) AS NOME_SUP
         //                           ,tc.ID_MATRICULA
         //                           , tc.NOME
         //                           ,tg.DESCRICAO 
         //                           ,CONVERT(VARCHAR(11),th.ENTRADA,114) as ENTRADA  
         //                  FROM tb_crm_colaborador tc
         //                  INNER JOIN tb_crm_grupo tg on tg.ID_GRUPO = tc.ID_GRUPO
         //                  INNER JOIN tb_crm_regiao tr on tr.ID_REGIAO = tg.ID_REGIAO
         //                  INNER JOIN tb_crm_cargo ta on ta.ID_CARGO = tc.ID_CARGO 
         //                  INNER JOIN tb_crm_horario th on th.ID_HORARIO = tc.ID_HORARIO
         //                     WHERE tc.ID_MATRICULA in ('{$numero_PaginaIni}','{$numero_PaginaTroca}')";

          $stmtValidaOpera = sqlsrv_prepare($conn, $sqlValidaOperac);
          $resultValidaOperac = sqlsrv_execute($stmtValidaOpera);
          
          $x=0;
          while ($dados = sqlsrv_fetch_array($stmtValidaOpera)) {
            $rowResul[$x][0] = $dados['CONSULTOR'];
            $rowResul[$x][1] = $dados['ID_MATRICULA'];
            $rowResul[$x][2] = $dados['NOME_SUP'];
            $rowResul[$x][3] = $dados['DESC_REGIAO'];
            $rowResul[$x][4] = $dados['ENTRADA'];
            $rowResul[$x][5] = $dados['ID_HORARIO'];
            $rowResul[$x][6] = $dados['ID_COLABORADOR'];
            $rowResul[$x][7] = $dados['ID_SUP'];
            $x++;
          }

          if ((($rowResul[0][5] == 13)&&($rowResul[1][5] == 1))
            || (($rowResul[0][5] == 1)&&($rowResul[1][5] == 13))
            ) {
            
            if (($rowResul[0][5] == 1)){
              $rowResul[0][4] = '09:00:00';
              $rowResul[0][5] = 4;
            }
            else {
              $rowResul[0][4] = '15:00:00';
              $rowResul[0][5] = 12;
            }

            if (($rowResul[1][5] == 13)){
              $rowResul[1][4] = '15:00:00';
              $rowResul[1][5] = 12;
            } 
            else {
              $rowResul[1][4] = '09:00:00';
              $rowResul[1][5] = 4;
            }

          }
         
           // foreach ($nome as $key => $value) {
           //   echo ("Deseja trocar o ". $key.$value);}              
          //header('location: PaginaIni.php');   não funciona

         

?>
<!DOCTYPE html>
<html>
<title>Planilha de Trocas</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="shortcut icon" href="icone.ico" >
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="..\PlanilhaTrocas\PaginaTrocaValida.css">
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
    <h2 class="w3-text-black">Verificar Troca</h2>
    <hr style="width:220px" class="w3-opacity">
    <p style="color: black;">Por Favor, verifique os dados dos consultores
    </p>

    <!-- Dentro desta DIV, inserir a Tabela para Visualizar -->
<form name="Form" method="post" id="formulario" action="PaginaPdf.php">
  <table style="color: black;" width="500px" >
    <tr>
      <td width="90%">
        <div id="inliner" style="width: 100%"">
          <div style="position:static; display: inline; border: solid #EEEEEE 1px; background-color: #EEEEEE;">
            <table id="leftTable" style="margin-left: 15px;">
              <tr>
                <td>
                  <span>
                    <span>Solicitante  </span>
                  </span>
                </td>
              </tr>
              <tr>
                <td>
                    <p>Consultor: <?php echo utf8_encode($rowResul[0][0]) ?></p>
                    <p>Matrícula: <?php echo $rowResul[0][1] ?></p>
                    <p>Supervisor: <?php echo utf8_encode($rowResul[0][2]) ?></p>
                    <p>Grupo: <?php echo utf8_encode($rowResul[0][3]) ?></p>
                    <p>Horário Entrada: <?php echo $rowResul[0][4] ?></p>                                      
                </td>
                </td>
              </tr>
            </table>
          </div>
          <div style="position:static; display: inline; border: solid #EEEEEE 1px; background-color: #EEEEEE;">
            <table id="rightTable" style="margin-left: 15px;">
              <tr>
                <td>
                  <span>
                  </span>
                </td>
              </tr>
              <tr>
                <td>
                <span>
                    <span>Cedente  </span>
                  </span>
                    <p>Consultor: <?php echo utf8_encode($rowResul[1][0]) ?></p>
                    <p>Matrícula: <?php echo $rowResul[1][1] ?></p>
                    <p>Supervisor: <?php echo utf8_encode($rowResul[1][2]) ?></p>
                    <p>Grupo: <?php echo utf8_encode($rowResul[1][3]) ?></p>
                    <p>Horário Entrada: <?php echo $rowResul[1][4] ?></p>                                       
                </td>
              </tr>
            </table>
          </div>
        </div>
      </td>
      <td>
      </td>
          <td> <input type="hidden" class="input_text" name="nomeSoli"           value="<?php echo utf8_encode($rowResul[0][0]) ?>" /></td>
          <td> <input type="hidden" class="input_text" name="matriculaSoli"      value="<?php echo $rowResul[0][1] ?>" /></td>
          <td> <input type="hidden" class="input_text" name="supervisorSoli"     value="<?php echo utf8_encode($rowResul[0][2]) ?>" /></td>
          <td> <input type="hidden" class="input_text" name="horarioSoli"        value="<?php echo $rowResul[0][4] ?>" /></td>
          <td> <input type="hidden" class="input_text" name="idHorarioSoli"      value="<?php echo $rowResul[0][5] ?>" /></td>
          <td> <input type="hidden" class="input_text" name="idColaboradorSoli"  value="<?php echo $rowResul[0][6] ?>" /></td>
          <td> <input type="hidden" class="input_text" name="idSupSoli"          value="<?php echo $rowResul[0][7] ?>" /></td>

          <td> <input type="hidden" class="input_text" name="nomeCede"           value="<?php echo utf8_encode($rowResul[1][0]) ?>" /></td>
          <td> <input type="hidden" class="input_text" name="matriculaCede"      value="<?php echo $rowResul[1][1] ?>" /></td>
          <td> <input type="hidden" class="input_text" name="supervisorCede"     value="<?php echo utf8_encode($rowResul[1][2]) ?>" /></td>
          <td> <input type="hidden" class="input_text" name="horarioCede"        value="<?php echo $rowResul[1][4] ?>" /></td>
          <td> <input type="hidden" class="input_text" name="idHorarioCede"      value="<?php echo $rowResul[1][5] ?>" /></td>
          <td> <input type="hidden" class="input_text" name="idColaboradorCede"  value="<?php echo $rowResul[1][6] ?>" /></td>
          <td> <input type="hidden" class="input_text" name="idSupCede"          value="<?php echo $rowResul[1][7] ?>" /></td>    
 
          <td> <input type="hidden" class="input_text" name="dateTroca_PaginaIni" value="<?php echo $dateTroca_PaginaIni ?>" /></td>

    </tr>
  </table>
      <button class="button" onclick=" return getConfirmation();" type="submit" value="<?php echo $row['ID_MATRICULA']?>"  name="ID_MATRICULA">Trocar</button> 
</form>

    
    
        <br>
        <form method="post" action="..\PlanilhaTrocas\PaginaIni.php"> 
          <input type="submit" class="button2" value="Cancelar Operação" ></input>
        </form>
      
        <!-- Footer -->
      <footer class="w3-content w3-padding-64 w3-text-grey w3-xlarge">
        <p class="w3-medium">Powered by <a href="http://d42150:8080/main" target="_blank" class="    w3-hover-text-green">CRM - UNICESUMAR</a></p>    
      <!-- End footer -->    
      </footer>    
    
    <!-- END PAGE CONTENT -->
    </div>

</div>
</div>

</body>
</html>


<script type="text/javascript">

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

