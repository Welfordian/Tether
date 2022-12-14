<?php

namespace App\Controllers;

use Tether\App;
use Tether\Config;
use Tether\Request;

class Controller
{
    private App $app;
    protected Config $config;
    protected Request $request;
    
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = new Request();
        $this->config = new Config();
    }

    public function view($template = '', $data = []): string
    {
        return $this->app->get('view')->make($template, $data);
    }
}