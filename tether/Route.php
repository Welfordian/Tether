<?php

namespace Tether;

use App\Http\Kernel;
use JetBrains\PhpStorm\NoReturn;

class Route
{
    protected Kernel $kernel;
    
    protected array $current = [];
    protected array $groups = [];
    protected array $registeredRoutes = ['get' => [], 'post' => []];

    public function __construct(private readonly App $app)
    {
        $this->kernel = new Kernel();
        
        require_once __DIR__ . '/../routes/web.php';
    }
    
    public function handle(Request $request): void
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        
        if ($path = $this->isValidRoute($request, $method)) {
            if (is_callable($this->registeredRoutes[$method][$path]['method'])) {
                $this->handleMiddleware($method, $path);
                
                echo $this->registeredRoutes[$method][$path]['method']($request);
                
                return;
            }
            
            if (is_array($this->registeredRoutes[$method][$path])) {
                if (count($this->registeredRoutes[$method][$path]['method']) === 2 && $this->hasControllerAndMethod(...$this->registeredRoutes[$method][$path]['method'])) {
                    if (class_exists($this->registeredRoutes[$method][$path]['method'][0])) {
                        $class = $this->registeredRoutes[$method][$path]['method'][0];
                    } else {
                        $class = 'App\\Http\\Controllers\\' . $this->registeredRoutes[$method][$path]['method'][0];
                    }
                    
                    $this->handleGlobalMiddleware();
                    $this->handleMiddleware($method, $path);
                    
                    if (array_key_exists('group', $this->registeredRoutes[$method][$path])) {
                        $this->handleGroupMiddleware($method, $path, $this->registeredRoutes[$method][$path]['group']);
                    }
                    
                    $class = new $class($this->app);
                    
                    echo $class->{$this->registeredRoutes[$method][$path]['method'][1]}($request);

                    $this->handleGlobalMiddleware('after');
                    
                    return;
                }
            }
        }
        
        echo $this->notFound();
    }
    
    public function isValidRoute($request, $method)
    {
        $path = explode('/', $request->path());
        
        if (array_key_exists($path[1], $this->registeredRoutes[$method])) {
            return $path[1];
        }

        if (array_key_exists('/' . $path[1], $this->registeredRoutes[$method])) {
            return '/' . $path[1];
        }
        
        return false;
    }
    
    public function handleMiddleware($method = 'get', $path = '', $middleware = []): void
    {
        $route = $this->registeredRoutes[$method][$path];
        
        if (! array_key_exists('middleware', $route) && $middleware === []) return;
        
        if ($middleware === []) $middleware = $route['middleware'];
        
        foreach ($middleware as $middleware) {
            if (is_array($middleware)) $this->handleMiddleware($method, $path, $middleware);
            
            if (array_key_exists($middleware, $this->kernel->getMiddleware())) {
                $middleware = $this->kernel->getMiddleware()[$middleware];

                $middleware = new $middleware;

                if ($middleware->handle($this->app) !== true) {
                    die($middleware->handle($this->app));
                }
            }
        }
    }
    
    public function handleGroupMiddleware($method = 'get', $path = '', $group = null, $middleware = []): void
    {
        if (is_null($group)) return;

        $group = $this->groups[$group];

        if (! array_key_exists('middleware', $group)) return;

        if ($middleware === []) $middleware = $group['middleware'];
        if (! is_array($middleware)) $middleware = [$middleware];

        $this->handleMiddleware($method, $path, $middleware);
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
    
    public function middleware($middleware): static
    {
        if ($this->current === []) return $this;
        
        $current = &$this->registeredRoutes[$this->current['type']][$this->current['path']];

        if (! isset($current['middleware']) && is_array($middleware)) $current['middleware'] = $middleware;
        if (! isset($current['middleware']) || ! is_array($current['middleware'])) $current['middleware'] = [];
        
        $current['middleware'][] = $middleware;
        
        return $this;
    }
    
    public function name($name): static
    {
        if ($this->current === []) return $this;

        $current = &$this->registeredRoutes[$this->current['type']][$this->current['path']];
        
        $current['name'] = $name;
        
        return $this;
    }
    
    public function getRouteByName($name): mixed
    {
        foreach ($this->registeredRoutes as $registeredRoutes)
        {
            foreach ($registeredRoutes as $key => $route)
            {
                if (array_key_exists('name', $route)) {
                    if ($route['name'] === $name) return $key;
                }
            }
        }
        
        return null;
    }

    public function get($path, $method): static
    {
        $this->current = ['type' => 'get', 'path' => $path, 'method' => $method];
        
        $route = [
            'method' => $method
        ];
        
        if ($this->groups !== []) {
            $route['group'] = count($this->groups) - 1;
        }

        $this->registeredRoutes['get'][$path] = $route;

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
    
    public function group($group, $fn)
    {
        $this->groups[] = $group;
        
        $fn();
    }
    
    #[NoReturn] public function abort($code = '404', $data = []): void
    {
        if (file_exists(basedir('templates/errors/' . $code . '.blade.php'))) {
            die($this->app->get('view')->make('errors.' . $code, $data));
        }

        die($this->app->get('view')->make('errors.404', $data));
    }
}