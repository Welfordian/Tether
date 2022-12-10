<?php

namespace App\Controllers;

use Tether\Config;
use Tether\Request;
use Tether\View;

class Controller
{    
    protected Config $config;
    protected Request $request;
    
    public function __construct()
    {
        $this->request = new Request();
        $this->config = new Config();
    }

    public function view($template = '', $data = []): string
    {
        return View::render($template, $data);
    }
}