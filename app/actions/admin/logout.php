<?php

require('../../../vendor/autoload.php');

use App\Models\Services\Auth\Middleware;

Middleware::logout();  // Faz o logout do administrador.

