<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function show(): string
    {
        return $this->view('dashboard');
    }
}