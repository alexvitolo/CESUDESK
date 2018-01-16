<?php include '..\BI\connectionTALSIMA.php';  

if (array_key_exists('TAMANHO', $_GET)){
	$tamanho = $_GET['TAMANHO'];
}else{
	$tamanho = 400;
}


$data_points = array();

   $sql = "     SELECT CONVERT(VARCHAR(10),CONVERT(DATE, LD.dh_distrib),103) AS DH_DISTRIB
                ,(SELECT tName FROM tblteam where aTeamID = ld.teamid) as TEAM
                ,COUNT(1) AS QT_LEAD
           FROM Unicesumar_Lead_Distrib LD
     INNER JOIN tblUser u ON u.aUserID = LD.iduser
          WHERE CONVERT(DATE, LD.dh_distrib) = CONVERT(DATE, GETDATE()) 
            and ld.teamid <> 2
       GROUP BY CONVERT(DATE, LD.dh_distrib) ,ld.teamid
       ORDER BY COUNT(1) DESC";
   
   $result = sqlsrv_prepare($conn, $sql);
   sqlsrv_execute($result);
   
         if (!($result)) {
                echo ("Falha na inclusão do registro");
                print_r(sqlsrv_errors());
         }   
   ;
// while($row = sqlsrv_fetch_array($result))
// {
// $point = array("y" => $row['QT_LEAD'] , "name" => $row['TEAM']);
// array_push($data_points, $point);
// }
// echo json_encode($data_points, JSON_NUMERIC_CHECK);
//  exit;



//sql entrada de leads diarios por hora



$data_points_2 = array();

   $sql2 = "SELECT CONCAT('new Date ( Date.UTC (',CONVERT(VARCHAR(10),DATEPART(YEAR, l.[FldDate21845])),',', 
	               CONVERT(VARCHAR(10),DATEPART(MONTH, l.[FldDate21845])-1),',',
			       CONVERT(VARCHAR(10),DATEPART(DAY, l.[FldDate21845])),',',
			       CONVERT(VARCHAR(10),DATEPART(HOUR, l.[FldDate21845])+2),
			       '))'
			       ) AS HORA_LEAD
				  ,DATEPART(HOUR, l.[FldDate21845]) AS HORA
                  ,COUNT(1) AS QT_LEAD
             FROM [tblObjectType20005_2] l WITH (NOLOCK)
            WHERE CONVERT(DATE, L.[FldDate21845]) = CONVERT(DATE, GETDATE())
         GROUP BY CONCAT('new Date ( Date.UTC (',CONVERT(VARCHAR(10),DATEPART(YEAR, l.[FldDate21845])),',', 
	              CONVERT(VARCHAR(10),DATEPART(MONTH, l.[FldDate21845])-1),',',
		      	  CONVERT(VARCHAR(10),DATEPART(DAY, l.[FldDate21845])),',',
		      	  CONVERT(VARCHAR(10),DATEPART(HOUR, l.[FldDate21845])+2),
			      '))'
			  )
			  ,DATEPART(HOUR, l.[FldDate21845])
		ORDER BY DATEPART(HOUR, l.[FldDate21845])";
   
   $result_2= sqlsrv_prepare($conn, $sql2);
   sqlsrv_execute($result_2);
   
         if (!($result_2)) {
                echo ("Falha na inclusão do registro");
                print_r(sqlsrv_errors());
         }   
   ;
$aux1 = 1;
$media = 0;
$soma = 0;
while($row2 = sqlsrv_fetch_array($result_2))
{
$aux1 ++;
$point_2 = array("x" => $row2['HORA_LEAD'], "y" => $row2['QT_LEAD']);
array_push($data_points_2, $point_2);
$media = $media + $row2['QT_LEAD'];
$soma = $soma + $row2['QT_LEAD'];
}

$Data_hora = json_encode($data_points_2, JSON_NUMERIC_CHECK);   // gabiara hehehe

$Data_hora = str_replace('"new', 'new', $Data_hora);
$Data_hora = str_replace('))"', '))', $Data_hora);
$media = $media/$aux1;
$media = round($media,0);
//print_r($media);
// exit;



// refresh automático pág

echo "<meta HTTP-EQUIV='refresh' CONTENT='900; URL=..\BI\Distribuicao_leads_dia.php'>";


?>

<!DOCTYPE HTML>
<html>
 <link href="..\BI\Distribuicao_leads_dia.css" rel="stylesheet" />
<head>
<meta charset="utf-8" />
	<title>Table Style</title>
	<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width;">

<script>
window.onload = function () {

var json2 = <?php echo $Data_hora ; ?>;
var media2 = <?php echo $media ; ?>;
var soma2 = <?php echo $soma ; ?>;
var chart2 = new CanvasJS.Chart("chartContainer2", {
	animationEnabled: true,  
	title:{
		text: "Criados Hoje ("+soma2+" Leads)"
	},
	axisY: {
		title: " ",
		suffix: "un ",
		stripLines: [{
			value: media2,
			label: "Média do Dia:"+media2
		}]
	},
	axisX:{
        title: "Horas",
        interval:1, 
        intervalType: "hour",        
        valueFormatString: "hh TT", 
        labelAngle: -25
      },
	data: [{
		type: "spline",
		dataPoints: json2
	}]
});
chart2.render();


}
</script>

</head>
     <body>
      <table class="table-fill" >
      <thead>
      <tr>
      <th class="text-left">Região</th>
      <th class="text-left">Leads</th>
      </tr>
      </thead>
      <tbody class="table-hover">
      	<?php 
      	while($row = sqlsrv_fetch_array($result)){
        ?>
      <tr>
      <td class="text-left"><?php echo $row['TEAM'] ?></td>
      <td class="text-left"><?php echo $row['QT_LEAD'] ?></td>
      </tr>
       <?php }
       ?>
      </tbody>
      </table>
  



<div id="chartContainer2" style="height: <?php echo $tamanho ?>px; width: 50%; float:right"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>