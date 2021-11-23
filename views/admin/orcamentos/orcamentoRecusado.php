<?php

ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

require('../../../vendor/autoload.php');

use App\Models\{Orcamento, Cliente};
use App\Models\Services\Auth\Middleware;

$_SESSION['orcamento_recusado'] = true;

Middleware::verificaCampos($_GET, array('token'), '/views/admin/403.php');

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
    <title>Orçamento recusado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="" rel="stylesheet" />
    <script type="text/javascript" src=""></script>

</head>

<body oncontextmenu="return false" class="snippet-body" style="height: 100vh">
    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-center align-items-center">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card1 p-3" style="height:250px; width: 700px;">
                        <div class="row">
                            <h2 class="text-center">Orcamento Recusado</h2>
                        </div>
                        <div class="text-center">
                            
                            <img src="data:image/png;base64,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" width="50" height="50" alt="">
                        </div>

                        <div class="row pt-4">
                            <div class="col-12">
                                <h5 class="text-center">Olá <?=$cliente['nome']?></h5>

                                <p class="text-center">Agradecemos a procura da nossa empresa! Caso deseje fazer um novo orcamento ou rever algo relacionado ao orcamento recusado entre em contato conosco!</p>
                            </div>


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