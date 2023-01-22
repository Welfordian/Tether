<?php

namespace Tether;

use App\Http\Kernel;

class Route
{
    private App $app;
    
    protected Kernel $kernel;
    protected array $registeredRoutes = ['get' => [], 'post' => []];

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->kernel = new Kernel();
        
        require_once __DIR__ . '/../routes/web.php';
    }
    
    public function handle(Request $request): void
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        
        if (array_key_exists($request->path(), $this->registeredRoutes[$method])) {
            if (is_callable($this->registeredRoutes[$method][$request->path()])) {
                $this->handleMiddleware();
                
                echo $this->registeredRoutes[$method][$request->path()]($request);
                
                $this->handleMiddleware('after');
                
                return;
            }
            
            if (is_array($this->registeredRoutes[$method][$request->path()])) {
                if (count($this->registeredRoutes[$method][$request->path()]) === 2 && $this->hasControllerAndMethod(...$this->registeredRoutes[$method][$request->path()])) {
                    $this->handleMiddleware();
                    
                    if (class_exists($this->registeredRoutes[$method][$request->path()][0])) {
                        $class = $this->registeredRoutes[$method][$request->path()][0];
                    } else {
                        $class = 'App\\Http\\Controllers\\' . $this->registeredRoutes[$method][$request->path()][0];
                    }
                    
                    $class = new $class($this->app);
                    
                    echo $class->{$this->registeredRoutes[$method][$request->path()][1]}($request);

                    $this->handleMiddleware('after');
                    
                    return;
                }
            }
        }
        
        echo $this->notFound();
    }
    
    public function handleMiddleware($type = 'before')
    {
        foreach ($this->kernel->middleware()[$type] as $middleware)
        {
            $middleware = new $middleware;
            
            if ($middleware->handle($this->app) !== true) {
                die($middleware->handle($this->app));
            }
        }
    }
    
    public function notFound(): string
    {
        return $this->app->get('view')->make('errors.404');
    }
    
    public function hasControllerAndMethod($controller, $method): bool
    {        
        if (class_exists($controller)) {
            $reflection = new \ReflectionClass($controller);
            
            return $reflection->hasMethod($method);
        }
        
        $fqn = 'App\\Http\\Controllers\\' . $controller;
        
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
    
    public function abort($code = '404', $data = [])
    {
        if (file_exists(basedir('templates/errors/' . $code . '.blade.php'))) {
            die($this->app->get('view')->make('errors.' . $code, $data));
        }

        die($this->app->get('view')->make('errors.404', $data));
    }
    
    public static function __callStatic($method, $args)
    {
        $router = new self();
        
        if (method_exists($router, $method)) {
            call_user_func_array(array($router, $method), $args);
        }
    }
}