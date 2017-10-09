<?php
namespace Fluent\Message;

class Content
{
    public static function markup($content = null)
    {
        return new Content\Markup($content);
    }

    public static function raw()
    {
        return new Content\Raw();
    }
}
