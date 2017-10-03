<?php include '..\PlanilhaTrocas\connection.php';
session_start();
$_SESSION["USUARIO"] = $_GET["USUARIO"];

?>
<link rel="stylesheet" href="..\PlanilhaTrocas\index.css">
<link rel="shortcut icon" href="icone.ico" >

<!DOCTYPE html>
<html>
<head>
</head>
<body onload="myFunction()" style="margin:0;">

<div id="loader"></div>

<div style="display:none;" id="myDiv" class="animate-bottom">
  <h2>Concluído!</h2>
  <p></p>
</div>
<div class ="close"> <meta http-equiv="refresh" content=5;url="PaginaIni.php"> </div>

<script>
var myVar;

function myFunction() {
    myVar = setTimeout(showPage, 3000);
}

function showPage() {
  document.getElementById("loader").style.display = "none";
  document.getElementById("myDiv").style.display = "block";
}

</script>

</body>
</html>