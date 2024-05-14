<?php

class Router {
    private $routes = array();

    public function addRoute($pattern, $method, $handler) {
        $this->routes[$pattern][$method] = $handler;
    }

    public function route($requestUri, $method) {
        if($requestUri == ''){
            http_response_code(200);
            echo "Ciao mondo";
            exit;
        }
        foreach ($this->routes as $routePattern => $actions) {
            if (preg_match('#^' . $routePattern . '$#', $requestUri, $matches)) {
                if (isset($actions[$method])) {
                    array_shift($matches);
                    return array('handler' => $actions[$method], 'matches' => $matches);
                } else {
                    throw new Exception('Bad Request', 400);
                }
            }
        }
        throw new Exception('Page not found', 404);
    }
}

class Dispatcher {
    public static function dispatch($handler, $matches, $paramsGet, $paramsPost) {
        if (is_callable($handler)) {
            $matches = array_merge($matches, $paramsGet, $paramsPost);
            $result = call_user_func_array($handler, $matches);
            if (is_array($result)) {
                echo json_encode($result);
            } elseif (is_string($result)) {
                echo $result;
            }elseif (is_bool($result)) {
                echo $result;
            } else {
                throw new Exception('Unsupported method', 405);
            }
        } elseif (is_array($handler) && count($handler) == 2 && is_string($handler[0]) && is_string($handler[1])) {
            $controllerName = $handler[0];
            $methodName = $handler[1];
            $controller = new $controllerName();
            $matches = array_merge($matches, $paramsGet, $paramsPost);
            $controller->$methodName(...$matches);
        } else {
            throw new Exception('Invalid handler', 500);
        }
    }
}

$router = new Router();
$router->addRoute('/api/v1/Customer', 'GET', array('Controller\CustomerController', 'index'));
$router->addRoute('/api/v1/Customer', 'POST', array('Controller\CustomerController', 'store'));
$router->addRoute('/api/v1/Customer/(\d+)', 'GET', array('Controller\CustomerController', 'getOne'));

$requestUri = rtrim($_SERVER['REQUEST_URI'], '/');
$method = $_SERVER['REQUEST_METHOD'];
$paramsGet = $_GET;
$paramsPost = $_POST;

try {
    $routeInfo = $router->route($requestUri, $method);
    Dispatcher::dispatch($routeInfo['handler'], $routeInfo['matches'], $paramsGet, $paramsPost);
} catch (Exception $e) {
    http_response_code($e->getCode());
    echo json_encode(array('message' => $e->getMessage()));
}
