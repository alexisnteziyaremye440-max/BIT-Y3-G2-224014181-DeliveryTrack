<?php
declare(strict_types=1);
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();
set_exception_handler(function (Throwable $t) {
    error_log((string)$t);
    http_response_code(500);
    exit('Something went wrong. Please try again.');
});
require __DIR__ . '/core/Database.php';
require __DIR__ . '/core/helpers.php';
require __DIR__ . '/core/Controller.php';
foreach (glob(__DIR__ . '/models/*.php') as $f) require $f;
