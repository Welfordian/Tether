<?php

namespace Tether;

class Request
{
    protected array $headers = [];
    protected array $data = [];
    protected mixed $path = '';
    
    public function __construct(protected App $app)
    {
        $this->data = $_GET + $_POST;
        $this->path = $_SERVER['REQUEST_URI'];
        $this->headers = getallheaders();
    }
    
    public function all(): array
    {
        return $_GET + $_POST;
    }
    
    public function path()
    {
        return $this->path;
    }
    
    public function get($property, $default = null)
    {
        if (array_key_exists($property, $this->data)) {
            return $this->data[$property];
        }
        
        return $default;
    }
    
    public function header($key = null, $default = null)
    {
        if (is_null($key)) return $default;
        
        if (! array_key_exists($key, $this->headers())) return $default;
        
        return $this->headers[$key];
    }
    
    public function headers()
    {
        return $this->headers;
    }
    
    public function redirect($location = '/'): void
    {
        header('Location: ' . $location);
        
        exit;
    }
    
    public function route($name)
    {
        return $this->app->get('route')->getRouteByName($name);
    }
    
    public function __get($property)
    {
        if (array_key_exists($property, $this->data)) {
            return $this->data[$property];
        }
        
        return null;
    }
}