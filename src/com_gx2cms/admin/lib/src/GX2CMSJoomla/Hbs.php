<?php

namespace GX2CMSJoomla;

use Handlebars\Engine\Hbs as BaseHbs;

class Hbs extends BaseHbs
{
    public static function render(string $tmpl, array $context, string $layoutDir = '', array $options = array())
    : string
    {
        self::$tokens = ['\$\{', '\}'];
        self::$ext = '.'.Constants::EXT;
        return parent::render($tmpl, $context, $layoutDir, $options);
    }
}