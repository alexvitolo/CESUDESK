<?php include '..\PlanilhaTrocas\connection.php';
session_start();
setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");
date_default_timezone_set('America/Sao_Paulo');

$dataValida = date("Y-m-d" ,strtotime("+2 days")); // variavel criada para definir a data minima para selecionar no campo date 

if($_SESSION["USUARIO"] =='alexandre.vitolo'){  //validar usuario para realizar trocas qualquer dia
$dataValida = date("Y-m-d" ,strtotime("-1 year"));
}

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
  <a href="PaginaIni.php" class="w3-bar-item w3-button w3-padding-large w3-blue">
    <i class="fa fa-home w3-xxlarge"></i>
    <p>HOME</p>
  </a>
  <a href="VisuTrocas.php" style="color: white;" class="w3-bar-item w3-button w3-padding-large w3-hover-blue">
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
  <header style="background-color: #EEEEEE;" class="w3-container w3-padding-32 w3-center" id="home">
    <h1 class="w3-jumbo"><span style="color:black;">Planilha de Trocas</span> </h1>
    <p class="w3-text-black">Call Center</p>
  </header>

  <!-- About Section -->
  <div class="w3-content w3-justify w3-text-grey w3-padding-64" id="about">
    <h2 style="color:black;">Troca de Consultores</h2>
    <hr align="left" style="width:315px" class="w3-opacity">
    <p style="color:black;">Digite o número da matrícula do consultor a ser trocado
    </p>

    <!-- Dentro desta DIV, inserir a Tabela para Visualizar -->
    <div>
      <form name="Form" method="post" id="formulario" action="PaginaTroca.php">
        <div class="box"> 
          <h1> Planilha de Troca :</h1>
       
          <label> 
            <span>Número Matrícula</span>
            <input type="text" class="input_text" name="numero_PaginaIni" id="numero" required value="" onkeypress='return event.charCode >= 48 && event.charCode <= 57'/>
          </label>
          <label>
            <span>Data</span>
            <!-- é necessário utilizar o echo para utilizar a variavel dentro do valor min html -->
            <input type="date"  min="<?php echo $dataValida ?>"  class="input_text" name="dateTroca_PaginaIni" id="date" required value=""/>
            <input type="submit" class="button" value="Enviar" >
            <br><br>
          </label>           
        </div>
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