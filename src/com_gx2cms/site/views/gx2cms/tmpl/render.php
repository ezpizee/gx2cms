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

use GX2CMSJoomla\Exception\Error;
use GX2CMSJoomla\Hbs;
use GX2CMSJoomla\Scanner;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

try {
    $renderPage = Route::_('index.php?option=com_gx2cms&layout=render');
    Hbs::setProcessor('GX2CMS');
    $input = Factory::getApplication()->input;
    $root = $this->getMenuParam('gx2cms_project_root');
    if (!empty($input->get('servlet'))) {
        $requestMethod = strtoupper($input->getMethod());
        $servlet = $input->getString('servlet');
        include_once __DIR__.GX2CMS_DS.'include'.GX2CMS_DS.'render.servlet.php';
    }
    else if (!empty($input->get('model'))) {
        $model = $input->getString('model');
        include_once __DIR__.GX2CMS_DS.'include'.GX2CMS_DS.'render.model.php';
    }
    else if (!empty($input->get('clientlib'))) {
        $clientlib = $input->getString('clientlib');
        $type = $input->getString('type');
        if (in_array($type, ['css', 'js'])) {
            include_once __DIR__.GX2CMS_DS.'include'.GX2CMS_DS.'render.clientlib.php';
        }
        else {
            throw new RuntimeException('Invalid type for the clientlib request', 500);
        }
    }
    else if (!empty($input->get('imagePath'))) {
        $imagePath = $input->getString('imagePath');
        include __DIR__.GX2CMS_DS.'include'.GX2CMS_DS.'render.image.php';
    }
    else if (!empty($input->get('filePath'))) {
        $filePath = $input->getString('filePath');
        include __DIR__.GX2CMS_DS.'include'.GX2CMS_DS.'render.file.php';
    }
    else {
        $layout = $input->getString('layout', 'render');
        $scanner = new Scanner($root);
        $page = $input->getString('page', '');
        $section = $input->getString('section', '');
        $sectionSelector = $input->getString('selector', '');
        if (empty($sectionSelector)) {
            $sectionSelector = 'properties';
        }
        if (!empty($page)) {
            if (!empty($section)) {
                include __DIR__.GX2CMS_DS.'include'.GX2CMS_DS.'render.section.php';
            }
            else {
                include __DIR__.GX2CMS_DS.'include'.GX2CMS_DS.'render.page.php';
            }
        }
        else {
            new Error('Page Not Found', 404);
        }
    }
}
catch (Exception $e) {
    new Error($e->getMessage(), 500);
}