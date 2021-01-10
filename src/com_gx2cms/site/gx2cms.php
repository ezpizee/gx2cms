<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_gx2cms
 *
 * @copyright   Copyright (C) 2018 - 2021 WEBCONSOL Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if (!defined('GX2CMS_DS')) {
    define('GX2CMS_DS', DIRECTORY_SEPARATOR);
    define('GX2CMS_COMP_ROOT', __DIR__);
}
if (!defined('GX2CMS_ADMIN_COMP_ROOT')) {
    define('GX2CMS_ADMIN_COMP_ROOT', JPATH_ROOT . GX2CMS_DS . 'administrator' . GX2CMS_DS . 'components' . GX2CMS_DS . 'com_gx2cms');
}

if (!file_exists(JPATH_LIBRARIES.GX2CMS_DS.'ezpzlib'.GX2CMS_DS.'autoload.php')) {
    include GX2CMS_ADMIN_COMP_ROOT.GX2CMS_DS.'asset'.GX2CMS_DS.'html'.GX2CMS_DS.'install-instructions.php';
}
else {
    include_once JPATH_LIBRARIES.GX2CMS_DS.'ezpzlib'.GX2CMS_DS.'autoload.php';
    \Ezpizee\ContextProcessor\CustomLoader::appendPackage([
        'GX2CMSJoomla' => GX2CMS_ADMIN_COMP_ROOT.GX2CMS_DS.'lib'.GX2CMS_DS.'src'
    ], true);

    \Joomla\CMS\Factory::getDocument()->addScript(\Joomla\CMS\Uri\Uri::base().'/components/com_gx2cms/asset/js/script.js');

    // Get an instance of the controller prefixed by GX2CMS
    $controller = JControllerLegacy::getInstance('GX2CMS');

    // Perform the Request task
    $input = JFactory::getApplication()->input;
    $controller->execute($input->getCmd('task'));

    // Redirect if set by the controller
    $controller->redirect();
}