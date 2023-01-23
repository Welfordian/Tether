<?php

return function ($app) {
    if (! function_exists('basedir'))
    {
        function basedir($directory = '')
        {
            return str_replace('/tether', '', __DIR__) . '/tether/';
        }
    }

    if (! function_exists('auth'))
    {
        function auth()
        {
            global $app;

            return $app->get('auth');
        }
    }
    
    if (! function_exists('redirect'))
    {
        function redirect()
        {
            global $app;
            
            return $app->get('redirect');
        }
    }
    
    if (! function_exists('session'))
    {
        function session($key = false, $value = false)
        {
            global $app;
            
            if ($key) {
                if ($value) {
                    return $app->get('session')->set($key, $value);
                }
                
                return $app->get('session')->get($key);
            }
            
            return $app->get('session');
        }
    }
    
    if (! function_exists('abort'))
    {
        function abort($code = 404, $data = [])
        {
            global $app;
            
            return $app->get('route')->abort($code, $data);
        }
    }
    
    if (! function_exists('config'))
    {
        function config(): array
        {
            global $app;
            
            return $app->getConfig();
        }
    }
    
    if (! function_exists('csrf_token'))
    {
        function csrf_token()
        {
            global $app;
            
            return $app->get('csrf')->get();
        }
    }
    
    if (! function_exists('route'))
    {
        function route($name)
        {
            global $app;
            
            return '/' . $app->get('route')->getRouteByName($name);
        }
    }
};