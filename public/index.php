<?php
require_once '../vendor/autoload.php';
require_once './core/EnvLoader.php';

use Core\Router;
use Core\EnvLoader;

EnvLoader::load('./.env');

$router = new Router();

require_once '../app/Routes/routes.php';

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
