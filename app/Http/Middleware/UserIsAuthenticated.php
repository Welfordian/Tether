<?php

namespace App\Http\Middleware;

class UserIsAuthenticated
{
    public function handle()
    {
        if (! auth()->check()) {
            return true;
            
            return redirect()->to('/');
        }
        
        return true;
    }
}