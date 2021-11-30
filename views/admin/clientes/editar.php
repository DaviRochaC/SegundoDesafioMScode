<?php

session_start();
require_once('../../../vendor/autoload.php');

use App\Models\Cliente;
use App\Models\Services\Auth\Middleware;

Middleware::verificaAdminLogado();  // Verifica se usuario administrador está logado.

// Obs: A variável global $_GET no indice 'i' contem o id do administrador criptografado em base64.
Middleware::verificaCampos($_GET, array('i'), '/views/admin/clientes/gerenciarClientes.php', 'Ocorreu um erro, tente novamente!'); // Verifica se o índices passados através da variável global $_GET  são vazios ou nulos.


$clienteModel = new Cliente(); // Instância da classe Cliente para utilização de seus metódos.


$cliente = $clienteModel->busca('id', (base64_decode($_GET['i'])));  // Buscando um cliente no banco de dados pela descriptografia em base64 do $_GET no indice i (que é o id);

if (!$cliente) {// Verifica se o retorno da variável $cliente é falso. O que representa que não foi encontrado nenhum cliente com o id  passado pelo $_GET.
    Middleware::redirecionar('/views/admin/clientes/gerenciarClientes.php', 'danger', 'Cliente não encontrado');  // Redireciona para a página de gerenciamento de clientes  com uma mensagem (informando o erro) armazenada  em uma sessão. 
}


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title> Editar cliente</title>

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
        <?php include('../components/navbar.php'); ?> <!-- incluindo o nav-bar -->
        <!--/. NAV TOP  -->
        <?php include('../components/menu.php'); ?> <!-- incluindo o menu -->

        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div class="header">
                <h3 class="page-header">
                    Editar
                </h3>

            </div>

            <div id="page-inner">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">

                            <div class="card-content">
                                <?php include('../components/alerts.php') ?>  <!-- incluindo o arquivo alerts.php para mostrar possiveis mensagens armazenadas em sessões -->
                                <form method="POST" action="../../../app/actions/admin/clientes/editar.php" class="col s12">
                                    <div class="row">
                                        <div class="input-field col s6">
                                            <input name="nome" type="text" class="validate" value="<?= $cliente['nome'] ?>">
                                            <label>Nome</label>

                                        </div>
                                        <div class="input-field col s4">
                                            <input name="cpf_cnpj" type="text" class="validate cpfOuCnpj" value="<?= $cliente['cpf_cnpj'] ?>" minlength="14" maxlength="18">
                                            <label>CPF/CNPJ</label>
                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="input-field col s6">
                                            <input name="email" type="email" class="validate" value="<?= $cliente['email'] ?>">
                                            <label>Email</label>
                                        </div>
                                                    
                                        <input name="i" type="hidden" value="<?= base64_encode($cliente['id']) ?>" class="validate">

                                    </div>
                                    <div class="row">
                                        <button type="submit" class="btn btn-success btn-block">Salvar Alterações</button>
                                    </div>

                                </form>
                                <div class="clearBoth"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /.col-lg-12 -->

            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
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
            var options = {
                onKeyPress: function(cpf, ev, el, op) {
                    var masks = ['000.000.000-000', '00.000.000/0000-00'];
                    $('.cpfOuCnpj').mask((cpf.length > 14) ? masks[1] : masks[0], op);
                }
            }

            $('.cpfOuCnpj').length > 11 ? $('.cpfOuCnpj').mask('00.000.000/0000-00', options) : $('.cpfOuCnpj').mask('000.000.000-00#', options);

        });
    </script>


</body>

</html>