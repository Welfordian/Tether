<?php

namespace App\Http\Controllers;

use Tether\Auth;
use Tether\Request;

class LoginController
{
    public function login(Request $request)
    {        
        if ($user = Auth::attempt($request->get('username'), $request->get('password'))) {
            session('user', $user['id']);
            
            return redirect()->to('/');
        }
        
        return redirect()->back()->with('errors', [
            'Username or password is invalid'
        ]);
    }
    
    public function logout()
    {
        session_destroy();
        
        return redirect()->to('/');
    }
}