<?php

session_start();


require_once('../../vendor/autoload.php');

use App\Models\Orcamento;
use App\Models\Services\Auth\Middleware;

Middleware::verificaAdminLogado();

$orcamentoModel = new Orcamento();
$orcamentosFaturados = $orcamentoModel->busca('status_orcamento_id', 5, false);
$orcamentosSemResposta = $orcamentoModel->busca('status_orcamento_id', 1, false);
$orcamentosCancelados = $orcamentoModel->busca('status_orcamento_id', 3, false);
$orcamentosRejeitados = $orcamentoModel->busca('status_orcamento_id', 4, false);

?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title> Dashboard</title>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="assets/materialize/css/materialize.min.css" media="screen,projection" />
	<!-- Bootstrap Styles-->
	<link href="assets/css/bootstrap.css" rel="stylesheet" />
	<!-- FontAwesome Styles-->
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
	<!-- Morris Chart Styles-->
	<link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
	<!-- Custom Styles-->
	<link href="assets/css/custom-styles.css" rel="stylesheet" />
	<!-- Google Fonts-->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
	<link rel="stylesheet" href="assets/js/Lightweight-Chart/cssCharts.css">
</head>

<body>
	<div id="wrapper">
		<!--/. NAV BAR -->
		<?php include('./components/navbar.php'); ?>
		<!--/. NAV TOP  -->
		<?php include('./components/menu.php'); ?>
		<!-- /. NAV SIDE  -->

		<div id="page-wrapper">
			<div class="header">
				<h1 class="page-header">
					Dashboard
				</h1>

			</div>
			<div id="page-inner">

				<!-- /. ROW  -->
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="cirStats">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-6">
									<div class="card-panel text-center">
										<h4> Orçamento faturados</h4>
										<span class="percent easypiechart"> TOTAL = <?= count($orcamentosFaturados) ?></span>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6">
									<div class="card-panel text-center">
										<h4>Orçamento sem resposta</h4>
										<span class="percent easypiechart"> TOTAL = <?= count($orcamentosSemResposta) ?></span>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-6">
									<div class="card-panel text-center">
										<h4>Orçamento cancelados</h4>
										<span class="percent easypiechart"> TOTAL = <?= count($orcamentosCancelados) ?></span>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6">
									<div class="card-panel text-center">
										<h4>Orçamento rejeitados</h4>
										<span class="percent easypiechart"> TOTAL = <?= count($orcamentosRejeitados) ?></span>
									</div>
								</div>
							</div>
						</div>



					</div>
				</div>
			</div>
			<!--/.row-->
			<div class="col-xs-12 col-sm-12 col-md-5">
				<div class="row">
					<div class="col-xs-12">
						<div class="card">
							<div class="card-image donutpad">
								<div id="morris-donut-chart"></div>
							</div>
							<div class="card-action">
								<b>Donut Chart Example</b>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--/.row-->
		</div>


		<div class="row">
			<div class="col-md-5">
				<div class="card">
					<div class="card-image">
						<div id="morris-line-chart"></div>
					</div>
					<div class="card-action">
						<b>Line Chart</b>
					</div>
				</div>

			</div>

			<div class="col-md-7">
				<div class="card">
					<div class="card-image">
						<div id="morris-bar-chart"></div>
					</div>
					<div class="card-action">
						<b> Bar Chart Example</b>
					</div>
				</div>
			</div>

		</div>
		<!-- /. ROW  -->





		<footer>
			<p>All right reserved. Template by: <a href="https://webthemez.com/admin-template/">WebThemez.com</a></p>


		</footer>
	</div>
	<!-- /. PAGE INNER  -->
	</div>
	<!-- /. PAGE WRAPPER  -->
	</div>
	<!-- /. WRAPPER  -->
	<!-- JS Scripts-->
	<!-- jQuery Js -->
	<script src="assets/js/jquery-1.10.2.js"></script>

	<!-- Bootstrap Js -->
	<script src="assets/js/bootstrap.min.js"></script>

	<script src="assets/materialize/js/materialize.min.js"></script>

	<!-- Metis Menu Js -->
	<script src="assets/js/jquery.metisMenu.js"></script>
	<!-- Morris Chart Js -->
	<script src="assets/js/morris/raphael-2.1.0.min.js"></script>
	<script src="assets/js/morris/morris.js"></script>


	<script src="assets/js/easypiechart.js"></script>
	<script src="assets/js/easypiechart-data.js"></script>

	<script src="assets/js/Lightweight-Chart/jquery.chart.js"></script>

	<!-- Custom Js -->
	<script src="assets/js/custom-scripts.js"></script>


</body>

</html>