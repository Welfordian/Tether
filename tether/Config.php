<?php

namespace Tether;

class Config
{
    public array $config;
    
    public function __construct()
    {
        $this->config = require __DIR__ . '/../configuration.php';
    }
    
    public function getByDotNotation($key, $default = null)
    {        
        if (preg_match('~^[^.\s]+(?:\.[^.\s]+)+$~', $key)) {
            $keys = explode('.', $key);
            
            if (! array_key_exists($keys[0], $this->config)) return $default;
            
            $value = $this->config[$keys[0]];
            array_shift($keys);

            foreach($keys as $key) {
                if (! array_key_exists($key, $value)) return $default;
                
                $value = $value[$key];
            }

            return $value;
        }
    }
    
    public static function get($key, $default = null)
    {
        $self = new self();
        
        if (array_key_exists($key, $self->config)) {
            return $self->config[$key];
        }
        
        if ($self->getByDotNotation($key) !== null) {
            return $self->getByDotNotation($key);
        }

        return $default;
    }
    
    public function __get($key)
    {
        return $this->get($key);
    }
}