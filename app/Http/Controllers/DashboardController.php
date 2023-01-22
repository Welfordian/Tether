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

        return $this->view('dashboard');
    }
}