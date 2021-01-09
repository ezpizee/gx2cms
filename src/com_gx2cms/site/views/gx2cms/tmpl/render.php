<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_gx2cms
 *
 * @copyright   2020 - 2021 GX2CMS Co., Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use GX2CMSJoomla\Exception\Error;
use GX2CMSJoomla\Hbs;
use GX2CMSJoomla\Scanner;
use Joomla\CMS\Factory;

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
try {
    $root = $this->getMenuParam('gx2cms_project_root');
    $scanner = new Scanner($root);
    $file = Factory::getApplication()->input->getString('page', '');
    if (!empty($file)) {
        $page = $scanner->getPages()->getChild($file);
        $properties = $page->getProperties()->getAsArray();
        Hbs::setGlobalContext(array_merge(
            ['currentPage' => isset($properties['properties']) ? $properties['properties'] : $properties],
            $scanner->getGlobalConfigData()
        ));
        die(Hbs::render(
            $page->getFSFile(),
            $properties,
            $root
        ));
    }
    else {
        new Error('page is required, but missing', 500);
    }
}
catch (Exception $e) {
    new Error($e->getMessage(), 500);
}