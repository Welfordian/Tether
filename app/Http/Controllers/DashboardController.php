<?php

namespace App\Http\Controllers;

use App\Exceptions\Test;

class DashboardController extends Controller
{
    public function show()
    {
        if (! auth()->check()) {
            return redirect()->to('/');
        }
        
        throw new Test('you need to actually make it work, cunt');

        return $this->view('dashboard');
    }
}