<?php

namespace Core ;

class Router{

    private array $routes = [
        'GET'=>[],
        'POST'=>[], 
        'PUT'=>[],
        'DELETE'=>[],
    ];


    // enregistrement des routes 

    public function get(string $uri , string $action){
        $this->add('GET', $uri, $action);
    }

    public function post(string $uri , string $action){
        $this->add('POST', $uri, $action);
    }

    public function put(string $uri , string $action){
        $this->add('PUT', $uri, $action);
    }

    public function delete(string $uri , string $action){
        $this->add('DELETE', $uri, $action);
    }

    public function add(string $method, string $uri, string $action){
        $pattern = '#^' . preg_replace('#\{[^/]+\}#', '([^/]+)' , rtrim($uri , '/')) . '$#'; 
        $this->routes[$method][$pattern] = $action;
    }

    // lancement (dispatcher ) dispatcher est une fonction qui permet de lancer les routes

    public function dispatch(string $requestUri , string $requestMethod){
        $requestPath = parse_url($requestUri , PHP_URL_PATH);
        $routes = $this->routes[$requestMethod] ?? []; 

        foreach($routes as $pattern => $action){
            if(preg_match($pattern, $requestPath , $params)){
                array_shift($params); 
                return $this->callAction($action, $params); 

            }
        }

        http_response_code(404); 
        echo 'Route non trouvée'; 
    }

    
    // appel du controlleur@methode fonction qui permet d'appeler le controlleur et la methode

    public function  callAction(string $action , array $params){
        [$controllerName, $method] = explode('@', $action ); 
        $controllerClass = 'App\\Controllers\\' . $controllerName;

        if(!class_exists($controllerClass)){
            throw new \Exception("Controller $controllerClass not found");
        }

        $controller = new $controllerClass();

        if(!method_exists($controller, $method)){
            throw new \Exception("Method $method not found in $controllerClass");
        }

        // injection dynamique des parametres  par exemple l  id 
        call_user_func_array([$controller, $method], $params); 
    }
}