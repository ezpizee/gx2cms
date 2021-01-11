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
use Joomla\CMS\Router\Route;

if (!empty($scanner) && !empty($page) && !empty($root))
{
    $renderPage = Route::_('index.php?option=com_gx2cms&layout=render');
    $page = $scanner->getPages()->getChild($page);
    $properties = $page->getProperties()->getAsArray();
    Hbs::setGlobalContext(array_merge(
        isset($properties['properties']) ? $properties['properties'] : $properties,
        ['renderPage' => $renderPage],
        ['global' => $scanner->getGlobalConfigData()]
    ));
    die(Hbs::render(
        $page->getFSFile(),
        Hbs::getGlobalContext(),
        $root
    ));
}