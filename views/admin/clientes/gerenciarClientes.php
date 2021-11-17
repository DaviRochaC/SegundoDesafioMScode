<?php

session_start();
require_once('../../../vendor/autoload.php');

use App\Models\Cliente;

$clienteModel = new Cliente();

$clientes = $clienteModel->busca();

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gerenciar Clientes</title>

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
    <!--/. NAV BAR -->
    <?php include('../components/navbar.php'); ?>
    <!--/. NAV TOP  -->
    <?php include('../components/menu.php'); ?>
    <!-- /. NAV SIDE  -->
    <div id="page-wrapper">
        <div class="header">
            <h3 class="page-header">
                Gerenciar clientes
            </h3>

        </div>

        <div id="page-inner">

            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <?php include('../components/alerts.php') ?>
                    <div class="card">
                        <div class="card-content">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>E-mail</th>
                                            <th>CPF/CNPJ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($clientes as $cliente) { ?>
                                            <tr class="odd gradeX">
                                                <td><?= $cliente['nome'] ?></td>
                                                <td><?= $cliente['email'] ?></td>

                                                <?php $cpfOucnpj = $clienteModel->formataCpfeCnpj($cliente['cpf_cnpj']) ?>
                                                <td><?= $cpfOucnpj ?></td>
                                                <td><a class="btn btn-primary"href="http://localhost/mscode/challengetwo/views/admin/clientes/editar.php?i='.<?=base64_encode($cliente['id'])?>">Editar</a></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <!--End Advanced Tables -->
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
    <!-- /. WRAPPER  -->
    <!-- JS Scripts-->






    

        <!-- jQuery Js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
        <!-- DATA TABLE SCRIPTS -->
        <script src="../assets/js/dataTables/jquery.dataTables.js"></script>
        <script src="../assets/js/dataTables/dataTables.bootstrap.js"></script>
        <script>
            $(document).ready(function() {
                $('#dataTables-example').dataTable();
            });
        </script>
        <!-- Custom Js -->
        <script src="../assets/js/custom-scripts.js"></script>


</body>

</html>