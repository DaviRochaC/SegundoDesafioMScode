<?php

function verificaCampos($post, $names, $urlRedirecionamento, $mensagemError)
{
    foreach ($names as $name) {
        if (!isset($post[$name]) or empty($post[$name])) {
            $_SESSION['danger'] = $mensagemError;
            header("Location:$urlRedirecionamento");
            die();
        }
    }
}
