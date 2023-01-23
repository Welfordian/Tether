<?php

namespace App\Http\Middleware;

use Tether\Csrf;
use Tether\Request;

class VerifyCsrfToken
{
    public function __construct(protected Csrf $csrf)
    {
        
    }
    
    public function handle(Request $request)
    {        
        if (! $this->csrf->verifyFromRequest($request)) abort(419);
        
        return true;
    }
}