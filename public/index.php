<?php

// TRUCO SENIOR: Subimos el límite de memoria para que el Profiler de Symfony pueda procesar los 20 archivos en desarrollo
ini_set('memory_limit', '512M');

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};