<?php include '..\PlanilhaTrocas\connection.php';
session_start(); 
setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");
date_default_timezone_set('America/Sao_Paulo');


$hora_servidor = date("H:i:s");
$horaLimite = '17:00:00';



          $nomeSoli            = $_POST["nomeSoli"]; 
          $matriculaSoli       = $_POST["matriculaSoli"]; 
          $supervisorSoli      = $_POST["supervisorSoli"];
          $horarioSoli         = $_POST["horarioSoli"];
          $idColaboradorSoli   = $_POST["idColaboradorSoli"];
          $idHorarioSoli       = $_POST["idHorarioSoli"];
          $idSupSoli           = $_POST["idSupSoli"];

          $nomeCede            = $_POST["nomeCede"]; 
          $matriculaCede       = $_POST["matriculaCede"]; 
          $supervisorCede      = $_POST["supervisorCede"];
          $horarioCede         = $_POST["horarioCede"];
          $idColaboradorCede   = $_POST["idColaboradorCede"];
          $idHorarioCede       = $_POST["idHorarioCede"];
          $idSupCede           = $_POST["idSupCede"];

          $dateTroca_PaginaIni = $_POST["dateTroca_PaginaIni"];


    if($_SESSION['BO_TROCA'] == 'S'){

        $sqlInsert = " INSERT INTO tb_crm_trocas_new
                     (ID_COLABORADOR1
                      ,ID_HORARIO1
                      ,ID_SUPERVISOR1
                      ,ID_COLABORADOR2
                      ,ID_HORARIO2
                      ,ID_SUPERVISOR2
                      ,DT_TROCA
                      ,TP_STATUS)
                     VALUES
                      (?
                      ,?
                      ,?
                      ,?
                      ,?
                      ,?
                      ,?
                      ,?)";

      $params = array($idColaboradorSoli
                      ,$idHorarioSoli
                      ,$idSupSoli
                      ,$idColaboradorCede
                      ,$idHorarioCede
                      ,$idSupCede
                      ,$dateTroca_PaginaIni 
                      ,'AGUARDANDO_ENVIO');

      $result_trocas = sqlsrv_query($conn, $sqlInsert, $params);
      
      if (!($result_trocas)) {
        echo ("Falha na inclusão do registro");
        print_r(sqlsrv_errors());
      }
      $_SESSION['BO_TROCA'] = 'N';      
       sqlsrv_free_stmt($result_trocas);
       sqlsrv_close($conn);
   }
   else {
    echo  '<script type="text/javascript"> window.location.href = "PaginaIni.php" </script>';
   }
   
?>
<link rel="stylesheet" href="..\PlanilhaTrocas\PaginaPdf.css">
<form method="post" action="http://d42150:8080/main"> 
          <input type="submit" class="botaoInv" value="Cancelar Operação" ></input>
</form>

<div id="topo" style="width: 960px; margin-left: 30px;" align="center">
  <div class="formulario" align="center">
    <img style="width: 300px;" src="http://imagens.ead.cesumar.br/crm/imgs/logo-ead.png">
  </div>
   <img src="..\PlanilhaTrocas\imagens\barraPDF.jpg" style="width:100%">
  <br>
  <h1 style="font-family: arial; font-weight: inherit;">TERMO DE CI&Ecirc;NCIA</h1> 
  <h2 style="font-family: arial; font-weight: inherit;"><i>SOLICITA&Ccedil;&Atilde;O DE TROCA DE HOR&Aacute;RIO</i></h2>  
  <div style="width: 760px;">
    <span style="text-align: center; font-size: 17px; font-family: arial; ">Eu <?php echo $nomeSoli ?>, declaro ter solicitado troca de horário de trabalho com <?php echo $nomeCede ?>, para o dia <?php echo date("d/m/Y", strtotime($dateTroca_PaginaIni)) ?>.  </span><br><br>
    <span style="text-align: center; font-size: 17px; font-family: arial; ">Desta forma nos comprometemos a cumprir rigorosamente o horário da troca conforme escala de trabalho determinada neste termo.</span><br><br><br>
  </div>
  <div align="left" style="font-family: arial; margin-left: 30px;">
    <span>Dados da Troca: <?php echo $dateTroca_PaginaIni ?></span><br><br><br>
    <ul style="margin-left: -40px;" >

      <li style="list-style-type: none; margin-bottom: 5px;">Solicitante <span style="margin-left: 30px;"> </span></li><br>
      <li style="list-style-type: none; margin-bottom: 5px;">Matr&iacute;cula: <span style="margin-left: 37px;"><?php echo $matriculaSoli ?></span> </li>
      <li style="list-style-type: none; margin-bottom: 5px;">Consultor: <span style="margin-left: 34px;"><?php echo $nomeSoli ?></span></li>
      <li style="list-style-type: none; margin-bottom: 5px;">Hor&aacute;rio: <span style="margin-left:49px;"s><?php echo $horarioSoli ?></span></li><br><br><br>

      <li style="list-style-type: none; margin-bottom: 5px;">Cedente <span style="margin-left:45px;"s> </span></li><br>
      <li style="list-style-type: none; margin-bottom: 5px;">Matr&iacute;cula: <span style="margin-left:35px;"s><?php echo $matriculaCede ?></span></li>
      <li style="list-style-type: none; margin-bottom: 5px;">Consultor: <span style="margin-left: 34px;"><?php echo $nomeCede ?></span></li>
      <li style="list-style-type: none; margin-bottom: 5px;">Hor&aacute;rio: <span style="margin-left:48px;"s><?php echo $horarioCede ?></span></li>
    </ul>
  </div><br><br><br><br>
  <div style="border-top: 2px solid black; width: 400px; float: left; margin: 0px 56px; font-family: arial;"><span>Solicitante</span></div>
  <div style="border-top: 2px solid black; width: 400px; float: left; font-family: arial;"><span>Cedente</span></div><br><br><br><br>
  <div style="border-top: 2px solid black; width: 400px; float: left; margin: 0px 56px; font-family: arial;"><span>Supervisor do solicitante</span></div>
  <div style="border-top: 2px solid black; width: 400px; float: left; font-family: arial;"><span>Supervisor do cedente</span></div>
  <br><br><br><br><br><br><br><br>
    
    <?php
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        $datahj = strftime('%A, %d de %B de %Y', strtotime('today'));
    ?>  
    <span style="font-family: arial;">Data:  <?php echo utf8_encode($datahj); ?></span>
  
</div>



<?php
echo "<script>window.print();</script>";
session_destroy();

?>
