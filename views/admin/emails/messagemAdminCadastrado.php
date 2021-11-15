<?php

$messagem = $_SESSION['cadastro_novo_admin'];
unset($_SESSION['cadastro_novo_admin']);


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title> Cadastro novo Administrador</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="" rel="stylesheet" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style></style>
</head>

<body oncontextmenu="return false" class="snippet-body">
    <!DOCTYPE html>
    <html>

    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    </head>

    <body style="
          background-color: #000;
          margin: 0 !important;
          padding: 0 !important;
        ">
        <!-- HIDDEN PREHEADER TEXT -->

        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <!-- LOGO -->
            <tr>
                <td bgcolor="#000" align="center">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px">
                        <tr>
                            <td align="center" valign="top" style="padding: 40px 10px 40px 10px"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td bgcolor="#000" align="center" style="padding: 0px 10px 0px 10px">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px">
                        <tr>
                            <td bgcolor="#ffffff" align="center" valign="top" style="
                      padding: 40px 20px 20px 20px;
                      border-radius: 4px 4px 0px 0px;
                      color: #111111;
                      font-family: 'Lato', Helvetica, Arial, sans-serif;
                      font-size: 48px;
                      font-weight: 400;
                      letter-spacing: 4px;
                      line-height: 48px;
                    ">
                                <h1 style="font-size: 48px; font-weight: 400; margin: 2">

                                </h1>
                                <img src="https://cdn-icons-png.flaticon.com/512/2722/2722007.png" width="125" height="120" style="display: block; border: 0px" />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px">
                        <tr>
                            <td bgcolor="#ffffff" align="left" style="
                      padding: 20px 30px 40px 30px;
                      color: #666666;
                      font-family: 'Lato', Helvetica, Arial, sans-serif;
                      font-size: 18px;
                      font-weight: 400;
                      line-height: 25px;
                    ">
                                <p style="margin: 0; text-align: justify-all;">
                                    <?= $messagem ?>
                                </p>
                            </td>
                        </tr>


                        <!-- COPY -->
                        <tr>
                            <td bgcolor="#ffffff" align="left" style="
                      padding: 20px 30px 20px 30px;
                      color: #666666;
                      font-family: 'Lato', Helvetica, Arial, sans-serif;
                      font-size: 18px;
                      font-weight: 400;
                      line-height: 25px;
                    ">
                                <p style="margin: 0">

                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#ffffff" align="left" style="
                      padding: 0px 30px 20px 30px;
                      color: #666666;
                      font-family: 'Lato', Helvetica, Arial, sans-serif;
                      font-size: 18px;
                      font-weight: 400;
                      line-height: 25px;
                    ">
                                <p style="margin: 0">

                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#ffffff" align="left" style="
                      padding: 0px 30px 40px 30px;
                      border-radius: 0px 0px 4px 4px;
                      color: #666666;
                      font-family: 'Lato', Helvetica, Arial, sans-serif;
                      font-size: 18px;
                      font-weight: 400;
                      line-height: 25px;
                    ">
                                <p style="margin: 0"> Atenciosamente, Ghapic.</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td bgcolor="#f4f4f4" align="center" style="padding: 30px 10px 0px 10px">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px">
                        <tr>

                        </tr>
                    </table>
                </td>
            </tr>

        </table>
    </body>

    </html>
    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript"></script>
</body>

</html>