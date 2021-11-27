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
        if ((!isset($_SESSION['admin']['logado'])) or (!$_SESSION['admin']['logado'])) {  // Verifica se a sessão admin logado é nula ou falsa.
            self::redirecionar('/views/admin/login.php', 'danger', 'Acesso negado!'); // Redireciona pra pagina de login através da função redirecionar, com uma mensagem de error.
        }
    }


    /**
     * Função de verificação sobre a hierarquia do administrador. Se o mesmo é administrador comum ou do tipo Master.
     * @param  string $urlRedirecionamento URL na qual a pagina redirecionará caso o usuario da aplicação não for administrador master.
     * @return void
     */
    public static function verificaAdminMaster(string $urlRedirecionamento): void
    {
        if ((!isset($_SESSION['admin']['admin_master'])) or ($_SESSION['admin']['admin_master'] != true)) { // Verifica se a sessão admin master é nula ou diferente de verdadeiro.
            self::redirecionar($urlRedirecionamento, 'danger', 'Acesso inválido!');  // Redireciona para a url passada como parâmetro através da função redirecionar.
        }
    }


    /**
     * Função para verificar se inputs ou índices são nulos ou vazios.
     * @param array $postOuget representa o  Array das variáveis globais $_POST ou $_GET.
     * @param array $names Array com os names colocados nos inputs para receber via $_POST ou os names dos indices para receber via $_GET.
     * @param string $urlRedirecionamento URL na qual a aplicação irá redirecionar caso o input ou indice ser nulo ou vazio.
     * @param string $mensagemError Mensagem de error presente em uma possível sessão que será ligada caso deseje redirecionar para a URL do terceiro parâmetro com uma mensagem de error.
     */
    public static function verificaCampos(array $postOuGet, array $names, string $urlRedirecionamento, string $mensagemError = null): void
    {
        foreach ($names as $name) {  // Faz uma iteraçao sobre o array do segundo parâmetro e executa o que estiver entre as chaves para cada item do mesmo.
            if (!isset($postOuGet[$name]) or empty($postOuGet[$name])) { // Verifica se o elemento do primeiro array com o indice do segundo array é nulo ou verdadeiro para vazio.

                if ($mensagemError != null) {  // Verifica se o último parâmetro é  diferente de nulo.
                    self::redirecionar($urlRedirecionamento, 'danger', $mensagemError); // Redireciona para a URL passada como parâmetro através da função redirecionar, liga uma sessão chamada danger, a qual recebe a mensagem passada no último parâmetro.
                }

                if ($mensagemError == null) {  // Verifica se o último parâmetro é igual a nulo.
                    self::redirecionar($urlRedirecionamento);  // Apenas redireciona para a URL passada como parâmetro através da função redirecionar.
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
        $dotenv = Dotenv::createUnsafeMutable('/opt/lampp/htdocs/mscode/challengetwo/');    //Instancia da classe Dotenv especificando o caminho do arquivo .env     
        $dotenv->load();    // Faz a leitura do arquivo .env
        if ($nomeSession != null and $mensagem != null) { // Verificando se os dois ultimos parametros são diferentes de nulo.
            $_SESSION[$nomeSession] = $mensagem;  // Liga uma sessão com o nome passado no segundo parâmetro, na qual recebe uma mensagem passada no terceiro parâmetro.
        }
        header('Location:' . getEnv('URL_BASE') . $urlRedirecionamento);  // Redireciona para a URL passada no primeiro parâmetro.
        die();   // Mata a aplicação.
    }


    /**
     * Função para sair da aplicação.
     * @return void
     */
    public static function logout(): void
    {
        session_start();   // Liga a sessão.
        session_unset();   // Limpa a sessão.
        session_destroy();  // Destrói a sessão.
        self::redirecionar('/views/admin/login.php'); // Redireciona para página de login.
    }
}
