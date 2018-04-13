<?php include '..\BI\connectionTALSIMA.php';  

ini_set('max_execution_time', 90);


exec('ping -n 1 -w 1 172.16.0.83', $saida, $retorno);
  if (count($saida)) {
    $PING =(substr($saida[2],33,10));
  }


$query = "
SET NOCOUNT ON 
DECLARE @dbName varchar(30), @Status int

DECLARE @Tabela table (string varchar(150) null)

DECLARE cursor_DBOnlineOffline CURSOR FOR


SELECT name, status FROM master..SYSDATABASES

WHERE name NOT IN ('master', 'msdb', 'model', 'tempdb')

ORDER BY 2 DESC



OPEN cursor_DBOnlineOffline

FETCH NEXT FROM cursor_DBOnlineOffline

INTO @dbName, @Status



WHILE @@FETCH_STATUS = 0

BEGIN

         --Verifica se está Online ou Offline

         IF @Status = 66048

INSERT INTO @Tabela VALUES ('OFFLINE --> ' + @dbName)

         ELSE

INSERT INTO @Tabela VALUES ('ONLINE --> ' + @dbName)

        


         FETCH NEXT FROM cursor_DBOnlineOffline

         INTO @dbName, @Status

END


CLOSE cursor_DBOnlineOffline

DEALLOCATE cursor_DBOnlineOffline

SELECT string FROM @Tabela";

$result = sqlsrv_prepare($conn, $query);
sqlsrv_execute($result);




$query2= "
SET NOCOUNT ON 
DECLARE @dbName varchar(30), @Status int

DECLARE @Tabela table (string varchar(150) null)

DECLARE cursor_DBOnlineOffline CURSOR FOR


SELECT name, status FROM master..SYSDATABASES

WHERE name NOT IN ('master', 'msdb', 'model', 'tempdb')

ORDER BY 2 DESC



OPEN cursor_DBOnlineOffline

FETCH NEXT FROM cursor_DBOnlineOffline

INTO @dbName, @Status



WHILE @@FETCH_STATUS = 0

BEGIN

         --Verifica se está Online ou Offline

         IF @Status = 66048

INSERT INTO @Tabela VALUES ('OFFLINE --> ' + @dbName)

         ELSE

INSERT INTO @Tabela VALUES ('ONLINE --> ' + @dbName)

        


         FETCH NEXT FROM cursor_DBOnlineOffline

         INTO @dbName, @Status

END


CLOSE cursor_DBOnlineOffline

DEALLOCATE cursor_DBOnlineOffline

SELECT string FROM @Tabela";

$result2 = sqlsrv_prepare($conn2, $query2);
sqlsrv_execute($result2);



$BANCO23= "SELECT COUNT(1) as BLOCK FROM SYS.SYSPROCESSES WHERE BLOCKED <> 0 HAVING COUNT(1) > 5  ";

$resultBanco = sqlsrv_prepare($conn, $BANCO23);
sqlsrv_execute($resultBanco);

$StatusBanco = sqlsrv_fetch_array($resultBanco);



$SMS23= "SELECT COUNT(1) as SMS  FROM tblOutboundSMS HAVING COUNT(1) > 15000";

$resultSMS = sqlsrv_prepare($conn, $SMS23);
sqlsrv_execute($resultSMS);

$StatusSMS = sqlsrv_fetch_array($resultSMS);



$EMAIL23 = "SELECT COUNT(1) as EMAIL  FROM tblOutgoingOBMs HAVING COUNT(1) > 15000";

$resultEMAIL = sqlsrv_prepare($conn, $EMAIL23);
sqlsrv_execute($resultEMAIL);

$StatusEMAIL = sqlsrv_fetch_array($resultEMAIL);




//sql SMS POR HORA







//sql SMS POR HORA



$data_points_3 = array();

   $sql3 = " SELECT CONCAT('new Date ( Date.UTC (',CONVERT(VARCHAR(10),DATEPART(YEAR, dtStatusUpdatedDate)),',', 
                    CONVERT(VARCHAR(10),DATEPART(MONTH, dtStatusUpdatedDate)-1),',',
                    CONVERT(VARCHAR(10),DATEPART(DAY, dtStatusUpdatedDate)),',',
                    CONVERT(VARCHAR(10),DATEPART(HOUR, dtStatusUpdatedDate)+2),
                     '))'
                     ) AS HORA_LEAD
         ,CASE WHEN ((select top 1 count(1) as soma_SMS from tblSMSDetails SMS_ENVIADOS WITH (NOLOCK)
                         where (SMS_ENVIADOS.dtStatusUpdatedDate >= CONVERT(date, GETDATE()))
                      group by SUBSTRING(CONVERT(varchar,dtStatusUpdatedDate,114),1,2)
                      order by count(1) desc) = count(1)) THEN CONVERT(varchar(MAX),CONCAT(count(1),', indexLabel: @highest@,markerColor: @red@, markerType: @triangle@')) 
               WHEN ((select top 1 count(1) as soma_SMS from tblSMSDetails SMS_ENVIADOS WITH (NOLOCK)
                         where (SMS_ENVIADOS.dtStatusUpdatedDate >= CONVERT(date, GETDATE()))
                      group by SUBSTRING(CONVERT(varchar,dtStatusUpdatedDate,114),1,2)
                      order by count(1) asc) = count(1)) THEN CONVERT(varchar(MAX),CONCAT(count(1),', indexLabel: @lowest@,markerColor: @DarkSlateGrey@, markerType: @cross@')) 
                ELSE CONVERT(varchar,count(1)) 
              END AS soma_SMS
                    ,CONVERT(VARCHAR(10),DATEPART(HOUR, dtStatusUpdatedDate)) as HORA
 
                          from tblSMSDetails SMS_ENVIADOS WITH (NOLOCK)
                         where (SMS_ENVIADOS.dtStatusUpdatedDate >= CONVERT(date, GETDATE()))
                      group by CONVERT(VARCHAR(10),DATEPART(YEAR, dtStatusUpdatedDate)),
                               CONVERT(VARCHAR(10),DATEPART(MONTH, dtStatusUpdatedDate)-1),
                               CONVERT(VARCHAR(10),DATEPART(DAY, dtStatusUpdatedDate)),
                               CONVERT(VARCHAR(10),DATEPART(HOUR, dtStatusUpdatedDate)+2),
                               DATEPART(HOUR, dtStatusUpdatedDate)
                     order by DATEPART(HOUR, dtStatusUpdatedDate) asc
 ";
   
   $result_3 = sqlsrv_prepare($conn, $sql3);
   sqlsrv_execute($result_3);
   
         if (!($result_3)) {
                echo ("Falha na inclusão do registro");
                print_r(sqlsrv_errors());
         }   
   ;
$aux1 = 0;
$mediaSMS = 0;
$somaSMS = 0;

while($row3 = sqlsrv_fetch_array($result_3))
{
$aux1 ++;
$point_3 = array("x" => $row3['HORA_LEAD'], "y" => $row3['soma_SMS']);
array_push($data_points_3, $point_3);
$mediaSMS = $mediaSMS + $row3['soma_SMS'];
$somaSMS = $somaSMS + $row3['soma_SMS'];
}

$Data_SMS = json_encode($data_points_3, JSON_NUMERIC_CHECK);   // gabiara hehehe

$Data_SMS = str_replace('"new', 'new', $Data_SMS);
$Data_SMS = str_replace('y":"', 'y":', $Data_SMS);
$Data_SMS = str_replace('))"', '))', $Data_SMS); 
$Data_SMS = str_replace('@', '"', $Data_SMS);
$Data_SMS = str_replace('""}', '"}', $Data_SMS);
// print_r($Data_SMS);exit;
$mediaSMS = $mediaSMS/$aux1;
$mediaSMS = round($mediaSMS,0);
//print_r($media);
// exit;




  $data_points_4 = array();

   $sql4 = " SELECT CONCAT('new Date ( Date.UTC (',CONVERT(VARCHAR(10),DATEPART(YEAR, dtCreatedDate)),',', 
                    CONVERT(VARCHAR(10),DATEPART(MONTH, dtCreatedDate)-1),',',
                    CONVERT(VARCHAR(10),DATEPART(DAY, dtCreatedDate)),',',
                    CONVERT(VARCHAR(10),DATEPART(HOUR, dtCreatedDate)+2),
                     '))'
                     ) AS HORA_LEAD
         ,CASE WHEN ((select top 1 count(1) as soma_SMS from tblSMSDetails SMS_ENVIADOS WITH (NOLOCK)
                         where (SMS_ENVIADOS.dtCreatedDate >= CONVERT(date, GETDATE()))
                      group by SUBSTRING(CONVERT(varchar,dtCreatedDate,114),1,2)
                      order by count(1) desc) = count(1)) THEN CONVERT(varchar(MAX),CONCAT(count(1),', indexLabel: @highest@,markerColor: @red@, markerType: @triangle@')) 
               WHEN ((select top 1 count(1) as soma_SMS from tblSMSDetails SMS_ENVIADOS WITH (NOLOCK)
                         where (SMS_ENVIADOS.dtCreatedDate >= CONVERT(date, GETDATE()))
                      group by SUBSTRING(CONVERT(varchar,dtCreatedDate,114),1,2)
                      order by count(1) asc) = count(1)) THEN CONVERT(varchar(MAX),CONCAT(count(1),', indexLabel: @lowest@,markerColor: @DarkSlateGrey@, markerType: @cross@')) 
                ELSE CONVERT(varchar,count(1)) 
              END AS soma_SMS
                    ,CONVERT(VARCHAR(10),DATEPART(HOUR, dtCreatedDate)) as HORA
 
                          from tblSMSDetails SMS_ENVIADOS WITH (NOLOCK)
                         where (SMS_ENVIADOS.dtCreatedDate >= CONVERT(date, GETDATE()))
                      group by CONVERT(VARCHAR(10),DATEPART(YEAR, dtCreatedDate)),
                               CONVERT(VARCHAR(10),DATEPART(MONTH, dtCreatedDate)-1),
                               CONVERT(VARCHAR(10),DATEPART(DAY, dtCreatedDate)),
                               CONVERT(VARCHAR(10),DATEPART(HOUR, dtCreatedDate)+2),
                               DATEPART(HOUR, dtCreatedDate)
                     order by DATEPART(HOUR, dtCreatedDate) asc
 ";
   
   $result_4 = sqlsrv_prepare($conn, $sql4);
   sqlsrv_execute($result_4);
   
         if (!($result_4)) {
                echo ("Falha na inclusão do registro");
                print_r(sqlsrv_errors());
         }   
   ;
$aux2 = 0;
$mediaSMS2 = 0;
$somaSMS2 = 0;

while($row4 = sqlsrv_fetch_array($result_4))
{
$aux2 ++;
$point_4 = array("x" => $row4['HORA_LEAD'], "y" => $row4['soma_SMS']);
array_push($data_points_4, $point_4);
$mediaSMS2 = $mediaSMS2 + $row4['soma_SMS'];
$somaSMS2 = $somaSMS2 + $row4['soma_SMS'];
}

$Data_SMS2 = json_encode($data_points_4, JSON_NUMERIC_CHECK);   // gabiara hehehe

$Data_SMS2 = str_replace('"new', 'new', $Data_SMS2);
$Data_SMS2 = str_replace('y":"', 'y":', $Data_SMS2);
$Data_SMS2 = str_replace('))"', '))', $Data_SMS2); 
$Data_SMS2 = str_replace('@', '"', $Data_SMS2);
$Data_SMS2 = str_replace('""}', '"}', $Data_SMS2);
// print_r($Data_SMS2);exit;
$mediaSMS2 = $mediaSMS2/$aux2;
$mediaSMS2 = round($mediaSMS2,0);








?>


<!doctype html>
<html><head>
    <meta charset="utf-8">
    <title>SMART - CRM Dashboard Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
 

    <!-- Le styles -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">
    <link href="assets/css/font-style.css" rel="stylesheet">
    <link href="assets/css/flexslider.css" rel="stylesheet">
    
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>

    <style type="text/css">
      body {
        padding-top: 60px;
      }
    </style>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">

  	<!-- Google Fonts call. Font Used Open Sans & Raleway -->
	<link href="http://fonts.googleapis.com/css?family=Raleway:400,300" rel="stylesheet" type="text/css">
  	<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">

<script type="text/javascript">
$(document).ready(function () {

    $("#btn-blog-next").click(function () {
      $('#blogCarousel').carousel('next')
    });
     $("#btn-blog-prev").click(function () {
      $('#blogCarousel').carousel('prev')
    });

     $("#btn-client-next").click(function () {
      $('#clientCarousel').carousel('next')
    });
     $("#btn-client-prev").click(function () {
      $('#clientCarousel').carousel('prev')
    });
    
});

 $(window).load(function(){

    $('.flexslider').flexslider({
        animation: "slide",
        slideshow: true,
        start: function(slider){
          $('body').removeClass('loading');
        }
    });  
});

</script>


    
  </head>
  <body>
  
  	<!-- NAVIGATION MENU -->

    <div class="navbar-nav navbar-inverse navbar-fixed-top">
        <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.html"><img src="assets/img/logo30.png" alt=""> SMART Dashboard</a>
        </div> 
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href=""><i class="icon-home icon-white"></i> CRM STATUS</a></li>

            </ul>
          </div><!--/.nav-collapse -->
        </div>
    </div>

    <div class="container">

	  <!-- FIRST ROW OF BLOCKS -->     
      <div class="row">

      <!-- USER PROFILE BLOCK -->
        <div class="col-sm-3 col-lg-3">
      		<div class="dash-unit">
	      		<dtitle>User Profile</dtitle>
	      		<hr>
				<div class="thumbnail">
					<img src="assets/img/face80x80.png" alt="Marcel Newman" class="img-circle">
				</div><!-- /thumbnail -->
				<h1>CRM</h1>
				<h3>UniCesumar, Maringá</h3>
				<br>
					<div class="info-user">
						<span aria-hidden="true" class="li_user fs1"></span>
						<span aria-hidden="true" class="li_settings fs1"></span>
						<span aria-hidden="true" class="li_mail fs1"></span>
						<span aria-hidden="true" class="li_key fs1"></span>
					</div>
				</div>
        </div>

      <!-- DONUT CHART BLOCK -->
        <div class="col-sm-3 col-lg-3">
      		<div class="dash-unit">
		  		<dtitle>Site Bandwidth</dtitle>
		  		<hr>
	        	<div id="load"></div>
	        	<h2>45%</h2>
			</div>
        </div>

      <!-- DONUT CHART BLOCK -->
        <div class="col-sm-3 col-lg-3">
      		<div class="dash-unit">
		  		<dtitle>Disk Space</dtitle>
		  		<hr>
	        	<div id="space"></div>
	        	<h2>65%</h2>
			</div>
        </div>
        
        <div class="col-sm-3 col-lg-3">

      <!-- LOCAL TIME BLOCK -->
      		<div class="half-unit">
	      		<dtitle>Local Time</dtitle>
	      		<hr>
		      		<div class="clockcenter">
			      		<digiclock>12:45:25</digiclock>
		      		</div>
			</div>

      <!-- SERVER UPTIME -->
			<div class="half-unit">
	      		<dtitle>Server Uptime</dtitle>
	      		<hr>
	      		<div class="cont">
					<p><img src="assets/img/up.png" alt=""> <bold>Up</bold> | <?php echo $PING; ?></p>
				</div>
			</div>

        </div>
      </div><!-- /row -->
      
      
	  <!-- SECOND ROW OF BLOCKS -->     
      <div class="row">
        <div class="col-sm-3 col-lg-3">
       <!-- MAIL BLOCK -->
      		<div class="dash-unit">
	      		<dtitle>Banco Talisma</dtitle>
	      		<hr>
				<div class="info-user">
					<span aria-hidden="true" class="li_news fs2"></span>
				</div>
				<br>
      			<div class="text">
      				<p>
      				<?php while($row2 = sqlsrv_fetch_array($result)) { 
      				           echo($row2['string']);	
      				           ?> <br> <?php 
      			          } 
      				?>
      				</p>

      			</div>
		</div><!-- /dash-unit -->
    </div><!-- /span3 -->

	  <!-- GRAPH CHART - lineandbars.js file -->     
        <div class="col-sm-3 col-lg-3">
      		<div class="dash-unit">
      		<dtitle>Other Information</dtitle>
      		<hr>
			        <div id="chartContainer" style="height: 220px; width: 100%;"></div>
					<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
			</div>
        </div>

	  <!-- LAST MONTH REVENUE -->     
        <div class="col-sm-3 col-lg-3">
      		<div class="dash-unit">
	      		<dtitle>Resumo Status Servidores</dtitle>
	      		<hr>
	      		<div class="cont">
					<p><bold>Banco SQL23</bold> | <?php if($StatusBanco['BLOCK']==''){?> <ok>Approved</ok> <?php ;}else { ?> <bad>Denied</bad> <?php }; ?> </p>
					<br>
					<p><bold>SMS</bold> |  <?php if($StatusSMS['SMS']==''){?> <ok>Approved</ok> <?php ;}else { ?> <bad>Denied</bad> <?php }; ?> </p>
					<br>
					<p><bold>E-Mail</bold> | <?php if($StatusEMAIL['EMAIL']==''){?> <ok>Approved</ok> <?php ;}else { ?> <bad>Denied</bad> <?php }; ?> </p>
					<br>
          <p><bold>Chat - TEST</bold> | <bad>Denied</bad></p>
          <br>

				</div>

			</div>
        </div>
        
	  <!-- 30 DAYS STATS - CAROUSEL FLEXSLIDER -->     
        <div class="col-sm-3 col-lg-3">
      		<div class="dash-unit">
	      		<dtitle>Last 30 Days Stats</dtitle>
	      		<hr>
	      		<br>
	      		<br>
	            <div class="flexslider">
					<ul class="slides">
						<li><img src="assets/img/slide01.png" alt="slider"></li>
						<li><img src="assets/img/slide02.png" alt="slider"></li>
					</ul>
            </div>
				<div class="cont">
					<p>StatCounter Information</p>
				</div>   
			</div>
        </div>
      </div><!-- /row -->
     
 
	  <!-- THIRD ROW OF BLOCKS -->     
      <div class="row">
      	<!-- LATEST NEWS BLOCK -->     
      	<div class="col-sm-3 col-lg-3">
      		<div class="dash-unit">
	      		<dtitle>Banco Cesudesk</dtitle>
	      		<hr>
				<div class="info-user">
					<span aria-hidden="true" class="li_news fs2"></span>
				</div>
				<br>
      			<div class="text">
      				<p>
      				<?php while($row2 = sqlsrv_fetch_array($result2)) { 
      				           echo($row2['string']);	
      				           ?> <br> <?php 
      			          } 
      				?>
      				</p>
      			</div>
      		</div>
      	</div>

      	<div class="col-sm-3 col-lg-3">

	  <!-- LIVE VISITORS BLOCK -->     
      		<div class="half-unit">
            <dtitle>SMS</dtitle>
            <hr>
            <div class="cont">
            <p><bold>14.744</bold></p>
            <p>Total SMS Mês</p>
            </div>
          </div>
      		
	  <!-- PAGE VIEWS BLOCK -->     
      		<div class="half-unit">
	      		<dtitle>Page Views</dtitle>
	      		<hr>
	      		<div class="cont">
      			<p><bold>145.0K</bold></p>
      			<p><img src="assets/img/up-small.png" alt=""> 23.88%</p>
	      		</div>
      		</div>
      	</div>

      	<div class="col-sm-3 col-lg-3">
	  <!-- TOTAL SUBSCRIBERS BLOCK -->     
      		<div class="half-unit">
	      		<dtitle>EMAIL</dtitle>
	      		<hr>
	      		<div class="cont">
      			<p><bold>14.744</bold></p>
      			<p>Total EMAIL Mês</p>
	      		</div>
      		</div>
      		
	  <!-- FOLLOWERS BLOCK -->     
      		<div class="half-unit">
	      		<dtitle>Twitter Followers</dtitle>
	      		<hr>
	      		<div class="cont">
      			<p><bold>17.833 Followers</bold></p>
      			<p>@SomeUser</p>
	      		</div>
      		</div>
      	</div>

      </div><!-- /row -->
      
	  <!-- FOURTH ROW OF BLOCKS -->     
	<div class="row">
	   <div id="chartContainer3" style="height: 300px; width: 100%; margin-top: 10px"></div>
     <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
     <br>
     <br>
	</div><!--/row -->     
      
      
	</div> <!-- /container -->
	<div id="footerwrap">
      	<footer class="clearfix"></footer>
      	<div class="container">
      		<div class="row">
      			<div class="col-sm-12 col-lg-12">
      			<p><img src="assets/img/logo.png" alt=""></p>
      			<p>Smart Dashboard CRM - STATUS CRM - Copyright 2018</p>
      			</div>

      		</div><!-- /row -->
      	</div><!-- /container -->
	</div><!-- /footerwrap -->


    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript" src="assets/js/bootstrap.js"></script>
	<script type="text/javascript" src="assets/js/lineandbars.js"></script>
    
	<script type="text/javascript" src="assets/js/gauge.js"></script>
	
	<!-- NOTY JAVASCRIPT -->
	<script type="text/javascript" src="assets/js/noty/jquery.noty.js"></script>
	<script type="text/javascript" src="assets/js/noty/layouts/top.js"></script>
	<script type="text/javascript" src="assets/js/noty/layouts/topLeft.js"></script>
	<script type="text/javascript" src="assets/js/noty/layouts/topRight.js"></script>
	<script type="text/javascript" src="assets/js/noty/layouts/topCenter.js"></script>
	
	<!-- You can add more layouts if you want -->
	<script type="text/javascript" src="assets/js/noty/themes/default.js"></script>
    <!-- <script type="text/javascript" src="assets/js/dash-noty.js"></script> This is a Noty bubble when you init the theme-->
	<script type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>
	<script src="assets/js/jquery.flexslider.js" type="text/javascript"></script>

    <script type="text/javascript" src="assets/js/admin.js"></script>

  
</body></html>

<script>
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
  backgroundColor: "#3d3d3d",
	theme: "light2",
	title:{
		text: "Status Envio SMS",
    fontColor: "white"
	},
	axisY:{
		includeZero: false
	},
	data: [{        
		type: "line",       
		dataPoints: [
			{ y: 450 },
			{ y: 414},
			{ y: 520, indexLabel: "highest",markerColor: "red", markerType: "triangle" },
			{ y: 460 },
			{ y: 450 },
			{ y: 500 },
			{ y: 480 },
			{ y: 480 },
			{ y: 410 , indexLabel: "lowest",markerColor: "DarkSlateGrey", markerType: "cross" },
			{ y: 500 },
			{ y: 480 },
			{ y: 510 }
		]
	}]
});
chart.render();








var jsonSMS  = <?php echo $Data_SMS ; ?>;
var jsonSMS2 = <?php echo $Data_SMS2 ; ?>;
var mediaSMS = <?php echo $mediaSMS ; ?>;
var somaSMS  = <?php echo $somaSMS ; ?>;
var somaSMS2 = <?php echo $somaSMS2 ; ?>;
var chart3   = new CanvasJS.Chart("chartContainer3", {
  animationEnabled: true,  
  backgroundColor: "#3d3d3d",
  title:{
    text: "SMS por HORA",
    fontColor: "white"
  },
  axisY: {
    title: "Quantidade Enviada",
    suffix: "Un",
    stripLines: [{
      value: 20,
      label: "Média do Dia: "+mediaSMS
    }]
  },
  toolTip: {
    shared: true
  },
  legend: {
    cursor: "pointer",
    verticalAlign: "top",
    horizontalAlign: "center",
    dockInsidePlotArea: true,
    itemclick: toogleDataSeries,
    fontColor: "white"
  },
  axisX:{
        title: "Horas",
        interval:1, 
        intervalType: "hour",        
        valueFormatString: "hh TT", 
        labelAngle: -75
      },
  data: [{
    type:"line",
    axisYType: "secondary",
    name: "DATA UPDATE: " +somaSMS,
    showInLegend: true,
    type: "spline",
    dataPoints: jsonSMS
  },
  {
    type:"line",
    axisYType: "secondary",
    name: "DATA CRIAÇÃO: " +somaSMS2,
    showInLegend: true,
    type: "spline",
    dataPoints: jsonSMS2
  }]
});
chart3.render();

function toogleDataSeries(e){
  if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
    e.dataSeries.visible = false;
  } else{
    e.dataSeries.visible = true;
  }
  chart3.render();
}


}




/*** First Chart in Dashboard page ***/

  $(document).ready(function() {
    info = new Highcharts.Chart({
      chart: {
        renderTo: 'load',
        margin: [0, 0, 0, 0],
        backgroundColor: null,
                plotBackgroundColor: 'none',
              
      },
      
      title: {
        text: null
      },

      tooltip: {
        formatter: function() { 
          return this.point.name +': '+ this.y +' %';
            
        }   
      },
        series: [
        {
        borderWidth: 2,
        borderColor: '#F1F3EB',
        shadow: false,  
        type: 'pie',
        name: 'Income',
        innerSize: '65%',
        data: [
          { name: 'load percentage', y: 33.0, color: '#b2c831' },
          { name: 'rest', y: 55.0, color: '#3d3d3d' }
        ],
        dataLabels: {
          enabled: false,
          color: '#000000',
          connectorColor: '#000000'
        }
      }]
    });
    
  });

/*** second Chart in Dashboard page ***/

  $(document).ready(function() {
    info = new Highcharts.Chart({
      chart: {
        renderTo: 'space',
        margin: [0, 0, 0, 0],
        backgroundColor: null,
                plotBackgroundColor: 'none',
              
      },
      
      title: {
        text: null
      },

      tooltip: {
        formatter: function() { 
          return this.point.name +': '+ this.y +' %';
            
        }   
      },
        series: [
        {
        borderWidth: 2,
        borderColor: '#F1F3EB',
        shadow: false,  
        type: 'pie',
        name: 'SiteInfo',
        innerSize: '65%',
        data: [
          { name: 'Used', y: 85.0, color: '#fa1d2d' },
          { name: 'Rest', y: 15.0, color: '#3d3d3d' }
        ],
        dataLabels: {
          enabled: false,
          color: '#000000',
          connectorColor: '#000000'
        }
      }]
    });
    
  });




</script>