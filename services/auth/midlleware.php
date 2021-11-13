<?php

function verificaCampos($post, $chaves, $urlRedirecionamento, $mensagemError)
{
    foreach ($chaves as $chave) {
        if (!isset($post[$chave]) or empty($post[$chave])) {
            $_SESSION['danger'] = $mensagemError;
            header("Location:$urlRedirecionamento");
            die();
        }
    }
}
