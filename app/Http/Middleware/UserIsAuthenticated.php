<?php

namespace App\Http\Middleware;

class UserIsAuthenticated
{
    public function handle(): mixed
    {
        if (! auth()->check()) {            
            return redirect()->to('/');
        }
        
        return true;
    }
}