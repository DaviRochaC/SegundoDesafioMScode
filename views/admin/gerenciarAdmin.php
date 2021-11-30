<?php
session_start();
require_once('../../vendor/autoload.php');

use App\Models\Administrador;
use App\Models\Services\Auth\Middleware;


Middleware::verificaAdminLogado();   // Verifica se usuario administrador está logado.
Middleware::verificaAdminMaster('/views/admin/dashboard.php'); // Verifica se o administrador é do tipo master.

$adminModel = new Administrador();  // Instância da classe Administrador para utilização de seus metódos.
$admins = $adminModel->busca('admin_master','0', false); //buscando todos os administradores que no indice 'admin_master' recebem "0" que representa falso,
                                                          // ou seja, todos os administradores que nao são do tipo master.

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gerenciar Clientes</title>
    <link rel="icon" type="image/png" href="http://localhost/mscode/challengetwo/views/admin/assets/img/graphic.png">
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
    <!--/. NAV BAR -->
    <?php include('components/navbar.php'); ?>  <!-- incluindo o nav-bar -->
    <!--/. NAV TOP  -->
    <?php include('components/menu.php'); ?> <!-- incluindo o menu -->
    <!-- /. NAV SIDE  -->
    <div id="page-wrapper">
        <div class="header">
            <h3 class="page-header">
                Gerenciar Administradores
            </h3>

        </div>

        <div id="page-inner">

            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <?php include('components/alerts.php') ?>  <!-- incluindo o arquivo alerts.php para mostrar possiveis mensagens armazenadas em sessões -->
                    <div class="card">
                        <div class="card-content">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>E-mail</th>
                                            <th>CPF</th>
                                            <th>status</th>
                                            <th>Criado em</th>
                                            <th>Ações</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($admins as $admin) { ?>
                                            <tr class="odd gradeX">
                                                <td><?= $admin['nome'] ?></td>
                                                <td><?= $admin['email'] ?></td>
                                                <td><?= Administrador::formataCpfeCnpj($admin['cpf']) ?></td>
                                                <td><?= (boolval($admin['ativo'])) ? 'ativo' : 'desativado' ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($admin['criado_em'])) ?></td>
                                                <?php if (boolval($admin['ativo'])) { ?>
                                                    <td> <a class="btn btn-danger" href="http://localhost/mscode/challengetwo/app/actions/admin/desativar.php?i='.<?= base64_encode($admin['id']) ?>">Desativar</a>
                                                    </td>
                                                <?php } else { ?>
                                                    <td> <a class="btn btn-success" href="http://localhost/mscode/challengetwo/app/actions/admin/ativar.php?i='.<?= base64_encode($admin['id']) ?>">Ativar</a>
                                                    </td>
                                                <?php } ?>
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
    <!-- DATA TABLE SCRIPTS -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTables-example').dataTable({
                "language": {
                    url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json'
                },
            });

        });
    </script>
    <!-- Custom Js -->
    <script src="assets/js/custom-scripts.js"></script>


</body>

</html>