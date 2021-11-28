<?php

ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require('../../../vendor/autoload.php');

use App\Models\{Orcamento, Cliente};
use App\Models\Services\Auth\Middleware;

Middleware::verificaCampos($_GET, array('token'), '/views/admin/403.php');

if ((isset($_SESSION['orcamento_aceito']) and $_SESSION['orcamento_aceito'] == true) or (isset($_SESSION['orcamento_recusado']) and $_SESSION['orcamento_recusado'] == true)) {
    Middleware::redirecionar('/views/admin/403.php');
}

$orcamento = ((new Orcamento())->busca('token', $_GET['token']));
$cliente = ((new Cliente())->busca('id', $orcamento['clientes_id']));

if (!$orcamento or !$cliente) {
    Middleware::redirecionar('/views/admin/403.php');
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title> Avaliação orçamento </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="" rel="stylesheet" />
    <script type="text/javascript" src=""></script>

</head>

<body oncontextmenu="return false" class="snippet-body">
    <div class="container mt-5 mb-5">
        <div class="d-flex flex row g-0">
            <div class="offset-2 col-md-6 mt-3">
                <div class="card card1 p-3" style="height:475px; width: 700px;">
                    <div class="d-flex flex-column">
                        <h2 class="text-center text-white"> Avaliação do Orcamento</h2>
                    </div>

                    <?php include('../components/alerts.php') ?>
                    <div class="row pt-3">
                        <div class="col-12">
                            <li class="list-group-item">
                                <strong>Título: </strong><?= $orcamento['titulo'] ?>
                            </li>
                        </div>


                    </div>

                    <div class="row pt-3">
                        <div class="col-12">
                            <li class="list-group-item">
                                <strong>Cliente: </strong><?= $cliente['nome'] ?>

                            </li>
                        </div>
                    </div>

                    <div class="row pt-3">
                        <div class="col-12">
                            <li class="list-group-item">

                                <strong>Valor:</strong> R$ <?= number_format($orcamento['valor_total'], 2, ',', '.') ?>

                            </li>
                        </div>
                    </div>

                    <div class="row pt-3">
                        <div class="col-12">
                            <li class="list-group-item">
                                <strong>PDF: </strong>
                                <a class="btn btn-secondary" href="<?= $orcamento['pdf_url'] ?>" target="_blank">Visualizar PDF</a>
                            </li>
                        </div>
                    </div>

                    <div class="row pt-3">

                        <div class="col-6">
                            <a class="btn btn-success btn-block" href="../../../app/actions/admin/orcamentos/avaliacaodoOrcamento.php?status=<?= base64_encode(2) ?>&token=<?= $_GET['token'] ?>">Aceitar</a>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-danger btn-block" data-bs-toggle="modal" data-bs-target="#motivo">
                                Rejeitar
                            </button>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>






    <!-- Modal para infomar o motivo da rejeição-->
    <div class="modal fade" id="motivo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Por favor nos informe o motivo:</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" method="POST" action="../../../app/actions/admin/orcamentos/avaliacaodoOrcamento.php?status=<?= base64_encode(4) ?>&token=<?= $_GET['token'] ?>">
                        <div class="col-md-12">
                            <label class="form-label">Motivo</label>
                            <input type="text" name="motivo" class="form-control">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger btn-block">Rejeitar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!--script das mascaras -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>


</body>


</html>