<?php

namespace Tether;

class Request
{
    protected array $data = [];
    protected mixed $path = '';
    
    public function __construct()
    {
        $this->data = $_GET + $_POST;
        $this->path = $_SERVER['REQUEST_URI'];
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
    
    public function redirect($location = '/'): void
    {
        header('Location: ' . $location);
        
        exit;
    }
    
    public function __get($property)
    {
        if (array_key_exists($property, $this->data)) {
            return $this->data[$property];
        }
        
        return null;
    }
}