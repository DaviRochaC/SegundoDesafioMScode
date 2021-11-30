<?php
session_start(); // Liga as sessões.

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title> Recuperar senha </title>
    <link rel="icon" type="image/png" href="http://localhost/mscode/challengetwo/views/admin/assets/img/graphic.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="" rel="stylesheet" />
    <script type="text/javascript" src=""></script>

</head>

<body  class="snippet-body">
    <div class="container mt-5 mb-5">
        <div class="d-flex flex row g-0">
            <div class="offset-3 col-md-6 mt-3">
                <div class="card  card1 p-3" style="width: 650px; height: 265px;">
                    <div class="d-flex flex-column">
                        <h2 class="text-center text-white">Informe seu CPF:</h2>
                    </div>

                    <?php include('../components/alerts.php') ?> <!-- incluindo o arquivo alerts.php para mostrar possiveis mensagens armazenadas em sessões -->

                    <form method="POST" action="../../../app/actions/admin/password/recuperarSenha.php">
                        <div class="input-field d-flex flex-column mt-3">
                            <span>CPF</span>
                            <input type="text" name="cpf" class="form-control cpf" maxlength="14">

                            <button type="submit" class="mt-3  btn btn-secondary"> Enviar
                            </button>

                    </form>

                </div>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!--script das mascaras -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.cpf').mask('000.000.000-00', {
                reverse: true
            });
        });
    </script>

</body>


</html>