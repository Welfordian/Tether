<?php

namespace Tether;

use Jenssegers\Blade\Blade;

class View
{
    private Blade $blade;

    public function __construct()
    {
        $this->blade = new Blade(
            Config::get('view.template_directory'),
            Config::get('view.cache.directory')
        );
        
        $this->setDirectives();
    }
    
    public function setDirectives(): void
    {
        $this->blade->directive('config', function ($expression) {
            return "<?php echo is_array(\Tether\Config::get('admin_emails')) ? json_encode(\Tether\Config::get('admin_emails')) : \Tether\Config::get('admin_emails'); ?>";
        });
    }

    public function make($template = '', $data = [])
    {
        return $this->blade->render($template, $data);
    }
    
    public static function render($template = '', $data = [])
    {
        return (new self())->make($template, $data);
    }
}