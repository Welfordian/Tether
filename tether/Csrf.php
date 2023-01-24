<?php

namespace Tether;

use Carbon\Carbon;

class Csrf
{    
    protected string $csrf_token;
    
    public function regenerate(): void
    {
        if (session('csrf_token') === null) {
            $this->generateCsrfToken();
        } else {
            $expiry = new Carbon(session('csrf_expiry'));
            
            if (Carbon::now()->gt($expiry)) {
                $this->generateCsrfToken();
            }
        }
    }
    
    public function generateCsrfToken()
    {
        $this->csrf_token = bin2hex(random_bytes(32));

        session('csrf_token', $this->csrf_token);
        session('csrf_expiry', Carbon::now()->addMinutes(30));
    }
    
    public function get(): string|null
    {
        return session('csrf_token');
    }
    
    public function verifyFromRequest(Request $request): bool
    {
        if ($request->header('X-XSRF-TOKEN') === null && $request->get('_token') === null) return false;
        if ($request->header('X-XSRF-TOKEN') !== session('csrf_token') && $request->get('_token') !== session('csrf_token')) return false;
        
        return true;
    }
}