<?php

namespace Tether;

class Route
{
    protected array $registeredRoutes = ['get' => [], 'post' => []];
    
    public function __construct()
    {
        require_once __DIR__ . '/../routes/web.php';
    }
    
    public function handle(Request $request): void
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        
        if (array_key_exists($request->path(), $this->registeredRoutes[$method])) {
            if (is_callable($this->registeredRoutes[$method][$request->path()])) {
                echo $this->registeredRoutes[$method][$request->path()]();
                
                return;
            }
            
            if (is_array($this->registeredRoutes[$method][$request->path()])) {
                if (count($this->registeredRoutes[$method][$request->path()]) === 2 && $this->hasControllerAndMethod(...$this->registeredRoutes[$method][$request->path()])) {
                    $class = new ('App\\Controllers\\' . $this->registeredRoutes[$method][$request->path()][0]);
                    
                    echo $class->{$this->registeredRoutes[$method][$request->path()][1]}();
                    
                    return;
                }
            }
        }
        
        echo $this->notFound();
    }
    
    public function notFound(): string
    {
        return View::render('errors.404');
    }
    
    public function hasControllerAndMethod($controller, $method): bool
    {        
        $fqn = 'App\\Controllers\\' . $controller;
        
        if (class_exists($fqn)) {
            $reflection = new \ReflectionClass($fqn);
            
            return $reflection->hasMethod($method);
        }
        
        return false;
    }
    
    public function get($path, $method): void
    {
        $this->registeredRoutes['get'][$path] = $method;
    }

    public function post($path, $method): void
    {
        $this->registeredRoutes['post'][$path] = $method;
    }
    
    public static function __callStatic($method, $args)
    {
        $router = new self();
        
        if (method_exists($router, $method)) {
            call_user_func_array(array($router, $method), $args);
        }
    }
}