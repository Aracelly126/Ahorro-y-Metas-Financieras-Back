<?php
namespace Core;

class Router {
    private $routes = [];

    public function add($method, $path, $callback) {
        $this->routes[strtoupper($method)][$path] = $callback;
    }

    public function dispatch($method, $uri) {
        $method = strtoupper($method);
        $uri = explode('?', $uri)[0]; // clean query string
        if (isset($this->routes[$method][$uri])) {
            call_user_func($this->routes[$method][$uri]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Not Found"]);
        }
    }
}
