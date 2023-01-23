<?php

namespace Tether;

class Cli
{
    public function __construct() {}
    
    public function handle($argv)
    {
        dd(array_splice($argv, 1));
    }
}