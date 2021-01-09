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

use Joomla\CMS\Access\Exception\NotAllowed as AccessExceptionNotAllowed;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;

if (!defined('GX2CMS_DS')) {
    define('GX2CMS_DS', DIRECTORY_SEPARATOR);
}

if (!file_exists(JPATH_LIBRARIES.GX2CMS_DS.'ezpzlib'.GX2CMS_DS.'autoload.php')) {
    include __DIR__.GX2CMS_DS.'asset'.GX2CMS_DS.'html'.GX2CMS_DS.'install-instructions.php';
}
else {
    if (!Factory::getUser()->authorise('core.manage', 'com_gx2cms')) {
        throw new AccessExceptionNotAllowed(Text::_('JERROR_ALERTNOAUTHOR'), 403);
    }

    include_once JPATH_LIBRARIES.GX2CMS_DS.'ezpzlib'.GX2CMS_DS.'autoload.php';
    include_once __DIR__.GX2CMS_DS.'helpers'.GX2CMS_DS.'Constants.php';
    include_once __DIR__.GX2CMS_DS.'helpers'.GX2CMS_DS.'GX2CMSAdminHelper.php';

    \Ezpizee\ContextProcessor\CustomLoader::appendPackage([
        'GX2CMSJoomla' => __DIR__.GX2CMS_DS.'lib'.GX2CMS_DS.'src'
    ]);
    \Ezpizee\ContextProcessor\CustomLoader::exec();

    // Set some global property
    $document = Factory::getDocument();
    $document->addStyleSheet(GX2CMSAdminHelper::assetRoot().'/css/style.css');

    // Access check: is this user allowed to access the backend of this component?
    if (!Factory::getUser()->authorise('core.manage', 'com_gx2cms'))
    {
        throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'));
    }

    // Get an instance of the controller prefixed by GX2CMS
    $controller = BaseController::getInstance('GX2CMS');

    // Perform the Request task
    $controller->execute(Factory::getApplication()->input->get('task'));;

    // Redirect if set by the controller
    $controller->redirect();
}