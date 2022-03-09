<?php

namespace app\library;

class Routing{
    /**
     * Holds the registered routes
     *
     * @var array $routes
     */
    private $routes = [];


    /**
     * Register a new route
     *
     * @param $action string
     * @param \Closure $callback Called when current URL matches provided action
     */
    public function route($action, \Closure $callback)
    {
        $action = trim($action, '/');
        $this->routes[$action] = $callback;
    }
    /**
     * Dispatch the router
     *
     * @param $action string
     */
    public function dispatch($action)
    {
        $action = trim($action, '/');
        //check if route is added or is wildcard route
        $parts = explode('/',$action);
        if(isset($this->routes[$action]) || isset($this->routes[$parts[0].'/*'])){
            $callback =isset($this->routes[$parts[0].'/*']) ?  $this->routes[$parts[0].'/*'] : $this->routes[$action];
        }else{
            $callback = $this->routes['err404'];
        }
        echo call_user_func($callback,$parts,$_SERVER['REQUEST_METHOD'],apache_request_headers());
    }
}
