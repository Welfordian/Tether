<?php

namespace App\Http\Middleware;

use Tether\App;

class StartSession
{
    public function handle(App $app)
    {
        $session = $app->get('config')->session;
        
        session_start([
            'name' => ($session['name'] ?? 'tether') . '_session',
            'cookie_lifetime' => $session['lifetime'] ?? '86400',
            'cookie_httponly' => $session['secure'] ?? false,
            'sid_length' => 128,
        ]);
        
        return true;
    }
}