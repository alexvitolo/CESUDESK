
<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<title>Home :: Powered by Subrion 4.0</title>
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="generator" content="Subrion CMS - Open Source Content Management System">
		<meta name="robots" content="index">
		<meta name="robots" content="follow">
		<meta name="revisit-after" content="1 day">
		<base href="https://demos.subrion.org/agency/">

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<link rel="shortcut icon" href="//demos.subrion.org/agency/favicon.ico">

		
		

			
	
	<meta property="og:title" content="Home">
	<meta property="og:url" content="https://demos.subrion.org/agency/">
	<meta property="og:description" content="">



		
	<link rel="stylesheet" type="text/css" href="//demos.subrion.org/agency/templates/agency/css/iabootstrap.css?fm=1460448386">
	<link rel="stylesheet" type="text/css" href="//demos.subrion.org/agency/templates/agency/css/user-style.css?fm=1460448388">
	<link rel="stylesheet" type="text/css" href="//demos.subrion.org/agency/plugins/fancybox/js/jquery.fancybox.css?fm=1455512036">

		
	</head>
	<body class="page-index">
		<header class="header">
			<nav class="navbar navbar-default">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						 <a class="navbar-brand page-scroll" href="#page-top">
															Campanhas EAD
													</a>
					</div>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="navbar-collapse">
						
						
				<ul class="nav navbar-nav navbar-right">
			<li><a href="https://demos.subrion.org/agency/login/">HOME </a></li>
		</ul>




					</div>
				</div>
			</nav>
			
	<!--__b_-->
	<div id="block_header" class="box box--no-header ">

<!--__b_c_-->

		<div class="teaser">
	<h3>Resumo Campanha EAD</h1>
	<h1>test</h2>
</div>
	
<!--__e_c_-->

	</div>
<!--__e_-->


		</header>
		
		
		<!-- PORTFOLIO -->
					<div class="verytop">
				<div class="container">
	<!--__b_-->
	<div id="block_new_portfolio_entries" class="box portfolio">
		<h4 id="caption_new_portfolio_entries" class="box__caption">CAMPANHA CHART JS</h4>
			</div>
				<!-- SERVICE -->

													<script>
									window.onload = function () {

									var dataPoints1 = [];
									var dataPoints2 = [];

									var chart = new CanvasJS.Chart("chartContainer", {
										zoomEnabled: true,
										title: {
											text: "ALO"
										},
										axisX: {
											title: "chart updates every 3 secs"
										},
										axisY:{
											prefix: "$",
											includeZero: false
										}, 
										toolTip: {
											shared: true
										},
										legend: {
											cursor:"pointer",
											verticalAlign: "top",
											fontSize: 22,
											fontColor: "dimGrey",
											itemclick : toggleDataSeries
										},
										data: [{ 
											type: "line",
											xValueType: "dateTime",
											yValueFormatString: "$####.00",
											xValueFormatString: "hh:mm:ss TT",
											showInLegend: true,
											name: "Company A",
											dataPoints: dataPoints1
											},
											{				
												type: "line",
												xValueType: "dateTime",
												yValueFormatString: "$####.00",
												showInLegend: true,
												name: "Company B" ,
												dataPoints: dataPoints2
										}]
									});

									function toggleDataSeries(e) {
										if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
											e.dataSeries.visible = false;
										}
										else {
											e.dataSeries.visible = true;
										}
										chart.render();
									}

									var updateInterval = 3000;
									// initial value
									var yValue1 = 600; 
									var yValue2 = 605;

									var time = new Date;
									// starting at 9.30 am
									time.setHours(9);
									time.setMinutes(30);
									time.setSeconds(00);
									time.setMilliseconds(00);

									function updateChart(count) {
										count = count || 1;
										var deltaY1, deltaY2;
										for (var i = 0; i < count; i++) {
											time.setTime(time.getTime()+ updateInterval);
											deltaY1 = .5 + Math.random() *(-.5-.5);
											deltaY2 = .5 + Math.random() *(-.5-.5);

										// adding random value and rounding it to two digits. 
										yValue1 = Math.round((yValue1 + deltaY1)*100)/100;
										yValue2 = Math.round((yValue2 + deltaY2)*100)/100;

										// pushing the new values
										dataPoints1.push({
											x: time.getTime(),
											y: yValue1
										});
										dataPoints2.push({
											x: time.getTime(),
											y: yValue2
										});
										}

										// updating legend text with  updated with y Value 
										chart.options.data[0].legendText = " E-mails Abertos     " + yValue1;
										chart.options.data[1].legendText = " E-mails Enviados    " + yValue2; 
										chart.render();
									}
									// generates first set of dataPoints 
									updateChart(100);	
									setInterval(function(){updateChart()}, updateInterval);

									}
									</script>
									</head>
									<body>
									<div id="chartContainer" style="height: 300px; width: 100%;"></div>
									<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>





			    <div class="verytop1">
					<div class="container">
						
	<!--__b_-->

		
		<!-- FOOTER -->
		<div class="footer">
			<div class="container">
				<div class="row">
					<div class="col-md-4">
						<p>&copy; 2017 Powered by <a href="http://www.subrion.org" title="Open Source CMS">CRM</a></p>
					</div>
		

			<ul class="list-inline quicklinks ">
				
			
			<!-- MORE menu dropdown -->
					</ul>
	



					</div>
				</div>
			</div>
		</div>
		<!-- SYSTEM STUFF -->
					<div style="display: none;">
				<img src="//demos.subrion.org/agency/cron/?324" width="1" height="1" alt="">
			</div>
		
		
		
		
	<script type="text/javascript" src="//demos.subrion.org/agency/js/jquery/jquery.js?fm=1455512036"></script>
	<script type="text/javascript" src="//demos.subrion.org/agency/js/intelli/intelli.js?fm=1455512036"></script>
	<script type="text/javascript" src="//demos.subrion.org/agency/tmp/cache/intelli.config.js?fm=1486982259"></script>
	<script type="text/javascript" src="//demos.subrion.org/agency/js/intelli/intelli.minmax.js?fm=1455512036"></script>
	<script type="text/javascript" src="//demos.subrion.org/agency/js/frontend/footer.js?fm=1455512036"></script>
	<script type="text/javascript" src="//demos.subrion.org/agency/tmp/cache/intelli.lang.en.js?fm=1486982259"></script>
	<script type="text/javascript" src="//demos.subrion.org/agency/js/bootstrap/js/bootstrap.min.js?fm=1455512036"></script>
	<script type="text/javascript" src="//demos.subrion.org/agency/plugins/fancybox/js/jquery.fancybox.pack.js?fm=1455512036"></script>
	<script type="text/javascript"><!-- 
$(function()
{
	$('a[rel^="ia_lightbox"]').fancybox(
	{
		nextEffect: 'elastic',
		prevEffect: 'elastic',
		openEffect: 'fade',
		closeEffect: 'fade',
		nextSpeed: 'fast',
		prevSpeed: 'fast',
		openSpeed: 'fast',
		closeSpeed: 'fast',
		padding: 15,
		arrows: 1,
		closeBtn: 1,
		closeClick: 0,
		helpers: {
			overlay: {
				locked: false
			}
		}
	});
});
 --></script>
	<script type="text/javascript"><!-- 
intelli.pageName = 'index';
 --></script>
	<script type="text/javascript" src="//demos.subrion.org/agency/templates/agency/js/app.js?fm=1460448458"></script>

		
	</body>
</html>