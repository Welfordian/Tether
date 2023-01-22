<?php

namespace App\Http;

use App\Http\Middleware\StartSession;
use App\Http\Middleware\UserIsAuthenticated;

class Kernel
{
    protected array $middleware = [
        'before' => [
            StartSession::class,
            UserIsAuthenticated::class,
        ],
        'after' => [
            // 
        ],
    ];
    
    public function middleware(): array
    {
        return $this->middleware;
    }
}