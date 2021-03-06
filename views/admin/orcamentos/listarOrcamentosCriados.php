<?php

ini_set('display_errors', true);
error_reporting(E_ALL);


session_start();
require_once('../../../vendor/autoload.php');

use App\Models\{Orcamento, Cliente, StatusOrcamento};

use App\Models\Services\Auth\Middleware;

Middleware::verificaAdminLogado();

$orcamentoModel = new Orcamento();
$clienteModel = new Cliente();
$statusOrcamentoModel = new StatusOrcamento();

$orcamentos = $orcamentoModel->busca('status_orcamento_id', 1, false);


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Orçamentos criados</title>
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
    <!--/. NAV BAR -->
    <?php include('../components/navbar.php'); ?>
    <!--/. NAV TOP  -->
    <?php include('../components/menu.php'); ?>
    <!-- /. NAV SIDE  -->
    <div id="page-wrapper">
        <div class="header">
            <h3 class="page-header">
                Orçamentos criados - não respondidos
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
                                            <th class="center">Titulo</th>
                                            <th class="center">Cliente</th>
                                            <th class="center">CPF/CNPJ</th>
                                            <th class="center">Valor</th>
                                            <th class="center">Data de Criação</th>
                                            <th class="center">PDF</th>
                                            <th class="center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($orcamentos) {
                                            foreach ($orcamentos as $orcamento) { ?>
                                                <tr class="odd gradeX">

                                                    <td class="center"><?= $orcamento['titulo'] ?></td>

                                                    <?php $cliente = $clienteModel->busca('id', $orcamento['clientes_id']); ?>

                                                    <td class="center"><?= $cliente['nome'] ?></td>
                                                    <td class="center"><?= Cliente::formataCpfeCnpj($cliente['cpf_cnpj']); ?></td>

                                                    <td class="center">R$<?= number_format($orcamento['valor_total'], 2, ',', '.') ?></td>
                                                    <?php $status = $statusOrcamentoModel->busca('id', $orcamento['status_orcamento_id']); ?>

                                                    <td class="center"><?= date('d/m/Y', strtotime($orcamento['criado_em'])) ?></td>
                                                    
                                                    <td><a class="btn btn-secondary btn-sm " href="<?= $orcamento['pdf_url'] ?>" target="_blank">Ver</a></td>
                                                    
                                                    <td class="center"><a class="btn btn-primary" href="../../../app/actions/admin/orcamentos/enviarEmail.php?token=<?= $orcamento['token'] ?>">Reenviar Email</a>
                                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#cancelar<?= $orcamento['id'] ?>">
                                                            Cancelar
                                                        </button>
                                                    </td>
                                                </tr>
                                        <?php }
                                        } ?>
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

    <?php foreach ($orcamentos as $orcamento) { ?>
        <!-- Modal -->
        <div class="modal fade" id="cancelar<?= $orcamento['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Informe o motivo do cancelamento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="../../../app/actions/admin/orcamentos/cancelarOrcamento.php?pag=1">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label">Motivo</label>
                                    <input class="form-control" type="text" name="motivo">

                                    <input type="hidden" name="token" value="<?= $orcamento['token'] ?>">
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Cancelar Orcamento</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>








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
            $('#dataTables-example').dataTable({
                "language": {
                    url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json'
                },
            });

        });
    </script>
    <!-- Custom Js -->
    <script src="../assets/js/custom-scripts.js"></script>


</body>

</html>