<?php

namespace App\Models\Services\Auth;

use Dotenv\Dotenv;



class Middleware

{


    public static function verificaAdminLogado(): void
    {
        if ((!isset($_SESSION['admin']['logado'])) or (!$_SESSION['admin']['logado'])) {
            self::redirecionar('/views/admin/login.php', 'danger', 'Acesso negado!');
        }
    }

    public static function verificaAdminMaster(string $urlRedirecionamento): void
    {
        if ((!isset($_SESSION['admin']['admin_master'])) or ($_SESSION['admin']['admin_master'] != true)) {
            self::redirecionar($urlRedirecionamento, 'danger', 'Acesso inválido!');
        }
    }

    public static function verificaCampos(array $postOuGet, array $names, string $urlRedirecionamento, string $mensagemError): void
    {
        foreach ($names as $name) {
            if (!isset($postOuGet[$name]) or empty($postOuGet[$name])) {

                self::redirecionar($urlRedirecionamento, 'danger', $mensagemError);
            }
        }
    }

    public static function redirecionar(string $urlRedirecionamento, string $nomeSession = null, string $mensagem = null): void
    {
        $dotenv = Dotenv::createUnsafeMutable('/opt/lampp/htdocs/mscode/challengetwo/');
        $dotenv->load();
        if ($nomeSession != null and $mensagem != null) {
            $_SESSION[$nomeSession] = $mensagem;
        }
        header('Location:' . getEnv('URL_BASE') . $urlRedirecionamento);
        die();
    }

    public static function logout():void
    {
        session_start();
        session_unset();
        session_destroy();
        self::redirecionar('/views/admin/login.php');
    }
}
