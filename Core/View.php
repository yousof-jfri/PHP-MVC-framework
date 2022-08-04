<?php
namespace Core;

use Philo\Blade\Blade;

class View
{
    public static function render($view, $args = [])
    {
        extract($args);

        $file = '../App/Views/' . $view . '.php';

        if(is_readable($file)){
            require $file;
        }else {
            throw new \Exception("{$file} not found");
        }
    }

    public static function renderTemplate($template, $args = [])
    {
        $cache = realpath(__DIR__. '/../Storage/Views');
        
        $views = realpath(__DIR__. '/../App/Views');

        $blade = new Blade($views, $cache);
        return $blade->view()->make($template, $args)->render(); 
    }
}