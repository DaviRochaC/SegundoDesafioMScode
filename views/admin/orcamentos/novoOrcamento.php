<?php
session_start();

require_once('../../../vendor/autoload.php');

use App\Models\Services\Auth\Middleware;
use App\Models\Cliente;

session_start();

Middleware::verificaAdminLogado();

$clientes = ((new Cliente)->busca());



?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title> Novo orçamento</title>
    <link rel="icon" type="image/png" href="http://localhost/mscode/challengetwo/views/admin/assets/img/graphic.png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../assets/materialize/css/materialize.min.css" media="screen,projection" />
    <!-- Bootstrap Styles-->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <!-- Morris Chart Styles-->
    <link href="../assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="../assets/css/custom-styles.css" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="../assets/js/Lightweight-Chart/cssCharts.css">
</head>

<body>
    <div id="wrapper">
        <!--/. NAV BAR -->
        <?php include('../components/navbar.php'); ?>
        <!--/. NAV TOP  -->
        <?php include('../components/menu.php'); ?>

        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div class="header">
                <h3 class="page-header">
                    Novo Orçamento
                </h3>

            </div>

            <div id="page-inner">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <?php include('../components/alerts.php') ?>
                            <div class="card-content">


                                <form method="POST" action="../../../app/actions/admin/orcamentos/novoOrcamento.php" class="col s12" enctype="multipart/form-data">

                                    <div class="row">
                                        <div class="input-field col s6">
                                            <input name="titulo" type="text" class="validate">
                                            <label>Titulo do orçamento</label>
                                        </div>
                                        <div class="input-field col s6">
                                            <input name="valor" type="text" class="validate money">
                                            <label>Valor</label>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="input-field  col s12">
                                            <select class="form-control" name="clientes_id">
                                                <option disabled selected>Selecione o Cliente</option>
                                                <?php foreach ($clientes as $cliente) { ?>
                                                    <option value="<?= $cliente['id'] ?>"> <?= $cliente['nome']?></option>
                                                <?php } ?>
                                            </select>

                                        </div>


                                        <div class="col s12" style="padding-top: 20px;">
                                            <label>PDF</label>
                                            <input name="pdf" class="form-control text-dark" type="file"   accept="application/pdf">

                                        </div>
                                    </div>



                                    <div class="row">
                                        <button type="submit" class="btn btn-success btn-block">Cadastrar</button>
                                    </div>

                                </form>
                                <div class="clearBoth"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /. WRAPPER  -->
                <!-- JS Scripts-->
                <!-- jQuery Js -->
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                <!--script das mascaras -->
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

                <!-- Bootstrap Js -->
                <script src="../assets/js/bootstrap.min.js"></script>

                <script src="../assets/materialize/js/materialize.min.js"></script>

                <!-- Metis Menu Js -->
                <script src="../assets/js/jquery.metisMenu.js"></script>
                <!-- Morris Chart Js -->
                <script src="../assets/js/morris/raphael-2.1.0.min.js"></script>
                <script src="../assets/js/morris/morris.js"></script>


                <script src="../assets/js/easypiechart.js"></script>
                <script src="../assets/js/easypiechart-data.js"></script>

                <script src="../assets/js/Lightweight-Chart/jquery.chart.js"></script>

                <!-- Custom Js -->
                <script src="../assets/js/custom-scripts.js"></script>

                <script>
                    $(document).ready(function() {
                        $('.money').mask('000.000.000.000.000,00', {reverse: true});
                    });
                </script>