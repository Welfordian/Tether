<?php

namespace App\Http\Middleware;

class RedirectIfAuthenticated
{
    public function handle(): mixed
    {
        if (auth()->check()) {
            return redirect()->to('/dashboard');
        }
        
        return true;
    }
}