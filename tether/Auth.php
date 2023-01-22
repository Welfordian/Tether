<?php

namespace Tether;

use App\Models\User;

class Auth
{
    public function __construct()
    {
        
    }
    

    public static function attempt($username, $password)
    {
        $user = User::where('username', $username)->first();
        
        if (empty($user)) {
            return false;
        }
        
        if (! password_verify($password, $user['password'])) {
            return false;
        }
        
        return $user;
    }
    
    public function check()
    {
        return isset($_SESSION['user']);
    }
    
    public function user()
    {
        return User::where('id', $_SESSION['user'])->first();
    }
}