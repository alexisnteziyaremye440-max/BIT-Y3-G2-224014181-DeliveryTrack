<?php
// Front controller: ?r=<controller>/<action>
require __DIR__ . '/../app/bootstrap.php';
$allowed = ['home', 'auth', 'orders', 'customers', 'deliveries', 'users', 'api'];
$r = $_GET['r'] ?? 'home/index';
if (!preg_match('~^([a-z]+)/([a-z]+)$~', $r, $m) || !in_array($m[1], $allowed, true)) { http_response_code(404); exit('404 Not Found'); }
$class = ucfirst($m[1]) . 'Controller';
require __DIR__ . "/../app/controllers/$class.php";
$c = new $class();
if (!method_exists($c, $m[2]) || !(new ReflectionMethod($c, $m[2]))->isPublic()) { http_response_code(404); exit('404 Not Found'); }
$c->{$m[2]}();
