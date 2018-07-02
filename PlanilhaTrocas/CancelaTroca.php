<?php include '..\PlanilhaTrocas\connection.php';
session_start();
setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");
date_default_timezone_set('America/Sao_Paulo');

$dataValida = date("Y-m-d" ,strtotime("+2 days")); // variavel criada para definir a data minima para selecionar no campo date 

if($_SESSION["USUARIO"] =='alexandre.vitolo'){  //validar usuario para realizar trocas qualquer dia
$dataValida = date("Y-m-d" ,strtotime("-1 year"));
}

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://dd42150:8087/CESUDESK/NewCesudesk/index.php"  </script>'; 
}



$sqlCancelaTroca = "SELECT ID_TROCA
                          ,(SELECT ID_MATRICULA FROM DB_CRM_REPORT.dbo.tb_crm_colaborador WHERE ID_COLABORADOR = tnew.ID_COLABORADOR1) as ID_MATRICULA1
                          ,(SELECT NOME FROM DB_CRM_REPORT.dbo.tb_crm_colaborador WHERE ID_COLABORADOR = tnew.ID_COLABORADOR1) as ID_COLABORADOR1
                          ,(SELECT ID_MATRICULA FROM DB_CRM_REPORT.dbo.tb_crm_colaborador WHERE ID_COLABORADOR = tnew.ID_COLABORADOR2) as ID_MATRICULA2
                          ,(SELECT NOME FROM DB_CRM_REPORT.dbo.tb_crm_colaborador WHERE ID_COLABORADOR = tnew.ID_COLABORADOR2) as ID_COLABORADOR2
                          ,tnew.ID_HORARIO2
                          ,tnew.ID_SUPERVISOR2
                          ,CONVERT(date,tnew.DT_TROCA) as DT_TROCA
                          ,tnew.TP_STATUS
                      FROM DB_CRM_REPORT.dbo.tb_crm_trocas_new tnew 
                     WHERE tnew.TP_STATUS = 'AGUARDANDO_ENVIO' ";
                    

$result_squila = sqlsrv_prepare($conn, $sqlCancelaTroca);
sqlsrv_execute($result_squila);





?>
<!DOCTYPE html>
<html>
<title>Planilha de Trocas</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="shortcut icon" href="icone.ico">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->  <!-- pagina  off bloqueada -->
<link rel="stylesheet" href="..\PlanilhaTrocas\font-awesome\css\font-awesome.css"> <!-- pagina  off bloqueada -->
<link rel="stylesheet" href="..\PlanilhaTrocas\font-awesome\css\font-awesome.css">
<link rel="stylesheet" href="..\PlanilhaTrocas\css\font-awesome-animation.min.css">
<link rel="stylesheet" href="..\PlanilhaTrocas\PaginaIni.css">
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
  <a href="PaginaIni.php" style="color: white;" class="w3-bar-item w3-button w3-padding-large w3-hover-blue">
    <i class="fa fa-home w3-xxlarge"></i>
    <p>HOME</p>
  </a>
  <a href="VisuTrocas.php" style="color: white;" class="w3-bar-item w3-button w3-padding-large w3-hover-blue">
    <i class="fa fa-user w3-xxlarge"></i>
    <p>Visualizar Trocas</p>
  </a>

 <?php if ($_SESSION['ACESSO'] == 1){ ?>
    <a href="CancelaTroca.php" style="color: white;" class="w3-bar-item w3-button w3-padding-large w3-blue">
      <i class="fa faa-flash animated fa-remove w3-xxlarge"></i>
      <p>Cancelar Trocas</p>
    </a>
<?php } ?>

</nav>

<!-- Navbar on small screens (Hidden on medium and large screens) -->
<div class="w3-top w3-hide-large w3-hide-medium" id="myNavbar">
  <div class="w3-bar w3-black w3-opacity w3-hover-opacity-off w3-center w3-small">
    <a href="#" class="w3-bar-item w3-button" style="width:25% !important">HOME</a>
    <a href="#about" class="w3-bar-item w3-button" style="width:25% !important">Visualizar Trocas</a>
  </div>
</div>

 <?php if ($_SESSION['ACESSO'] == 1){ ?>
<!-- Navbar on small screens (Hidden on medium and large screens) -->
    <div class="w3-top w3-hide-large w3-hide-medium" id="myNavbar">
      <div class="w3-bar w3-black w3-opacity w3-hover-opacity-off w3-center w3-small">
        <a href="#" class="w3-bar-item w3-button" style="width:25% !important">HOME</a>
        <a href="#about" class="w3-bar-item w3-button" style="width:25% !important">Cancelar Trocas</a>
      </div>
    </div>
 <?php } ?>



<!-- Page Content -->
<div class="w3-padding-large" id="main">
  <!-- Header/Home -->
  <header style="background-color: #EEEEEE;" class="w3-container w3-padding-32 w3-center" id="home">
    <h1 class="w3-jumbo"><span style="color:black;">Cancelamento de Trocas</span> </h1>
    <p class="w3-text-black">Call Center</p>
  </header>

  <!-- About Section -->
  <div class="w3-content w3-justify w3-text-grey w3-padding-64" id="about">
    <h2 style="color:black;">Troca de Consultores</h2>
    <hr align="left" style="width:315px" class="w3-opacity">

    <!-- Dentro desta DIV, inserir a Tabela para Visualizar -->
   
    <!-- Dentro desta DIV, inserir a Tabela para Visualizar -->


 <form name="Form" method="post" id="formulario" action="ValidaCancelaTroca.php">
   <table id="consultor" class="order-table table-wrapper table" style="color:black;">
    <thead>

        <tr>
            <th ALIGN=MIDDLE style="width:110px">Matrícula 1</th>
            <th ALIGN=MIDDLE style="width:200px">Nome Consultor 1</th>
            <th ALIGN=MIDDLE style="width:110px">Matrícula 2</th>
            <th ALIGN=MIDDLE style="width:200px">Nome Consultor 2</th>
            <th ALIGN=MIDDLE style="width:250px">Data Troca</th>
            <th ALIGN=MIDDLE>Status</th>              
        </tr>
    </thead>
    <tbody>
      <?php  while($row = sqlsrv_fetch_array($result_squila)) { 
         ?>
            <tr>
                <td ALIGN=MIDDLE><?php echo $row['ID_MATRICULA1']?></td>
                <td ALIGN=MIDDLE><?php echo ($row['ID_COLABORADOR1'])?></td>
                <td ALIGN=MIDDLE><?php echo ($row['ID_MATRICULA2'])?></td>
                <td ALIGN=MIDDLE><?php echo $row['ID_COLABORADOR2']?></td>
                <td ALIGN=MIDDLE><?php echo date_format($row['DT_TROCA'], "d/m/Y"); ?></td>
                <td ALIGN=MIDDLE><?php echo $row['TP_STATUS']?></td>
                <td ALIGN=MIDDLE><button class="btn btn-primary btn-block" onclick=" return getConfirmation();" type="submit" value="<?php echo $row['ID_TROCA']?>"  name="ID_TROCA">Cancelar Troca <i class="fa fa-pencil"></i></button> </a>
            </tr>
                  <!--Tabela criada para guardar valores para a proxima pagina-->
        <?php
            }
        ?>
           
    </tbody>
   </table>        
</form>

</div>



</div>
  

  
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

      // função criada para retornar falso ou verdadeiro no botão formulário
      //caso for falso o formulário não dispara ação de entrar em outra pag

    function getConfirmation(){
       // var retVal = confirm("Do you want to continue ?");
       if(  confirm(" Deseja realizar cancelar a troca ?") == true ){
          return true;
       }
       else{
          return false;
       }
    }
        

</script>