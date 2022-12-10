<?php

namespace App\Models;

use Tether\Database;

class Model extends Database
{    
    public function __construct()
    {
        if (! $this->table) {
            $reflection = new \ReflectionClass($this);
            
            $this->table = strtolower(preg_replace('/\B([A-Z])/', '_$1', self::pluralize(2, $reflection->getShortName())));
        }
    }

    public static function all()
    {
        $self = new (static::class)();
        
        return $self->select('*')->get();
    }
}