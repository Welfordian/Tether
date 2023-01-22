<?php

namespace Tether\Facade;

class Str
{
    public static function __callStatic($name, $arguments)
    {
        return (new \Tether\Str())->{$name}(...$arguments);
    }
}