<?php

namespace Tether;

class Config
{
    public array $config;
    
    public function __construct()
    {
        $this->config = require __DIR__ . '/../configuration.php';
    }
    
    public static function get($key, $default = null)
    {
        $self = new self();
        
        if (array_key_exists($key, $self->config)) {
            return $self->config[$key];
        }
        
        if (preg_match('~^[^.\s]+(?:\.[^.\s]+)+$~', $key)) {
            $keys = explode('.', $key);
            $value = \Tether\Config::get($keys[0]);
            array_shift($keys);

            foreach($keys as $key) {
                $value = $value[$key];
            }

            return $value;
        }

        return $default;
    }
    
    public function __get($key)
    {
        return $this->get($key);
    }
}