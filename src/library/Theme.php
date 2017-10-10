<?php
namespace Fluent;

class Theme
{
    public static function factory($theme, $content)
    {
        switch ($theme) {
            case 'musimal':

                if (!class_exists('\\Fluent\\Theme\\\Musimal')) {
                    throw new Exception('The fluent/musimal package is required to render messages locally, but has not been installed');
                }

                return new Theme\Musimal($content);
                
                break;
            default: 
                throw new \Exception("Unknown theme: '{$theme}'");
                break;
        }
    }
}