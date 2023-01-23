<?php

namespace Tether;

class Session
{
    public function __construct() {}
    
    public function get($key)
    {
        return $_SESSION[$key];
    }
    
    public function set($key, $value)
    {
        return $_SESSION[$key] = $value;
    }
}