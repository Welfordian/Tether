<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
    public function show(): string
    {        
        return $this->view('index');
    }
}