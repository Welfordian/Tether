<?php

namespace App\Http\Controllers;

use Tether\Request;

class DashboardController extends Controller
{
    public function show(Request $request): string
    {
        return $this->view('dashboard');
    }
    
    public function test(): string
    {
        return 'lol';
    }
}