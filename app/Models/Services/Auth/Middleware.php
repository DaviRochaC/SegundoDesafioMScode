<?php

namespace App\Models\Services\Auth;



class Middleware

{


    public static function verificaAdminLogado():void
    {
        if ((!isset($_SESSION['admin']['logado']))or($_SESSION['admin']['logado'] != true)) {
            self::redirecionar('danger', 'Acesso negado!', 'http://localhost/mscode/challengetwo/views/admin/login.php');
        }
    }

    public static function verificaCampos(array $post, array $names, string $urlRedirecionamento, string $mensagemError): void
    {
        foreach ($names as $name) {
            if (!isset($post[$name]) or empty($post[$name])) {

                self::redirecionar('danger', $mensagemError, $urlRedirecionamento);
            }
        }
    }

    public static function redirecionar(string $nomeSession, string $mensagem, string $urlRedirecionamento,): void
    {
        $_SESSION[$nomeSession] = "$mensagem";
        header("Location: $urlRedirecionamento");
        die();
    }

}
