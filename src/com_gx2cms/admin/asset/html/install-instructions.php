<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
$document = Factory::getDocument();
$document->addStyleSheet('/administrator/components/com_gx2cms/asset/css/style.css');
?>
<div class="contained-m">
    <p class="alert alert-info">
        In order to use our connector, you need to fist install the library,
        which can be found in the release package that you downloaded from our Github repository:
        <strong><i>/dist/ezpzlib.zip</i></strong>
        <i>(<a href="https://github.com/ezpizee/JoomlaConnector/tree/master/dist" target="_blank">
                https://github.com/ezpizee/JoomlaConnector/tree/master/dist</a)</i>
    </p>
</div>