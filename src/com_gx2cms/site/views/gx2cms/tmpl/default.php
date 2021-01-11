<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_gx2cms
 *
 * @copyright   2020 - 2021 GX2CMS Co., Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use GX2CMSJoomla\Exception\Error;
use GX2CMSJoomla\Scanner;
use GX2CMSJoomla\Hbs;
use Joomla\CMS\Router\Route;

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
try {
    Hbs::setProcessor('GX2CMS');
    $root = $this->getMenuParam('gx2cms_project_root');
    $scanner = new Scanner($root);
    if ($scanner->getPages()->hasChildren()) {
        $context = $scanner->toArray();
        $context['basePage'] = Route::_('index.php?option=com_gx2cms');
        echo Hbs::render(
            GX2CMS_COMP_ROOT.GX2CMS_DS.'asset'.GX2CMS_DS.'hbs'.GX2CMS_DS.'pages.hbs',
            $context,
            $root
        );
    }
    else {
        echo '<h1>There is no page to display</h1>';
    }
}
catch (Exception $e) {
    new Error($e->getMessage(), 500);
}