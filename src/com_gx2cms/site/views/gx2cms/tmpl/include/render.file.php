<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_gx2cms
 *
 * @copyright   2020 - 2021 GX2CMS Co., Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
use HandlebarsHelpers\Utils\DigitalAssetRenderer;

defined('_JEXEC') or die('Restricted access');

if (!empty($filePath) && !empty($root))
{
    $digitalAssetRenderer = new DigitalAssetRenderer($root, $filePath);
    $digitalAssetRenderer->render();
    die();
}