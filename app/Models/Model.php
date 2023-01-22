<?php

namespace App\Models;

use Tether\Database;
use Tether\Facade\Str;

class Model extends Database
{    
    protected array $attributes;
    
    public function __construct()
    {
        if (! $this->table) {
            $reflection = new \ReflectionClass($this);
            
            $this->table = strtolower(preg_replace('/\B([A-Z])/', '_$1', Str::pluralize(2, $reflection->getShortName())));
        }
    }
    
    public function __get($key)
    {
        if (! array_key_exists($key, $this->attributes)) return null;
        
        return $this->attributes[$key];
    }
    
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public static function all()
    {
        $self = new (static::class)();
        
        return $self->select('*')->get();
    }
    
    public function save()
    {
        $query = "UPDATE " . $this->table . " SET ";
        
        $loop = 0;
        
        foreach ($this->attributes as $field => $value) {
            if ($loop > 0) {
                $query .= ', ';
            }
            
            $query .= $field . ' = \'' . $value . '\'';
            
            $loop++;
        }
        
        $query .= 'WHERE id = ' . $this->attributes[$this->primaryKey];

        $statement = self::$connections[$this->connection]->prepare($query);

        $statement->execute();
    }
    
    public function fill($values = [])
    {        
        foreach ($values as $key => $value) {
            $this->attributes[$key] = $value;
        }
        
        return $this;
    }
}