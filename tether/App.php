<?php

namespace Tether;

class App
{
    protected array $handlers = [];
    protected array $config = [];
    protected array $mappings = [];
    protected array $container = [];
    
    public function __construct(){}
    
    public function initialize(): void
    {
        $this->setExceptionHandler();
        $this->loadConfiguration();
        $this->loadMappings();
        $this->registerMappings();
        $this->registerShutdownFunction();
    }
    
    public function waitFor($class, $fn): void
    {
        if (! isset($this->handlers[$class])) $this->handlers[$class] = [];
        
        $this->handlers[$class][] = $fn;
    }
    
    public function triggerHandlers($class, $instance): void
    {
        if (! isset($this->handlers[$class])) return;
        
        foreach ($this->handlers[$class] as $handler) $handler($instance);
    }
    
    public function setExceptionHandler(): void
    {
        $this->waitFor(Exception::class, fn($exception) => set_exception_handler(function ($e) use ($exception) {
            $exception->handle($e);
        }));
    }
    
    public function loadConfiguration(): void
    {
        $this->config = require_once __DIR__ . '/../configuration.php';
    }
    
    public function getConfig()
    {
        return $this->config;
    }
    
    public function loadMappings(): void
    {
        $this->mappings = require_once __DIR__ . '/mappings.php';
    }
    
    public function registerMappings(): void
    {
        foreach ($this->mappings['classes'] as $name => $class) {            
            $requiredParameters = $this->withParameters($class, $name);
            
            $this->container[$name] = new $class(...$requiredParameters);
            
            $this->triggerHandlers($class, $this->container[$name]);
        }
    }
    
    public function withParameters($class, $name = null)
    {
        $classes = $this->container;

        foreach ($classes as $key => $value) {
            if (is_string($value)) return;

            $classes[$value::class] = $value;
            unset($classes[$key]);
        }
        
        $reflection = new \ReflectionClass($class);

        $requiredParameters = [];

        if (! $reflection->hasMethod('__construct')) return $requiredParameters;
        
        foreach ($reflection->getMethod('__construct')->getParameters() as $parameter) {
            $key = null;

            if (array_key_exists($name, $this->config)) {
                if (array_key_exists($parameter->getName(), $this->config[$name])) {
                    $key = $this->config[$name][$parameter->getName()];
                }
            }

            if (array_key_exists($parameter->getType()?->getName(), $this->mappings['aliases'])) {
                $key = $this->container[$this->mappings['aliases'][$parameter->getType()?->getName()]];
            }

            if (in_array($parameter->getType()?->getName(), array_values($classes))) {
                $key = $this->container[$parameter->getType()?->getName()];
            }

            if (array_key_exists($parameter->getType()?->getName(), $classes)) {
                $key = $classes[$parameter->getType()?->getName()];
            }

            if ($parameter->getType()?->getName() === 'Tether\\App') {
                $requiredParameters[] = $this;
            } else {
                $requiredParameters[] = $key;
            }
        }
        
        return $requiredParameters;
    }
    
    public function getMappings(): array
    {
        return $this->mappings;
    }
    
    public function registerShutdownFunction(): void
    {
        register_shutdown_function(function () {
            unset($_SESSION['flash']);
        });
    }
    
    public function get($key): mixed
    {
        if (! array_key_exists($key, $this->container)) return null;
        
        return $this->container[$key];
    }
    
    public function run($argv = []): void
    {
        \Tether\Database::mapDatabaseConnections();
        
        if (php_sapi_name() === 'cli') {
            $this->container['cli']->handle(
                $argv
            );
        } else {
            $this->container['route']->handle(
                $this->container['request']
            );

            if (! \Tether\Config::get('blade.cache.enabled', true)) {
                $config_dir = \Tether\Config::get('blade.cachePath');

                system('rm -rf -- ' . escapeshellarg($config_dir));
            }
        }
    }
}