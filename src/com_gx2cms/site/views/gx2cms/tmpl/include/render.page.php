<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_gx2cms
 *
 * @copyright   2020 - 2021 GX2CMS Co., Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use GX2CMSJoomla\Hbs;
use Handlebars\Processors\Processor;

if (!empty($scanner) && !empty($page) && !empty($root))
{
    $page = $scanner->getPages()->getChild($page);
    $properties = $page->getProperties()->getAsArray();
    Hbs::setGlobalContext(array_merge(
        isset($properties['properties']) ? $properties['properties'] : $properties,
        ['renderPage' => $renderPage],
        ['global' => $scanner->getGlobalConfigData()]
    ));
    $wcag = isset($scanner->getGlobalConfigData()['wcag']) ? $scanner->getGlobalConfigData()['wcag'] : 'na';
    $html = Hbs::render($page->getFSFile(), Hbs::getGlobalContext(), $root);
    Processor::putBackIgnore($html);
    $pattern = ['<body','</body>'];
    $replace = ['<body data-wcag="'.$wcag.'"','<script src="https://cdn.ezpz.solutions/accessibility.min.js"></script></body>'];
    $html = str_replace($pattern, $replace, $html);
    $pattern = '</head>';
    $replace = '<link rel="stylesheet" href="https://cdn.ezpz.solutions/accessibility.min.css" type="text/css"></head>';
    $html = str_replace($pattern, $replace, $html);
    die($html);
}