<?php

namespace Tether;

class Str
{
    public function pluralize($quantity, $singular, $plural=null) {
        if($quantity==1 || !strlen($singular)) return $singular;
        if($plural!==null) return $plural;

        $last_letter = strtolower($singular[strlen($singular)-1]);

        return match ($last_letter) {
            'y' => substr($singular, 0, -1) . 'ies',
            's' => $singular . 'es',
            default => $singular . 's',
        };
    }
}