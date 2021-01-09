<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_gx2cms
 *
 * @copyright   Copyright (C) 2018 - 2021 WEBCONSOL Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<div class="contained-l border-ddd">
    <h1>Install instruction</h1>
    <ul>
        <li>Go to Menu</li>
        <li>Add your new Site Menu item</li>
        <li>Menu Type, Select "GX2CMS Main View"</li>
        <li>Field in the name of the your menu item</li>
        <li>Select "Menu" from the dropdown that you want to display in</li>
        <li>Select "Parent Item" from the dropdown that you want to display</li>
        <li>Click on the "GX2CMS Menu Settings" tab</li>
        <li>Type in the absolute path to your GX2CMS on your file system (or drive) in the "Root Path" field.</li>
        <li>Save</li>
    </ul>
    <p>
        Now, go to the front-end (or site) and click on your menu item that you just created
        <br />
        <a href="<?php echo $_SERVER['HTTPS']?'https://':'http://',Joomla\CMS\Uri\Uri::getInstance()->getHost();?>" target="_blank">
            <?php echo \Joomla\CMS\Factory::getApplication()->get('sitename'); ?>
        </a>
    </p>
</div>