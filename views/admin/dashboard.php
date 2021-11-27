<?php

session_start();


require_once('../../vendor/autoload.php');

use App\Models\Orcamento;
use App\Models\Services\Auth\Middleware;

Middleware::verificaAdminLogado();

$orcamentoModel = new Orcamento();

$orcamentosTotal = count($orcamentoModel->busca());
$orcamentosAceitos = count($orcamentoModel->busca('status_orcamento_id', 2, false));
$orcamentosFaturados = count($orcamentoModel->busca('status_orcamento_id', 5, false));
$orcamentosSemResposta = count($orcamentoModel->busca('status_orcamento_id', 1, false));
$orcamentosCancelados = count($orcamentoModel->busca('status_orcamento_id', 3, false));
$orcamentosRejeitados = count($orcamentoModel->busca('status_orcamento_id', 4, false));
$orcamentosRespondidos = $orcamentosTotal - $orcamentosSemResposta;

$porcentagemOrçamentosFaturados = round(($orcamentosFaturados / $orcamentosTotal) * 100);
$porcentagemOrçamentosSemResposta = round(($orcamentosSemResposta / $orcamentosTotal) * 100);
$porcentagemOrçamentosCancelados = round(($orcamentosCancelados / $orcamentosTotal) * 100);
$porcentagemOrçamentosRejeitados = round(($orcamentosRejeitados / $orcamentosTotal) * 100);
$porcentagemOrçamentosAceitos = round(($orcamentosAceitos / $orcamentosTotal) * 100);

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


				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="cirStats">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-6">
									<div class="card-panel text-center">
										<h4>Orçamentos Faturados</h4><br>
										<h4>Total = <?= $orcamentosFaturados ?> </h4>

										<div class="easypiechart" id="easypiechart-blue" data-percent="<?= $porcentagemOrçamentosFaturados ?>"><span class="percent">%<?= $porcentagemOrçamentosFaturados ?></span>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6">
									<div class="card-panel text-center">
										<h4>Orçamentos Sem resposta</h4><br>
										<h4>Total = <?= $orcamentosSemResposta ?> </h4>

										<div class="easypiechart" id="easypiechart-red" data-percent="<?= $porcentagemOrçamentosSemResposta ?>"><span class="percent">%<?= $porcentagemOrçamentosSemResposta ?></span>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-6">
									<div class="card-panel text-center">
										<h4>Orçamentos Cancelados</h4><br>
										<h4>Total = <?= $orcamentosCancelados ?></h4>
										<div class="easypiechart" id="easypiechart-teal" data-percent="<?= $porcentagemOrçamentosCancelados ?>"><span class="percent">%<?= $porcentagemOrçamentosCancelados ?></span>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6">
									<div class="card-panel text-center">
										<h4>Orçamentos Rejeitados</h4><br>
										<h4>Total = <?= $orcamentosRejeitados ?> </h4>
										<div class="easypiechart" id="easypiechart-orange" data-percent="<?= $porcentagemOrçamentosRejeitados ?>"><span class="percent">%<?= $porcentagemOrçamentosRejeitados ?></span>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
					<!--/.row-->
				</div>


				<div class="row">
					<div class="col-lg-6 col-sm-12">
						<div class="card">

							<div class="card-image">
								<canvas class="grafico1">
								</canvas>
							</div>
							<div class="card-action">
								<b> Orçamentos Aceitos X Orçamentos Rejeitados</b>
							</div>

						</div>
					</div>
					<div class="col-lg-6 col-sm-12">
						<div class="card">

							<div class="card-image">
								<canvas class="grafico2">
								</canvas>
							</div>
							<div class="card-action">
								<b> Orçamentos Enviados X Orçamentos Respondidos</b>
							</div>

						</div>
					</div>
				</div>
				<!-- /. ROW  -->






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


	<!-- Chart Js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.0/chart.min.js" integrity="sha512-GMGzUEevhWh8Tc/njS0bDpwgxdCJLQBWG3Z2Ct+JGOpVnEmjvNx6ts4v6A2XJf1HOrtOsfhv3hBKpK9kE5z8AQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


	<script>
		var ctx = document.getElementsByClassName("grafico1");


		var chartGraph = new Chart(ctx, {
			type: 'doughnut',
			data: {
				labels: ['Orcamentos aceitos', 'Orcamentos Rejeitados'],
				datasets: [{
					label: "Orcamentos",
					data: [<?= $orcamentosAceitos + $orcamentosFaturados ?>, <?= $orcamentosRejeitados ?>],
					backgroundColor: [
						'rgb(34,139,34)',
						'rgb(255,5,5)',
					],
					hoverOffset: 4
				}]
			},
			options: {
				maintainAspectRatio: false,
			}

		});
	</script>

	<script>
		var ctx = document.getElementsByClassName("grafico2");


		var chartGraph = new Chart(ctx, {
			type: 'doughnut',
			data: {
				labels: ['Orcamentos Respondidos', 'Orcamentos não Respondidos'],
				datasets: [{
					label: "Orcamentos",
					data: [<?= $orcamentosRespondidos ?>, <?= $orcamentosSemResposta ?>],
					backgroundColor: [
						'rgb(255,255,0)',
						'rgb(54, 162, 235)',
					],
					hoverOffset: 4
				}]
			},
			options: {
				maintainAspectRatio: false,
			}

		});
	</script>


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