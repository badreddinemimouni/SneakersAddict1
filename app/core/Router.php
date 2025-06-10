<?php

class Router {
    private $routes = [];
    private $currentRoute;
    
    public function __construct() {
        $this->currentRoute = $this->getCurrentRoute();
    }
    
    private function getCurrentRoute() {
        return $_GET['route'] ?? 'home';
    }
    
    public function get($route, $controller, $action) {
        $this->routes['GET'][$route] = ['controller' => $controller, 'action' => $action];
    }
    
    public function post($route, $controller, $action) {
        $this->routes['POST'][$route] = ['controller' => $controller, 'action' => $action];
    }
    
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $route = $this->currentRoute;
        
              
        if (isset($this->routes[$method][$route])) {
            $routeInfo = $this->routes[$method][$route];
            $controllerName = $routeInfo['controller'];
            $actionName = $routeInfo['action'];
            
            $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';
            
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                
                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    
                    if (method_exists($controller, $actionName)) {
                        $controller->$actionName();
                    } else {
                        $this->handleNotFound("Méthode '$actionName' non trouvée dans le contrôleur '$controllerName'");
                    }
                } else {
                    $this->handleNotFound("Classe '$controllerName' non trouvée");
                }
            } else {
                $this->handleNotFound("Contrôleur '$controllerName' non trouvé : $controllerFile");
            }
        } else {
            $this->handleNotFound("Route '$route' non définie pour la méthode $method");
        }
    }
    
    private function handleNotFound($debug = '') {
        http_response_code(404);
        echo "<h1> Page non trouvée</h1>";
        echo "<p>La route demandée n'existe pas.</p>";
        
        if (!empty($debug)) {
            echo "<p><strong>Debug:</strong> $debug</p>";
        }
        
        echo "<h3>Routes disponibles:</h3>";
        echo "<ul>";
        if (!empty($this->routes['GET'])) {
            foreach ($this->routes['GET'] as $route => $info) {
                echo "<li><a href='?route=$route'>$route</a> (GET) → {$info['controller']}::{$info['action']}</li>";
            }
        }
        echo "</ul>";
        
        echo "<p><a href='?route=home'>← Retour à l'accueil</a></p>";
    }
} 