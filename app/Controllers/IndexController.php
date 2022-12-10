<?php

namespace App\Controllers;

class IndexController extends Controller
{
    public function show(): string
    {
        return $this->view('index');
    }
    
    public function handle()
    {
        return $this->view('handle', [
            'username' => $this->request->get('username')
        ]);
    }
}