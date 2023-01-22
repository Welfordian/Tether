<?php

namespace Tether;

use JetBrains\PhpStorm\NoReturn;

class Redirect
{
    protected array $flash = [];
    
    public function __construct()
    {
        
    }

    public function back()
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        
        return $this;
    }
    
    public function to($location = '/')
    {
        header('Location: ' . $location);
    }
    
    public function with($key, $value): void
    {
        $this->flash[$key] = $value;
    }
    
    public function __destruct()
    {
        if (count($this->flash) > 0) {
            if (! isset($_SESSION['flash']) || ! is_array($_SESSION['flash'])) {
                $_SESSION['flash'] = [];
            }

            foreach ($this->flash as $key => $value) {
                $_SESSION['flash'][$key] = $value;
            }
        }
        
        die();
    }
}