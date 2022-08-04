<?php

namespace Core;

class Router 
{
    // all the routes are registered here
    protected $routes = [];

    protected $params = [];

    protected $nameSpace = 'App\Controllers\\';

    // register route
    public function add($route, $action)
    {
        // remove the first / from the path
        $route = preg_replace('/^\//', '', $route);

        // replace all the '/' inside the url with \/
        $route = preg_replace('/\//', '\\/', $route);
        
        $route = preg_replace('/\{([a-zA-Z0-9-]+)\}/', '(?<\1>[a-zA-Z0-9-]+)', $route);

        if(is_array($action)){
            $action = ['controller' => $action[0], 'method' => $action[1]];
            $this->routes[$route] = $action;
        }else{
            list($param['controller'], $param['method']) = explode('@', $action);
            $this->routes[$route] = $param;
        }

        return $this;
    }

    // return all the registered routes
    public function allRoutes()
    {
        return $this->routes;
    }

    // check the current route exsists or not
    public function match($url)
    {
        foreach($this->routes as $route => $params){
            if(preg_match('/^' . $route . '$/', $url, $matches)) {

                foreach($matches as $key => $match){
                    if(is_string($key)){
                        $params['params'][$key] = $match;
                    }
                }

                $this->params = $params;
                return true;
            }
        }  
        return false;
    }

    // return the current route params
    public function getParams()
    {
        return $this->params;
    }


    // connect route with a controller
    public function dispatch($url)
    {
        $url = $this->removeVariablesOfQueryString($url);
        if ($this->match($url)){
            $controller = $this->params['controller'];
            $controller = $this->nameSpace . $controller;

            // check the controller exists or not
            if(class_exists($controller)){
                $controllerObj = new $controller();

                $method = $this->params['method'];

                if(is_callable([$controllerObj, $method])){
                    if($controllerObj->before() == true){
                        echo call_user_func_array([$controllerObj, $method] , $this->params['params'] ?? []);
                        $controllerObj->after();
                    }
                }else{
                    throw new \Exception("Method {$method} Not Found");
                }
            }else {
                throw new \Exception("The $controller Does not exists");
            }

        } else {
            throw new \Exception('404 NOT FOUND', 404);
        }
    }

    // explode the get request from the main url
    protected function removeVariablesOfQueryString($url)
    {
        if($url != ''){
            $parts = explode('?', $url, 2);
            if(strpos($parts[0], '=') === false){
                $url = $parts[0];
            } else {
                $url = '';
            }
            return $url;
        }
    }
}