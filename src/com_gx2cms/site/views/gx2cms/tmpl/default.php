<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_gx2cms
 *
 * @copyright   2020 - 2021 GX2CMS Co., Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use GX2CMSJoomla\Exception\Error;
use GX2CMSJoomla\Page\Page;
use GX2CMSJoomla\Scanner;
use Joomla\CMS\Router\Route;

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
try {
    $root = $this->getMenuParam('gx2cms_project_root');
    $scanner = new Scanner($root);
    if ($scanner->getPages()->hasChildren()) {
        $basePage = Route::_('index.php?option=com_gx2cms');
        echo '<h2>Pages from your GX2CMS Project</h2>';
        echo '<p class="text-success">'.$root.'</p>';
        echo '<ul>';
        foreach ($scanner->getPages()->getChildren() as $page) {
            if ($page instanceof Page) {
                echo '<li><a href="'.$basePage.'?layout=render&page='.$page->getPath().'" target="new">'.$page->getTitle().'</a></li>';
            }
        }
        echo '</ul>';
    }
    else {
        echo '<h1>There is no page to display</h1>';
    }
}
catch (Exception $e) {
    new Error($e->getMessage(), 500);
}