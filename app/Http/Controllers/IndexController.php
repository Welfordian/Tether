<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
    public function show(): string
    {
        if (auth()->check()) {
            return redirect()->to('/dashboard');
        }
        
        return $this->view('index');
    }
}