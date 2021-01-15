<?php

namespace GX2CMSJoomla;

use Ezpizee\Utils\StringUtil;

class HTMLDoc
{
    public static final function styleSheet(string $url): string
    {
        return '<link rel="stylesheet" href="'.$url.'" type="text/css" />';
    }

    public static final function script(string $url): string
    {
        return '<script src="'.$url.'" type="text/javascript"></script>';
    }

    public static final function removeDoubleSlashes(string &$path): void
    {
        $path = StringUtil::removeDoubleSlashes($path);
    }
}