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
    <link rel="icon" type="image/png" href="http://localhost/mscode/challengetwo/views/admin/assets/img/graphic.png">
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
                            <h2 class="text-center text-white">Orcamento Recusado!</h2>
                        </div>
                        <div class="text-center">
                            
                            <img src="https://cdn-icons-png.flaticon.com/512/395/395848.png" width="50" height="50" alt="">
                        </div>

                        <div class="row pt-4">
                            <div class="col-12">
                                <h5 class="text-center text-white">Olá <?=$cliente['nome']?></h5>

                                <p class="text-center text-white">Agradecemos a procura da nossa empresa! Caso deseje fazer um novo orcamento ou rever algo relacionado ao orcamento recusado entre em contato conosco!</p>
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