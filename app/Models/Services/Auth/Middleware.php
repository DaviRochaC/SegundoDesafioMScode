<?php

namespace App\Models\Services\Auth;

use Dotenv\Dotenv;



class Middleware

{

    /**
     * Função de verificação para a informação sobre o efetuamento do login do usuario Administrador.
     * @return void
     */
    public static function verificaAdminLogado(): void
    {
        if ((!isset($_SESSION['admin']['logado'])) or (!$_SESSION['admin']['logado'])) {
            self::redirecionar('/views/admin/login.php', 'danger', 'Acesso negado!');
        }
    }


    /**
     * Função de verificação sobre a hierarquia do administrador. Se o mesmo é administrador comum ou do tipo Master.
     * @return void
     */
    public static function verificaAdminMaster(string $urlRedirecionamento): void
    {
        if ((!isset($_SESSION['admin']['admin_master'])) or ($_SESSION['admin']['admin_master'] != true)) {
            self::redirecionar($urlRedirecionamento, 'danger', 'Acesso inválido!');
        }
    }

    public static function verificaCampos(array $postOuGet, array $names, string $urlRedirecionamento, string $mensagemError = null): void
    {
        foreach ($names as $name) {
            if (!isset($postOuGet[$name]) or empty($postOuGet[$name])) {

                if ($urlRedirecionamento != null and $mensagemError != null) {
                    self::redirecionar($urlRedirecionamento, 'danger', $mensagemError);
                }

                if ($mensagemError == null) {
                    self::redirecionar($urlRedirecionamento);
                }
            }
        }
    }

    /**
     * Função para realizar redirecionamentos na aplicação.
     * @param string $urlRedirecionamento Endereço da página para onde deseja redirecionar.
     * @param string $nomeSession Nome da sessão que deseja iniciar.   ex:$_SESSION['danger'] ou $_SESSION['success'].
     * @param string $mensagem Mensagem da sessão iniciada.
     * @return void
     */
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


    /**
     * Função para sair da aplicação.
     * @return void
     */
    public static function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
        self::redirecionar('/views/admin/login.php');
    }
}
