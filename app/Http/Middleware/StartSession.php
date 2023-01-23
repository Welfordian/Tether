<?php

namespace App\Http\Middleware;

use Tether\App;

class StartSession
{
    public function handle()
    {        
        session_start([
            'name' => (config()->session['name'] ?? 'tether') . '_session',
            'cookie_lifetime' => config()->session['lifetime'] ?? '86400',
            'cookie_httponly' => config()->session['secure'] ?? false,
            'sid_length' => 128,
        ]);
        
        return true;
    }
}