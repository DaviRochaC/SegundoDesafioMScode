<?php
session_start();

require_once('../../../vendor/autoload.php');

use App\Models\Services\Auth\Middleware;

session_start();

Middleware::verificaAdminLogado();


?>