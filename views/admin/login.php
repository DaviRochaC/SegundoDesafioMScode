<?php
session_start(); // Liga as sessões.

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/png" href="http://localhost/mscode/challengetwo/views/admin/assets/img/graphic.png">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="" rel="stylesheet" />
    <script type="text/javascript" src=""></script>

</head>

<body class="snippet-body">
    <div class="container mt-5 mb-5">
        <div class="d-flex flex row g-0">
            <div class="col-md-6 mt-3">
                <div class="card card1 p-3">
                    <div class="d-flex flex-column">
                        <h2 class="text-center text-white">Bem vindo!</h2>
                    </div>

                    <?php include('components/alerts.php') ?> <!-- incluindo o arquivo alerts.php para mostrar possiveis mensagens armazenadas em sessões -->

                    <form method="POST" action="../../app/actions/admin/login.php">
                        <div class="input-field d-flex flex-column mt-3">
                            <span>CPF</span>
                            <input type="text" name="cpf" class="form-control cpf" maxlength="14">

                            <span class="mt-3">Password</span>
                            <input name="password" class="form-control" type="password">

                            <button type="submit" class="mt-3 btn btn-secondary"> Login
                            </button>

                            <div class="text2 mt-4 d-flex flex-row align-items-center">
                                <span class="text-white">Esqueceu a senha?<span class="register"><a  class="text-white" href="http://localhost/mscode/challengetwo/views/admin/password/recuperarSenha.php">clique aqui</a></span></span>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
            <div class="col-md-6 mt-3 ps-5">
                <div class="card card2 p-3">
                    <div class="image">
                        <img src="assets/img/graphic.png" height="250 px">
                    </div>
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