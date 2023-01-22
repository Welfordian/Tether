<?php

namespace Tether;

class Collection
{
    protected array $values;
    
    public function __construct($values = [])
    {
        $this->values = $values;
    }
    
    public function first()
    {
        return $this->values[0];
    }
    
    public function values()
    {
        return $this->values;
    }
}