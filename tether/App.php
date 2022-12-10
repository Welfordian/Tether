<?php

namespace Tether;

class App
{
    protected array $config = [];
    protected array $mappings = [];
    protected array $container = [];
    
    public function __construct()
    {
        $this->loadConfiguration();
        $this->loadMappings();
        $this->registerMappings();
    }
    
    public function loadConfiguration(): void
    {
        $this->config = require_once __DIR__ . '/../configuration.php';
    }
    
    public function loadMappings(): void
    {
        $this->mappings = require_once __DIR__ . '/mappings.php';
    }
    
    public function registerMappings(): void
    {
        foreach ($this->mappings['classes'] as $name => $class) {
            $reflection = new \ReflectionClass($class);

            $requiredParameters = [];
            
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
                
                if ($parameter->getType()?->getName() === 'Tether\\App') {
                    $requiredParameters[] = $this;
                } else {
                    $requiredParameters[] = $key;
                }                
            }
            
            $this->container[$name] = new $class(...$requiredParameters);
        }
    }
    
    public function get($key)
    {
        if (! array_key_exists($key, $this->container)) return null;
        
        return $this->container[$key];
    }
    
    public function run(): void
    {
        \Tether\Database::mapConnections();

        $this->container['route']->handle(
            $this->container['request']
        );

        if (! \Tether\Config::get('blade.cache.enabled', true)) {
            $config_dir = \Tether\Config::get('blade.cachePath');

            system('rm -rf -- ' . escapeshellarg($config_dir));
        }
    }
}