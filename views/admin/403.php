<?php
session_start(); // Liga as sessões.
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Nao autorizado</title>
    <link rel="icon" type="image/png" href="http://localhost/mscode/challengetwo/views/admin/assets/img/graphic.png">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Cabin|Ubuntu:700'>
    <link rel="stylesheet" href="./assets/css/403.css">

</head>

<body>
    <!-- partial:index.partial.html -->
    <div class="base io">
        <h1 class="io">403</h1>
        <h2>Acesso Negado</h2>
        <?php if($_SESSION['admin']['logado']){ ?>  <!-- verificando se a sessão admin no indice logado é verdadeira -->
            <h5>
                <a href="http://localhost/mscode/challengetwo/views/admin/dashboard.php" class="btn btn-dark">Página Inicial</a>
            </h5>
        <?php } ?>
    </div>
    <!-- partial -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
</body>

</html>