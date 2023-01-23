<?php

namespace App\Http;

use App\Http\Middleware\StartSession;
use App\Http\Middleware\UserIsAuthenticated;

class Kernel
{
    protected array $globalMiddleware = [
        'before' => [
            StartSession::class,
        ],
        'after' => [
            // 
        ],
    ];
    
    protected array $middleware = [
        'auth' => UserIsAuthenticated::class,
    ];
    
    public function getGlobalMiddleware(): array
    {
        return $this->globalMiddleware;
    }
    
    public function getMiddleware(): array
    {
        return $this->middleware;
    }
}