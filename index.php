<?php

session_start();

require_once "vendor/autoload.php";

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

if ($_ENV['APP_DEBUG']) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

$routes = Symfony\Component\Yaml\Yaml::parseFile(__DIR__.'/app/routes/routes.yml');

$uri = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['PHP_SELF']);

foreach ($routes as $name => $route) {
    foreach ($route['path'] as $path) {
        if (preg_match('/^'.(str_replace('/', '\/', $path)).'$/', $uri, $matches)) {
            
            list($class, $action) = explode('::', $route['controller'], 2);

            $params = [];
            
            if (count($matches) > 0) {
                unset($matches[0]);
                $params = $matches;
            }

            if (class_exists($class) && in_array($action, get_class_methods($class))) {
                $class = new $class;
                call_user_func_array([$class, $action], $matches);
            }
        }
    }
}


