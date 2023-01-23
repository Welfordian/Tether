<?php

namespace Tether;

use App\Http\Kernel;

class Route
{
    protected array $current = [];
    protected Kernel $kernel;
    protected array $registeredRoutes = ['get' => [], 'post' => []];

    public function __construct(private readonly App $app)
    {
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
                
                return;
            }
            
            if (is_array($this->registeredRoutes[$method][$request->path()])) {
                if (count($this->registeredRoutes[$method][$request->path()]['method']) === 2 && $this->hasControllerAndMethod(...$this->registeredRoutes[$method][$request->path()]['method'])) {
                    if (class_exists($this->registeredRoutes[$method][$request->path()]['method'][0])) {
                        $class = $this->registeredRoutes[$method][$request->path()]['method'][0];
                    } else {
                        $class = 'App\\Http\\Controllers\\' . $this->registeredRoutes[$method][$request->path()]['method'][0];
                    }
                    
                    $this->handleGlobalMiddleware();
                    $this->handleMiddleware($method, $request->path());
                    
                    $class = new $class($this->app);
                    
                    echo $class->{$this->registeredRoutes[$method][$request->path()]['method'][1]}($request);

                    $this->handleGlobalMiddleware('after');
                    
                    return;
                }
            }
        }
        
        echo $this->notFound();
    }
    
    public function handleMiddleware($method = 'get', $path = '', $middleware = [])
    {
        $route = $this->registeredRoutes[$method][$path];
        
        if (! array_key_exists('middleware', $route)) return;
        
        if ($middleware === []) $middleware = $route['middleware'];
        
        foreach ($middleware as $middleware) {
            if (is_array($middleware)) return $this->handleMiddleware($method, $path, $middleware);
            
            if (array_key_exists($middleware, $this->kernel->getMiddleware())) {
                $middleware = $this->kernel->getMiddleware()[$middleware];

                $middleware = new $middleware;

                if ($middleware->handle($this->app) !== true) {
                    die($middleware->handle($this->app));
                }
            }
        }
    }
    
    public function handleGlobalMiddleware($type = 'before'): void
    {
        foreach ($this->kernel->getGlobalMiddleware()[$type] as $middleware)
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
    
    public function get($path, $method): static
    {
        $this->current = ['type' => 'get', 'path' => $path, 'method' => $method];
        
        $this->registeredRoutes['get'][$path] = [
            'method' => $method
        ];
        
        return $this;
    }
    
    public function middleware($middleware): static
    {
        if ($this->current === []) return $this;
        
        $current = &$this->registeredRoutes[$this->current['type']][$this->current['path']];

        if (! isset($current['middleware']) && is_array($middleware)) $current['middleware'] = $middleware;
        if (! isset($current['middleware']) || ! is_array($current['middleware'])) $current['middleware'] = [];
        
        $current['middleware'][] = $middleware;
        
        return $this;
    }

    public function post($path, $method): static
    {
        $this->current = ['type' => 'post', 'path' => $path, 'method' => $method];

        $this->registeredRoutes['post'][$path] = [
            'method' => $method
        ];

        return $this;
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