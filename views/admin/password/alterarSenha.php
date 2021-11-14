<?php
session_start();

if (!isset($_GET['i'])) {
    $_SESSION['danger'] = 'Ocorreu um erro tente novamente!';
    header('Location:http://localhost/mscode/challengetwo/views/admin/dashboard.php');
    die();
}





?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Target Material Design Bootstrap Admin Template</title>

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
                    Alterar Senha
                </h3>

            </div>

            <div id="page-inner">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <?php include('../components/alerts.php') ?>
                            <div class="card-content">


                                <form method="POST" action="../../../actions/admin/password/alterarSenha.php" class="col s12">

                                    <div class="row">
                                        <div class="input-field col s6">
                                            <input name="senhaAtual" type="text" class="validate">
                                            <label>Senha antiga</label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="input-field col s6">
                                            <input name="novaSenha" type="text" class="validate">
                                            <label> Nova senha</label>
                                        </div>
                                        <div class="input-field col s6">
                                            <input name="confirmacaoSenha" type="text" class="validate">
                                            <label>Confirmacao nova senha</label>
                                        </div>
                                    </div>


                                    <input type="hidden" name="i" value="<?= $_GET['i'] ?>">

                                    <div class="row">
                                        <button type="submit" class="btn btn-success btn-block">Salvar</button>
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
                <script src="../assets/js/jquery-1.10.2.js"></script>

                <!-- Bootstrap Js -->
                <script src="../assets/js/bootstrap.min.js"></script>

                <script src="../assets/materialize/js/materialize.min.js"></script>

                <!-- Metis Menu Js -->
                <script src="../assets/js/jquery.metisMenu.js"></script>
                <!-- Custom Js -->
                <script src="../assets/js/custom-scripts.js"></script>



</body>

</html>