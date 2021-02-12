<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_gx2cms
 *
 * @copyright   2020 - 2021 GX2CMS Co., Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
use Handlebars\Utils\ClientlibManager;
use Handlebars\Processors\Processor;

defined('_JEXEC') or die('Restricted access');

if (!empty($root) && !empty($clientlib) && !empty($type) && isset($renderPage))
{
    $q = $clientlib.'.'.$type;
    $clientLibManager = new ClientlibManager($root, $q);
    $clientLibManager->setRenderHeaderContentType();
    $content = $clientLibManager->getContent();
    Processor::processAssetInCSS($content, [
        'renderPage' => $renderPage,
        'root' => $root,
        'clientlibRoot' => dirname($q).DS.pathinfo($q, PATHINFO_FILENAME)
    ]);
    die($content);
}